<?php
/**
 * Checkout shipping information form
 *
 * This template overrides the WooCommerce shipping form
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-shipping-fields">
    <?php if (true === WC()->cart->needs_shipping_address()) : ?>

        <h3><?php esc_html_e('Shipping details', 'woocommerce'); ?></h3>
        
        <div class="shipping-info-note mb-4">
            <div class="alert alert-light border-left border-info">
                <i class="fas fa-info-circle text-info"></i>
                <span><?php esc_html_e('Shipping address details will be collected through the delivery method selection below.', 'woocommerce'); ?></span>
            </div>
        </div>

        <div class="shipping_address">
            <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

            <div class="woocommerce-shipping-fields__field-wrapper">
                <?php
                $fields = $checkout->get_checkout_fields('shipping');

                foreach ($fields as $key => $field) {
                    // Add Bootstrap classes to form fields
                    if (!isset($field['class'])) {
                        $field['class'] = array();
                    }
                    
                    // Add form-control class to input fields
                    $field['class'][] = 'form-control';
                    
                    // Add column classes based on field type
                    $field['class'][] = 'form-row-' . (isset($field['type']) && $field['type'] === 'checkbox' ? 'wide' : (isset($field['class'][0]) ? $field['class'][0] : 'full'));
                    
                    woocommerce_form_field($key, $field, $checkout->get_value($key));
                }
                ?>
            </div>

            <?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
        </div>

    <?php endif; ?>
</div> 