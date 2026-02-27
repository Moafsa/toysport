<?php
/**
 * Scraper for ToySport Catalog
 * Run inside Docker container: php scraper.php
 */

set_time_limit(0);
error_reporting(E_ALL & ~E_WARNING); // Suppress DOM parsing warnings

$base_url = 'https://toysport.com.br/loja/';
$max_pages = 29;
$output_file = '/var/www/html/extracted_products.json';
$products = [];

echo "Starting extraction...\n";

for ($page = 1; $page <= $max_pages; $page++) {
    $url = ($page === 1) ? $base_url : $base_url . 'page/' . $page . '/';
    echo "Processing Page $page: $url\n";

    $html = file_get_contents($url);
    if (!$html) {
        echo "Failed to fetch page $page\n";
        continue;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    // Queries based on standard WooCommerce/Theme structure (need to be generic or specific if structure known)
    // Looking at provided chunks, looks like standard WP structure, let's try generic selectors
    // Usually <li class="product">
    // Use type-product to filter out menu items which also have 'product' in class
    $nodes = $xpath->query('//li[contains(@class, "type-product")]');



    // If no generic product class, try to infer from structure in chunks (headers like "## Nome do Produto")
    // But DOM traversal is better. Let's assume standard Woo classes first (`type-product`, `product`).

    if ($nodes->length === 0) {
        // Fallback: Try to find by structure if classes are stripped/custom
        // Looking at previous chunks: `[Almofada coração ... R$ 49,90 ...]` link text.
        // Let's rely on `h2.woocommerce-loop-product__title` or similar if possible.
        // If standard classes fail, we might need a test run.
        echo "No products found with standard selector on page $page. Trying broader search.\n";
        $nodes = $xpath->query('//ul[contains(@class, "products")]/li');
    }

    echo "Found " . $nodes->length . " items.\n";

    foreach ($nodes as $node) {
        $product = [];

        // Title
        $titleNode = $xpath->query('.//h2[contains(@class, "woocommerce-loop-product__title")]', $node)->item(0);
        $product['name'] = $titleNode ? trim($titleNode->textContent) : 'Unknown Product';

        // URL
        $linkNode = $xpath->query('.//a[contains(@class, "woocommerce-LoopProduct-link")]', $node)->item(0);
        $product['url'] = $linkNode ? $linkNode->getAttribute('href') : '';

        // Image
        $imgNode = $xpath->query('.//img', $node)->item(0);
        $product['image'] = $imgNode ? $imgNode->getAttribute('src') : '';
        if ($imgNode && $imgNode->hasAttribute('data-src')) {
            $product['image'] = $imgNode->getAttribute('data-src');
        }

        // Price
        $priceNode = $xpath->query('.//span[contains(@class, "price")]', $node)->item(0);
        $product['price_html'] = $priceNode ? trim($priceNode->textContent) : '';

        // Classes
        $classes = $node->getAttribute('class');
        $product['classes'] = $classes;

        // FETCH DETAILS FROM PRODUCT PAGE
        if (!empty($product['url'])) {
            echo "  > Fetching details for: " . $product['name'] . "... ";
            $detailHtml = @file_get_contents($product['url']);

            if ($detailHtml) {
                $detailDom = new DOMDocument();
                @$detailDom->loadHTML($detailHtml);
                $detailXpath = new DOMXPath($detailDom);

                // Try to get Long Description (#tab-description or .woocommerce-Tabs-panel--description)
                $descNode = $detailXpath->query('//*[@id="tab-description"] | //div[contains(@class, "woocommerce-Tabs-panel--description")]')->item(0);

                // Fallback to Short Description if long is empty
                if (!$descNode) {
                    $descNode = $detailXpath->query('//div[contains(@class, "woocommerce-product-details__short-description")]')->item(0);
                }

                if ($descNode) {
                    // Export HTML content
                    $product['description'] = trim($detailDom->saveHTML($descNode));
                    // Cleanup common junk if needed (e.g. "Description" title)
                    $product['description'] = preg_replace('/<h2[^>]*>.*?<\/h2>/i', '', $product['description'], 1);
                } else {
                    $product['description'] = '';
                }
                echo "OK (" . strlen($product['description']) . " bytes)\n";
            } else {
                echo "Failed to fetch URL\n";
                $product['description'] = '';
            }
            // Sleep slightly to be polite
            usleep(200000); // 200ms
        }

        if (!empty($product['name'])) {
            $products[] = $product;
        }
    }

    // Sleep between pages
    sleep(1);
}

// Save to JSON
file_put_contents($output_file, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Extraction complete. " . count($products) . " products saved to $output_file\n";
