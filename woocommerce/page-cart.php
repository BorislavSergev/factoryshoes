<?php
/**
 * Cart Page Template for WooCommerce
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

// We should NOT try to handle the order-received endpoint here
// This file should only handle the cart page

get_header('shop');

?>
<!-- Cart Hero Section -->
<section class="cart-hero-section">
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container">
        <div class="hero-wrapper">
            <div class="hero-content">
                <div class="sale-badge-wrapper">
                    <div class="sale-badge">
                        <i class="fas fa-shopping-cart fa-sm"></i>
                        <span><?php _e('Вашата Кошница', 'shoes-store'); ?></span>
                    </div>
                </div>
                <h1 class="hero-title"><?php _e('Прегледайте и завършете поръчката си', 'shoes-store'); ?></h1>
                <p class="hero-subtitle"><?php _e('Проверете продуктите, количествата и финализирайте покупката си.', 'shoes-store'); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="hero-secondary-button">
                        <i class="fas fa-arrow-left me-2"></i> <?php _e('Продължи пазаруването', 'shoes-store'); ?>
                    </a>
                </div>
            </div>
            <div class="hero-product">
                <div class="hero-product-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/cart-hero.png" 
                         alt="Cart" 
                         class="featured-shoe-img" 
                         loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <?php
            // Output the cart shortcode
            echo do_shortcode('[woocommerce_cart]');
            ?>
        </div>
    </div>
</div>

<!-- Cart Page Styles -->
<style>
:root {
    --primary-color: #8e44ad;
    --primary-hover: #7d3c98;
    --secondary-color: #e74c3c;
    --light-bg: #f8f9fa;
    --border-color: #eee;
    --box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

/* Hero Section */
.cart-hero-section {
    background-color: #f8f4fa;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}

.hero-shapes .shape {
    position: absolute;
    background-color: rgba(142, 68, 173, 0.05);
    border-radius: 50%;
}

.shape-1 {
    width: 300px;
    height: 300px;
    top: -100px;
    right: -100px;
}

.shape-2 {
    width: 200px;
    height: 200px;
    bottom: -80px;
    left: 10%;
}

.shape-3 {
    width: 150px;
    height: 150px;
    top: 40px;
    left: 30%;
}

.hero-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hero-content {
    max-width: 600px;
}

.sale-badge-wrapper {
    margin-bottom: 20px;
}

.sale-badge {
    display: inline-flex;
    align-items: center;
    background-color: var(--primary-color);
    color: #fff;
    padding: 8px 15px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 14px;
}

.sale-badge i {
    margin-right: 8px;
}

.hero-title {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
}

.hero-subtitle {
    font-size: 16px;
    margin-bottom: 30px;
    color: #666;
    line-height: 1.6;
}

.hero-buttons {
    display: flex;
    gap: 15px;
}

.hero-secondary-button {
    display: inline-flex;
    align-items: center;
    padding: 12px 25px;
    border: 2px solid var(--primary-color);
    border-radius: 30px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.hero-secondary-button:hover {
    background-color: var(--primary-color);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(142, 68, 173, 0.2);
}

.hero-product-image img {
    max-height: 250px;
    transform: rotate(-10deg);
    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.15));
    transition: all 0.5s ease;
}

.hero-product-image img:hover {
    transform: rotate(-5deg) translateY(-10px);
}

/* Enhanced WooCommerce Cart Styling */
.woocommerce-cart-form {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: var(--box-shadow);
    overflow: hidden;
    transition: all 0.3s ease;
    margin-bottom: 30px;
}

.woocommerce-cart-form:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.woocommerce-cart-form table {
    margin-bottom: 0 !important;
}

.woocommerce-cart-form thead {
    background-color: #f8f4fa;
}

.woocommerce-cart-form th {
    padding: 15px !important;
    font-weight: 600 !important;
    color: #333 !important;
}

.woocommerce-cart-form td {
    padding: 20px 15px !important;
    vertical-align: middle !important;
}

