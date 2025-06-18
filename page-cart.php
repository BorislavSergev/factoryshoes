<?php
/**
 * Template for displaying the cart page
 *
 * Template Name: Cart Page
 * 
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

// We should NOT handle the order-received endpoint here
// Let WordPress and WooCommerce handle it properly

get_header();

// Check if WooCommerce is active
if (class_exists('WooCommerce')) {
    // Remove any actions that might interfere with the cart display
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    
    // Add custom wrappers
    add_action('woocommerce_before_main_content', function() {
        echo '<div class="container"><div class="row"><div class="col-12">';
    }, 10);
    
    add_action('woocommerce_after_main_content', function() {
        echo '</div></div></div>';
    }, 10);
    
    // Output the cart content
    echo do_shortcode('[woocommerce_cart]');
} else {
    // Fallback if WooCommerce is not active
    echo '<div class="container py-5"><div class="row"><div class="col-12">';
    echo '<div class="alert alert-warning">WooCommerce is not active. Please install and activate WooCommerce to view your cart.</div>';
    echo '</div></div></div>';
}

get_footer();
?> 