<?php
/**
 * Checkout Page
 *
 * This template overrides the WooCommerce checkout page
 * Integrated with Econt delivery plugin
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header('shop');

do_action('woocommerce_before_main_content');
?>

<div class="modern-checkout-container">
    <div class="checkout-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="checkout-header">
                    <h1 class="checkout-title"><?php esc_html_e('Checkout', 'woocommerce'); ?></h1>
                </div>
                
                <!-- Econt Information Notice -->
                <div class="econt-notice">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span><?php esc_html_e('The shipping address will be entered through the Econt delivery form after selecting a shipping method.', 'woocommerce'); ?></span>
                    </div>
                </div>

                <?php
                // Include the WooCommerce checkout form template
                wc_get_template('checkout/form-checkout.php', array('checkout' => WC()->checkout()));
                ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Checkout Styles */
.modern-checkout-container {
    background-color: #f9f9f9;
    padding: 40px 0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden; /* Prevent horizontal scrolling */
    position: relative;
}

/* Floating Shapes similar to homepage */
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

.checkout-header {
    margin-bottom: 30px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding-bottom: 15px;
    position: relative;
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

.checkout-form-wrapper,
.order-summary-wrapper {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-bottom: 30px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.checkout-form-wrapper:hover,
.order-summary-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.checkout-form-wrapper h3,
.order-summary-wrapper h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    color: #333;
}

/* Checkout Features */
.checkout-features {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.checkout-feature {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s;
}

.checkout-feature:hover {
    transform: translateX(5px);
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

/* Form Styles */
.woocommerce-billing-fields__field-wrapper,
.woocommerce-shipping-fields__field-wrapper,
.woocommerce-additional-fields__field-wrapper {
    margin-bottom: 20px;
}

.form-row {
    margin-bottom: 20px;
}

.form-row label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
    color: #555;
}

.form-row input,
.form-row select,
.form-row textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    background-color: #f9f9f9;
}

.form-row input:focus,
.form-row select:focus,
.form-row textarea:focus {
    border-color: #8e44ad;
    box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
    outline: none;
    background-color: #fff;
}

/* Shipping Methods */
.woocommerce-shipping-methods {
    list-style: none;
    padding: 0;
    margin: 0 0 15px;
}

.woocommerce-shipping-methods li {
    margin-bottom: 10px;
    padding: 12px 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #eee;
    transition: all 0.3s;
    display: flex;
    align-items: center;
}

.woocommerce-shipping-methods li:hover {
    border-color: #8e44ad;
    background-color: #f8f4fa;
    transform: translateY(-2px);
}

.woocommerce-shipping-methods li label {
    margin-bottom: 0;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.woocommerce-shipping-methods input[type="radio"] {
    margin-right: 8px;
    flex: 0 0 auto;
}

/* Validation Styles */
.woocommerce-invalid input {
    border-color: #e74c3c;
}

.woocommerce-validated input {
    border-color: #2ecc71;
}

.required {
    color: #e74c3c;
}

/* Loading State */
.processing {
    opacity: 0.5;
    pointer-events: none;
}

/* Additional fields styling */
.woocommerce-additional-fields {
    margin-top: 10px;
}

.woocommerce-additional-fields h3 {
    font-size: 18px;
}

/* Econt Notice Styling */
.econt-notice {
    margin-bottom: 25px;
}

.econt-notice .alert {
    background-color: #f8f4fa;
    border-left: 4px solid #8e44ad;
    color: #333;
    padding: 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.econt-notice .alert i {
    font-size: 20px;
    margin-right: 15px;
    color: #8e44ad;
}

.econt-notice .alert span {
    font-size: 15px;
    font-weight: 500;
}

/* Fix for horizontal scrollbar */
html, body {
    overflow-x: hidden;
    max-width: 100%;
}

.container {
    max-width: 100%;
    overflow-x: hidden;
}

.row {
    margin-left: 0;
    margin-right: 0;
}

/* Econt Plugin Integration Styles */
.econt-delivery-options {
    margin-top: 20px;
    padding: 15px;
    background-color: #f8f4fa;
    border-radius: 8px;
    border: 1px solid rgba(142, 68, 173, 0.2);
}

.econt-delivery-type {
    margin-bottom: 20px;
}

.econt-delivery-type label {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.econt-delivery-type-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.econt-delivery-type-option {
    flex: 1 0 30%;
    min-width: 150px;
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s;
}

.econt-delivery-type-option:hover,
.econt-delivery-type-option.selected {
    border-color: #8e44ad;
    background-color: #f8f4fa;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(142, 68, 173, 0.1);
}

.econt-delivery-type-option i {
    display: block;
    font-size: 24px;
    margin-bottom: 10px;
    color: #8e44ad;
}

.econt-city-field {
    margin-top: 20px;
}

.econt-loading {
    text-align: center;
    padding: 20px;
    color: #8e44ad;
}

.econt-loading i {
    font-size: 24px;
    animation: spin 1s infinite linear;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Styles */
@media (max-width: 991px) {
    .checkout-form-wrapper,
    .order-summary-wrapper {
        margin-bottom: 30px;
        padding: 20px;
    }
    
    .col-lg-7,
    .col-lg-5 {
        padding: 0 15px;
    }
    
    .checkout-title {
        font-size: 28px;
    }
    
    .checkout-features {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    
    .checkout-feature {
        flex: 0 0 calc(50% - 10px);
    }
    
    .econt-delivery-type-option {
        flex: 1 0 45%;
    }
}

@media (max-width: 576px) {
    .checkout-title {
        font-size: 24px;
    }
    
    .checkout-form-wrapper h3,
    .order-summary-wrapper h3 {
        font-size: 18px;
    }
    
    .checkout-features {
        flex-direction: column;
    }
    
    .checkout-feature {
        flex: 0 0 100%;
    }
    
    .form-row label {
        font-size: 13px;
    }
    
    .form-row input,
    .form-row select,
    .form-row textarea {
        padding: 10px 12px;
        font-size: 14px;
    }
    
    .econt-delivery-type-option {
        flex: 1 0 100%;
    }
    
    .econt-delivery-type-options {
        flex-direction: column;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Show loading indicator on form submit
    $('form.checkout').on('submit', function() {
        $(this).addClass('processing');
        $('#place_order').html('<i class="fas fa-spinner fa-spin"></i> Обработка...');
    });
    
    // Real-time form validation
    $('.woocommerce-input-wrapper input, .woocommerce-input-wrapper select').on('blur', function() {
        const $input = $(this);
        const $wrapper = $input.closest('.form-row');
        
        if ($input.val() === '' && $wrapper.hasClass('validate-required')) {
            $wrapper.removeClass('woocommerce-validated').addClass('woocommerce-invalid');
        } else {
            $wrapper.removeClass('woocommerce-invalid').addClass('woocommerce-validated');
        }
    });
    
    // Smooth scroll to checkout sections
    $('.checkout-form-wrapper h3').on('click', function() {
        $(this).next().slideToggle(300);
    });
    
    // Add animation to checkout features
    $('.checkout-feature').each(function(index) {
        $(this).css('animation-delay', (index * 0.15) + 's');
        $(this).addClass('fade-in');
    });
    
    // Handle shipping method selection
    $('body').on('change', '.shipping_method', function() {
        // Add active class to selected shipping method
        $(this).closest('li').addClass('selected');
        $(this).closest('li').siblings().removeClass('selected');
    });
    
    // Compatibility with Econt plugin
    // This ensures that when the Econt delivery form is loaded, it gets proper styling
    $(document).on('econt_delivery_form_loaded', function() {
        $('.econt-delivery-type-option').on('click', function() {
            $('.econt-delivery-type-option').removeClass('selected');
            $(this).addClass('selected');
        });
    });
});
</script>

<?php
do_action('woocommerce_after_main_content');

get_footer('shop');
?> 