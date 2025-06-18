<?php
/**
 * Cart Page
 *
 * This template overrides the WooCommerce cart page
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header('shop');

do_action('woocommerce_before_main_content');
?>

<!-- Cart Hero Section (like home page hero, but for cart) -->
<section class="cart-hero-section modern-hero">
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container">
        <div class="hero-wrapper">
            <div class="hero-content">
                <div class="sale-badge-wrapper">
                    <div class="sale-badge">
                        <i class="fas fa-shopping-cart fa-sm"></i>
                        <span><?php _e('Вашата Кошница', 'shoes-store'); ?></span>
                    </div>
                </div>
                <h1 class="hero-title"><?php _e('Прегледайте и завършете поръчката си', 'shoes-store'); ?></h1>
                <p class="hero-subtitle"><?php _e('Проверете продуктите, количествата и финализирайте покупката си.', 'shoes-store'); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="hero-secondary-button">
                        <?php _e('Продължи пазаруването', 'shoes-store'); ?>
                    </a>
                </div>
            </div>
            <div class="hero-product">
                <div class="hero-product-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/cart-hero.png"
                         alt="Cart"
                         class="featured-shoe-img"
                         loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
  <h1 class="mb-4 fw-bold"><?php _e('Кошница', 'woocommerce'); ?></h1>

  <?php do_action( 'woocommerce_before_cart' ); ?>

  <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th scope="col"><?php _e('Продукт', 'woocommerce'); ?></th>
            <th scope="col"><?php _e('Цена', 'woocommerce'); ?></th>
            <th scope="col"><?php _e('Количество', 'woocommerce'); ?></th>
            <th scope="col"><?php _e('Общо', 'woocommerce'); ?></th>
            <th scope="col"><?php _e('Премахни', 'woocommerce'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
              $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
          ?>
          <tr class="woocommerce-cart-form__cart-item">
            <td class="text-start">
              <div class="d-flex align-items-center gap-3">
                <?php echo $_product->get_image( 'thumbnail', ['class' => 'img-thumbnail', 'style' => 'width: 75px; height: auto;'] ); ?>
                <div>
                  <?php if ( $product_permalink ) : ?>
                    <a href="<?php echo esc_url( $product_permalink ); ?>" class="fw-semibold text-decoration-none">
                      <?php echo $_product->get_name(); ?>
                    </a>
                  <?php else : ?>
                    <span class="fw-semibold"><?php echo $_product->get_name(); ?></span>
                  <?php endif; ?>
                  <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                </div>
              </div>
            </td>
            <td><?php echo wc_price( $_product->get_price() ); ?></td>
            <td>
              <?php
                if ( $_product->is_sold_individually() ) {
                  echo '1 <input type="hidden" name="cart[' . $cart_item_key . '][qty]" value="1">';
                } else {
                  woocommerce_quantity_input([
                    'input_name'  => "cart[{$cart_item_key}][qty]",
                    'input_value' => $cart_item['quantity'],
                    'max_value'   => $_product->get_max_purchase_quantity(),
                    'min_value'   => '0',
                  ], $_product );
                }
              ?>
            </td>
            <td><?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?></td>
            <td>
              <?php
                echo apply_filters(
                  'woocommerce_cart_item_remove_link',
                  sprintf(
                    '<a href="%s" class="btn btn-sm btn-outline-danger" aria-label="%s">&times;</a>',
                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                    esc_html__( 'Remove this item', 'woocommerce' )
                  ),
                  $cart_item_key
                );
              ?>
            </td>
          </tr>
          <?php endif; endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline-secondary">
        ← <?php _e('Продължи пазаруването', 'woocommerce'); ?>
      </a>
      <button type="submit" class="btn btn-primary" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
        <?php _e('Обнови кошницата', 'woocommerce'); ?>
      </button>
    </div>

    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    <?php do_action( 'woocommerce_cart_actions' ); ?>
  </form>

  <div class="cart-collaterals mt-5">
    <?php do_action( 'woocommerce_cart_collaterals' ); ?>
  </div>

  <?php do_action( 'woocommerce_after_cart' ); ?>
</div>

<style>
/* Modern Cart Styles */
.modern-cart-container {
    background-color: #f9f9f9;
    padding: 0px 0px;
    font-family: 'Inter', sans-serif;
}

.container {
    max-width: 100%;
    overflow-x: hidden;
}

.row {
    margin-left: 0;
    margin-right: 0;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #222;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
}

.page-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background-color: #e74c3c;
}

