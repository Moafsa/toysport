<?php
/**
 * O modelo para exibir os resultados da pesquisa
 *
 * @package ToySport
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Resultados da busca por: %s', 'toysport'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
        </header>

        <?php if (have_posts()): ?>
            <div class="posts-grid">
                <?php
                while (have_posts()):
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="post-header">
                                <?php the_title(sprintf('<h2 class="post-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                                <?php if ('post' === get_post_type()): ?>
                                    <div class="post-meta">
                                        <span class="post-date">
                                            <i class="far fa-calendar"></i>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="post-excerpt">
                                <?php echo wp_trim_words(get_the_content(), 20, '...'); ?>
                            </div>

                            <footer class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Ver detalhes', 'toysport'); ?>
                                </a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Anterior', 'toysport'),
                'next_text' => __('Próximo', 'toysport') . ' <i class="fas fa-chevron-right"></i>',
            ));
            ?>

        <?php else: ?>

            <div class="no-results not-found">
                <header class="page-header">
                    <h2 class="page-title"><?php esc_html_e('Nada encontrado', 'toysport'); ?></h2>
                </header>

                <div class="page-content">
                    <p><?php esc_html_e('Desculpe, mas nada coincidiu com seus termos de pesquisa. Por favor, tente novamente com palavras-chave diferentes.', 'toysport'); ?>
                    </p>
                    <?php get_search_form(); ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
    <?php
    get_footer();
