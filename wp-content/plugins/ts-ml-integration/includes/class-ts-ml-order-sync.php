<?php
/**
 * Order Sync
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Order Sync class
 */
class TS_ML_Order_Sync {
    
    /**
     * Instance
     *
     * @var TS_ML_Order_Sync
     */
    private static $instance = null;
    
    /**
     * Get instance
     *
     * @return TS_ML_Order_Sync
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
     * Sync account orders
     *
     * @param int $account_id Account ID
     * @return bool
     */
    public function sync_account_orders($account_id) {
        $api_handler = TS_ML_API_Handler::instance();
        $access_token = $api_handler->get_valid_token($account_id);
        
        if (is_wp_error($access_token)) {
            return false;
        }
        
        // Get recent orders from ML
        $orders = $this->get_ml_orders($access_token);
        
        foreach ($orders as $ml_order) {
            $this->import_order_from_ml($ml_order, $account_id);
        }
        
        return true;
    }
    
    /**
     * Get orders from Mercado Livre
     *
     * @param string $access_token Access token
     * @return array
     */
    private function get_ml_orders($access_token) {
        $api_handler = TS_ML_API_Handler::instance();
        
        $response = $api_handler->api_request(
            '/orders/search',
            'GET',
            array(
                'seller' => 'me',
                'order.status' => 'paid',
            ),
            $access_token
        );
        
        if (is_wp_error($response)) {
            return array();
        }
        
        return isset($response['results']) ? $response['results'] : array();
    }
    
    /**
     * Import order from Mercado Livre
     *
     * @param array $ml_order ML order data
     * @param int $account_id Account ID
     * @return int|false Order ID or false
     */
    private function import_order_from_ml($ml_order, $account_id) {
        global $wpdb;
        $table_orders = $wpdb->prefix . 'ts_ml_orders';
        
        // Check if order already exists
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_orders WHERE ml_order_id = %s AND account_id = %d",
            $ml_order['id'],
            $account_id
        ));
        
        if ($existing) {
            // Update existing order
            return $this->update_order_from_ml($ml_order, $existing->order_id, $account_id);
        }
        
        // Create new WooCommerce order
        $order = wc_create_order();
        
        if (is_wp_error($order)) {
            return false;
        }
        
        // Add order items
        if (isset($ml_order['order_items'])) {
            foreach ($ml_order['order_items'] as $item) {
                $product_id = $this->get_product_by_ml_id($item['item']['id']);
                if ($product_id) {
                    $order->add_product(wc_get_product($product_id), $item['quantity']);
                }
            }
        }
        
        // Set order data
        $order->set_billing_email($ml_order['buyer']['email'] ?? '');
        $order->set_billing_first_name($ml_order['buyer']['first_name'] ?? '');
        $order->set_billing_last_name($ml_order['buyer']['last_name'] ?? '');
        $order->set_payment_method('mercadolivre');
        $order->set_payment_method_title('Mercado Livre');
        $order->set_total($ml_order['total_amount'] ?? 0);
        $order->set_status('processing');
        
        $order->save();
        
        // Save sync record
        $wpdb->insert(
            $table_orders,
            array(
                'account_id' => $account_id,
                'order_id' => $order->get_id(),
                'ml_order_id' => $ml_order['id'],
                'sync_status' => 'synced',
                'last_sync_at' => current_time('mysql'),
                'created_at' => current_time('mysql'),
            ),
            array('%d', '%d', '%s', '%s', '%s', '%s')
        );
        
        return $order->get_id();
    }
    
    /**
     * Update order from ML
     *
     * @param array $ml_order ML order data
     * @param int $order_id Order ID
     * @param int $account_id Account ID
     * @return int
     */
    private function update_order_from_ml($ml_order, $order_id, $account_id) {
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return false;
        }
        
        // Update order status based on ML status
        $ml_status = $ml_order['status'] ?? '';
        $wc_status = $this->map_ml_status_to_wc($ml_status);
        
        if ($wc_status) {
            $order->update_status($wc_status);
        }
        
        // Update sync record
        global $wpdb;
        $table_orders = $wpdb->prefix . 'ts_ml_orders';
        
        $wpdb->update(
            $table_orders,
            array(
                'sync_status' => 'synced',
                'last_sync_at' => current_time('mysql'),
            ),
            array(
                'order_id' => $order_id,
                'account_id' => $account_id,
            )
        );
        
        return $order_id;
    }
    
    /**
     * Map ML status to WooCommerce status
     *
     * @param string $ml_status ML status
     * @return string|null
     */
    private function map_ml_status_to_wc($ml_status) {
        $mapping = array(
            'paid' => 'processing',
            'confirmed' => 'processing',
            'shipped' => 'on-hold',
            'delivered' => 'completed',
            'cancelled' => 'cancelled',
        );
        
        return isset($mapping[$ml_status]) ? $mapping[$ml_status] : null;
    }
    
    /**
     * Get product by ML ID
     *
     * @param string $ml_item_id ML item ID
     * @return int|false Product ID or false
     */
    private function get_product_by_ml_id($ml_item_id) {
        global $wpdb;
        $table_products = $wpdb->prefix . 'ts_ml_products';
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT product_id FROM $table_products WHERE ml_item_id = %s LIMIT 1",
            $ml_item_id
        ));
        
        return $result ? intval($result) : false;
    }
}
