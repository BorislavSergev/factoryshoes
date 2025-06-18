<?php
/**
 * Cart Page
 *
 * This template overrides the WooCommerce cart page
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

?>

<div class="modern-cart-container">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title mb-4">Кошница</h1>
                
                <?php do_action('woocommerce_before_cart'); ?>

                <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                    <?php do_action('woocommerce_before_cart_table'); ?>

                    <?php if (WC()->cart->is_empty()) : ?>
                        <div class="empty-cart-message">
                            <div class="empty-cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <p class="empty-cart-text"><?php esc_html_e('Вашата кошница е празна.', 'woocommerce'); ?></p>
                            <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="btn btn-primary return-to-shop">
                                <?php esc_html_e('Продължи пазаруването', 'woocommerce'); ?>
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="cart-content">
                            <div class="row">
                                <!-- Cart Items - Left Column -->
                                <div class="col-lg-8">
                                    <div class="cart-items-wrapper">
                                        <?php do_action('woocommerce_before_cart_contents'); ?>

                                        <?php
                                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                                ?>
                                                <div class="cart-item-card <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                                    <!-- Product Image -->
                                                    <div class="cart-item-image">
                                                        <?php
                                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                                        if (!$product_permalink) {
                                                            echo $thumbnail;
                                                        } else {
                                                            printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                                        }
                                                        ?>
                                                    </div>
                                                    
                                                    <!-- Product Info -->
                                                    <div class="cart-item-details">
                                                        <div class="cart-item-title">
                                                            <?php
                                                            if (!$product_permalink) {
                                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                                            } else {
                                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                            }

                                                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                                            // Meta data
                                                            echo wc_get_formatted_cart_item_data($cart_item);

                                                            // Backorder notification
                                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <div class="cart-item-price">
                                                            <?php
                                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                                            ?>
                                                        </div>
                                                        
                                                        <div class="cart-item-quantity">
                                                            <div class="quantity-selector cart-quantity">
                                                                <button type="button" class="quantity-btn minus" data-item-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                                                                <?php
                                                                if ($_product->is_sold_individually()) {
                                                                    $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                                                } else {
                                                                    $product_quantity = woocommerce_quantity_input(
                                                                        array(
                                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                                            'input_value'  => $cart_item['quantity'],
                                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                                            'min_value'    => '0',
                                                                            'product_name' => $_product->get_name(),
                                                                        ),
                                                                        $_product,
                                                                        false
                                                                    );
                                                                }

                                                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                                                ?>
                                                                <button type="button" class="quantity-btn plus" data-item-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="cart-item-subtotal">
                                                            <?php
                                                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                                            ?>
                                                        </div>
                                                        
                                                        <div class="cart-item-remove">
                                                            <?php
                                                            echo apply_filters(
                                                                'woocommerce_cart_item_remove_link',
                                                                sprintf(
                                                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fas fa-times"></i></a>',
                                                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                                    esc_html__('Remove this item', 'woocommerce'),
                                                                    esc_attr($product_id),
                                                                    esc_attr($_product->get_sku())
                                                                ),
                                                                $cart_item_key
                                                            );
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>

                                        <?php do_action('woocommerce_cart_contents'); ?>

                                        <div class="cart-actions">
                                            <?php if (wc_coupons_enabled()) { ?>
                                                <div class="coupon">
                                                    <input type="text" name="coupon_code" class="input-text form-control" id="coupon_code" value="" placeholder="<?php esc_attr_e('Код за отстъпка', 'woocommerce'); ?>" />
                                                    <button type="submit" class="button btn btn-outline-secondary" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Приложи', 'woocommerce'); ?></button>
                                                    <?php do_action('woocommerce_cart_coupon'); ?>
                                                </div>
                                            <?php } ?>

                                            <button type="submit" class="button btn btn-outline-primary" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Обнови кошницата', 'woocommerce'); ?></button>

                                            <?php do_action('woocommerce_cart_actions'); ?>

                                            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                        </div>

                                        <?php do_action('woocommerce_after_cart_contents'); ?>
                                    </div>
                                </div>
                                
                                <!-- Cart Totals - Right Column -->
                                <div class="col-lg-4">
                                    <div class="cart-totals-wrapper">
                                        <?php do_action('woocommerce_before_cart_collaterals'); ?>

                                        <div class="cart-collaterals">
                                            <?php
                                            /**
                                             * Cart collaterals hook.
                                             *
                                             * @hooked woocommerce_cross_sell_display
                                             * @hooked woocommerce_cart_totals - 10
                                             */
                                            do_action('woocommerce_cart_collaterals');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php do_action('woocommerce_after_cart_table'); ?>
                </form>

                <?php do_action('woocommerce_after_cart'); ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Cart Styles */
.modern-cart-container {
    background-color: #f8f9fa;
    padding: 30px 0;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 30px;
}

/* Empty Cart Styles */
.empty-cart-message {
    background-color: #fff;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
}

