<?php
/**
 * Econt Calculator Template
 * 
 * This template overrides the default Econt calculator template for improved UI
 * 
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

// Get shipping information if available
$cart_total = WC()->cart ? WC()->cart->get_cart_contents_total() : 0;
$cart_weight = WC()->cart ? WC()->cart->get_cart_weight() : 0;
?>

<div class="econt-calculator-container">
    <h4 class="econt-calculator-title"><?php esc_html_e('Calculate Shipping Cost', 'woocommerce'); ?></h4>
    
    <form class="econt-calculator" method="post">
        <?php wp_nonce_field('econt_calculate_shipping', 'econt_nonce'); ?>
        
        <div class="econt-field">
            <label for="econt_city" class="form-label"><?php esc_html_e('City', 'woocommerce'); ?></label>
            <select name="econt_city" id="econt_city" class="form-control" required>
                <option value=""><?php esc_html_e('Select city', 'woocommerce'); ?></option>
                <?php 
                // This would be populated with cities from the Econt API
                // Placeholder logic that would be replaced by actual data:
                $popular_cities = array(
                    'София' => 'Sofia',
                    'Пловдив' => 'Plovdiv',
                    'Варна' => 'Varna',
                    'Бургас' => 'Burgas',
                    'Русе' => 'Ruse'
                );
                
                foreach ($popular_cities as $city_name => $city_value) {
                    echo '<option value="' . esc_attr($city_value) . '">' . esc_html($city_name) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="econt-field">
            <label for="econt_office" class="form-label"><?php esc_html_e('Office', 'woocommerce'); ?></label>
            <select name="econt_office" id="econt_office" class="form-control">
                <option value=""><?php esc_html_e('Select office', 'woocommerce'); ?></option>
                <!-- This would be dynamically populated based on city selection -->
            </select>
        </div>
        
        <input type="hidden" name="cart_total" value="<?php echo esc_attr($cart_total); ?>" />
        <input type="hidden" name="cart_weight" value="<?php echo esc_attr($cart_weight); ?>" />
        
        <button type="submit" name="econt_calculate" class="econt-calculate-btn">
            <i class="fas fa-calculator"></i>
            <?php esc_html_e('Calculate Price', 'woocommerce'); ?>
        </button>
    </form>
    
    <div class="econt-auto-calculate-note">
        <?php esc_html_e('Price will be calculated automatically after filling required fields', 'woocommerce'); ?>
    </div>
    
    <?php if (isset($_POST['econt_calculate']) && wp_verify_nonce($_POST['econt_nonce'], 'econt_calculate_shipping')) : ?>
        <div class="econt-price-results">
            <div class="price-row">
                <span class="price-label"><?php esc_html_e('Base Price', 'woocommerce'); ?></span>
                <span class="price-value"><?php echo wc_price(5.60); ?></span>
            </div>
            <div class="price-row">
                <span class="price-label"><?php esc_html_e('Weight Surcharge', 'woocommerce'); ?></span>
                <span class="price-value"><?php echo wc_price(1.20); ?></span>
            </div>
            <div class="price-row">
                <span class="price-label"><?php esc_html_e('Additional Services', 'woocommerce'); ?></span>
                <span class="price-value"><?php echo wc_price(0.80); ?></span>
            </div>
            <div class="price-row">
                <span class="price-label total-price"><?php esc_html_e('Total', 'woocommerce'); ?></span>
                <span class="price-value total-price"><?php echo wc_price(7.60); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div> 