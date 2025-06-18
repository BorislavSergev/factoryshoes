<?php
/**
 * Template Name: WooCommerce Cart
 * Template Post Type: page
 *
 * This template is used for the WooCommerce Cart page
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header();

?>
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
<?php

get_footer();
?> 