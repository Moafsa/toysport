<?php
/**
 * Toy Sport Theme Functions
 *
 * @package ToySport
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes do tema
define('TOYSPORT_VERSION', '1.0.6');
define('TOYSPORT_PATH', get_template_directory());
define('TOYSPORT_URI', get_template_directory_uri());

/**
 * Configurações do tema
 */
function toysport_setup()
{
    // Suporte a tradução
    load_theme_textdomain('toysport', TOYSPORT_PATH . '/languages');

    // Suporte a título automático
    add_theme_support('title-tag');

    // Suporte a imagens destacadas
    add_theme_support('post-thumbnails');

    // Tamanhos de imagem personalizados
    add_image_size('toysport-product-thumb', 300, 300, true);
    add_image_size('toysport-product-large', 800, 800, true);
    add_image_size('toysport-hero', 1920, 800, true);

    // Suporte a menus
    register_nav_menus([
        'primary' => __('Menu Principal', 'toysport'),
        'footer' => __('Menu Rodapé', 'toysport'),
    ]);

    // Suporte a HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ]);

    // Suporte a logo customizado
    add_theme_support('custom-logo', [
        'height' => 80,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    // Suporte a WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'toysport_setup');

/**
 * Enfileirar estilos e scripts
 */
function toysport_scripts()
{
    // Google Fonts
    wp_enqueue_style(
        'toysport-fonts',
        'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700&family=Barlow:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    // Estilo principal
    wp_enqueue_style(
        'toysport-style',
        get_stylesheet_uri(),
        [],
        TOYSPORT_VERSION
    );

    // CSS adicional
    wp_enqueue_style(
        'toysport-custom',
        TOYSPORT_URI . '/assets/css/custom.css',
        ['toysport-style'],
        TOYSPORT_VERSION
    );

    // JavaScript principal
    wp_enqueue_script(
        'toysport-script',
        TOYSPORT_URI . '/assets/js/main.js',
        [],
        TOYSPORT_VERSION,
    );

    // Font Awesome para ícones
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    // Ajax Search
    wp_localize_script('toysport-script', 'toysportAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('toysport-nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'toysport_scripts');

/**
 * AJAX Search Handler
 */
function toysport_ajax_search()
{
    check_ajax_referer('toysport-nonce', 'nonce');

    $query = sanitize_text_field($_POST['query']);

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        's' => $query,
    ];

    $search_query = new WP_Query($args);

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            global $product;

            echo '<a href="' . get_permalink() . '" class="search-result-item">';
            echo '<div class="search-result-thumb">' . $product->get_image() . '</div>';
            echo '<div class="search-result-info">';
            echo '<h4>' . get_the_title() . '</h4>';
            echo '<span class="search-result-price">' . $product->get_price_html() . '</span>';
            echo '</div>';
            echo '</a>';
        }
        wp_reset_postdata();
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_toysport_ajax_search', 'toysport_ajax_search');
add_action('wp_ajax_nopriv_toysport_ajax_search', 'toysport_ajax_search');

/**
 * Registrar áreas de widgets
 */
function toysport_widgets_init()
{
    register_sidebar([
        'name' => __('Sidebar Principal', 'toysport'),
        'id' => 'sidebar-1',
        'description' => __('Widgets para a sidebar principal', 'toysport'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);

    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name' => sprintf(__('Rodapé %d', 'toysport'), $i),
            'id' => 'footer-' . $i,
            'description' => sprintf(__('Área de widgets do rodapé %d', 'toysport'), $i),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="footer-widget-title">',
            'after_title' => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'toysport_widgets_init');

/**
 * Suporte a WooCommerce
 */
function toysport_woocommerce_setup()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'toysport_woocommerce_setup');

/**
 * Personalizar loops do WooCommerce
 */
function toysport_woocommerce_products_per_page()
{
    return 12;
}
add_filter('loop_shop_per_page', 'toysport_woocommerce_products_per_page', 20);

function toysport_woocommerce_columns()
{
    return 4;
}
add_filter('loop_shop_columns', 'toysport_woocommerce_columns');

/**
 * SEO: Schema.org para organização
 */
function toysport_organization_schema()
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Store',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url(),
        'logo' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
    ];

    if (get_theme_mod('toysport_store_address')) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => get_theme_mod('toysport_store_address'),
            'addressLocality' => get_theme_mod('toysport_store_city', 'Xanxerê'),
            'addressRegion' => get_theme_mod('toysport_store_state', 'SC'),
            'postalCode' => get_theme_mod('toysport_store_postcode', '89820-000'),
            'addressCountry' => 'BR',
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
add_action('wp_head', 'toysport_organization_schema');

/**
 * SEO: Schema.org para produtos
 */
function toysport_product_schema($product)
{
    if (!is_product()) {
        return;
    }

    global $product;
    if (!$product) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_description() ?: $product->get_short_description()),
        'image' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
        'sku' => $product->get_sku(),
        'brand' => [
            '@type' => 'Brand',
            'name' => get_bloginfo('name'),
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => get_permalink(),
            'priceCurrency' => 'BRL',
            'price' => $product->get_price(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
            ],
        ],
    ];

    if ($product->get_review_count() > 0) {
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
add_action('woocommerce_single_product_summary', 'toysport_product_schema', 1);

/**
 * Breadcrumbs personalizados
 */
function toysport_breadcrumbs()
{
    if (is_front_page()) {
        return;
    }

    echo '<nav class="breadcrumbs" aria-label="breadcrumb">';
    echo '<ol>';
    echo '<li><a href="' . home_url() . '">Home</a></li>';

    if (is_category() || is_single()) {
        $category = get_the_category();
        if (!empty($category)) {
            echo '<li><a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->name . '</a></li>';
        }
        if (is_single()) {
            echo '<li>' . get_the_title() . '</li>';
        }
    } elseif (is_page()) {
        echo '<li>' . get_the_title() . '</li>';
    } elseif (is_search()) {
        echo '<li>Resultados da busca: ' . get_search_query() . '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * Meta tags adicionais para SEO
 */
function toysport_meta_tags()
{
    // Se Yoast SEO estiver ativo, deixa ele cuidar disso
    if (defined('WPSEO_VERSION')) {
        return;
    }

    if (is_single() || is_page()) {
        global $post;
        $description = wp_trim_words(strip_shortcodes($post->post_content), 30);

        if (has_excerpt()) {
            $description = wp_trim_words(get_the_excerpt(), 30);
        }

        echo '<meta name="description" content="' . esc_attr($description) . '">';

        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">';
        echo '<meta property="og:description" content="' . esc_attr($description) . '">';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">';
        echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '">';

        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'large')) . '">';
        }

        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">';
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">';
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">';
    }
}
add_action('wp_head', 'toysport_meta_tags');

/**
 * Otimizar carregamento de imagens
 */
function toysport_lazy_load_images($content)
{
    if (is_admin() || is_feed()) {
        return $content;
    }

    $content = preg_replace_callback(
        '/<img([^>]*)>/i',
        function ($matches) {
            $img = $matches[0];
            if (strpos($img, 'loading=') !== false) {
                return $img;
            }
            return str_replace('<img', '<img loading="lazy"', $img);
        },
        $content
    );

    return $content;
}
add_filter('the_content', 'toysport_lazy_load_images');
add_filter('woocommerce_product_get_image', 'toysport_lazy_load_images');

/**
 * WhatsApp Float Button
 */
function toysport_whatsapp_button()
{
    $whatsapp_number = get_theme_mod('toysport_whatsapp_number', '5549999999999');
    $whatsapp_message = urlencode('Olá! Gostaria de saber mais sobre os produtos.');

    echo '<a href="https://wa.me/' . esc_attr($whatsapp_number) . '?text=' . esc_attr($whatsapp_message) . '" 
         class="whatsapp-float" 
         target="_blank" 
         rel="noopener"
         aria-label="Fale conosco no WhatsApp">
         <i class="fab fa-whatsapp"></i>
         </a>';
}
add_action('wp_footer', 'toysport_whatsapp_button');

/**
 * Customizer settings
 */
function toysport_customize_register($wp_customize)
{
    // Seção de contato
    $wp_customize->add_section('toysport_contact', [
        'title' => __('Informações de Contato', 'toysport'),
        'priority' => 30,
    ]);

    // WhatsApp
    $wp_customize->add_setting('toysport_whatsapp_number', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('toysport_whatsapp_number', [
        'label' => __('Número do WhatsApp', 'toysport'),
        'section' => 'toysport_contact',
        'type' => 'text',
        'description' => __('Formato: 5549999999999 (sem caracteres especiais)', 'toysport'),
    ]);

    // Endereço da loja
    $wp_customize->add_setting('toysport_store_address', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('toysport_store_address', [
        'label' => __('Endereço da Loja', 'toysport'),
        'section' => 'toysport_contact',
        'type' => 'text',
    ]);
}
add_action('customize_register', 'toysport_customize_register');

/**
 * Menu fallback
 */
function toysport_fallback_menu()
{
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'toysport') . '</a></li>';
    if (function_exists('wc_get_page_id')) {
        $shop_page_id = wc_get_page_id('shop');
        if ($shop_page_id) {
            echo '<li><a href="' . esc_url(get_permalink($shop_page_id)) . '">' . __('Loja', 'toysport') . '</a></li>';
        }
    }
    echo '</ul>';
}