.empty-cart-icon {
    font-size: 60px;
    color: #e0e0e0;
    margin-bottom: 20px;
}

.empty-cart-text {
    font-size: 18px;
    color: #666;
    margin-bottom: 25px;
}

.return-to-shop {
    background-color: #e74c3c;
    border-color: #c0392b;
    padding: 12px 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.return-to-shop:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}

/* Cart Items */
.cart-items-wrapper {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.cart-item-card {
    display: flex;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.cart-item-card:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 100px;
    margin-right: 20px;
}

.cart-item-image img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.cart-item-details {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    position: relative;
}

.cart-item-title {
    width: 100%;
    margin-bottom: 10px;
    font-weight: 600;
}

.cart-item-title a {
    color: #333;
    text-decoration: none;
}

.cart-item-price {
    width: 25%;
    font-size: 14px;
    color: #666;
}

.cart-item-quantity {
    width: 50%;
}

.cart-item-subtotal {
    width: 25%;
    font-size: 16px;
    font-weight: 600;
    color: #e74c3c;
    text-align: right;
}

.cart-item-remove {
    position: absolute;
    top: 0;
    right: 0;
}

.cart-item-remove a {
    color: #999;
    font-size: 14px;
}

.cart-item-remove a:hover {
    color: #e74c3c;
}

/* Quantity Selector in Cart */
.cart-quantity {
    max-width: 120px;
    margin: 0 auto;
}

.quantity-selector {
    display: flex;
    align-items: center;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
}

input.qty {
    width: 45px !important;
    height: 32px !important;
    text-align: center;
    border: 1px solid #ddd;
    margin: 0 5px;
    padding: 0 !important;
}

/* Cart Actions */
.cart-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.coupon {
    display: flex;
    align-items: center;
}

.coupon input {
    width: 180px;
    margin-right: 10px;
}

/* Cart Totals */
.cart-totals-wrapper {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
}

.cart_totals {
    width: 100% !important;
}

.cart_totals h2 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.cart_totals table {
    width: 100%;
    margin-bottom: 20px;
}

.cart_totals th {
    padding: 12px 0;
    font-weight: 500;
}

.cart_totals td {
    padding: 12px 0;
    text-align: right;
}

.cart_totals .order-total th,
.cart_totals .order-total td {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.wc-proceed-to-checkout .checkout-button {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background-color: #e74c3c;
    border-color: #c0392b;
}

.wc-proceed-to-checkout .checkout-button:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}

@media (max-width: 767px) {
    .cart-item-card {
        flex-direction: column;
        padding: 15px 0;
    }
    
    .cart-item-image {
        width: 80px;
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .cart-item-details {
        width: 100%;
    }
    
    .cart-item-price,
    .cart-item-quantity,
    .cart-item-subtotal {
        width: 33.333%;
        text-align: center;
        margin-top: 10px;
    }
    
    .cart-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .coupon {
        width: 100%;
    }
    
    .coupon input {
        flex: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cart quantity buttons
    const minusBtns = document.querySelectorAll('.cart-quantity .quantity-btn.minus');
    const plusBtns = document.querySelectorAll('.cart-quantity .quantity-btn.plus');
    
    if (minusBtns.length > 0) {
        minusBtns.forEach(button => {
            button.addEventListener('click', function() {
                const itemKey = this.getAttribute('data-item-key');
                const inputField = this.closest('.quantity-selector').querySelector('input.qty');
                let currentValue = parseInt(inputField.value);
                
                if (currentValue > 1) {
                    inputField.value = currentValue - 1;
                    // Trigger change for potential AJAX update
                    const event = new Event('change', { bubbles: true });
                    inputField.dispatchEvent(event);
                }
            });
        });
    }
    
    if (plusBtns.length > 0) {
        plusBtns.forEach(button => {
            button.addEventListener('click', function() {
                const itemKey = this.getAttribute('data-item-key');
                const inputField = this.closest('.quantity-selector').querySelector('input.qty');
                let currentValue = parseInt(inputField.value);
                
                const maxValue = inputField.getAttribute('max') ? parseInt(inputField.getAttribute('max')) : '';
                
                if (maxValue === '' || currentValue < maxValue) {
                    inputField.value = currentValue + 1;
                    // Trigger change for potential AJAX update
                    const event = new Event('change', { bubbles: true });
                    inputField.dispatchEvent(event);
                }
            });
        });
    }
    
    // Auto-update cart when quantity changes (optional)
    const qtyInputs = document.querySelectorAll('input.qty');
    if (qtyInputs.length > 0) {
        qtyInputs.forEach(input => {
            input.addEventListener('change', function() {
                const updateButton = document.querySelector('button[name="update_cart"]');
                if (updateButton) {
                    updateButton.disabled = false;
                    // Optional: Auto-update cart
                    // updateButton.click();
                }
            });
        });
    }
});
</script> 