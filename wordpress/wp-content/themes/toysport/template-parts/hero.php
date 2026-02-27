<?php
/**
 * Hero Section - Toy Sport
 *
 * @package ToySport
 */
?>

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">
            <?php _e('Shopping dos Brinquedos', 'toysport'); ?>
        </h1>
        <p class="hero-subtitle">
            <?php _e('Toda loja em até 10x sem juros', 'toysport'); ?>
        </p>
        <?php if (function_exists('wc_get_page_id')): ?>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="hero-cta">
                <?php _e('Ver Produtos', 'toysport'); ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
</section>

<?php if (function_exists('WC') && wc_get_page_id('shop')): ?>
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Destaques', 'toysport'); ?></h2>
                <p class="section-subtitle"><?php _e('Produtos em destaque para você', 'toysport'); ?></p>
            </div>

            <?php
            $args = [
                'limit' => 8,
                'featured' => true,
                'orderby' => 'date',
                'order' => 'DESC',
            ];

            $products = wc_get_products($args);

            if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-image">
                                <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                                <?php if ($product->is_featured()): ?>
                                    <span class="product-badge"><?php _e('Destaque', 'toysport'); ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h3>
                                <div class="product-price">
                                    <span class="product-price-current">
                                        <?php echo $product->get_price_html(); ?>
                                    </span>
                                    <?php if ($product->get_price()): ?>
                                        <span class="product-price-installment">
                                            <?php _e('Em até 10x sem juros', 'toysport'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-actions">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary">
                                        <?php _e('Ver Produto', 'toysport'); ?>
                                    </a>
                                    <?php if ($product->is_in_stock()): ?>
                                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                            class="btn btn-secondary add-to-cart"
                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    </section>
<?php endif; ?>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php _e('Navegue por Categorias', 'toysport'); ?></h2>
        </div>
        <div class="categories-grid">
            <?php
            $cat_args = [
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number' => 6,
                'orderby' => 'count',
                'order' => 'DESC',
            ];
            $categories = get_terms($cat_args);

            if (!empty($categories) && !is_wp_error($categories)):
                foreach ($categories as $cat):
                    $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                    $image_url = wp_get_attachment_url($thumbnail_id);
                    if (!$image_url) {
                        $image_url = wc_placeholder_img_src();
                    }
                    ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="category-card">
                        <div class="category-image">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($cat->name); ?>"
                                loading="lazy">
                        </div>
                        <h3 class="category-title"><?php echo esc_html($cat->name); ?></h3>
                    </a>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- Social Proof / Testimonials -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php _e('O que dizem nossos clientes', 'toysport'); ?></h2>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    "<?php _e('Site muito fácil de comprar e a entrega foi super rápida! Recomendo a todos.', 'toysport'); ?>"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">MS</div>
                    <div class="author-info">
                        <strong>Maria Silva</strong>
                        <span>São Paulo, SP</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    "<?php _e('Adorei o atendimento via WhatsApp, tiraram todas as minhas dúvidas sobre o brinquedo.', 'toysport'); ?>"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">JP</div>
                    <div class="author-info">
                        <strong>João Pereira</strong>
                        <span>Rio de Janeiro, RJ</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    "<?php _e('Produtos de ótima qualidade e preço justo. Com certeza voltarei a comprar.', 'toysport'); ?>"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">AL</div>
                    <div class="author-info">
                        <strong>Ana Lima</strong>
                        <span>Curitiba, PR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="feature-title"><?php _e('Receba em casa', 'toysport'); ?></h3>
                <p class="feature-description"><?php _e('Enviamos para todo Brasil', 'toysport'); ?></p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3 class="feature-title"><?php _e('Devolução', 'toysport'); ?></h3>
                <p class="feature-description"><?php _e('7 Dias após o recebimento', 'toysport'); ?></p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3 class="feature-title"><?php _e('Parcele em até 10x', 'toysport'); ?></h3>
                <p class="feature-description"><?php _e('Toda loja em até 10x sem juros', 'toysport'); ?></p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title"><?php _e('Site 100% seguro', 'toysport'); ?></h3>
                <p class="feature-description">
                    <?php _e('Loja com espaço físico localizada em Xanxerê-SC', 'toysport'); ?></p>
            </div>
        </div>
    </div>
</section>