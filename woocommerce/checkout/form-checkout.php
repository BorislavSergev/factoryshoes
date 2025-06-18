<?php
/**
 * Checkout Form
 *
 * This template overrides /woocommerce/templates/checkout/form-checkout.php
 *
 * @package Shoes_Store_Theme
 */

if (!defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

		<div class="row" id="customer_details">
			<div class="col-lg-7">
				<div class="checkout-form-wrapper">
					<div class="billing-details-wrapper mb-4">
						<?php do_action('woocommerce_checkout_billing'); ?>
					</div>

					<?php if (WC()->cart->needs_shipping()) : ?>
						<div class="shipping-details-wrapper mb-4">
							<div class="shipping-methods-section">
								<?php do_action('woocommerce_checkout_shipping'); ?>
								
								<!-- Critical hook for Econt delivery plugin -->
								<?php do_action('woocommerce_review_order_after_shipping'); ?>
							</div>
						</div>
					<?php endif; ?>
					
					<?php 
					// We keep the hooks but remove the visible additional information fields
					do_action('woocommerce_before_order_notes', $checkout);
					do_action('woocommerce_after_order_notes', $checkout); 
					?>
				</div>
			</div>

			<div class="col-lg-5">
				<div class="order-summary-wrapper">
					<h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>
					
					<?php do_action('woocommerce_checkout_before_order_review'); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action('woocommerce_checkout_order_review'); ?>
					</div>

					<?php do_action('woocommerce_checkout_after_order_review'); ?>
				</div>
				
				<!-- Checkout Features -->
				<div class="checkout-features mt-4">
					<div class="checkout-feature">
						<i class="fas fa-truck-fast"></i>
						<span><?php esc_html_e('Delivery with Econt', 'woocommerce'); ?></span>
					</div>
					<div class="checkout-feature">
						<i class="fas fa-shield-alt"></i>
						<span><?php esc_html_e('Quality Guarantee', 'woocommerce'); ?></span>
					</div>
					<div class="checkout-feature">
						<i class="fas fa-exchange-alt"></i>
						<span><?php esc_html_e('30-Day Returns', 'woocommerce'); ?></span>
					</div>
				</div>
			</div>
		</div>

		<?php do_action('woocommerce_checkout_after_customer_details'); ?>

	<?php endif; ?>
	
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?> 