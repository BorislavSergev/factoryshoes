<?php
/**
 * Variable product add to cart
 *
 * This template is a robust, UX-focused version that ensures compatibility
 * with WooCommerce JS and provides clear user feedback, especially for mobile/first-time users.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ($attributes as $attribute_name => $options) : ?>
					<tr>
						<th class="label">
                            <label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name); // WPCS: XSS ok. ?></label>
                        </th>
						<td class="value">
							<?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
                                        'show_option_none' => sprintf(esc_html__('Choose %s', 'shoes-store'), wc_attribute_label($attribute_name)),
									)
								);
							?>
                            <?php if (strpos(strtolower($attribute_name), 'size') !== false) : ?>
                                <a href="#" class="size-guide-link">
                                    <i class="fas fa-ruler-horizontal"></i>
                                    <?php esc_html_e('Size Guide', 'shoes-store'); ?>
                                </a>
                            <?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
        <div class="variation-actions-wrapper">
            <?php echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__('Clear', 'woocommerce') . '</a>')) : ''; ?>
        </div>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action('woocommerce_before_single_variation');

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty selector and cart button.
				 */
				do_action('woocommerce_single_variation');

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action('woocommerce_after_single_variation');
			?>
		</div>
	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>

<!-- STYLES FOR VARIABLE PRODUCT FORM -->
<style>
    .variations_form .variations { margin-bottom: 1rem; border: none; width: 100%; }
    .variations_form .variations td,
    .variations_form .variations th { padding: 0.5rem 0; border: none; text-align: left; vertical-align: middle; }
    .variations_form .variations .label { font-weight: 600; color: #333; }
    .variations_form .variations th.label { padding-right: 1rem; }
    .variations_form .variations .value { display: flex; align-items: center; gap: 1rem; }
    .variations_form .variations select { width: 100%; max-width: 250px; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; background-color: #fff; font-size: 1rem; }
    .variations_form .size-guide-link { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.9rem; color: #3498db; text-decoration: none; white-space: nowrap; }
    .variations_form .size-guide-link:hover { text-decoration: underline; }
    .variation-actions-wrapper { text-align: right; margin: -0.5rem 0 1rem 0; }
    .variations_form .reset_variations { font-size: 0.9em; color: #666; }

    /* Variation Details and Add to Cart Button Styling */
    .single_variation_wrap { transition: all 0.3s ease; margin-top: 1rem; min-height: 120px; /* Reserve space to prevent layout shift */ }
    .woocommerce-variation-price .price { font-size: 1.8rem; font-weight: 700; color: #e74c3c; }
    .woocommerce-variation-availability .stock { font-weight: 600; color: #27ae60; }
    .single_variation_wrap .variations_button { display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem; }
    .single_variation_wrap .quantity .qty { width: 60px; text-align: center; padding: 0.75rem; font-size: 1.1rem; border: 1px solid #ddd; border-radius: 4px; }

    /* KEY UX STYLES FOR BUTTON STATES */
    .single_add_to_cart_button {
        flex-grow: 1;
        background-color: #2ecc71 !important;
        color: white !important;
        padding: 1rem !important;
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        border-radius: 4px !important;
        border: none !important;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    .single_add_to_cart_button:hover {
        background-color: #27ae60 !important;
        transform: translateY(-2px);
    }
    .single_add_to_cart_button.disabled,
    .single_add_to_cart_button:disabled {
        background-color: #bdc3c7 !important;
        color: #7f8c8d !important;
        cursor: not-allowed !important;
        transform: none !important;
        opacity: 1 !important;
    }
    .single_add_to_cart_button.loading {
        background-color: #f39c12 !important;
        color: white !important;
        cursor: wait !important;
    }
    .single_add_to_cart_button.added {
        background-color: #27ae60 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .variations_form .variations tr { display: flex; flex-direction: column; margin-bottom: 1rem; }
        .variations_form .variations td, .variations_form .variations th { padding: 0; width: 100%; }
        .variations_form .variations th.label { margin-bottom: 0.5rem; }
        .variations_form .variations select { max-width: none; }
        .single_variation_wrap .variations_button { flex-direction: column; align-items: stretch; }
        .single_variation_wrap .quantity .qty { width: 100%; }
    }
</style>

<!-- ROBUST ADD TO CART JAVASCRIPT -->
<script type="text/javascript">
jQuery(function($) {
    // Ensure this code runs after WooCommerce's own scripts.
    if (typeof wc_add_to_cart_variation_params === 'undefined') {
        return false;
    }

    // Get the form element
    const $form = $('.variations_form');

    // Function to update button state
    function updateAddToCartButton(button, state = 'default', message = '') {
        const originalText = "<?php esc_html_e('Add to cart', 'woocommerce'); ?>";
        button.removeClass('loading added').prop('disabled', false);

        switch (state) {
            case 'disabled':
                button.prop('disabled', true).html("<?php esc_html_e('Select options', 'woocommerce'); ?>");
                break;
            case 'enabled':
                button.prop('disabled', false).html(originalText);
                break;
            case 'loading':
                button.addClass('loading').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?php esc_html_e('Adding...', 'shoes-store'); ?>');
                break;
            case 'added':
                button.addClass('added').prop('disabled', true).html('<i class="fas fa-check"></i> <?php esc_html_e('Added!', 'shoes-store'); ?>');
                // Reset after a delay
                setTimeout(() => {
                    updateAddToCartButton(button, 'enabled');
                }, 2500);
                break;
            case 'error':
                 button.prop('disabled', false).html(originalText);
                 // You can add more specific error feedback if needed
                 break;
        }
    }
    
    // --- EVENT LISTENERS ---

    // 1. When a variation is found (a valid combination is selected)
    $form.on('show_variation', function(event, variation, purchasable) {
        const $button = $form.find('.single_add_to_cart_button');
        if (purchasable) {
            updateAddToCartButton($button, 'enabled');
        } else {
            updateAddToCartButton($button, 'disabled');
        }
    });

    // 2. When the selection is cleared or becomes invalid
    $form.on('hide_variation', function() {
        const $button = $form.find('.single_add_to_cart_button');
        updateAddToCartButton($button, 'disabled');
    });

    // 3. When the add to cart button is clicked (BEFORE the AJAX request)
    $form.on('click', '.single_add_to_cart_button', function(e) {
        const $button = $(this);
        // If the button is not disabled, show loading state immediately
        if (!$button.prop('disabled')) {
            updateAddToCartButton($button, 'loading');
        }
    });

    // 4. Listen to the global 'added_to_cart' event triggered by WooCommerce on success
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
        // Check if the event was triggered by our form's button
        if ($button && $button.hasClass('single_add_to_cart_button')) {
            updateAddToCartButton($button, 'added');
        }
    });

    // 5. CRITICAL FOR IPHONE/ERROR ISSUES: Detect if WooCommerce displays an error
    // This handles cases where the AJAX request fails or returns an error.
    const wcNoticesWrapper = $('.woocommerce-notices-wrapper').first();
    if (wcNoticesWrapper.length) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    const $nodes = $(mutation.addedNodes);
                    if ($nodes.find('.woocommerce-error').length || $nodes.hasClass('woocommerce-error')) {
                        // An error was added, so reset our button
                        const $button = $form.find('.single_add_to_cart_button');
                        console.log('WooCommerce error detected. Resetting button.');
                        updateAddToCartButton($button, 'error');
                    }
                }
            });
        });

        observer.observe(wcNoticesWrapper[0], { childList: true, subtree: true });
    }

    // Initial state check on page load
    // If the form is already valid (e.g., on back button), enable the button.
    setTimeout(function() {
        if ($form.find('input.variation_id').val()) {
            $form.trigger('show_variation', [null, true]);
        } else {
             $form.trigger('hide_variation');
        }
    }, 100); // Small delay to ensure WC scripts have run

});
</script>
