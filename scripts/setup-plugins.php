<?php
/**
 * Script de Configuração Inicial de Plugins - Toy Sport
 * 
 * Este script configura automaticamente os plugins essenciais após a instalação
 * 
 * Uso: php setup-plugins.php
 * 
 * IMPORTANTE: Execute este script após instalar todos os plugins
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

echo "========================================\n";
echo "  Configuração de Plugins - Toy Sport  \n";
echo "========================================\n\n";

/**
 * Instalar e ativar plugins necessários
 */
function install_required_plugins() {
    $plugins = [
        'woocommerce/woocommerce.php',
        'wordpress-seo/wp-seo.php', // Yoast SEO
        'contact-form-7/wp-contact-form-7.php',
        'wordfence/wordfence.php',
    ];
    
    require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/misc.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    
    foreach ($plugins as $plugin) {
        $plugin_data = explode('/', $plugin);
        $slug = $plugin_data[0];
        
        echo "Instalando $slug...\n";
        
        $api = plugins_api('plugin_information', ['slug' => $slug]);
        
        if (is_wp_error($api)) {
            echo "  Erro ao buscar informações: " . $api->get_error_message() . "\n";
            continue;
        }
        
        $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
        $result = $upgrader->install($api->download_link);
        
        if ($result === true) {
            activate_plugin($plugin);
            echo "  ✓ Instalado e ativado com sucesso\n";
        } else {
            echo "  ✗ Erro na instalação\n";
        }
    }
}

/**
 * Configurar WooCommerce
 */
function configure_woocommerce() {
    echo "\nConfigurando WooCommerce...\n";
    
    // Configurações básicas
    update_option('woocommerce_store_address', 'Rua Exemplo, 123');
    update_option('woocommerce_store_city', 'Xanxerê');
    update_option('woocommerce_default_country', 'BR:SC');
    update_option('woocommerce_store_postcode', '89820-000');
    update_option('woocommerce_currency', 'BRL');
    update_option('woocommerce_currency_pos', 'left');
    update_option('woocommerce_price_thousand_sep', '.');
    update_option('woocommerce_price_decimal_sep', ',');
    update_option('woocommerce_price_num_decimals', 2);
    
    echo "  ✓ Configurações básicas salvas\n";
}

/**
 * Criar páginas essenciais
 */
function create_essential_pages() {
    echo "\nCriando páginas essenciais...\n";
    
    $pages = [
        'home' => [
            'title' => 'Home',
            'content' => 'Bem-vindo à Toy Sport!',
            'template' => 'page-home.php'
        ],
        'sobre-nos' => [
            'title' => 'Sobre Nós',
            'content' => 'Conteúdo sobre a Toy Sport...'
        ],
        'contato' => [
            'title' => 'Contato',
            'content' => '[contact-form-7]'
        ],
        'politica-devolucao-trocas' => [
            'title' => 'Política de Devolução e Trocas',
            'content' => 'Nossa política de devolução...'
        ],
        'rastreamento-pedidos' => [
            'title' => 'Rastreamento de Pedidos',
            'content' => 'Rastreie seu pedido aqui...'
        ],
        'politica-privacidade' => [
            'title' => 'Política de Privacidade',
            'content' => 'Nossa política de privacidade...'
        ],
        'termos-condicoes' => [
            'title' => 'Termos e Condições',
            'content' => 'Nossos termos e condições...'
        ]
    ];
    
    foreach ($pages as $slug => $page_data) {
        $page = get_page_by_path($slug);
        
        if (!$page) {
            $page_id = wp_insert_post([
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ]);
            
            if ($page_id) {
                echo "  ✓ Página '{$page_data['title']}' criada (ID: $page_id)\n";
            }
        } else {
            echo "  → Página '{$page_data['title']}' já existe\n";
        }
    }
}

/**
 * Criar categorias de produtos
 */
function create_product_categories() {
    echo "\nCriando categorias de produtos...\n";
    
    $categories = [
        'adultos' => [
            'name' => 'Adultos',
            'description' => 'Brinquedos e produtos para adultos',
            'children' => [
                'barcos' => 'Barcos',
                'bicicleta-eletrica' => 'Bicicleta Elétrica',
                'brinquedos-agro' => 'Brinquedos Agro',
                'caixa-de-som' => 'Caixa de Som',
                'carrinho-controle-remoto' => 'Carrinho de Controle Remoto',
                'quadriciclo-eletrico' => 'Quadriciclo Elétrico',
                'miniaturas' => 'Miniaturas',
                'patinete-eletrico' => 'Patinete Elétrico',
                'pelucias' => 'Pelúcias'
            ]
        ],
        'bebes' => [
            'name' => 'Bebês',
            'description' => 'Brinquedos para bebês'
        ],
        'drones' => [
            'name' => 'Drones',
            'description' => 'Drones e helicópteros de controle remoto'
        ],
        'meninas' => [
            'name' => 'Meninas',
            'description' => 'Brinquedos para meninas',
            'children' => [
                'bonecas' => 'Bonecas',
                'micangas' => 'Miçangas',
                'ursos' => 'Ursos'
            ]
        ],
        'meninos' => [
            'name' => 'Meninos',
            'description' => 'Brinquedos para meninos',
            'children' => [
                'carrinhos-controle' => 'Carrinhos de Controle'
            ]
        ]
    ];
    
    foreach ($categories as $slug => $category_data) {
        $term = get_term_by('slug', $slug, 'product_cat');
        
        if (!$term) {
            $term_data = wp_insert_term(
                $category_data['name'],
                'product_cat',
                [
                    'description' => $category_data['description'],
                    'slug' => $slug
                ]
            );
            
            if (!is_wp_error($term_data)) {
                $parent_id = $term_data['term_id'];
                echo "  ✓ Categoria '{$category_data['name']}' criada\n";
                
                // Criar subcategorias
                if (isset($category_data['children'])) {
                    foreach ($category_data['children'] as $child_slug => $child_name) {
                        wp_insert_term(
                            $child_name,
                            'product_cat',
                            [
                                'parent' => $parent_id,
                                'slug' => $child_slug
                            ]
                        );
                        echo "    ✓ Subcategoria '$child_name' criada\n";
                    }
                }
            }
        } else {
            echo "  → Categoria '{$category_data['name']}' já existe\n";
        }
    }
}

/**
 * Configurar permalinks
 */
function configure_permalinks() {
    echo "\nConfigurando permalinks...\n";
    
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
    
    echo "  ✓ Permalinks configurados\n";
}

// Executar configurações
try {
    // install_required_plugins();
    configure_woocommerce();
    create_essential_pages();
    create_product_categories();
    configure_permalinks();
    
    echo "\n========================================\n";
    echo "  Configuração concluída com sucesso!  \n";
    echo "========================================\n";
    
} catch (Exception $e) {
    echo "\nErro: " . $e->getMessage() . "\n";
    exit(1);
}
