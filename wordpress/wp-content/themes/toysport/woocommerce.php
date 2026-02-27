<?php
/**
 * Template WooCommerce - Toy Sport
 *
 * @package ToySport
 */

get_header('shop');
?>

<main id="main" class="site-main woocommerce-page">
    <?php
    if (is_shop() || is_product_category() || is_product_tag()) {
        ?>
        <div class="container">
            <header class="woocommerce-products-header">
                <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                    <h1 class="woocommerce-products-header__title page-title">
                        <?php woocommerce_page_title(); ?>
                    </h1>
                <?php endif; ?>

                <?php
                do_action('woocommerce_archive_description');
                ?>
            </header>

            <?php
            if (woocommerce_product_loop()) {
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

                do_action('woocommerce_after_shop_loop');
            } else {
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>
        <?php
    } elseif (is_product()) {
        wc_get_template_part('single-product');
    }
    ?>
</main>

<?php
get_footer('shop');
