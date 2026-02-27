<?php
/**
 * Import Catalog Script
 * Usage: php import_catalog.php
 */

define('WP_USE_THEMES', false);
require_once('/var/www/html/wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

$json_file = '/var/www/html/extracted_products.json';

if (!file_exists($json_file)) {
    die("Error: JSON file not found at $json_file\n");
}

$products = json_decode(file_get_contents($json_file), true);

if (!$products) {
    die("Error: Failed to decode JSON\n");
}

echo "Starting import of " . count($products) . " products...\n";

$count_new = 0;
$count_skipped = 0;

foreach ($products as $item) {
    $name = sanitize_text_field($item['name']);

    // Check if exists
    $existing = get_page_by_title($name, OBJECT, 'product');
    $is_update = false;

    if ($existing) {
        echo "Updating existing: $name... ";
        $product = wc_get_product($existing->ID);
        $is_update = true;
    } else {
        echo "Importing: $name... ";
        $product = new WC_Product_Simple();
        $product->set_name($name);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
    }

    // Set/Update Price
    $price_html = $item['price_html'];
    $price = 0;
    if (preg_match('/R\$\s*([\d\.,]+)/u', $price_html, $matches)) {
        $price_str = str_replace('.', '', $matches[1]);
        $price = str_replace(',', '.', $price_str);
    }

    // Only update price if it's new or zero, or always? Let's always update for sync.
    $product->set_price($price);
    $product->set_regular_price($price);

    if (!$is_update) {
        $product->set_manage_stock(true);
        $product->set_stock_quantity(100);
    }

    // Update Description
    $desc = isset($item['description']) && !empty($item['description']) ? $item['description'] : "Produto importado de toysport.com.br\nLink original: " . $item['url'];
    $product->set_description($desc);

    // Ensure short description is also set if available, or use a snippet
    if (!empty($desc) && empty($product->get_short_description())) {
        $product->set_short_description(wp_trim_words($desc, 20));
    }

    // Save to get ID
    $product_id = $product->save();

    // Categories
    $classes = $item['classes'];
    if (preg_match_all('/product_cat-([^\s]+)/', $classes, $cat_matches)) {
        $cat_ids = [];
        foreach ($cat_matches[1] as $slug) {
            // Clean slug (sometimes encoded)
            $slug = urldecode($slug);
            $term = get_term_by('slug', $slug, 'product_cat');
            if (!$term) {
                // Create if not exists (try to make pretty name from slug)
                $cat_name = ucwords(str_replace('-', ' ', $slug));
                $new_term = wp_insert_term($cat_name, 'product_cat', ['slug' => $slug]);
                if (!is_wp_error($new_term)) {
                    $cat_ids[] = $new_term['term_id'];
                }
            } else {
                $cat_ids[] = $term->term_id;
            }
        }
        if (!empty($cat_ids)) {
            $product->set_category_ids($cat_ids);
            $product->save();
        }
    }

    // Image
    $image_url = $item['image'];
    if (!empty($image_url)) {
        // Fix protocol relative URLs
        if (strpos($image_url, '//') === 0) {
            $image_url = 'https:' . $image_url;
        }

        // Sideload image
        $image_id = media_sideload_image($image_url, $product_id, $name, 'id');

        if (!is_wp_error($image_id)) {
            $product->set_image_id($image_id);
            $product->save();
        } else {
            echo "[Image Error: " . $image_id->get_error_message() . "] ";
        }
    }

    echo "Done (ID: $product_id)\n";
    $count_new++;
}

echo "Import Complete.\n";
echo "New: $count_new\n";
echo "Skipped: $count_skipped\n";
