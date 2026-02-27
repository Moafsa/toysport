<?php
/**
 * Product Sync
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Sync class
 */
class TS_ML_Product_Sync {
    
    /**
     * Instance
     *
     * @var TS_ML_Product_Sync
     */
    private static $instance = null;
    
    /**
     * Get instance
     *
     * @return TS_ML_Product_Sync
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Constructor
    }
    
    /**
     * Sync account products
     *
     * @param int $account_id Account ID
     * @return bool
     */
    public function sync_account_products($account_id) {
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            return false;
        }
        
        // Get products to sync
        $products = $this->get_products_to_sync($account_id);
        
        foreach ($products as $product) {
            $this->sync_product($product->product_id, $account_id, $product->sync_direction);
        }
        
        return true;
    }
    
    /**
     * Sync product
     *
     * @param int $product_id Product ID
     * @param int $account_id Account ID
     * @param string $direction Sync direction
     * @return bool
     */
    public function sync_product($product_id, $account_id, $direction = 'bidirectional') {
        $product = wc_get_product($product_id);
        
        if (!$product) {
            TS_ML_Logger::error('Produto não encontrado', array('product_id' => $product_id));
            return false;
        }
        
        // Validate account
        global $wpdb;
        $table_accounts = $wpdb->prefix . 'ts_ml_accounts';
        $account = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_accounts WHERE id = %d AND is_active = 1",
            $account_id
        ));
        
        if (!$account) {
            TS_ML_Logger::error('Conta não encontrada ou inativa', array('account_id' => $account_id));
            return false;
        }
        
        // Create or update sync record
        $table_products = $wpdb->prefix . 'ts_ml_products';
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_products WHERE product_id = %d AND account_id = %d",
            $product_id,
            $account_id
        ));
        
        if (!$existing) {
            $wpdb->insert(
                $table_products,
                array(
                    'account_id' => $account_id,
                    'product_id' => $product_id,
                    'sync_direction' => $direction,
                    'sync_status' => 'pending',
                    'created_at' => current_time('mysql'),
                ),
                array('%d', '%d', '%s', '%s', '%s')
            );
        } else {
            $wpdb->update(
                $table_products,
                array(
                    'sync_direction' => $direction,
                    'sync_status' => 'pending',
                    'updated_at' => current_time('mysql'),
                ),
                array('id' => $existing->id),
                array('%s', '%s', '%s'),
                array('%d')
            );
        }
        
        if ($direction === 'woo_to_ml' || $direction === 'bidirectional') {
            return $this->export_product_to_ml($product, $account_id);
        }
        
        if ($direction === 'ml_to_woo' || $direction === 'bidirectional') {
            // Import logic will be implemented
        }
        
        return true;
    }
    
    /**
     * Export product to Mercado Livre
     *
     * @param WC_Product $product Product object
     * @param int $account_id Account ID
     * @return bool
     */
    private function export_product_to_ml($product, $account_id) {
        TS_ML_Logger::info('Iniciando exportação de produto para ML', array('product_id' => $product->get_id(), 'account_id' => $account_id));
        
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            TS_ML_Logger::error('Erro ao obter token de acesso', array('error' => $access_token->get_error_message()));
            $this->update_sync_status($product->get_id(), $account_id, 'error', $access_token->get_error_message());
            return false;
        }
        
        // Update status to syncing
        $this->update_sync_status($product->get_id(), $account_id, 'syncing');
        
        // Prepare product data for ML
        $ml_product_data = $this->prepare_product_for_ml($product);
        
        TS_ML_Logger::debug('Dados do produto preparados', array('data' => $ml_product_data));
        
        // Check if product already exists in ML
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_products WHERE product_id = %d AND account_id = %d",
            $product->get_id(),
            $account_id
        ));
        
        if ($existing && !empty($existing->ml_item_id)) {
            // Update existing listing
            TS_ML_Logger::info('Atualizando publicação existente', array('ml_item_id' => $existing->ml_item_id));
            $response = $api_handler->api_request(
                '/items/' . $existing->ml_item_id,
                'PUT',
                $ml_product_data,
                $access_token
            );
        } else {
            // Create new listing
            TS_ML_Logger::info('Criando nova publicação');
            $response = $api_handler->api_request(
                '/items',
                'POST',
                $ml_product_data,
                $access_token
            );
        }
        
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            TS_ML_Logger::error('Erro na API do Mercado Livre', array('error' => $error_message));
            $this->update_sync_status($product->get_id(), $account_id, 'error', $error_message);
            return false;
        }
        
        // Update sync record
        $this->update_sync_record($product->get_id(), $account_id, $response);
        
        TS_ML_Logger::info('Produto sincronizado com sucesso', array('ml_item_id' => isset($response['id']) ? $response['id'] : 'N/A'));
        
        return true;
    }
    
    /**
     * Prepare product for Mercado Livre
     *
     * @param WC_Product $product Product object
     * @return array
     */
    private function prepare_product_for_ml($product) {
        $data = array(
            'title' => $product->get_name(),
            'category_id' => $this->get_ml_category($product),
            'price' => floatval($product->get_price()),
            'currency_id' => 'BRL',
            'available_quantity' => $product->get_stock_quantity() ?: 1,
            'buying_mode' => 'buy_it_now',
            'listing_type_id' => 'gold_special',
            'condition' => 'new',
            'description' => array(
                'plain_text' => $product->get_description() ?: $product->get_short_description(),
            ),
            'pictures' => $this->get_product_images($product),
            'attributes' => $this->get_product_attributes($product),
        );
        
        return $data;
    }
    
    /**
     * Get ML category
     *
     * @param WC_Product $product Product object
     * @return string
     */
    private function get_ml_category($product) {
        // Default category - should be mapped from WooCommerce categories
        return 'MLB1000'; // Example category
    }
    
    /**
     * Get product images
     *
     * @param WC_Product $product Product object
     * @return array
     */
    private function get_product_images($product) {
        $images = array();
        $attachment_ids = $product->get_gallery_image_ids();
        
        if ($product->get_image_id()) {
            array_unshift($attachment_ids, $product->get_image_id());
        }
        
        foreach ($attachment_ids as $attachment_id) {
            $image_url = wp_get_attachment_image_url($attachment_id, 'full');
            if ($image_url) {
                $images[] = array('source' => $image_url);
            }
        }
        
        return $images;
    }
    
    /**
     * Get product attributes
     *
     * @param WC_Product $product Product object
     * @return array
     */
    private function get_product_attributes($product) {
        $attributes = array();
        
        // Add brand if available
        if ($product->get_meta('_brand')) {
            $attributes[] = array(
                'id' => 'BRAND',
                'value_name' => $product->get_meta('_brand'),
            );
        }
        
        // Add model if available
        if ($product->get_meta('_model')) {
            $attributes[] = array(
                'id' => 'MODEL',
                'value_name' => $product->get_meta('_model'),
            );
        }
        
        return $attributes;
    }
    
    /**
     * Get products to sync
     *
     * @param int $account_id Account ID
     * @return array
     */
    private function get_products_to_sync($account_id) {
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_products WHERE account_id = %d AND sync_status IN ('pending', 'syncing')",
            $account_id
        ));
    }
    
    /**
     * Update sync record
     *
     * @param int $product_id Product ID
     * @param int $account_id Account ID
     * @param array $ml_response ML API response
     */
    private function update_sync_record($product_id, $account_id, $ml_response) {
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        
        $data = array(
            'ml_item_id' => isset($ml_response['id']) ? $ml_response['id'] : null,
            'ml_listing_id' => isset($ml_response['id']) ? $ml_response['id'] : null,
            'sync_status' => 'synced',
            'last_sync_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );
        
        $where = array(
            'product_id' => $product_id,
            'account_id' => $account_id,
        );
        
        $wpdb->update($table_products, $data, $where);
    }
    
    /**
     * Update sync status
     *
     * @param int $product_id Product ID
     * @param int $account_id Account ID
     * @param string $status Status
     * @param string $error_message Error message
     */
    private function update_sync_status($product_id, $account_id, $status, $error_message = '') {
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        
        $data = array(
            'sync_status' => $status,
            'sync_errors' => $error_message,
            'updated_at' => current_time('mysql'),
        );
        
        $where = array(
            'product_id' => $product_id,
            'account_id' => $account_id,
        );
        
        $wpdb->update($table_products, $data, $where);
    }
    
    /**
     * Sync account stock
     *
     * @param int $account_id Account ID
     * @return bool
     */
    public function sync_account_stock($account_id) {
        TS_ML_Logger::info('Iniciando sincronização de estoque', array('account_id' => $account_id));
        
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            TS_ML_Logger::error('Erro ao obter token para sincronização de estoque', array('error' => $access_token->get_error_message()));
            return false;
        }
        
        // Get all synced products for this account
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        $synced_products = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_products WHERE account_id = %d AND sync_status = 'synced' AND ml_item_id IS NOT NULL",
            $account_id
        ));
        
        $synced_count = 0;
        $error_count = 0;
        
        foreach ($synced_products as $sync_record) {
            $product = wc_get_product($sync_record->product_id);
            
            if (!$product) {
                continue;
            }
            
            $stock_quantity = $product->get_stock_quantity();
            if ($stock_quantity === null) {
                $stock_quantity = $product->is_in_stock() ? 999 : 0;
            }
            
            // Update stock on ML
            $response = $api_handler->api_request(
                '/items/' . $sync_record->ml_item_id,
                'PUT',
                array('available_quantity' => intval($stock_quantity)),
                $access_token
            );
            
            if (is_wp_error($response)) {
                TS_ML_Logger::error('Erro ao atualizar estoque no ML', array(
                    'product_id' => $sync_record->product_id,
                    'ml_item_id' => $sync_record->ml_item_id,
                    'error' => $response->get_error_message()
                ));
                $error_count++;
            } else {
                // Update last sync time
                $wpdb->update(
                    $table_products,
                    array('last_sync_at' => current_time('mysql')),
                    array('id' => $sync_record->id),
                    array('%s'),
                    array('%d')
                );
                $synced_count++;
            }
        }
        
        TS_ML_Logger::info('Sincronização de estoque concluída', array(
            'synced' => $synced_count,
            'errors' => $error_count
        ));
        
        return true;
    }
    
    /**
     * Sync account prices
     *
     * @param int $account_id Account ID
     * @return bool
     */
    public function sync_account_prices($account_id) {
        TS_ML_Logger::info('Iniciando sincronização de preços', array('account_id' => $account_id));
        
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            TS_ML_Logger::error('Erro ao obter token para sincronização de preços', array('error' => $access_token->get_error_message()));
            return false;
        }
        
        // Get all synced products for this account
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        $synced_products = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_products WHERE account_id = %d AND sync_status = 'synced' AND ml_item_id IS NOT NULL",
            $account_id
        ));
        
        $synced_count = 0;
        $error_count = 0;
        
        foreach ($synced_products as $sync_record) {
            $product = wc_get_product($sync_record->product_id);
            
            if (!$product) {
                continue;
            }
            
            $price = floatval($product->get_price());
            
            if ($price <= 0) {
                continue;
            }
            
            // Update price on ML
            $response = $api_handler->api_request(
                '/items/' . $sync_record->ml_item_id,
                'PUT',
                array('price' => $price),
                $access_token
            );
            
            if (is_wp_error($response)) {
                TS_ML_Logger::error('Erro ao atualizar preço no ML', array(
                    'product_id' => $sync_record->product_id,
                    'ml_item_id' => $sync_record->ml_item_id,
                    'error' => $response->get_error_message()
                ));
                $error_count++;
            } else {
                // Update last sync time
                $wpdb->update(
                    $table_products,
                    array('last_sync_at' => current_time('mysql')),
                    array('id' => $sync_record->id),
                    array('%s'),
                    array('%d')
                );
                $synced_count++;
            }
        }
        
        TS_ML_Logger::info('Sincronização de preços concluída', array(
            'synced' => $synced_count,
            'errors' => $error_count
        ));
        
        return true;
    }
    
    /**
     * Sync single product stock
     *
     * @param int $product_id Product ID
     * @param int $account_id Account ID
     * @return bool
     */
    public function sync_product_stock($product_id, $account_id) {
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        
        $sync_record = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_products WHERE product_id = %d AND account_id = %d AND sync_status = 'synced' AND ml_item_id IS NOT NULL",
            $product_id,
            $account_id
        ));
        
        if (!$sync_record) {
            return false;
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            return false;
        }
        
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            return false;
        }
        
        $stock_quantity = $product->get_stock_quantity();
        if ($stock_quantity === null) {
            $stock_quantity = $product->is_in_stock() ? 999 : 0;
        }
        
        $response = $api_handler->api_request(
            '/items/' . $sync_record->ml_item_id,
            'PUT',
            array('available_quantity' => intval($stock_quantity)),
            $access_token
        );
        
        if (!is_wp_error($response)) {
            $wpdb->update(
                $table_products,
                array('last_sync_at' => current_time('mysql')),
                array('id' => $sync_record->id),
                array('%s'),
                array('%d')
            );
            return true;
        }
        
        return false;
    }
    
    /**
     * Sync single product price
     *
     * @param int $product_id Product ID
     * @param int $account_id Account ID
     * @return bool
     */
    public function sync_product_price($product_id, $account_id) {
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        
        $sync_record = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_products WHERE product_id = %d AND account_id = %d AND sync_status = 'synced' AND ml_item_id IS NOT NULL",
            $product_id,
            $account_id
        ));
        
        if (!$sync_record) {
            return false;
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            return false;
        }
        
        $price = floatval($product->get_price());
        if ($price <= 0) {
            return false;
        }
        
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            return false;
        }
        
        $response = $api_handler->api_request(
            '/items/' . $sync_record->ml_item_id,
            'PUT',
            array('price' => $price),
            $access_token
        );
        
        if (!is_wp_error($response)) {
            $wpdb->update(
                $table_products,
                array('last_sync_at' => current_time('mysql')),
                array('id' => $sync_record->id),
                array('%s'),
                array('%d')
            );
            return true;
        }
        
        return false;
    }
}
