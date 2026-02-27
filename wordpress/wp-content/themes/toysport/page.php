<?php
/**
 * Template de Página - Toy Sport
 *
 * @package ToySport
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        // Breadcrumbs
        if (!is_front_page()) {
            if (function_exists('woocommerce_breadcrumb')) {
                woocommerce_breadcrumb([
                    'delimiter' => ' / ',
                    'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="breadcrumb"><div class="breadcrumbs">',
                    'wrap_after' => '</div></nav>',
                ]);
            } else {
                toysport_breadcrumbs();
            }
        }
        ?>
        
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                <header class="page-header">
                    <?php the_title('<h1 class="page-title">', '</h1>'); ?>
                </header>

                <div class="page-content-inner">
                    <?php
                    the_content();

                    wp_link_pages([
                        'before' => '<div class="page-links">' . __('Páginas:', 'toysport'),
                        'after' => '</div>',
                    ]);
                    ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
