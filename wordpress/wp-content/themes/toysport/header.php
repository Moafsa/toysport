<?php
/**
 * Header do tema - Toy Sport
 *
 * @package ToySport
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php _e('Pular para o conteúdo', 'toysport'); ?></a>

    <header id="masthead" class="site-header">
        <!-- Top Bar -->
        <div class="header-top">
            <div class="header-top-content">
                <div class="header-top-left">
                    <span><i class="fas fa-truck"></i> <?php _e('Frete grátis para todo Brasil', 'toysport'); ?></span>
                </div>
                <div class="header-top-right">
                    <span><i class="fas fa-credit-card"></i> <?php _e('Parcelamento em até 10x sem juros', 'toysport'); ?></span>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="header-main">
            <div class="header-main-content">
                <!-- Logo -->
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>

                <!-- Navigation -->
                <nav id="site-navigation" class="main-navigation">
                    <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="menu-toggle-icon">
                            <i class="fas fa-bars"></i>
                        </span>
                        <span class="screen-reader-text"><?php _e('Menu', 'toysport'); ?></span>
                    </button>
                    
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => false,
                        'menu_class' => 'nav-menu',
                        'fallback_cb' => 'toysport_fallback_menu',
                    ]);
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search -->
                    <form role="search" method="get" class="header-search" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="search" 
                               placeholder="<?php _e('Buscar produtos...', 'toysport'); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s"
                               aria-label="<?php _e('Buscar', 'toysport'); ?>">
                        <button type="submit" aria-label="<?php _e('Buscar', 'toysport'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- User Account -->
                    <?php if (function_exists('wc_get_page_permalink')) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="header-action" title="<?php _e('Minha Conta', 'toysport'); ?>">
                            <i class="fas fa-user"></i>
                            <span class="header-action-text"><?php _e('Conta', 'toysport'); ?></span>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/wp-admin')); ?>" class="header-action" title="<?php _e('Minha Conta', 'toysport'); ?>">
                            <i class="fas fa-user"></i>
                            <span class="header-action-text"><?php _e('Conta', 'toysport'); ?></span>
                        </a>
                    <?php endif; ?>

                    <!-- Cart -->
                    <?php if (function_exists('wc_get_cart_url')) : ?>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-action cart-action" title="<?php _e('Carrinho', 'toysport'); ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="header-action-text"><?php _e('Carrinho', 'toysport'); ?></span>
                            <?php if (function_exists('WC') && WC()->cart) : ?>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

