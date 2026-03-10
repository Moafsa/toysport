<?php
/**
 * Test Scraper Functionality
 * Run via: php tests/test-scraper.php
 */

// Basic WordPress bootstrap (minimal)
define('ABSPATH', dirname(__FILE__) . '/../wordpress/');
require_once(ABSPATH . 'wp-load.php');

// Include Scraper if not already loaded
if (!class_exists('TS_ML_Scraper')) {
    require_once(dirname(__FILE__) . '/../wordpress/wp-content/plugins/ts-ml-integration/includes/class-ts-ml-scraper.php');
}

$scraper = TS_ML_Scraper::instance();

// Test 1: Single Product (Example)
$test_id = 'MLB3367123456'; // Substitute with a real ID for manual testing if needed
echo "--- Test 1: Scraping Product ---\n";
$product_data = $scraper->scrape_product($test_id);

if (is_wp_error($product_data)) {
    echo "Error: " . $product_data->get_error_message() . "\n";
} else {
    echo "Title: " . ($product_data['title'] ?? 'N/A') . "\n";
    echo "Price: " . ($product_data['price'] ?? 'N/A') . "\n";
    echo "Images Count: " . (isset($product_data['pictures']) ? count($product_data['pictures']) : 0) . "\n";
}

// Test 2: Search Results
echo "\n--- Test 2: Scraping Search Results ---\n";
$search_url = 'https://lista.mercadolivre.com.br/tenis-nike';
$search_ids = $scraper->scrape_search_results($search_url);

if (is_wp_error($search_ids)) {
    echo "Error: " . $search_ids->get_error_message() . "\n";
} else {
    echo "Found IDs: " . count($search_ids) . "\n";
    echo "First 5 IDs: " . implode(', ', array_slice($search_ids, 0, 5)) . "\n";
}
