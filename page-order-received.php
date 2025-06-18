<?php
/**
 * Template Name: Order Received
 *
 * This template is used to display the order received page.
 */

defined('ABSPATH') || exit;

// Ensure this page is only used for order-received endpoint
if (!isset($_GET['order']) && !isset($wp->query_vars['order-received'])) {
    // If not on order-received endpoint, redirect to homepage
    wp_redirect(home_url());
    exit;
}

// Get the order ID from URL or query vars
$order_id = isset($_GET['order']) ? absint($_GET['order']) : 0;
if (!$order_id && isset($wp->query_vars['order-received'])) {
    $order_id = absint($wp->query_vars['order-received']);
}

// Get the order key from URL
$order_key = isset($_GET['key']) ? wc_clean($_GET['key']) : '';

// Get the order
$order = false;
if ($order_id > 0) {
    $order = wc_get_order($order_id);
    
    // Verify order key if we have an order and a key
    if ($order && $order_key && $order->get_order_key() !== $order_key) {
        $order = false;
    }
}

// Set up the global $wp variable
global $wp;
if ($order_id) {
    $wp->query_vars['order-received'] = $order_id;
}

// Tell WooCommerce we're on the order-received endpoint
if (class_exists('WC_Query') && isset(WC()->query)) {
    WC()->query->is_order_received = true;
}

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        // Output the thank you template
        wc_get_template('checkout/thankyou.php', array('order' => $order));
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); 