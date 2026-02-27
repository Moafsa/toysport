<?php
/**
 * Script de Importação de Produtos - Toy Sport
 * 
 * Este script importa produtos a partir de um arquivo CSV
 * 
 * Uso: php import-products.php [arquivo.csv]
 */

// Verificar se está rodando via CLI
if (php_sapi_name() !== 'cli') {
    die('Este script deve ser executado via linha de comando (CLI).');
}

// Carregar WordPress
$wp_load_path = dirname(__FILE__) . '/../wordpress/wp-load.php';

if (!file_exists($wp_load_path)) {
    die("Erro: WordPress não encontrado em: $wp_load_path\n");
}

require_once($wp_load_path);

// Verificar WooCommerce
if (!class_exists('WooCommerce')) {
    die("Erro: WooCommerce não está instalado.\n");
}

echo "========================================\n";
echo "  Importação de Produtos - Toy Sport   \n";
echo "========================================\n\n";

// Arquivo CSV
$csv_file = isset($argv[1]) ? $argv[1] : dirname(__FILE__) . '/products-sample.csv';

if (!file_exists($csv_file)) {
    echo "Arquivo não encontrado: $csv_file\n";
    echo "Criando arquivo de exemplo...\n";
    create_sample_csv($csv_file);
    echo "Arquivo de exemplo criado. Edite-o e execute novamente.\n";
    exit(0);
}

// Ler arquivo CSV
$products = read_csv($csv_file);

if (empty($products)) {
    die("Nenhum produto encontrado no arquivo CSV.\n");
}

echo "Produtos encontrados: " . count($products) . "\n\n";

$imported = 0;
$updated = 0;
$errors = 0;

foreach ($products as $index => $product_data) {
    echo "Processando produto " . ($index + 1) . ": {$product_data['name']}...\n";
    
    try {
        $result = import_product($product_data);
        
        if ($result['status'] === 'created') {
            $imported++;
            echo "  ✓ Produto criado (ID: {$result['id']})\n";
        } elseif ($result['status'] === 'updated') {
            $updated++;
            echo "  ✓ Produto atualizado (ID: {$result['id']})\n";
        } else {
            $errors++;
            echo "  ✗ Erro: {$result['message']}\n";
        }
    } catch (Exception $e) {
        $errors++;
        echo "  ✗ Erro: " . $e->getMessage() . "\n";
    }
}

echo "\n========================================\n";
echo "Resumo da importação:\n";
echo "  Criados: $imported\n";
echo "  Atualizados: $updated\n";
echo "  Erros: $errors\n";
echo "========================================\n";

/**
 * Ler arquivo CSV
 */
function read_csv($file) {
    $products = [];
    $handle = fopen($file, 'r');
    
    if ($handle === false) {
        die("Erro ao abrir arquivo CSV.\n");
    }
    
    // Ler cabeçalho
    $headers = fgetcsv($handle);
    
    if ($headers === false) {
        die("Erro ao ler cabeçalho do CSV.\n");
    }
    
    // Ler linhas
    while (($row = fgetcsv($handle)) !== false) {
        $product = [];
        foreach ($headers as $index => $header) {
            $product[trim($header)] = isset($row[$index]) ? trim($row[$index]) : '';
        }
        $products[] = $product;
    }
    
    fclose($handle);
    return $products;
}

/**
 * Importar produto
 */
function import_product($data) {
    // Verificar se produto já existe (por SKU ou nome)
    $existing_id = null;
    
    if (!empty($data['sku'])) {
        $existing_id = wc_get_product_id_by_sku($data['sku']);
    }
    
    if (!$existing_id && !empty($data['name'])) {
        $posts = get_posts([
            'post_type' => 'product',
            'title' => $data['name'],
            'numberposts' => 1
        ]);
        
        if (!empty($posts)) {
            $existing_id = $posts[0]->ID;
        }
    }
    
    // Criar ou atualizar produto
    $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Simple();
    
    // Dados básicos
    if (!empty($data['name'])) {
        $product->set_name($data['name']);
    }
    
    if (!empty($data['description'])) {
        $product->set_description($data['description']);
    }
    
    if (!empty($data['short_description'])) {
        $product->set_short_description($data['short_description']);
    }
    
    if (!empty($data['sku'])) {
        $product->set_sku($data['sku']);
    }
    
    // Preço
    if (!empty($data['price'])) {
        $price = str_replace(['.', ','], ['', '.'], $data['price']);
        $product->set_regular_price($price);
        $product->set_price($price);
    }
    
    // Estoque
    if (!empty($data['stock'])) {
        $product->set_manage_stock(true);
        $product->set_stock_quantity((int)$data['stock']);
        $product->set_stock_status('instock');
    }
    
    // Categorias
    if (!empty($data['categories'])) {
        $categories = explode(',', $data['categories']);
        $category_ids = [];
        
        foreach ($categories as $category_slug) {
            $term = get_term_by('slug', trim($category_slug), 'product_cat');
            if ($term) {
                $category_ids[] = $term->term_id;
            }
        }
        
        if (!empty($category_ids)) {
            $product->set_category_ids($category_ids);
        }
    }
    
    // Tags
    if (!empty($data['tags'])) {
        $tags = explode(',', $data['tags']);
        $tag_ids = [];
        
        foreach ($tags as $tag_name) {
            $term = get_term_by('name', trim($tag_name), 'product_tag');
            if ($term) {
                $tag_ids[] = $term->term_id;
            } else {
                $term_data = wp_insert_term(trim($tag_name), 'product_tag');
                if (!is_wp_error($term_data)) {
                    $tag_ids[] = $term_data['term_id'];
                }
            }
        }
        
        if (!empty($tag_ids)) {
            $product->set_tag_ids($tag_ids);
        }
    }
    
    // Imagens
    if (!empty($data['image'])) {
        $image_id = upload_image_from_url($data['image'], $product->get_name());
        if ($image_id) {
            $product->set_image_id($image_id);
        }
    }
    
    // Status
    $product->set_status(isset($data['status']) ? $data['status'] : 'publish');
    
    // Salvar produto
    $product_id = $product->save();
    
    if ($product_id) {
        return [
            'status' => $existing_id ? 'updated' : 'created',
            'id' => $product_id
        ];
    } else {
        return [
            'status' => 'error',
            'message' => 'Erro ao salvar produto'
        ];
    }
}

/**
 * Upload de imagem a partir de URL
 */
function upload_image_from_url($url, $alt = '') {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $tmp = download_url($url);
    
    if (is_wp_error($tmp)) {
        return false;
    }
    
    $file_array = [
        'name' => basename($url),
        'tmp_name' => $tmp
    ];
    
    $id = media_handle_sideload($file_array, 0);
    
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return false;
    }
    
    if (!empty($alt)) {
        update_post_meta($id, '_wp_attachment_image_alt', $alt);
    }
    
    return $id;
}

/**
 * Criar arquivo CSV de exemplo
 */
function create_sample_csv($file) {
    $sample = [
        ['name', 'sku', 'price', 'stock', 'categories', 'tags', 'image', 'description', 'short_description'],
        ['Carrinho de controle zmoto3', 'ZMOTO3-001', '1820.00', '10', 'adultos,carrinho-controle-remoto', 'destaque,novo', 'https://exemplo.com/imagem.jpg', 'Descrição completa do produto...', 'Descrição curta do produto']
    ];
    
    $handle = fopen($file, 'w');
    foreach ($sample as $row) {
        fputcsv($handle, $row);
    }
    fclose($handle);
}