/* Empty Cart Styles */
.empty-cart-message {
    background-color: #fff;
    border-radius: 10px;
    padding: 50px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.empty-cart-icon {
    font-size: 70px;
    color: #e0e0e0;
    margin-bottom: 25px;
}

.empty-cart-text {
    font-size: 20px;
    color: #555;
    margin-bottom: 30px;
    font-weight: 500;
}

.return-to-shop {
    background-color: #e74c3c;
    border-color: #e74c3c;
    padding: 14px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.return-to-shop:hover {
    background-color: #c0392b;
    border-color: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
}

/* Cart Items */
.cart-items-wrapper {
    background-color: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.cart-item-card {
    display: flex;
    align-items: center;
    padding: 25px 0;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}

.cart-item-card:hover {
    background-color: #fafafa;
    transform: translateY(-2px);
}

.cart-item-card:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 120px;
    margin-right: 25px;
}

.cart-item-image img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.cart-item-image img:hover {
    transform: scale(1.05);
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
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 18px;
}

.cart-item-title a {
    color: #222;
    text-decoration: none;
    transition: color 0.2s ease;
}

.cart-item-title a:hover {
    color: #e74c3c;
}

.cart-item-price {
    width: 25%;
    font-size: 16px;
    color: #555;
}

.cart-item-quantity {
    width: 50%;
    display: flex;
    justify-content: center;
}

.cart-item-subtotal {
    width: 25%;
    font-size: 18px;
    font-weight: 700;
    color: #e74c3c;
    text-align: right;
}

.cart-item-remove {
    position: absolute;
    top: -10px;
    right: 0;
}

.cart-item-remove a {
    color: #aaa;
    font-size: 16px;
    transition: all 0.2s ease;
    width: 30px;
    height: 30px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.cart-item-remove a:hover {
    color: #fff;
    background-color: #e74c3c;
    transform: rotate(90deg);
}

/* Quantity Selector in Cart */
.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 2px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    max-width: 110px;
    margin: 0 auto;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    background-color: #f8f8f8;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #555;
}

.quantity-btn:hover {
    background-color: #e0e0e0;
}

input.qty {
    width: 45px !important;
    height: 32px !important;
    text-align: center;
    border: none;
    margin: 0;
    padding: 0 !important;
    font-weight: 600;
    color: #333;
    background: transparent;
}

/* Cart Actions */
.cart-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.coupon {
    display: flex;
    align-items: center;
}

.coupon input {
    width: 200px;
    margin-right: 10px;
    height: 48px;
    border-radius: 5px;
    border: 1px solid #e0e0e0;
    padding: 0 15px;
    transition: all 0.2s ease;
}

button[name="update_cart"] {
    background-color: #f8f8f8;
    color: #666;
    border: 1px solid #ddd;
    height: 48px;
    padding: 0 20px;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease;
}

button[name="update_cart"]:hover {
    background-color: #e0e0e0;
}

button[name="update_cart"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.coupon button {
    height: 48px;
    padding: 0 20px;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease;
    background-color: #f8f8f8;
    color: #666;
    border: 1px solid #ddd;
}

.coupon button:hover {
    background-color: #e0e0e0;
}

/* Cart Totals */
.cart-totals-wrapper {
    background-color: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    position: sticky;
    top: 30px;
}

.cart_totals {
    width: 100% !important;
}

.cart_totals h2 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #222;
}

.cart_totals table {
    width: 100%;
    margin-bottom: 25px;
}

.cart_totals th {
    padding: 15px 0;
    font-weight: 500;
    color: #555;
    width: 40%;
    text-align: left;
}

.cart_totals td {
    padding: 15px 0;
    text-align: right;
    font-weight: 500;
}

.cart_totals .order-total th,
.cart_totals .order-total td {
    font-size: 20px;
    font-weight: 700;
    color: #222;
    padding-top: 20px;
}

.cart_totals .order-total td .amount {
    color: #333;
}

.wc-proceed-to-checkout .checkout-button {
    width: 100%;
    padding: 16px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background-color: #8e44ad !important; /* Purple checkout button */
    border-color: #8e44ad !important;
    border-radius: 5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(142, 68, 173, 0.2);
}

.wc-proceed-to-checkout .checkout-button:hover {
    background-color: #7d3c98 !important;
    border-color: #7d3c98 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(142, 68, 173, 0.3);
}

/* Shipping Calculator */
.shipping-calculator-button {
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    display: inline-block;
    margin-top: 10px;
    transition: all 0.2s ease;
}

.shipping-calculator-button:hover {
    color: #2980b9;
    text-decoration: underline;
}

/* Simplified shipping information */
.woocommerce-shipping-destination {
    display: none !important; /* Hide the default shipping destination text */
}

.woocommerce-shipping-methods {
    list-style: none;
    padding: 0;
    margin: 0;
}

.woocommerce-shipping-methods li {
    padding: 8px 0;
}

/* Hide shipping destination text completely */
td.shipping p {
    display: none !important;
}

/* Price formatting */
.woocommerce-Price-amount {
    font-weight: 600;
}

/* Additional WooCommerce styling overrides */
.shop_table {
    border-collapse: collapse !important;
    border: none !important;
}

.shop_table th, 
.shop_table td {
    border: none !important;
    border-bottom: 1px solid #f0f0f0 !important;
    padding: 15px 0 !important;
}

.shop_table tr:last-child td {
    border-bottom: none !important;
}

/* Responsive Styles */
@media (max-width: 991px) {
    .cart-totals-wrapper {
        margin-top: 30px;
        position: static;
    }
}

@media (max-width: 767px) {
    .cart-item-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px 0;
    }
    
    .cart-item-image {
        width: 100px;
        margin-right: 0;
        margin-bottom: 20px;
    }
    
    .cart-item-details {
        width: 100%;
    }
    
    .cart-item-title {
        margin-right: 30px;
    }
    
    .cart-item-price,
    .cart-item-quantity,
    .cart-item-subtotal {
        width: 33.333%;
        text-align: center;
        margin-top: 15px;
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
    
    .page-title {
        font-size: 28px;
    }
    
    .cart-items-wrapper,
    .cart-totals-wrapper,
    .empty-cart-message {
        padding: 20px;
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


    
    // Simplify shipping method display
    const shippingMethods = document.querySelectorAll('.woocommerce-shipping-methods li label');
    if (shippingMethods.length > 0) {
        shippingMethods.forEach(method => {
            method.innerHTML = 'Econt';
        });
    }
});
</script>

<?php
do_action('woocommerce_after_main_content');

get_footer('shop');
?> 