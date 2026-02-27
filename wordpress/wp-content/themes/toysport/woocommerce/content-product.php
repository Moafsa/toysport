<?php
/**
 * Custom Product Card Template for ToySport
 * Modern, card-based layout (Magalu/Netshoes Style)
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<li <?php wc_product_class('', $product); ?>>
    <div class="product-card">

        <?php
        // Badge
        if ($product->is_on_sale()): ?>
            <span class="product-badge">Oferta!</span>
        <?php endif; ?>

        <!-- Image Wrapper -->
        <div class="product-image">
            <a href="<?php echo esc_url(get_permalink()); ?>">
                <?php
                // Force display of image - prioritizing 'woocommerce_thumbnail' size
                if ($product->get_image_id()) {
                    echo $product->get_image('woocommerce_thumbnail');
                } elseif (has_post_thumbnail()) {
                    the_post_thumbnail('woocommerce_thumbnail');
                } else {
                    echo wc_placeholder_img('woocommerce_thumbnail');
                }
                ?>
            </a>
        </div>

        <!-- Info Wrapper -->
        <div class="product-info">

            <h2 class="product-title">
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <?php echo get_the_title(); ?>
                </a>
            </h2>

            <div class="product-price">
                <!-- Main Price -->
                <?php if ($price_html = $product->get_price_html()): ?>
                    <span class="product-price-current"><?php echo $price_html; ?></span>
                <?php endif; ?>

                <!-- Installment Calculation Mock -->
                <?php
                $price = $product->get_price();
                if ($price > 0) {
                    $installment_count = 10;
                    $min_installment = 5.00; // Minimum installment value
                
                    $installment_value = $price / $installment_count;

                    if ($installment_value < $min_installment) {
                        $installment_count = floor($price / $min_installment);
                        if ($installment_count > 0)
                            $installment_value = $price / $installment_count;
                    }

                    if ($installment_count > 1) {
                        // Format: "ou 10x de R$ 9,99 sem juros"
                        echo '<span class="product-price-installment">ou ' . $installment_count . 'x de ' . wc_price($installment_value) . ' sem juros</span>';
                    } else {
                        echo '<span class="product-price-installment">à vista no Pix</span>';
                    }
                }
                ?>
            </div>

            <div class="product-actions">
                <a href="?add-to-cart=<?php echo esc_attr($product->get_id()); ?>" class="btn btn-primary"
                    aria-label="Adicionar <?php echo esc_attr(get_the_title()); ?> ao carrinho">
                    Comprar
                </a>
            </div>

        </div>
    </div>
</li>