.woocommerce-cart-form .product-thumbnail img {
    width: 80px !important;
    height: auto !important;
    border-radius: 8px !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1) !important;
    transition: all 0.3s ease !important;
}

.woocommerce-cart-form .product-thumbnail img:hover {
    transform: scale(1.05) !important;
}

.woocommerce-cart-form .product-name a {
    color: #333 !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: color 0.2s ease !important;
}

.woocommerce-cart-form .product-name a:hover {
    color: var(--primary-color) !important;
}

.woocommerce-cart-form .product-price,
.woocommerce-cart-form .product-subtotal {
    font-weight: 600 !important;
}

.woocommerce-cart-form .product-subtotal {
    color: var(--primary-color) !important;
}

.woocommerce-cart-form .quantity .qty {
    width: 70px !important;
    height: 40px !important;
    border-radius: 30px !important;
    border: 1px solid #ddd !important;
    text-align: center !important;
    padding: 0 10px !important;
    font-weight: 600 !important;
}

.woocommerce-cart-form .product-remove a {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background-color: #f5f5f5 !important;
    color: #999 !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    font-size: 18px !important;
    margin: 0 auto !important;
}

.woocommerce-cart-form .product-remove a:hover {
    background-color: var(--secondary-color) !important;
    color: white !important;
    transform: rotate(90deg) !important;
}

.woocommerce-cart-form .actions {
    padding: 20px !important;
    background-color: #f8f9fa !important;
}

.woocommerce-cart-form .coupon .input-text {
    width: 200px !important;
    height: 48px !important;
    border-radius: 8px !important;
    border: 1px solid #ddd !important;
    padding: 0 15px !important;
    margin-right: 10px !important;
}

.woocommerce-cart-form .coupon button,
.woocommerce-cart-form button[name="update_cart"] {
    height: 48px !important;
    padding: 0 25px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    text-transform: none !important;
    letter-spacing: normal !important;
    transition: all 0.3s ease !important;
}

.woocommerce-cart-form .coupon button {
    background-color: #f8f8f8 !important;
    color: #666 !important;
    border: 1px solid #ddd !important;
}

.woocommerce-cart-form .coupon button:hover {
    background-color: #e0e0e0 !important;
}

.woocommerce-cart-form button[name="update_cart"] {
    background-color: var(--primary-color) !important;
    color: white !important;
    border: none !important;
    float: right !important;
}

.woocommerce-cart-form button[name="update_cart"]:hover {
    background-color: var(--primary-hover) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(142, 68, 173, 0.2) !important;
}

.woocommerce-cart-form button[name="update_cart"]:disabled {
    opacity: 0.5 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Cart Totals */
.cart_totals {
    background-color: #fff !important;
    border-radius: 12px !important;
    padding: 30px !important;
    box-shadow: var(--box-shadow) !important;
    transition: all 0.3s ease !important;
}

.cart_totals:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.15) !important;
    transform: translateY(-5px) !important;
}

.cart_totals h2 {
    font-size: 22px !important;
    font-weight: 700 !important;
    margin-bottom: 25px !important;
    color: #333 !important;
    padding-bottom: 10px !important;
    border-bottom: 1px solid var(--border-color) !important;
}

.cart_totals table {
    width: 100% !important;
    margin-bottom: 25px !important;
}

.cart_totals th {
    padding: 12px 0 !important;
    font-weight: 500 !important;
    color: #666 !important;
    width: 40% !important;
    text-align: left !important;
    border: none !important;
}

.cart_totals td {
    padding: 12px 0 !important;
    text-align: right !important;
    font-weight: 500 !important;
    border: none !important;
}

.cart_totals .order-total th,
.cart_totals .order-total td {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #333 !important;
    padding-top: 20px !important;
}

.cart_totals .order-total td .amount {
    color: var(--primary-color) !important;
}

