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
                        
                        <button class="cart-toggle" aria-label="<?php esc_attr_e('Отвори количката', 'shoes-store'); ?>">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-count"><?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_contents_count() : '0'; ?></span>
                        </button>
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
                    <nav id="site-navigation" class="" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'shoes-store'); ?>">
                        <div class="nav-container">
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
                        </div>
                    </nav>

                    <!-- Desktop Header Actions -->
                    <div class="header-actions">
                        <button class="cart-toggle" aria-label="<?php esc_attr_e('Отвори количката', 'shoes-store'); ?>">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-count"><?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_contents_count() : '0'; ?></span>
                        </button>
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

            <!-- Cart Offcanvas -->
    <div class="cart-overlay" id="cart-overlay"></div>
    <div class="cart-offcanvas" id="cart-offcanvas">
        <div class="cart-header">
            <h3><?php _e('Количка', 'shoes-store'); ?></h3>
            <button class="cart-close" aria-label="<?php esc_attr_e('Затвори количката', 'shoes-store'); ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="cart-content">
            <div class="cart-items" id="cart-items">
                <?php if (class_exists('WooCommerce')) : ?>
                    <?php $cart_items = WC()->cart->get_cart(); ?>
                    <?php if (count($cart_items) > 0) : ?>
                        <?php foreach ($cart_items as $cart_item_key => $cart_item) : 
                            $product = $cart_item['data']; 
                            $product_id = $cart_item['product_id'];
                            $quantity = $cart_item['quantity'];
                            $price = WC()->cart->get_product_price($product);
                            $subtotal = WC()->cart->get_product_subtotal($product, $quantity);
                            $thumbnail = $product->get_image('thumbnail');
                        ?>
                            <div class="cart-item" data-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                <div class="cart-item-image">
                                    <?php echo $thumbnail; ?>
                                </div>
                                <div class="cart-item-details">
                                    <h4><?php echo esc_html($product->get_name()); ?></h4>
                                    <div class="cart-item-price">
                                        <?php echo $price; ?> × <?php echo esc_html($quantity); ?>
                                    </div>
                                    <div class="cart-item-quantity">
                                        <button class="cart-item-dec" data-item-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                                        <span><?php echo esc_html($quantity); ?></span>
                                        <button class="cart-item-inc" data-item-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                    </div>
                                </div>
                                <div class="cart-item-subtotal">
                                    <?php echo $subtotal; ?>
                                </div>
                                <button class="cart-item-remove" data-item-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="<?php esc_attr_e('Премахни', 'shoes-store'); ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="empty-cart">
                            <i class="fas fa-shopping-bag"></i>
                            <p><?php _e('Количката е празна', 'shoes-store'); ?></p>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-bag"></i>
                        <p><?php _e('Количката е празна', 'shoes-store'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="cart-footer">
            <div class="cart-total">
                <strong><?php _e('Общо: ', 'shoes-store'); ?><span id="cart-total">
                    <?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_subtotal() : '0,00 лв'; ?>
                </span></strong>
            </div>
            <div class="cart-actions">
                <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_cart_url()) : '#'; ?>" class="view-cart-btn">
                    <?php _e('Виж Количката', 'shoes-store'); ?>
                </a>
                <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_checkout_url()) : '#'; ?>" class="checkout-btn">
                    <?php _e('Поръчай', 'shoes-store'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <style>
    /* Cart Offcanvas Styles */
    .cart-offcanvas {
        position: fixed;
        top: 0;
        right: -400px;
        width: 90%;
        max-width: 400px;
        height: 100vh;
        background: #fff;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    .cart-offcanvas.active {
        right: 0;
    }
    
    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .cart-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .cart-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .cart-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: #666;
        transition: color 0.3s;
    }
    
    .cart-close:hover {
        color: #e74c3c;
    }
    
    .cart-content {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
        position: relative;
    }
    
    .cart-content.loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-footer {
        padding: 1rem;
        border-top: 1px solid #eee;
        background: #f8f9fa;
    }
    
    .cart-total {
        margin-bottom: 1rem;
        font-size: 1.1rem;
        text-align: right;
    }
    
    .cart-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .view-cart-btn, .checkout-btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .view-cart-btn {
        background: #f8f9fa;
        border: 1px solid #ddd;
        color: #333;
    }
    
    .view-cart-btn:hover {
        background: #eee;
    }
    
    .checkout-btn {
        background: #e74c3c;
        color: white;
        border: 1px solid #e74c3c;
    }
    
    .checkout-btn:hover {
        background:rgb(201, 70, 56);
    }
    
    .empty-cart {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
        color: #7f8c8d;
    }
    
    .empty-cart i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ddd;
    }
    
    .cart-item.updating {
        opacity: 0.5;
        pointer-events: none;
    }
    
    /* Cart Item Styles */
    .cart-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
        position: relative;
    }
    
    .cart-item-image {
        width: 60px;
        height: 60px;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .cart-item-details {
        flex: 1;
        min-width: 0;
    }
    
    .cart-item-details h4 {
        margin: 0 0 5px;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .cart-item-price {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }
    
    .cart-item-quantity {
        display: flex;
        align-items: center;
    }
    
    .cart-item-quantity button {
        width: 24px;
        height: 24px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .cart-item-quantity button:hover {
        background: #f5f5f5;
    }
    
    .cart-item-quantity span {
        margin: 0 8px;
        min-width: 20px;
        text-align: center;
    }
    
    .cart-item-subtotal {
        font-weight: 600;
        margin-left: 15px;
        font-size: 14px;
    }
    
    .cart-item-remove {
        background: none;
        border: none;
        color: #999;
        font-size: 16px;
        cursor: pointer;
        padding: 5px;
        margin-left: 10px;
        transition: color 0.2s;
    }
    
    .cart-item-remove:hover {
        color: #e74c3c;
    }
    
    /* Price updating animations */
    @keyframes priceUpdating {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    @keyframes priceUpdated {
        0% { color: #2ecc71; transform: scale(1.1); }
        100% { color: inherit; transform: scale(1); }
    }
    
    .price-updating {
        animation: priceUpdating 0.8s infinite;
    }
    
    .price-updated {
        animation: priceUpdated 1s;
        font-weight: bold;
    }
    
    /* Enhanced cart count styling */
    .cart-count, .mobile-cart-count {
        transition: all 0.3s ease;
    }
    
    .cart-count.has-items, .mobile-cart-count.has-items {
        background: #e74c3c;
        animation: pulse 1s;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
    </style>
    
    <style>
    /* Cart Offcanvas Styles */
    .cart-offcanvas {
        position: fixed;
        top: 0;
        right: -400px;
        width: 90%;
        max-width: 400px;
        height: 100vh;
        background: #fff;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        transition: right 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    .cart-offcanvas.active {
        right: 0;
    }
    
    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .cart-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .cart-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .cart-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: #666;
        transition: color 0.3s;
    }
    
    .cart-close:hover {
        color: #e74c3c;
    }
    
    .cart-content {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
        position: relative;
    }
    
    .cart-content.loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-footer {
        padding: 1rem;
        border-top: 1px solid #eee;
        background: #f8f9fa;
    }
    
    .cart-total {
        margin-bottom: 1rem;
        font-size: 1.1rem;
        text-align: right;
    }
    
    .cart-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .view-cart-btn, .checkout-btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .view-cart-btn {
        background: #f8f9fa;
        border: 1px solid #ddd;
        color: #333;
    }
    
    .view-cart-btn:hover {
        background: #eee;
    }
    
    .checkout-btn {
        background: #3498db;
        color: white;
        border: 1px solid #3498db;
    }
    
    .checkout-btn:hover {
        background: #2980b9;
    }
    
    .empty-cart {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
        color: #7f8c8d;
    }
    
    .empty-cart i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ddd;
    }
    
    .cart-item.updating {
        opacity: 0.5;
        pointer-events: none;
    }
    </style>



    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Menu Toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const body = document.body;
        
        // Toggle mobile menu when hamburger is clicked
        if (mobileMenuToggle && mobileMenu && mobileMenuOverlay) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
                mobileMenuOverlay.classList.toggle('active');
                mobileMenuToggle.classList.toggle('active');
                body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            });
            
            // Prevent clicks on menu from closing it
            mobileMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Close mobile menu when X button is clicked
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    body.style.overflow = '';
                });
            }
            
            // Close mobile menu when clicking on overlay
            mobileMenuOverlay.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                body.style.overflow = '';
            });
            
            // Close mobile menu when clicking on menu links
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
        

        
        // Add to Cart functionality with WooCommerce AJAX
        const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
        
        function updateCartContents() {
            if (typeof wc_add_to_cart_params === 'undefined') {
                return;
            }
            
            const $cartItems = jQuery('#cart-items');
            const $cartContent = $cartItems.closest('.cart-content');
            
            // Add loading state
            if ($cartItems.length) {
                $cartContent.addClass('loading');
            }
            
            // Use WooCommerce's built-in fragment refresh
            jQuery.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                data: {
                    time: new Date().getTime() // Prevent caching
                },
                success: function(response) {
                    if (response && response.fragments) {
                        // Apply each fragment
                        jQuery.each(response.fragments, function(key, value) {
                            jQuery(key).replaceWith(value);
                        });
                        
                        // Update our custom cart items if they're not in the fragments
                        if ($cartItems.length && !response.fragments['#cart-items']) {
                            // Update cart contents from custom endpoint
                            jQuery.ajax({
                                type: 'POST',
                                url: wc_add_to_cart_params.ajax_url,
                                data: {
                                    action: 'get_cart_contents'
                                },
                                success: function(cartData) {
                                    if (cartData && cartData.success) {
                                        $cartItems.html(cartData.data.html);
                                        
                                        // Update cart total display
                                        jQuery('#cart-total').html(cartData.data.total);
                                        
                                        // Update cart count badges
                                        const count = cartData.data.count || 0;
                                        document.querySelectorAll('.cart-count').forEach(elem => {
                                            elem.textContent = count;
                                        });
                                        
                                        $cartContent.removeClass('loading');
                                        
                                        // Initialize cart item actions
                                        initCartActions();
                                    }
                                },
                                error: function(error) {
                                    console.error('Error fetching cart contents:', error);
                                    $cartContent.removeClass('loading');
                                }
                            });
                        } else {
                            $cartContent.removeClass('loading');
                            
                            // Initialize cart item actions
                            initCartActions();
                        }
                        
                        // Trigger event
                        jQuery(document.body).trigger('wc_fragments_refreshed');
                    }
                },
                error: function(error) {
                    console.error('Error updating cart:', error);
                    $cartContent.removeClass('loading');
                }
            });
        }
        
        // Update cart item quantity via WooCommerce AJAX
        function updateCartItemQuantity(key, qty) {
            const $item = jQuery('.cart-item[data-item-key="' + key + '"]');
            $item.addClass('updating');
            
            console.log('Updating cart item quantity:', key, qty);
            
            // Make sure WooCommerce params are available
            if (typeof wc_add_to_cart_params === 'undefined') {
                console.error('Error: wc_add_to_cart_params is undefined');
                $item.removeClass('updating');
                return;
            }
            
            // Get the fragment nonce from our localized parameters
            const fragmentNonce = woocommerce_params.fragment_nonce || '';
            
            // Show immediate visual feedback
            const $quantitySpan = $item.find('.cart-item-quantity span');
            const oldValue = $quantitySpan.text();
            $quantitySpan.text(qty);
            
            // Apply pulsing animation to price and subtotal
            const $subtotal = $item.find('.cart-item-subtotal');
            $subtotal.addClass('price-updating');
            
            jQuery.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: 'update_cart_item_quantity',
                    cart_item_key: key,
                    quantity: qty,
                    security: fragmentNonce
                },
                success: function(response) {
                    console.log('Update quantity response:', response);
                    
                    if (!response || response.error) {
                        console.error('Error updating quantity:', response ? response.message : 'Unknown error');
                        // Revert to old value if there was an error
                        $quantitySpan.text(oldValue);
                        $item.removeClass('updating');
                        $subtotal.removeClass('price-updating');
                        return;
                    }
                    
                    // Update the item price and subtotal with updated values
                    if (response.item_data) {
                        const itemData = response.item_data;
                        if (itemData.subtotal) {
                            $subtotal.html(itemData.subtotal);
                            $subtotal.removeClass('price-updating');
                            $subtotal.addClass('price-updated');
                            
                            // Remove the class after animation completes
                            setTimeout(() => {
                                $subtotal.removeClass('price-updated');
                            }, 1000);
                        }
                    }
                    
                    // Update cart contents
                    updateCartContents();
                    
                    // Update the cart count display on all elements
                    const count = response.cart_count || 0;
                    updateAllCartCounters(count);
                    
                    // Update total in footer
                    if (response.cart_total && jQuery('#cart-total').length) {
                        jQuery('#cart-total').html(response.cart_total);
                    }
                    
                    // Trigger update event
                    jQuery(document.body).trigger('updated_cart_totals');
                    
                    $item.removeClass('updating');
                },
                error: function(error) {
                    console.error('AJAX Error updating quantity:', error);
                    // Revert to old value on error
                    $quantitySpan.text(oldValue);
                    $item.removeClass('updating');
                    $subtotal.removeClass('price-updating');
                    
                    // Fallback refresh
                    updateCartContents();
                }
            });
        }
        
        // Initialize cart actions (quantity +/- and remove)
        function initCartActions() {
            console.log('Initializing cart actions');
            
            // Quantity decrease buttons
            jQuery(document).off('click', '.cart-item-dec').on('click', '.cart-item-dec', function() {
                const itemKey = jQuery(this).attr('data-item-key');
                const cartItem = jQuery(this).closest('.cart-item');
                const quantitySpan = cartItem.find('.cart-item-quantity span');
                let currentQty = parseInt(quantitySpan.text());
                
                console.log('Decrease button clicked:', itemKey, currentQty);
                
                if (currentQty > 1) {
                    cartItem.addClass('updating');
                    updateCartItemQuantity(itemKey, currentQty - 1);
                }
            });
            
            // Quantity increase buttons
            jQuery(document).off('click', '.cart-item-inc').on('click', '.cart-item-inc', function() {
                const itemKey = jQuery(this).attr('data-item-key');
                const cartItem = jQuery(this).closest('.cart-item');
                const quantitySpan = cartItem.find('.cart-item-quantity span');
                let currentQty = parseInt(quantitySpan.text());
                
                console.log('Increase button clicked:', itemKey, currentQty);
                
                cartItem.addClass('updating');
                updateCartItemQuantity(itemKey, currentQty + 1);
            });
            
            // Remove item buttons
            jQuery(document).off('click', '.cart-item-remove').on('click', '.cart-item-remove', function() {
                const itemKey = jQuery(this).attr('data-item-key');
                const cartItem = jQuery(this).closest('.cart-item');
                
                console.log('Remove button clicked:', itemKey);
                
                cartItem.addClass('updating');
                removeCartItem(itemKey);
            });
        }
        
        // Call initCartActions on document ready and after cart updates
        jQuery(document).ready(function() {
            initCartActions();
            
            // Update cart contents when cart offcanvas is opened
            jQuery(document).on('click', '.cart-toggle', function() {
                updateCartContents();
                
                // Reinitialize cart actions after cart contents are updated
                setTimeout(() => {
                    initCartActions();
                }, 500);
            });
            
            // Add to cart via WooCommerce AJAX
            const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
            addToCartBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const productId = this.getAttribute('data-product-id');
                    const quantity = this.getAttribute('data-quantity') || 1;
                    const variationId = this.getAttribute('data-variation-id') || 0;
                    const originalText = this.innerHTML;
                    
                    // Show loading state
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Добавяне...</span>';
                    this.disabled = true;
                    
                    // Use WooCommerce's built-in add to cart functionality (jQuery)
                    jQuery(document.body).trigger('adding_to_cart', [jQuery(this), {}]);
                    
                    jQuery.ajax({
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
                                
                                // Trigger fragment refresh
                                jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, jQuery(this)]);
                                
                                // Update all cart count badges immediately
                                if (response.cart_count) {
                                    updateAllCartCounters(response.cart_count);
                                }
                                
                                // Show cart offcanvas
                                const cartOffcanvas = document.getElementById('cart-offcanvas');
                                const cartOverlay = document.getElementById('cart-overlay');
                                if (cartOffcanvas && cartOverlay) {
                                    setTimeout(() => {
                                        cartOffcanvas.classList.add('active');
                                        cartOverlay.classList.add('active');
                                        document.body.style.overflow = 'hidden';
                                        
                                        // Initialize cart actions after showing cart
                                        setTimeout(() => {
                                            initCartActions();
                                        }, 100);
                                    }, 300);
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
                
                // Only process if the href is a valid ID selector
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
                                   
                // If we're on the homepage, just scroll to the section
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
                // If on another page, the normal href navigation will occur
                // which will load the homepage and then scroll to the anchor
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
        
        // Remove cart item via WooCommerce AJAX
        function removeCartItem(key) {
            const $item = jQuery('.cart-item[data-item-key="' + key + '"]');
            $item.addClass('updating');
            
            console.log('Removing cart item with key:', key);
            
            // Make sure WooCommerce params are available
            if (typeof wc_add_to_cart_params === 'undefined') {
                console.error('Error: wc_add_to_cart_params is undefined');
                $item.removeClass('updating');
                return;
            }

            // Get the fragment nonce from our localized parameters
            const fragmentNonce = woocommerce_params.fragment_nonce || '';
            
            // Visual feedback - start fading the item
            $item.css('opacity', '0.5');
            
            jQuery.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: 'remove_cart_item',
                    cart_item_key: key,
                    security: fragmentNonce
                },
                success: function(response) {
                    console.log('Remove cart item response:', response);
                    
                    if (!response || response.error) {
                        console.error('Error removing item:', response ? response.message : 'Unknown error');
                        $item.css('opacity', '1');
                        $item.removeClass('updating');
                        return;
                    }
                    
                    // Remove the item from DOM with animation
                    $item.slideUp(300, function() {
                        $item.remove();
                        
                        // Check if cart is empty now
                        if (response.is_cart_empty || response.count === 0) {
                            const $cartItems = jQuery('#cart-items');
                            $cartItems.html('<div class="empty-cart"><i class="fas fa-shopping-bag"></i><p>Количката е празна</p></div>');
                        }
                    });
                    
                    // Update the cart count display on all elements
                    const count = response.count || 0;
                    updateAllCartCounters(count);
                    
                    // Update cart total in the footer
                    if (response.cart_total && jQuery('#cart-total').length) {
                        jQuery('#cart-total').html(response.cart_total);
                    }
                    
                    // Trigger WooCommerce events
                    jQuery(document.body).trigger('removed_from_cart');
                    
                    // Update entire cart contents
                    updateCartContents();
                },
                error: function(error) {
                    console.error('AJAX Error removing item:', error);
                    $item.css('opacity', '1');
                    $item.removeClass('updating');
                    
                    // Fallback: Try to refresh cart content
                    updateCartContents();
                }
            });
        }
        
        // Helper function to update all cart counter elements
        function updateAllCartCounters(count) {
            count = parseInt(count) || 0;
            // Get current count for comparison
            const currentCount = parseInt(jQuery('.cart-count').first().text()) || 0;
            
            // Update all cart count elements
            jQuery('.cart-count').text(count);
            jQuery('.mobile-cart-count').text(count);
            
            // Add special styling for count > 0
            if (count > 0) {
                jQuery('.cart-count, .mobile-cart-count').addClass('has-items');
            } else {
                jQuery('.cart-count, .mobile-cart-count').removeClass('has-items');
            }
            
            // Add animation class if count changed
            if (count !== currentCount) {
                jQuery('.cart-count, .mobile-cart-count').addClass('updated');
                
                // Remove the class after animation completes
                setTimeout(function() {
                    jQuery('.cart-count, .mobile-cart-count').removeClass('updated');
                }, 400); // Match animation duration
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
    .main-navigation {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
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

    .main-navigation ul {
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

    .main-navigation a.current-menu-item,
    .main-navigation a.current_page_item,
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
        border-radius: 0;
        font-weight: 400;
        color: #666;
        border-radius: 8px;
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
    .cart-count, 
    .mobile-cart-count {
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
    .cart-count.has-items,
    .mobile-cart-count.has-items {
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

    .cart-count.updated,
    .mobile-cart-count.updated {
        animation: pulse 0.4s ease;
    }

    /* Mobile menu hamburger styles */
    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .mobile-menu-toggle:hover {
        background: rgba(44, 62, 80, 0.05);
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

    /* Mobile and Tablet Responsive */
    @media (max-width: 1024px) {
        .main-navigation {
            gap: 0.8rem;
            padding: 6px 16px;
        }
        
        .main-navigation a {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
        
        .header-actions {
            gap: 0.8rem;
        }
        

    }

    /* Mobile Navigation */
    @media (max-width: 768px) {
        .header-content {
            grid-template-columns: 1fr auto;
            gap: 1rem;
            position: relative;
            justify-content: space-between;
            align-items: center;
        }

        .site-branding {
            order: 1;
        }

        .mobile-menu-toggle {
            display: block;
            order: 2;
            z-index: 1002;
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: rgba(44, 62, 80, 0.05);
        }

        /* Hide desktop header actions on mobile */
        .header-actions {
            display: none;
        }

        /* Off-canvas mobile menu */
        .main-navigation {
            position: fixed;
            top: 0;
            left: -100%;
            width: 320px;
            height: 100vh;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            padding: 0;
            margin: 0;
            flex-direction: column;
            gap: 0;
            z-index: 1001;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            border-radius: 0;
            backdrop-filter: none;
            transform: none;
        }

        .main-navigation.active {
            left: 0;
        }

        /* Show mobile menu header only on mobile */
        .mobile-menu-header {
            display: flex;
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }



        .mobile-menu-header .cart-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #2c3e50;
            color: white;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }

        .mobile-menu-header .cart-link:hover {
            background: #1a252f;
            transform: scale(1.05);
        }

        .mobile-menu-header .cart-icon {
            font-size: 1.1rem;
        }

        .mobile-menu-header .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Mobile menu navigation items */
        .main-navigation ul {
            flex-direction: column;
            gap: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .main-navigation li {
            width: 100%;
            border-bottom: 1px solid #f0f0f0;
        }

        .main-navigation a {
            padding: 18px 20px;
            border-radius: 0;
            text-align: left;
            font-weight: 500;
            width: 100%;
            font-size: 1rem;
            color: #333;
            transition: all 0.3s ease;
            border-bottom: none;
        }

        .main-navigation a:hover {
            background: #f8f9fa;
            color: #2c3e50;
            transform: none;
            padding-left: 25px;
        }

        /* Mobile submenu styles */
        .main-navigation ul ul {
            position: static;
            opacity: 1;
            transform: none;
            pointer-events: auto;
            box-shadow: none;
            background: #f8f9fa;
            margin: 0;
            border-radius: 0;
            width: 100%;
            padding: 0;
        }

        .main-navigation ul ul li {
            border-bottom: 1px solid #e9ecef;
        }

        .main-navigation ul ul a {
            margin: 0;
            padding: 15px 30px;
            font-size: 0.95rem;
            background: #f8f9fa;
        }

        .main-navigation ul ul a:hover {
            background: #e9ecef;
            padding-left: 35px;
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

        /* Hamburger animation */
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
            transition: all 0.3s ease;
            border-radius: 1px;
        }
        
        .mobile-menu-toggle[aria-expanded="true"] .hamburger span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .mobile-menu-toggle[aria-expanded="true"] .hamburger span:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle[aria-expanded="true"] .hamburger span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
    }

    @media (max-width: 480px) {
        .header-content {
            padding: 0.8rem 0;
            gap: 0.8rem;
        }
        


        .header-actions {
            gap: 0.5rem;
        }

        .cart-link {
            padding: 8px;
        }
        
        .cart-icon {
            font-size: 1.1rem;
        }

        .logo-text {
            font-size: 1.3rem;
        }
        
        .logo-icon {
            font-size: 1.2rem;
        }

        .main-navigation a {
            padding: 12px 16px;
            font-size: 0.95rem;
        }
        
        .main-navigation ul ul a {
            padding: 10px 16px;
            font-size: 0.9rem;
        }

        .mobile-menu-toggle {
            padding: 6px;
        }
        
        .hamburger {
            width: 18px;
            height: 13px;
        }
    }
    
    @media (max-width: 360px) {
        .header-content {
            padding: 0.6rem 0;
            gap: 0.6rem;
        }
        

        
        .logo-text {
            font-size: 1.2rem;
        }
        
        .header-actions {
            gap: 0.4rem;
        }
        
        .cart-link {
            padding: 6px;
        }
        
        .main-navigation a {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
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
            display: none;
        }
    }
    </style>
</body>
</html> 