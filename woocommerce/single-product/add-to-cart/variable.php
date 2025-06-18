<?php
/**
 * Variable product add to cart
 *
 * This template is a robust, UX-focused version that ensures compatibility
 * with WooCommerce JS and provides clear user feedback.
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
                                        'show_option_none' => wc_attribute_label( $attribute_name ), // Makes the default text "Choose a [Size]..."
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

<?php
do_action('woocommerce_after_add_to_cart_form');
?>

<!-- STYLES FOR VARIABLE PRODUCT FORM -->
<style>
    .variations_form .variations {
        margin-bottom: 1rem;
        border: none;
        width: 100%;
    }
    .variations_form .variations td,
    .variations_form .variations th {
        padding: 0.5rem 0;
        border: none;
        text-align: left;
        vertical-align: middle;
    }
    .variations_form .variations .label {
        font-weight: 600;
        color: #333;
    }
    .variations_form .variations th.label {
        padding-right: 1rem;
    }
    .variations_form .variations .value {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .variations_form .variations select {
        width: 100%;
        max-width: 250px;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        font-size: 1rem;
    }
    .variations_form .size-guide-link {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.9rem;
        color: #3498db;
        text-decoration: none;
        white-space: nowrap;
    }
    .variations_form .size-guide-link:hover {
        text-decoration: underline;
    }
    .variation-actions-wrapper {
        text-align: right;
        margin: -0.5rem 0 1rem 0;
    }
    .variations_form .reset_variations {
        font-size: 0.9em;
        color: #666;
    }

    /* Variation Details and Add to Cart Button Styling */
    .single_variation_wrap {
        transition: all 0.3s ease;
        margin-top: 1rem;
    }
    .woocommerce-variation-description,
    .woocommerce-variation-price,
    .woocommerce-variation-availability {
        margin-bottom: 1rem;
    }
    .woocommerce-variation-price .price {
        font-size: 1.8rem;
        font-weight: 700;
        color: #e74c3c;
    }
    .woocommerce-variation-availability .stock {
        font-weight: 600;
        color: #27ae60;
    }
    .single_variation_wrap .variations_button {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .single_variation_wrap .quantity {
        margin: 0;
    }
    .single_variation_wrap .quantity .qty {
        width: 60px;
        text-align: center;
        padding: 0.75rem;
        font-size: 1.1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    /* THIS IS THE KEY PART FOR UX */
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
    }
    .single_add_to_cart_button:hover {
        background-color: #27ae60 !important;
        transform: translateY(-2px);
    }

    /* Style for when the button is DISABLED */
    .single_add_to_cart_button.disabled,
    .single_add_to_cart_button:disabled {
        background-color: #bdc3c7 !important;
        color: #7f8c8d !important;
        cursor: not-allowed !important;
        transform: none !important;
        opacity: 1 !important; /* Override default opacity */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .variations_form .variations tr {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }
        .variations_form .variations td,
        .variations_form .variations th {
            padding: 0;
            width: 100%;
        }
        .variations_form .variations th.label {
            margin-bottom: 0.5rem;
        }
        .variations_form .variations select {
            max-width: none;
        }
        .single_variation_wrap .variations_button {
            flex-direction: column;
            align-items: stretch;
        }
        .single_variation_wrap .quantity .qty {
            width: 100%;
        }
    }
</style>
