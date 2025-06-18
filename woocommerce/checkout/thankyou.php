<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order">
    <?php if ($order) :
        do_action('woocommerce_before_thankyou', $order->get_id());
    ?>

        <?php if ($order->has_status('failed')) : ?>
            <div class="order-failed-container">
                <div class="order-icon-container failed">
                    <i class="fas fa-times"></i>
                </div>
                <h2 class="order-failed-title"><?php esc_html_e('Неуспешна поръчка', 'woocommerce'); ?></h2>
                <p class="order-failed-message"><?php esc_html_e('За съжаление вашата поръчка не може да бъде обработена, тъй като банката/издателят на картата ви отказа транзакцията.', 'woocommerce'); ?></p>
                <p class="order-actions">
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php esc_html_e('Плати', 'woocommerce'); ?></a>
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button account"><?php esc_html_e('Моят профил', 'woocommerce'); ?></a>
                    <?php endif; ?>
                </p>
            </div>
        <?php else : ?>
            <div class="order-success-container">
                <div class="order-icon-container success">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="order-success-title"><?php esc_html_e('Благодарим ви. Вашата поръчка е получена.', 'woocommerce'); ?></h2>
                
                <div class="order-details-container">
                    <div class="order-details-table">
                        <div class="order-detail-row">
                            <div class="order-detail-label"><?php esc_html_e('Номер на поръчка:', 'woocommerce'); ?></div>
                            <div class="order-detail-value"><?php echo esc_html($order->get_order_number()); ?></div>
                        </div>

                        <div class="order-detail-row">
                            <div class="order-detail-label"><?php esc_html_e('Дата:', 'woocommerce'); ?></div>
                            <div class="order-detail-value"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></div>
                        </div>

                        <?php if ($order->get_payment_method_title()) : ?>
                        <div class="order-detail-row">
                            <div class="order-detail-label"><?php esc_html_e('Метод на плащане:', 'woocommerce'); ?></div>
                            <div class="order-detail-value"><?php echo esc_html($order->get_payment_method_title()); ?></div>
                        </div>
                        <?php endif; ?>

                        <div class="order-detail-row">
                            <div class="order-detail-label"><?php esc_html_e('Обща сума:', 'woocommerce'); ?></div>
                            <div class="order-detail-value"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($order->get_customer_note()) : ?>
                <div class="order-note-container">
                    <h3><?php esc_html_e('Допълнителна информация', 'woocommerce'); ?></h3>
                    <p><?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
            <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

            <div class="order-details-section">
                <h3><?php esc_html_e('Детайли на поръчката', 'woocommerce'); ?></h3>
                
                <div class="order-items-container">
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th class="product-name"><?php esc_html_e('Продукт', 'woocommerce'); ?></th>
                                <th class="product-quantity"><?php esc_html_e('Количество', 'woocommerce'); ?></th>
                                <th class="product-total"><?php esc_html_e('Цена', 'woocommerce'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->get_items() as $item_id => $item) : 
                                $product = $item->get_product();
                                $product_name = $item->get_name();
                                $quantity = $item->get_quantity();
                                $subtotal = $order->get_formatted_line_subtotal($item);
                            ?>
                            <tr>
                                <td class="product-name">
                                    <?php echo esc_html($product_name); ?>
                                    <?php echo wc_get_formatted_cart_item_data($item); ?>
                                </td>
                                <td class="product-quantity"><?php echo esc_html($quantity); ?></td>
                                <td class="product-total"><?php echo wp_kses_post($subtotal); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <?php foreach ($order->get_order_item_totals() as $key => $total) : ?>
                            <tr>
                                <th scope="row" colspan="2"><?php echo esc_html($total['label']); ?></th>
                                <td><?php echo wp_kses_post($total['value']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="order-addresses-section">
                <div class="addresses-container">
                    <div class="billing-address">
                        <h3><?php esc_html_e('Адрес за фактуриране', 'woocommerce'); ?></h3>
                        <address>
                            <?php echo wp_kses_post($order->get_formatted_billing_address(esc_html__('Н/П', 'woocommerce'))); ?>
                            
                            <?php if ($order->get_billing_phone()) : ?>
                                <p class="address-phone"><?php echo esc_html($order->get_billing_phone()); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($order->get_billing_email()) : ?>
                                <p class="address-email"><?php echo esc_html($order->get_billing_email()); ?></p>
                            <?php endif; ?>
                        </address>
                    </div>

                    <?php if ($order->needs_shipping_address()) : ?>
                    <div class="shipping-address">
                        <h3><?php esc_html_e('Адрес за доставка', 'woocommerce'); ?></h3>
                        <address>
                            <?php echo wp_kses_post($order->get_formatted_shipping_address(esc_html__('Н/П', 'woocommerce'))); ?>
                        </address>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="order-actions-container">
                <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="button continue-shopping">
                    <?php esc_html_e('Продължете с пазаруването', 'woocommerce'); ?>
                </a>
                
                <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button view-account">
                    <?php esc_html_e('Моят профил', 'woocommerce'); ?>
                </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="order-not-found-container">
            <div class="order-icon-container not-found">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="order-not-found-title"><?php esc_html_e('Не е намерена поръчка', 'woocommerce'); ?></h2>
            <p class="order-not-found-message"><?php esc_html_e('За съжаление не можахме да намерим вашата поръчка.', 'woocommerce'); ?></p>
            <p class="order-actions">
                <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="button return-shop"><?php esc_html_e('Към магазина', 'woocommerce'); ?></a>
            </p>
        </div>
    <?php endif; ?>
</div>