.wc-proceed-to-checkout .checkout-button {
    width: 100% !important;
    padding: 15px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    border-radius: 8px !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(142, 68, 173, 0.2) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    position: relative !important;
    overflow: hidden !important;
}

.wc-proceed-to-checkout .checkout-button::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: -100% !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent) !important;
    transition: left 0.7s !important;
}

.wc-proceed-to-checkout .checkout-button:hover {
    background-color: var(--primary-hover) !important;
    border-color: var(--primary-hover) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 20px rgba(142, 68, 173, 0.3) !important;
}

.wc-proceed-to-checkout .checkout-button:hover::before {
    left: 100% !important;
}

/* Empty Cart */
.cart-empty.woocommerce-info {
    background-color: #fff !important;
    border-radius: 12px !important;
    padding: 50px 20px !important;
    text-align: center !important;
    box-shadow: var(--box-shadow) !important;
    border: none !important;
    font-size: 18px !important;
    color: #666 !important;
    margin-bottom: 20px !important;
}

.return-to-shop .button {
    background-color: var(--primary-color) !important;
    color: white !important;
    border-radius: 30px !important;
    padding: 12px 30px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.return-to-shop .button:hover {
    background-color: var(--primary-hover) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 20px rgba(142, 68, 173, 0.3) !important;
}

/* Add secure checkout badge */
.cart_totals::after {
    content: '';
    display: block;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    text-align: center;
}

.cart_totals::after {
    content: '\f023  Сигурно и защитено плащане';
    font-family: 'Font Awesome 5 Free', sans-serif;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #eee;
    gap: 10px;
}

/* Responsive Styles */
@media (max-width: 991px) {
    .hero-wrapper {
        flex-direction: column;
    }

    .hero-content {
        text-align: center;
        margin-bottom: 30px;
    }

    .hero-buttons {
        justify-content: center;
    }
}

@media (max-width: 767px) {
    .cart-hero-section {
        padding: 40px 0;
    }
    
    .hero-title {
        font-size: 28px;
    }
    
    .hero-product-image img {
        max-height: 200px;
    }
    
    .woocommerce-cart-form .shop_table_responsive thead {
        display: none;
    }
    
    .woocommerce-cart-form .shop_table_responsive tr td {
        display: block;
        text-align: right !important;
        padding: 10px 15px !important;
    }
    
    .woocommerce-cart-form .shop_table_responsive tr td::before {
        content: attr(data-title) ": ";
        font-weight: 600;
        float: left;
    }
    
    .woocommerce-cart-form .shop_table_responsive tr td.product-remove::before,
    .woocommerce-cart-form .shop_table_responsive tr td.product-thumbnail::before {
        display: none;
    }
    
    .woocommerce-cart-form .shop_table_responsive tr td.product-thumbnail {
        text-align: center !important;
    }
    
    .woocommerce-cart-form .actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .woocommerce-cart-form .coupon {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
    
    .woocommerce-cart-form .coupon .input-text {
        width: 100% !important;
        margin-right: 0 !important;
    }
    
    .woocommerce-cart-form button[name="update_cart"] {
        width: 100% !important;
        float: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add icons to checkout button
    const checkoutBtn = document.querySelector('.checkout-button');
    if (checkoutBtn) {
        checkoutBtn.innerHTML = '<i class="fas fa-lock"></i> ' + checkoutBtn.innerHTML;
    }
    
    // Add icons to update cart button
    const updateCartBtn = document.querySelector('button[name="update_cart"]');
    if (updateCartBtn) {
        updateCartBtn.innerHTML = '<i class="fas fa-sync-alt"></i> ' + updateCartBtn.innerHTML;
    }
    
    // Add icons to return to shop button
    const returnToShopBtn = document.querySelector('.return-to-shop .button');
    if (returnToShopBtn) {
        returnToShopBtn.innerHTML = '<i class="fas fa-arrow-left"></i> ' + returnToShopBtn.innerHTML;
    }
});
</script>

<?php
get_footer('shop');
?> 