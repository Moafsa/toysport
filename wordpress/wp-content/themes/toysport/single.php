<?php
/**
 * Template de Post Individual - Toy Sport
 *
 * @package ToySport
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                <header class="post-header">
                    <?php the_title('<h1 class="post-title">', '</h1>'); ?>
                    <div class="post-meta">
                        <span class="post-date">
                            <i class="far fa-calendar"></i>
                            <?php echo get_the_date(); ?>
                        </span>
                        <span class="post-author">
                            <i class="far fa-user"></i>
                            <?php the_author(); ?>
                        </span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <?php
                    the_content();

                    wp_link_pages([
                        'before' => '<div class="page-links">' . __('Páginas:', 'toysport'),
                        'after' => '</div>',
                    ]);
                    ?>
                </div>

                <footer class="post-footer">
                    <?php
                    if (has_category()) {
                        echo '<div class="post-categories">';
                        echo '<span>' . __('Categorias:', 'toysport') . ' </span>';
                        the_category(', ');
                        echo '</div>';
                    }

                    if (has_tag()) {
                        echo '<div class="post-tags">';
                        echo '<span>' . __('Tags:', 'toysport') . ' </span>';
                        the_tags('', ', ', '');
                        echo '</div>';
                    }
                    ?>
                </footer>
            </article>

            <?php
            the_post_navigation([
                'prev_text' => '<span class="nav-subtitle">' . __('Anterior:', 'toysport') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . __('Próximo:', 'toysport') . '</span> <span class="nav-title">%title</span>',
            ]);

            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
