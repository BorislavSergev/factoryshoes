<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

// Get product data
$product_id = $product->get_id();
$product_name = $product->get_name();
$product_permalink = get_permalink($product_id);
$product_price_html = $product->get_price_html();
$product_image_id = $product->get_image_id();
$product_image = wp_get_attachment_image_src($product_image_id, 'medium');
$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src('medium');
$product_rating = $product->get_average_rating();
$product_categories = wc_get_product_category_list($product_id, ', ');
$product_category = '';

// Extract a single category name for display
if ($product_categories) {
    $dom = new DOMDocument();
    $dom->loadHTML(mb_convert_encoding($product_categories, 'HTML-ENTITIES', 'UTF-8'));
    $links = $dom->getElementsByTagName('a');
    if ($links->length > 0) {
        $product_category = $links->item(0)->textContent;
    }
}

if (empty($product_category)) {
    $product_category = 'UNCATEGORIZED';
}

// Check if product is on sale
$on_sale = $product->is_on_sale();

// Check if product is variable (has sizes)
$is_variable = $product->is_type('variable');
?>

<li <?php wc_product_class('modern-product-item', $product); ?>>
    <div class="product-card-wrapper">
        <?php if ($on_sale) : ?>
            <span class="product-badge sale">Промоция</span>
        <?php endif; ?>
        
        <div class="product-image-container">
            <a href="<?php echo esc_url($product_permalink); ?>">
                <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_name); ?>" class="product-image">
            </a>
            
            <div class="product-actions">
                <a href="<?php echo esc_url($product_permalink); ?>" class="view-product-btn">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        
        <div class="product-info">
            <div class="product-brand"><?php echo esc_html($product_category); ?></div>
            
            <h3 class="product-title">
                <a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($product_name); ?></a>
            </h3>
            
            <div class="product-price-action">
                <div class="product-price">
                    <?php echo $product_price_html; ?>
                </div>
                <a href="<?php echo esc_url($product_permalink); ?>" 
                   class="select-options-btn"
                   aria-label="<?php esc_attr_e('Избери размер', 'shoes-store'); ?>">
                    <i class="fas fa-shoe-prints"></i>
                    <span><?php _e('Избери размер', 'shoes-store'); ?></span>
                </a>
            </div>
        </div>
    </div>
</li>

<style>
/* Modern Product Card Styles - Matching Homepage Design */
.modern-product-item {
    transition: all 0.3s ease;
    margin-bottom: 30px !important;
}

.product-card-wrapper {
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.modern-product-item:hover .product-card-wrapper {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}

.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #e74c3c;
    color: #fff;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 3px;
    z-index: 10;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    padding: 20px;
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
}

.product-image {
    max-width: 100%;
    max-height: 200px;
    width: auto !important;
    height: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.modern-product-item:hover .product-image {
    transform: scale(1.05);
}

.product-actions {
    position: absolute;
    bottom: -50px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    opacity: 0;
}

.modern-product-item:hover .product-actions {
    bottom: 20px;
    opacity: 1;
}

.product-actions a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #fff;
    color: #333;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.product-actions a:hover {
    background-color: #8e44ad;
    color: #fff;
}

.product-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-brand {
    color: #888;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.product-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 10px 0;
    padding: 0;
    line-height: 1.4;
    color: #333;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 22px;
    max-height: 44px;
}

.product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-title a:hover {
    color: #8e44ad;
}

.product-rating {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.product-rating .star {
    color: #ffc107;
    font-size: 14px;
    margin-right: 2px;
}

.product-rating .rating-count {
    color: #888;
    font-size: 12px;
    margin-left: 5px;
}

.product-price-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.product-price {
    font-weight: 700;
    font-size: 18px;
    color: #333;
}

.product-price del {
    color: #999;
    font-size: 14px;
    font-weight: 400;
    margin-right: 5px;
}

.product-price ins {
    text-decoration: none;
    color: #e74c3c;
}

a.select-options-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 15px;
    background-color: #8e44ad;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    text-align: center;
    font-size: 13px;
    transition: all 0.3s ease;
    text-decoration: none;
    line-height: 1.4;
}

a.select-options-btn:hover {
    background-color: #7d3c98;
    color: #fff;
}

a.select-options-btn i {
    margin-right: 5px;
}

/* Out of stock styling */
.outofstock .product-image-container:after {
    content: 'Изчерпан';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.outofstock .select-options-btn {
    background-color: #95a5a6;
    cursor: not-allowed;
}

.outofstock .select-options-btn:hover {
    background-color: #7f8c8d;
}

/* WooCommerce overrides */
.woocommerce ul.products {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin: 0;
    padding: 0;
    list-style: none;
    width: 100%;
}

.woocommerce-page ul.products li.product {
    float: none;
    margin: 0;
    width: 100%;
}

.woocommerce .products .star-rating {
    display: none;
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .woocommerce ul.products {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 575px) {
    .woocommerce ul.products {
        grid-template-columns: 1fr;
    }
    
    .product-title {
        font-size: 14px;
    }
    
    .product-price {
        font-size: 16px;
    }
    
    a.select-options-btn {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    a.select-options-btn span {
        display: none;
    }
    
    a.select-options-btn i {
        margin-right: 0;
    }
}
</style> 