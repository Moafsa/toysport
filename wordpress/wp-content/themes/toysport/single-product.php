<?php
/**
 * Template de Produto Individual - Toy Sport
 *
 * @package ToySport
 */

get_header('shop');
?>

<main id="main" class="site-main woocommerce-page">
    <div class="container">
        <?php
        while (have_posts()) {
            the_post();
            wc_get_template_part('content', 'single-product');
        }
        ?>
    </div>
</main>

<?php
get_footer('shop');
