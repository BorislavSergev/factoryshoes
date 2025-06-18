<?php
/**
 * Template Name: Checkout Page
 * 
 * Custom checkout page template that integrates with WooCommerce
 * 
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="checkout-page">
    <div class="checkout-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="checkout-header mb-4">
                    <h1 class="checkout-title"><?php esc_html_e('Checkout', 'shoes-store'); ?></h1>
                </div>
                
                <?php
                // Check if WooCommerce is active
                if (class_exists('WooCommerce')) {
                    // Check if cart is empty
                    if (WC()->cart->is_empty()) {
                        ?>
                        <div class="empty-cart-message text-center py-5">
                            <i class="fas fa-shopping-cart mb-4" style="font-size: 64px; color: #8e44ad;"></i>
                            <h2><?php esc_html_e('Your cart is empty', 'shoes-store'); ?></h2>
                            <p class="mb-4"><?php esc_html_e('Looks like you haven\'t added any products to your cart yet.', 'shoes-store'); ?></p>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left mr-2"></i> <?php esc_html_e('Return to Shop', 'shoes-store'); ?>
                            </a>
                        </div>
                        <?php
                    } else {
                        // Cart is not empty, show checkout form
                        ?>
                        <div class="woocommerce">
                            <?php echo do_shortcode('[woocommerce_checkout]'); ?>
                        </div>

                        <!-- Checkout Features -->
                        <div class="checkout-features mt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="checkout-feature">
                                        <i class="fas fa-truck-fast"></i>
                                        <span><?php esc_html_e('Fast Delivery', 'shoes-store'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="checkout-feature">
                                        <i class="fas fa-shield-alt"></i>
                                        <span><?php esc_html_e('Quality Guarantee', 'shoes-store'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="checkout-feature">
                                        <i class="fas fa-exchange-alt"></i>
                                        <span><?php esc_html_e('30 Days Return', 'shoes-store'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="woocommerce-not-active text-center py-5">
                        <i class="fas fa-exclamation-triangle mb-4" style="font-size: 64px; color: #e74c3c;"></i>
                        <h2><?php esc_html_e('WooCommerce is not active', 'shoes-store'); ?></h2>
                        <p><?php esc_html_e('Please activate WooCommerce plugin to use the checkout functionality.', 'shoes-store'); ?></p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Checkout Page Styles */
.checkout-page {
    position: relative;
    background-color: #f9f9f9;
    padding: 40px 0;
    overflow-x: hidden;
}

/* Floating Shapes */
.checkout-shapes {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
}

.checkout-shapes .shape {
    position: absolute;
    opacity: 0.05;
    z-index: -1;
}

.checkout-shapes .shape-1 {
    top: -10%;
    right: -5%;
    width: 500px;
    height: 500px;
    background: #8e44ad;
    border-radius: 50%;
    animation: floatAnimation 10s ease-in-out infinite;
}

.checkout-shapes .shape-2 {
    bottom: -15%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: #e74c3c;
    border-radius: 50%;
    animation: floatAnimation 12s ease-in-out infinite 1s;
}

.checkout-shapes .shape-3 {
    top: 40%;
    right: 15%;
    width: 300px;
    height: 300px;
    background: #3498db;
    border-radius: 50%;
    animation: floatAnimation 8s ease-in-out infinite 0.5s;
}

@keyframes floatAnimation {
    0%, 100% {
        transform: translateY(0) scale(1);
    }
    50% {
        transform: translateY(20px) scale(1.05);
    }
}

.checkout-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 0;
    position: relative;
    display: inline-block;
}

.checkout-title::after {
    content: '';
    position: absolute;
    bottom: -16px;
    left: 0;
    width: 80px;
    height: 3px;
    background: #8e44ad;
    border-radius: 3px;
}

/* WooCommerce Form Styling */
.woocommerce form .form-row {
    margin-bottom: 20px;
}

.woocommerce form .form-row label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
    color: #555;
}

.woocommerce form .form-row input.input-text,
.woocommerce form .form-row select,
.woocommerce form .form-row textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    background-color: #f9f9f9;
}

.woocommerce form .form-row input.input-text:focus,
.woocommerce form .form-row select:focus,
.woocommerce form .form-row textarea:focus {
    border-color: #8e44ad;
    box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
    outline: none;
    background-color: #fff;
}

/* Checkout Features */
.checkout-features {
    margin-top: 30px;
}

.checkout-feature {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    height: 100%;
    transition: transform 0.3s;
}

.checkout-feature:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.checkout-feature i {
    font-size: 20px;
    color: #8e44ad;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(142, 68, 173, 0.1);
    border-radius: 50%;
}

.checkout-feature span {
    font-weight: 500;
    color: #333;
}

/* WooCommerce Checkout Form */
.woocommerce-checkout #customer_details,
.woocommerce-checkout #order_review {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-bottom: 30px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.woocommerce-checkout #customer_details:hover,
.woocommerce-checkout #order_review:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.woocommerce-checkout h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    color: #333;
}

/* Payment Methods */
.woocommerce-checkout #payment {
    background-color: transparent;
    border-radius: 8px;
}

.woocommerce-checkout #payment ul.payment_methods {
    border-bottom: 1px solid #eee;
    padding: 20px 0;
}

.woocommerce-checkout #payment ul.payment_methods li {
    margin-bottom: 10px;
}

.woocommerce-checkout #payment div.payment_box {
    background-color: #f8f4fa;
    border-left: 4px solid #8e44ad;
    color: #333;
}

.woocommerce-checkout #payment div.payment_box::before {
    border-bottom-color: #f8f4fa;
}

/* Place Order Button */
.woocommerce #payment #place_order {
    background-color: #8e44ad;
    color: #fff;
    padding: 15px 30px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s;
    border: none;
    width: 100%;
    margin-top: 20px;
}

.woocommerce #payment #place_order:hover {
    background-color: #7d3c98;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(142, 68, 173, 0.2);
}

/* Responsive Styles */
@media (max-width: 991px) {
    .checkout-title {
        font-size: 28px;
    }
    
    .woocommerce-checkout #customer_details,
    .woocommerce-checkout #order_review {
        padding: 20px;
    }
    
    .woocommerce-checkout h3 {
        font-size: 18px;
    }
}

@media (max-width: 576px) {
    .checkout-title {
        font-size: 24px;
    }
    
    .woocommerce form .form-row {
        margin-bottom: 15px;
    }
    
    .woocommerce form .form-row label {
        font-size: 13px;
    }
    
    .woocommerce form .form-row input.input-text,
    .woocommerce form .form-row select,
    .woocommerce form .form-row textarea {
        padding: 10px 12px;
        font-size: 14px;
    }
    
    .checkout-feature {
        padding: 12px 15px;
    }
    
    .checkout-feature i {
        width: 35px;
        height: 35px;
        font-size: 16px;
    }
    
    .checkout-feature span {
        font-size: 14px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Show loading indicator on form submit
    $('form.checkout').on('submit', function() {
        $('#place_order').html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });
    
    // Add animation to checkout features
    $('.checkout-feature').each(function(index) {
        $(this).css('animation-delay', (index * 0.15) + 's');
        $(this).addClass('fade-in');
    });
});
</script>

<?php
get_footer();
?> 