<?php
/**
 * Template de Arquivo de Produtos - WooCommerce
 *
 * @package ToySport
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

<main id="main" class="site-main woocommerce-page">
    <div class="container">
        <?php
        if (woocommerce_product_loop()) {
            /**
             * Hook: woocommerce_before_shop_loop.
             */
            do_action('woocommerce_before_shop_loop');

            woocommerce_product_loop_start();

            if (wc_get_loop_prop('is_shortcode')) {
                $columns = absint(wc_get_loop_prop('columns'));
                $woocommerce_loop['columns'] = $columns;
            }

            while (have_posts()) {
                the_post();
                wc_get_template_part('content', 'product');
            }

            woocommerce_product_loop_end();

            /**
             * Hook: woocommerce_after_shop_loop.
             */
            do_action('woocommerce_after_shop_loop');
        } else {
            /**
             * Hook: woocommerce_no_products_found.
             */
            do_action('woocommerce_no_products_found');
        }
        ?>
    </div>
</main>

<?php
get_footer('shop');
