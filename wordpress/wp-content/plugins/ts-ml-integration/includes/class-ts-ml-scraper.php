<?php
/**
 * Mercado Livre Scraper class
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Scraper class to extract data from public Mercado Livre pages
 */
class TS_ML_Scraper
{
    /**
     * Instance
     *
     * @var TS_ML_Scraper
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return TS_ML_Scraper
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Scrape a single product by URL or ML ID
     *
     * @param string $url_or_id
     * @return array|WP_Error
     */
    public function scrape_product($url_or_id)
    {
        $url = $this->normalize_product_url($url_or_id);
        
        $response = wp_remote_get($url, array(
            'timeout'    => 30,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $html = wp_remote_retrieve_body($response);
        if (empty($html)) {
            return new WP_Error('empty_html', __('Não foi possível obter o conteúdo da página.', 'ts-ml-integration'));
        }

        return $this->parse_product_html($html, $url_or_id);
    }

    /**
     * Scrape search results for product IDs
     *
     * @param string $search_url
     * @return array|WP_Error
     */
    public function scrape_search_results($search_url)
    {
        $response = wp_remote_get($search_url, array(
            'timeout'    => 30,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $html = wp_remote_retrieve_body($response);
        
        // Match MLB123456789 patterns in product links
        preg_match_all('/(MLB|MLM|MLA|MCO|MLC|MLU|MLV|MPE|MEC|MGT|MNI|MPY|MCR|MSV|MPA|MBO)\d+/', $html, $matches);
        
        if (empty($matches[0])) {
            return array();
        }

        return array_values(array_unique($matches[0]));
    }

    /**
     * Parse HTML to extract product data
     */
    private function parse_product_html($html, $original_ref)
    {
        $data = array();

        // Extract Title
        if (preg_match('/<h1 class="ui-pdp-title">([^<]+)<\/h1>/', $html, $matches)) {
            $data['title'] = html_entity_decode(trim($matches[1]));
        }

        // Extract Price (Meta tag is often easier and more reliable)
        if (preg_match('/<meta itemprop="price" content="([^"]+)"/', $html, $matches)) {
            $data['price'] = floatval($matches[1]);
        }

        // Extract Images (JSON state usually contains high-res)
        if (preg_match('/"pictures":(\[[^\]]+\])/', $html, $matches)) {
            $pictures = json_decode($matches[1], true);
            if (is_array($pictures)) {
                $data['pictures'] = array_map(function($pic) {
                    return array('url' => $pic['url'] ?? $pic['secure_url'] ?? '');
                }, $pictures);
            }
        }

        // Description (ML often heaps it in a separate div or JSON)
        // Note: Full description is often loaded via AJAX, but plain text might be in JSON state
        if (preg_match('/"description":\{"plain_text":"([^"]+)"/', $html, $matches)) {
            $data['description'] = str_replace('\n', "\n", $matches[1]);
        }

        // Attributes (Brand, Model etc)
        if (preg_match('/"attributes":(\[[^\]]+\])/', $html, $matches)) {
            $attrs = json_decode($matches[1], true);
            if (is_array($attrs)) {
                $data['attributes'] = array_map(function($attr) {
                    return array(
                        'id' => $attr['id'] ?? '',
                        'name' => $attr['name'] ?? '',
                        'value_name' => $attr['value_name'] ?? ''
                    );
                }, $attrs);
            }
        }

        // ID normalization
        if (preg_match('/(MLB|MLM|MLA|MCO|MLC|MLU|MLV|MPE|MEC|MGT|MNI|MPY|MCR|MSV|MPA|MBO)\d+/', $original_ref, $matches)) {
            $data['id'] = $matches[0];
        }

        return $data;
    }

    /**
     * Normalize URL from ID or relative path
     */
    private function normalize_product_url($ref)
    {
        if (strpos($ref, 'http') === 0) {
            return $ref;
        }

        // If it's just an ID
        if (preg_match('/(MLB|MLM|MLA|MCO|MLC|MLU|MLV|MPE|MEC|MGT|MNI|MPY|MCR|MSV|MPA|MBO)\d+/', $ref, $matches)) {
            return 'https://articulo.mercadolibre.com.br/' . $matches[0];
        }

        return $ref;
    }
}
