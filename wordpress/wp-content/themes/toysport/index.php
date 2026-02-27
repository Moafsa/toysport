<?php
/**
 * Template padrão - Toy Sport
 *
 * @package ToySport
 */

get_header();
?>

<main id="main" class="site-main">
    <?php if (is_front_page()) : ?>
        <?php get_template_part('template-parts/hero'); ?>
    <?php endif; ?>

    <div class="container">
        <?php if (have_posts()) : ?>
            <?php if (!is_front_page()) : ?>
                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                    ?>
                </header>
            <?php endif; ?>

            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="post-header">
                                <?php the_title('<h2 class="post-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                                <div class="post-meta">
                                    <span class="post-date">
                                        <i class="far fa-calendar"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                </div>
                            </header>

                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php _e('Ler mais', 'toysport'); ?>
                                </a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Anterior', 'toysport'),
                'next_text' => __('Próximo', 'toysport') . ' <i class="fas fa-chevron-right"></i>',
            ]);
            ?>

        <?php else : ?>
            <div class="no-content">
                <h2><?php _e('Nada encontrado', 'toysport'); ?></h2>
                <p><?php _e('Desculpe, mas não encontramos nada aqui.', 'toysport'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
