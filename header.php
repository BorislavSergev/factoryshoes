<?php
/**
 * The header for our theme
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
                <!-- Mobile Header Layout -->
                <div class="mobile-header">
                    <!-- Logo -->
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                        <i class="fas fa-shoe-prints"></i>
                        <?php echo esc_html(get_theme_mod('site_title', 'factoryshoes')); ?>
                    </a>
                    
                    <!-- Mobile Actions -->
                    <div class="mobile-actions">
                        <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle menu', 'shoes-store'); ?>">
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                        
                        <!-- Cart Link for Mobile -->
                        <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_cart_url()) : '#'; ?>" class="mobile-cart-link" aria-label="<?php esc_attr_e('View your shopping cart', 'shoes-store'); ?>">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-count mobile-cart-count"><?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_contents_count() : '0'; ?></span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Header Layout -->
                <div class="desktop-header">
                    <!-- Logo -->
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                        <i class="fas fa-shoe-prints"></i>
                        <?php echo esc_html(get_theme_mod('site_title', 'factoryshoes')); ?>
                    </a>

                    <!-- Desktop Navigation -->
                    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'shoes-store'); ?>">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'nav-menu',
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

                    <!-- Desktop Header Actions -->
                    <div class="header-actions">
                        <!-- Cart Link for Desktop -->
                        <div class="cart-wrapper">
                            <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_cart_url()) : '#'; ?>" class="cart-link" aria-label="<?php esc_attr_e('View your shopping cart', 'shoes-store'); ?>">
                                <i class="fas fa-shopping-bag cart-icon"></i>
                                <span class="cart-count"><?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_contents_count() : '0'; ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Offcanvas -->
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
    
    <script>
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
            
            mobileMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    body.style.overflow = '';
                });
            }
            
            mobileMenuOverlay.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                body.style.overflow = '';
            });
            
            const mobileMenuLinks = mobileMenu.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(function() {
                        mobileMenu.classList.remove('active');
                        mobileMenuOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                        body.style.overflow = '';
                    }, 100);
                });
            });
        }
        
        // Add to Cart functionality with WooCommerce AJAX
        jQuery(document).ready(function($) {
            // Add to cart via WooCommerce AJAX
            const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
            addToCartBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const $thisbutton = $(this);
                    const productId = this.getAttribute('data-product-id');
                    const quantity = this.getAttribute('data-quantity') || 1;
                    const variationId = this.getAttribute('data-variation-id') || 0;
                    const originalText = this.innerHTML;
                    
                    // Show loading state
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Добавяне...</span>';
                    this.disabled = true;
                    
                    // Use WooCommerce's built-in add to cart functionality (jQuery)
                    $(document.body).trigger('adding_to_cart', [$thisbutton, {}]);
                    
                    $.ajax({
                        type: 'POST',
                        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                        data: {
                            product_id: productId,
                            variation_id: variationId,
                            quantity: quantity
                        },
                        success: (response) => {
                            if (response.error) {
                                // Error state
                                this.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Грешка</span>';
                                this.style.background = 'linear-gradient(45deg, #e74c3c, #c0392b)';
                                console.error('Error adding to cart:', response.message);
                            } else {
                                // Success state
                                this.innerHTML = '<i class="fas fa-check"></i> <span>Добавено!</span>';
                                this.style.background = 'linear-gradient(45deg, #27ae60, #2ecc71)';
                                
                                // Trigger fragment refresh to update cart count in header
                                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                                
                                // Update all cart count badges immediately
                                // Note: The 'added_to_cart' trigger usually handles this, 
                                // but we can call our helper for instant feedback.
                                if (response.fragments && response.fragments['a.cart-contents']) {
                                    // Let WC handle it. But if not, we can find the count.
                                    // Let's find the new count from the fragment if possible
                                    let newCount = $(response.fragments['a.cart-contents']).find('.count').text();
                                    if(newCount) {
                                       updateAllCartCounters(newCount.replace(/\D/g, ''));
                                    }
                                }
                            }
                            
                            // Reset button after 2 seconds
                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.style.background = '';
                                this.disabled = false;
                            }, 2000);
                        },
                        error: (error) => {
                            console.error('Error:', error);
                            // Error state
                            this.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Грешка</span>';
                            this.style.background = 'linear-gradient(45deg, #e74c3c, #c0392b)';
                            
                            // Reset button after 2 seconds
                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.style.background = '';
                                this.disabled = false;
                            }, 2000);
                        }
                    });
                    
                    // Add visual feedback
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Re-run the counter update after WC fragments are refreshed.
            $(document.body).on('wc_fragments_refreshed', function() {
                const newCount = $('.cart-count').first().text() || '0';
                updateAllCartCounters(newCount);
            });

        });
        
        // Header scroll effect
        const header = document.querySelector('.site-header');
        const scrollThreshold = 50;
        
        function handleScroll() {
            if (window.scrollY > scrollThreshold) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
        
        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Check initial scroll position
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                if (targetId && targetId !== '#' && document.querySelector(targetId)) {
                    e.preventDefault();
                    
                    const targetElement = document.querySelector(targetId);
                    const headerHeight = header.offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                    
                    window.scrollTo({
                        top: targetPosition - headerHeight - 20, // 20px extra padding
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Handle products link for scrolling to products section
        document.querySelectorAll('.products-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const isHomePage = window.location.pathname === '/' || 
                                   window.location.pathname === '/index.php' || 
                                   window.location.href === window.location.origin + '/' ||
                                   window.location.href === window.location.origin;
                                   
                if (isHomePage) {
                    e.preventDefault();
                    const targetId = href.split('#')[1];
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        const headerHeight = header.offsetHeight;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                        
                        window.scrollTo({
                            top: targetPosition - headerHeight - 20, // 20px extra padding
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Back to top button
        const backToTopButton = document.getElementById('back-to-top');
        if (backToTopButton) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });
            
            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        
        // Helper function to update all cart counter elements
        function updateAllCartCounters(count) {
            count = parseInt(count) || 0;
            const currentCount = parseInt(jQuery('.cart-count').first().text()) || 0;
            
            jQuery('.cart-count').text(count);
            jQuery('.mobile-cart-count').text(count);
            
            if (count > 0) {
                jQuery('.cart-count, .mobile-cart-count').addClass('has-items');
            } else {
                jQuery('.cart-count, .mobile-cart-count').removeClass('has-items');
            }
            
            if (count !== currentCount) {
                jQuery('.cart-count, .mobile-cart-count').addClass('updated');
                
                setTimeout(function() {
                    jQuery('.cart-count, .mobile-cart-count').removeClass('updated');
                }, 400); 
            }
        }
    });
    </script>

    <style>
    /* Header Updates */
    .header-content {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem 0;
        position: relative;
    }

    /* Professional Logo Styling */
    .site-logo {
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logo-text {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2c3e50;
        text-transform: lowercase;
        letter-spacing: -0.5px;
        position: relative;
    }

    .logo-icon {
        font-size: 1.4rem;
        color: #e74c3c;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .site-logo:hover .logo-text {
        color: #e74c3c;
        transition: color 0.3s ease;
    }

    .site-logo:hover .logo-icon {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Navigation Styling */
    #site-navigation {
        justify-self: center; /* Center the nav container */
    }
    
    .main-navigation {
        display: flex;
        list-style: none;
        gap: 1rem;
        margin: 0;
        padding: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 8px 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    /* Hide mobile menu header on desktop */
    .mobile-menu-header {
        display: none;
    }

    .main-navigation .nav-menu {
        display: flex;
        list-style: none;
        gap: 1rem;
        margin: 0;
        padding: 0;
    }

    .main-navigation li {
        margin: 0;
        position: relative;
    }

    .main-navigation a {
        text-decoration: none;
        color: #555;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 8px 16px;
        border-radius: 20px;
        transition: all 0.3s ease;
        position: relative;
        display: block;
        white-space: nowrap;
    }

    .main-navigation a:hover {
        color: #2c3e50;
        background: rgba(44, 62, 80, 0.05);
        transform: translateY(-1px);
    }

    .main-navigation .current-menu-item > a,
    .main-navigation .current_page_item > a {
        color: #2c3e50;
        background: rgba(44, 62, 80, 0.1);
        font-weight: 600;
    }

    /* Submenu styles */
    .main-navigation ul ul {
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        padding: 0.5rem 0;
        min-width: 200px;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 1000;
    }

    .main-navigation li:hover > ul {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .main-navigation ul ul li {
        width: 100%;
    }

    .main-navigation ul ul a {
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 400;
        color: #666;
        margin: 0 8px;
    }

    .main-navigation ul ul a:hover {
        background: rgba(44, 62, 80, 0.05);
        color: #2c3e50;
        transform: none;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        justify-self: end;
    }

    /* Cart Styles */
    .cart-wrapper {
        position: relative;
    }

    .cart-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #333;
        padding: 10px;
        border-radius: 50%;
        transition: all 0.3s ease;
        position: relative;
        background: rgba(44, 62, 80, 0.05);
    }

    .cart-link:hover {
        background: rgba(44, 62, 80, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Cart Count Badge */
    .cart-count {
        position: absolute;
        top: 0;
        right: 0;
        background: #f8f8f8;
        color: #666;
        border: 1px solid #eee;
        font-size: 0.7rem;
        font-weight: 600;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    /* Special styling for non-empty cart */
    .cart-count.has-items {
        background: #e74c3c;
        color: white;
        border-color: #e74c3c;
        transform: scale(1.1);
    }

    /* Animation for counter update */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1.1); }
    }

    .cart-count.updated {
        animation: pulse 0.4s ease;
    }

    /* Mobile menu hamburger styles */
    .mobile-menu-toggle {
        display: none; /* Hidden by default */
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background 0.3s ease;
    }
    
    .hamburger {
        display: flex;
        flex-direction: column;
        width: 20px;
        height: 15px;
        justify-content: space-between;
    }
    
    .hamburger span {
        display: block;
        height: 2px;
        width: 100%;
        background-color: #2c3e50;
        transition: all 0.3s ease;
        border-radius: 1px;
    }
    
    .mobile-menu-toggle[aria-expanded="true"] .hamburger span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }
    
    .mobile-menu-toggle[aria-expanded="true"] .hamburger span:nth-child(2) {
        opacity: 0;
    }
    
    .mobile-menu-toggle[aria-expanded="true"] .hamburger span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }
    
    .screen-reader-text {
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
    
    .mobile-header {
        display: none; /* Hide mobile header by default */
    }

    /* Mobile and Tablet Responsive */
    @media (max-width: 1024px) {
        .main-navigation {
            gap: 0.5rem;
            padding: 6px 16px;
        }
        
        .main-navigation a {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
    }

    /* Mobile Navigation */
    @media (max-width: 768px) {
        .desktop-header {
            display: none; /* Hide desktop header on mobile */
        }
        
        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .mobile-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .mobile-menu-toggle {
            display: block;
        }

        .mobile-cart-link {
            position: relative;
            font-size: 1.5rem;
            color: #333;
            padding: 8px;
        }
        
        .mobile-cart-link .mobile-cart-count {
            top: 2px;
            right: 2px;
        }

        .logo-text {
            font-size: 1.5rem;
        }

        /* Mobile overlay */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }
    }
    </style>

    <style>
    /* Mobile Menu Styles */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .mobile-menu-offcanvas {
        position: fixed;
        top: 0;
        left: -300px;
        width: 280px;
        height: 100vh;
        background: #fff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        transition: left 0.3s ease;
        overflow-y: auto;
    }

    .mobile-menu-offcanvas.active {
        left: 0;
    }

    .mobile-menu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .mobile-menu-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .mobile-menu-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: #666;
        transition: color 0.3s;
    }

    .mobile-menu-close:hover {
        color: #e74c3c;
    }

    .mobile-menu-content {
        padding: 1rem 0;
    }

    .mobile-nav-menu {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .mobile-nav-menu li {
        border-bottom: 1px solid #f5f5f5;
    }

    .mobile-nav-menu li:last-child {
        border-bottom: none;
    }

    .mobile-nav-menu a {
        display: block;
        padding: 0.75rem 1rem;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .mobile-nav-menu a:hover {
        background: #f8f9fa;
        padding-left: 1.25rem;
    }

    @media (min-width: 769px) {
        .mobile-menu-offcanvas,
        .mobile-menu-overlay {
            display: none !important;
        }
    }
    </style>
</body>
</html>
