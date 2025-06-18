<?php
/**
 * The header for our theme
 *
 * This version includes a unified, responsive header structure to improve
 * compatibility and fix visibility issues on mobile devices.
 *
 * @package Shoes_Store_Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preload key fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'shoes-store'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-content">
                
                <!-- 1. Site Logo (Unified for both mobile and desktop) -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                    <i class="fas fa-shoe-prints logo-icon"></i>
                    <span class="logo-text"><?php echo esc_html(get_theme_mod('site_title', 'factoryshoes')); ?></span>
                </a>

                <!-- 2. Desktop Navigation (Hidden on mobile) -->
                <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'shoes-store'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            echo '<ul class="nav-menu">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('Начало', 'shoes-store') . '</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/')) . '#products" class="products-link">' . __('Обувки', 'shoes-store') . '</a></li>';
                            echo '<li><a href="#about">' . __('За Нас', 'shoes-store') . '</a></li>';
                            echo '<li><a href="#contact">' . __('Контакти', 'shoes-store') . '</a></li>';
                            echo '</ul>';
                        }
                    ));
                    ?>
                </nav>

                <!-- 3. Header Actions (Contains mobile toggle and cart) -->
                <div class="header-actions">
                    <div class="cart-wrapper">
                        <button class="cart-toggle" aria-label="<?php esc_attr_e('Отвори количката', 'shoes-store'); ?>">
                            <i class="fas fa-shopping-bag cart-icon"></i>
                            <span class="cart-count"><?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_contents_count() : '0'; ?></span>
                        </button>
                    </div>

                    <button class="mobile-menu-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle menu', 'shoes-store'); ?>">
                        <span class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <!-- Mobile Menu Offcanvas (Remains the same) -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>
    <nav class="mobile-menu-offcanvas" id="mobile-menu">
        <div class="mobile-menu-header">
            <h3><?php _e('Меню', 'shoes-store'); ?></h3>
            <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Затвори менюто', 'shoes-store'); ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mobile-menu-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'menu-1',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'fallback_cb'    => function() {
                    echo '<ul class="mobile-nav-menu">';
                    echo '<li><a href="' . esc_url(home_url('/')) . '">' . __('Начало', 'shoes-store') . '</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/')) . '#products" class="products-link">' . __('Обувки', 'shoes-store') . '</a></li>';
                    echo '<li><a href="#about">' . __('За Нас', 'shoes-store') . '</a></li>';
                    echo '<li><a href="#contact">' . __('Контакти', 'shoes-store') . '</a></li>';
                    echo '</ul>';
                }
            ));
            ?>
        </div>
    </nav>

    <!-- Cart Offcanvas (Remains the same) -->
    <div class="cart-overlay" id="cart-overlay"></div>
    <div class="cart-offcanvas" id="cart-offcanvas">
        <div class="cart-header">
            <h3><?php _e('Количка', 'shoes-store'); ?></h3>
            <button class="cart-close" aria-label="<?php esc_attr_e('Затвори количката', 'shoes-store'); ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="cart-content widget_shopping_cart_content">
             <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
    
    <!-- CONSOLIDATED AND IMPROVED STYLES -->
    <style>
    /* General Header Styles */
    .site-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 0.5rem 0;
        position: sticky;
        top: 0;
        z-index: 999;
        transition: box-shadow 0.3s ease;
    }
    .site-header.scrolled {
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    /* Logo Styles */
    .site-logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        flex-shrink: 0;
    }
    .logo-icon {
        font-size: 1.5rem;
        color: #e74c3c;
        transition: transform 0.3s ease;
    }
    .logo-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: #2c3e50;
        letter-spacing: -0.5px;
    }
    .site-logo:hover .logo-icon {
        transform: rotate(-15deg) scale(1.1);
    }

    /* Actions Wrapper (Cart + Mobile Toggle) */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .cart-wrapper {
        position: relative;
    }
    .cart-toggle {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.3rem;
        color: #34495e;
        padding: 8px;
        position: relative;
        transition: color 0.3s ease;
    }
    .cart-toggle:hover {
        color: #e74c3c;
    }
    .cart-count {
        position: absolute;
        top: 0px;
        right: 0px;
        background: #e74c3c;
        color: white;
        font-size: 10px;
        font-weight: 600;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 2px solid white;
        transition: transform 0.3s ease;
    }
    .cart-count.updated {
        animation: pulse 0.4s ease;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }

    /* Mobile Menu Toggle (Hamburger) */
    .mobile-menu-toggle {
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        z-index: 10001; /* Above overlays */
    }
    .hamburger {
        display: flex;
        flex-direction: column;
        width: 22px;
        height: 16px;
        justify-content: space-between;
    }
    .hamburger span {
        display: block;
        height: 2px;
        width: 100%;
        background-color: #2c3e50;
        transition: all 0.3s ease-in-out;
        border-radius: 1px;
    }
    .mobile-menu-toggle.active .hamburger span:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
    }
    .mobile-menu-toggle.active .hamburger span:nth-child(2) {
        opacity: 0;
    }
    .mobile-menu-toggle.active .hamburger span:nth-child(3) {
        transform: translateY(-7px) rotate(-45deg);
    }

    /* Desktop Navigation (Hidden by default on mobile) */
    .main-navigation {
        display: none;
    }

    /* --- RESPONSIVE STYLES (for tablets and desktops) --- */
    @media (min-width: 769px) {
        .header-content {
            display: grid;
            grid-template-columns: auto 1fr auto; /* Logo | Nav | Actions */
            align-items: center;
        }
        .site-logo {
            grid-column: 1 / 2;
        }
        .header-actions {
            grid-column: 3 / 4;
            justify-self: end;
        }
        .mobile-menu-toggle {
            display: none; /* Hide hamburger on desktop */
        }
        .main-navigation {
            display: block; /* Show desktop nav */
            grid-column: 2 / 3;
            justify-self: center;
        }
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1rem;
        }
        .nav-menu a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .nav-menu a:hover,
        .nav-menu .current-menu-item > a {
            background: #f1f2f6;
            color: #2c3e50;
        }
    }


    /* --- Off-Canvas Mobile Menu Styles --- */
    .mobile-menu-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); z-index: 9998;
        opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .mobile-menu-overlay.active { opacity: 1; visibility: visible; }

    .mobile-menu-offcanvas {
        position: fixed; top: 0; left: -300px; width: 280px; height: 100vh;
        background: #fff; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        z-index: 9999; transition: left 0.3s ease; overflow-y: auto;
    }
    .mobile-menu-offcanvas.active { left: 0; }

    .mobile-menu-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem; border-bottom: 1px solid #eee;
    }
    .mobile-menu-header h3 { margin: 0; font-size: 1.25rem; font-weight: 600; }
    .mobile-menu-close { background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #666; }

    .mobile-nav-menu { margin: 0; padding: 0; list-style: none; }
    .mobile-nav-menu li a {
        display: block; padding: 0.75rem 1rem; color: #333;
        text-decoration: none; transition: all 0.3s ease; border-bottom: 1px solid #f5f5f5;
    }
    .mobile-nav-menu a:hover { background: #f8f9fa; padding-left: 1.25rem; }


    /* --- Off-Canvas Cart Styles --- */
    .cart-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); z-index: 9999;
        opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .cart-overlay.active { opacity: 1; visibility: visible; }

    .cart-offcanvas {
        position: fixed; top: 0; right: -400px; width: 90%; max-width: 400px;
        height: 100vh; background: #fff; box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        z-index: 10000; transition: right 0.3s ease;
        display: flex; flex-direction: column;
    }
    .cart-offcanvas.active { right: 0; }
    .cart-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem; border-bottom: 1px solid #eee; flex-shrink: 0;
    }
    .cart-header h3 { margin: 0; font-weight: 600; font-size: 1.25rem; }
    .cart-close { background: none; border: none; font-size: 1.25rem; cursor: pointer; color: #666; }
    
    /* WooCommerce Mini Cart Styles */
    .widget_shopping_cart_content {
        flex: 1;
        overflow-y: auto;
    }
    .woocommerce-mini-cart {
        padding: 0;
    }
    .woocommerce-mini-cart-item {
        display: flex;
        padding: 1rem;
        gap: 1rem;
        border-bottom: 1px solid #eee;
        align-items: center;
    }
    .woocommerce-mini-cart-item a:not(.remove) {
        display: block;
        flex-shrink: 0;
    }
    .woocommerce-mini-cart-item img {
        width: 60px; height: 60px; object-fit: cover; border-radius: 4px;
    }
    .woocommerce-mini-cart-item .quantity {
        font-size: 0.9rem; color: #666;
    }
    .woocommerce-mini-cart-item .remove_from_cart_button {
        color: #999 !important;
        font-size: 1.2rem !important;
        margin-left: auto;
        text-decoration: none;
    }
    .woocommerce-mini-cart-item .remove_from_cart_button:hover {
        color: #e74c3c !important;
        background: none !important;
    }
    .woocommerce-mini-cart__total {
        padding: 1rem;
        border-top: 2px solid #333;
        margin: 0;
        font-size: 1.1rem;
    }
    .woocommerce-mini-cart__buttons {
        padding: 0 1rem 1rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }
    .woocommerce-mini-cart__buttons .button {
        padding: 0.75rem !important;
        text-align: center;
        border-radius: 4px !important;
        font-weight: 600 !important;
    }
    .woocommerce-mini-cart__buttons .button.wc-forward {
        background-color: #e74c3c !important;
        color: white !important;
    }
    .woocommerce-mini-cart__empty-message {
        padding: 3rem 1rem; text-align: center; color: #7f8c8d;
    }
    
    </style>

    <!-- YOUR JAVASCRIPT (Unchanged) -->
    <script>
        // All your previous JavaScript code remains here...
        // It should work without changes because the class names are the same.
        // For brevity, I am not repeating the large script block here,
        // but you should ensure it is present in your final file.
        // Example of how it starts:
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenuClose = document.querySelector('.mobile-menu-close');
            const body = document.body;
            
            if (mobileMenuToggle && mobileMenu && mobileMenuOverlay) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('active');
                    mobileMenuOverlay.classList.toggle('active');
                    mobileMenuToggle.classList.toggle('active');
                    body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
                });
                
                // ... and so on for the rest of your script.
            }

            // Cart Toggle
            const cartToggle = document.querySelectorAll('.cart-toggle');
            const cartOffcanvas = document.getElementById('cart-offcanvas');
            const cartOverlay = document.getElementById('cart-overlay');
            const cartClose = document.querySelector('.cart-close');
            
            if (cartToggle.length && cartOffcanvas && cartOverlay) {
                cartToggle.forEach(toggle => {
                    toggle.addEventListener('click', function() {
                        cartOffcanvas.classList.toggle('active');
                        cartOverlay.classList.toggle('active');
                        body.style.overflow = cartOffcanvas.classList.contains('active') ? 'hidden' : '';
                    });
                });
                
                if (cartClose) {
                    cartClose.addEventListener('click', function() {
                        cartOffcanvas.classList.remove('active');
                        cartOverlay.classList.remove('active');
                        body.style.overflow = '';
                    });
                }
                
                cartOverlay.addEventListener('click', function() {
                    cartOffcanvas.classList.remove('active');
                    cartOverlay.classList.remove('active');
                    body.style.overflow = '';
                });
            }

            // Header scroll effect
            const header = document.querySelector('.site-header');
            if (header) {
                const scrollThreshold = 50;
                
                function handleScroll() {
                    if (window.scrollY > scrollThreshold) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                }
                
                window.addEventListener('scroll', handleScroll, { passive: true });
                handleScroll(); // Check initial scroll position
            }
            
            // NOTE: I've left out the extensive cart AJAX javascript for brevity.
            // Please make sure to include your full original script block here.
        });
    </script>
</body>
</html>
