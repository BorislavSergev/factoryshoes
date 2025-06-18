<?php
/**
 * The Template for displaying all single products
 * Enhanced Variable Product Layout
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * woocommerce_before_main_content hook.
 */
do_action('woocommerce_before_main_content');
?>

<div class="modern-product-container">
    <div class="container py-5">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            
            <?php 
            global $product;
            // Ensure $product is valid and get its type
            if (!is_a($product, 'WC_Product')) {
                $product = wc_get_product(get_the_ID());
            }
            
            // Get the product type
            $product_type = $product->get_type();
            $is_variable = ($product_type === 'variable');
            ?>
            
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class('modern-product-wrapper', $product); ?>>
                
                <!-- Main Product Content -->
                <div class="product-layout">
                    <div class="row">
                        
                        <!-- Product Image - Left Side -->
                        <div class="col-md-8">
                            <div class="product-image-card">
                                <?php
                                // Use the enhanced product gallery template
                                wc_get_template_part('single-product/product-image');
                                ?>
                            </div>
                        </div>
                        
                        <!-- Product Information - Right Side -->
                        <div class="col-md-4">
                            <div class="product-info-wrapper">
                                <!-- Category Badge -->
                                <?php if ($product->is_on_sale()) : ?>
                                <div class="product-badge">
                                    <span class="badge bg-primary">НАЧИСЛЕНО</span>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Product Title -->
                                <h1 class="product-title"><?php the_title(); ?></h1>
                                
                                <!-- Product Price -->
                                <div class="product-price">
                                    <span class="price-amount"><?php echo $product->get_price_html(); ?></span>
                                </div>
                                
                                <?php if ($product->is_in_stock()) : ?>
                                    
                                    <?php if ($is_variable) : ?>
                                        <!-- Variable Product Form -->
                                        <form class="variations_form cart" method="post" enctype="multipart/form-data" data-product_id="<?php echo absint($product->get_id()); ?>">
                                            <?php 
                                            // Get Available Variations
                                            $available_variations = $product->get_available_variations();
                                            $attributes = $product->get_variation_attributes();
                                            
                                            // Check if variations are available
                                            if (!empty($available_variations)) :
                                            ?>
                                                <!-- Display Attribute Options -->
                                                <div class="variations">
                                                    <?php foreach ($attributes as $attribute_name => $options) : 
                                                        // Get attribute label
                                                        $attribute_label = wc_attribute_label($attribute_name);
                                                        
                                                        // Check if this is a size attribute
                                                        $is_size_attr = (strpos(strtolower($attribute_name), 'size') !== false || 
                                                                        strpos(strtolower($attribute_name), 'размер') !== false);
                                                        
                                                        // Skip color attributes
                                                        $is_color_attr = (strpos(strtolower($attribute_name), 'color') !== false || 
                                                                         strpos(strtolower($attribute_name), 'colour') !== false || 
                                                                         strpos(strtolower($attribute_name), 'цвят') !== false);
                                                                         
                                                        // Skip rendering color attributes
                                                        if ($is_color_attr) continue;
                                                    ?>
                                                        <div class="variation-row <?php echo $is_size_attr ? 'size-control' : 'attribute-control'; ?>">
                                                            <div class="<?php echo $is_size_attr ? 'size-label' : 'attribute-label'; ?>">
                                                                <?php echo esc_html($attribute_label); ?> <span class="required">*</span>
                                                                <?php if ($is_size_attr) : ?>
                                                                    <span class="size-required-notice">Моля, изберете размер преди да добавите в кошницата</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <?php if ($is_size_attr) : ?>
                                                                <!-- Size attribute display as buttons -->
                                                                <div class="size-options">
                                                                    <?php
                                                                    if (!empty($options)) :
                                                                        foreach ($options as $option) :
                                                                            // Check if variation with this size is in stock
                                                                            $is_available = false;
                                                                            $variation_id = 0;
                                                                            $variation_price = '';
                                                                            
                                                                            foreach ($available_variations as $variation) {
                                                                                if (isset($variation['attributes']['attribute_' . sanitize_title($attribute_name)]) &&
                                                                                    $variation['attributes']['attribute_' . sanitize_title($attribute_name)] == $option &&
                                                                                    $variation['is_in_stock']) {
                                                                                    $is_available = true;
                                                                                    $variation_id = $variation['variation_id'];
                                                                                    $variation_price = $variation['display_price'];
                                                                                    break;
                                                                                }
                                                                            }
                                                                    ?>
                                                                        <div class="size-option <?php echo !$is_available ? 'out-of-stock' : ''; ?>"
                                                                             data-attribute="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                                                                             data-value="<?php echo esc_attr($option); ?>"
                                                                             data-variation-id="<?php echo esc_attr($variation_id); ?>"
                                                                             data-price="<?php echo esc_attr($variation_price); ?>">
                                                                            <?php echo esc_html($option); ?>
                                                                        </div>
                                                                    <?php
                                                                        endforeach;
                                                                    endif;
                                                                    ?>
                                                                </div>
                                                                <div class="validation-message">Моля, изберете размер</div>
                                                            <?php else : ?>
                                                                <!-- Standard dropdown for other attributes -->
                                                                <select id="<?php echo esc_attr(sanitize_title($attribute_name)); ?>" 
                                                                        name="attribute_<?php echo esc_attr(sanitize_title($attribute_name)); ?>" 
                                                                        class="attribute-select form-select">
                                                                    <option value="">Изберете <?php echo esc_html(strtolower($attribute_label)); ?></option>
                                                                    <?php foreach ($options as $option) : ?>
                                                                        <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <!-- Clear and variation ID fields -->
                                                <div class="single_variation_wrap">
                                                    <input type="hidden" name="variation_id" class="variation_id" value="" />
                                                    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
                                                    
                                                    <!-- Quantity Selector -->
                                                    <div class="quantity-control">
                                                        <div class="quantity-label">Количество</div>
                                                        <div class="quantity-selector">
                                                            <button type="button" class="quantity-btn minus">-</button>
                                                            <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                                                            <button type="button" class="quantity-btn plus">+</button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Add To Cart Button -->
                                                    <div class="cart-button-wrapper">
                                                        <button type="submit" class="btn btn-primary btn-add-to-cart single_add_to_cart_button">
                                                            <i class="fas fa-shopping-cart"></i> ДОБАВИ В КОШНИЦАТА
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php else : ?>
                                                <div class="out-of-stock-message">
                                                    <span>Изчерпано количество</span>
                                                </div>
                                            <?php endif; ?>
                                        </form>
                                    <?php else : ?>
                                        <!-- Simple Product Form -->
                                        <form class="cart" method="post" enctype="multipart/form-data">
                                            <!-- Quantity Selector -->
                                            <div class="quantity-control">
                                                <div class="quantity-label">Количество</div>
                                                <div class="quantity-selector">
                                                    <button type="button" class="quantity-btn minus">-</button>
                                                    <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                                                    <button type="button" class="quantity-btn plus">+</button>
                                                </div>
                                            </div>
                                            
                                            <!-- Add To Cart Button -->
                                            <div class="cart-button-wrapper">
                                                <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
                                                <button type="submit" class="btn btn-primary btn-add-to-cart single_add_to_cart_button">
                                                    <i class="fas fa-shopping-cart"></i> ДОБАВИ В КОШНИЦАТА
                                                </button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                    
                                <?php else : ?>
                                    <!-- Out of Stock Message -->
                                    <div class="out-of-stock-message">
                                        <span>Изчерпано количество</span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Product Features -->
                                <div class="product-features">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-4">
                                            <div class="feature-item">
                                                <div class="feature-icon-wrapper">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </div>
                                                <div class="feature-text">
                                                    <div class="feature-title">Безплатна доставка</div>
                                                    <div class="feature-subtitle">За поръчки над 100 лв.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="feature-item">
                                                <div class="feature-icon-wrapper">
                                                    <i class="fas fa-undo-alt"></i>
                                                </div>
                                                <div class="feature-text">
                                                    <div class="feature-title">Лесно връщане</div>
                                                    <div class="feature-subtitle">30-дневна политика</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="feature-item">
                                                <div class="feature-icon-wrapper">
                                                    <i class="fas fa-shield-alt"></i>
                                                </div>
                                                <div class="feature-text">
                                                    <div class="feature-title">Гаранция</div>
                                                    <div class="feature-subtitle">1 година гаранция</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty(get_the_content())) : ?>
                <!-- Product Description (Only if content exists) -->
                <div class="product-description-section mt-5">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10">
                            <div class="description-card">
                                <h2 class="section-title">Описание</h2>
                                <div class="description-content">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php
                $related_ids = wc_get_related_products($product->get_id(), 4);
                if (!empty($related_ids)) :
                ?>
                <!-- Related Products -->
                <div class="related-products-section mt-5">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="section-title">Може да харесате също</h2>
                            <div class="related-products-grid">
                                <div class="row">
                                    <?php foreach ($related_ids as $related_id) :
                                        $related_product = wc_get_product($related_id);
                                        if (!$related_product) continue;
                                    ?>
                                    <div class="col-md-3 col-6">
                                        <div class="product-card">
                                            <a href="<?php echo get_permalink($related_id); ?>" class="product-link">
                                                <div class="product-image">
                                                    <?php echo $related_product->get_image('medium'); ?>
                                                </div>
                                                <div class="product-details">
                                                    <h4 class="product-title"><?php echo $related_product->get_name(); ?></h4>
                                                    <div class="product-price"><?php echo $related_product->get_price_html(); ?></div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
        <?php endwhile; ?>
    </div>
</div>

<style>
/* Modern Product Layout Styles */
.modern-product-container {
    background-color: #f8f9fa;
    padding: 30px 0;
}

.product-layout {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
}

/* Product Image Card */
.product-image-card {
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    position: relative;
    height: 100%;
    width: 100%;
}

/* Gallery integration styles */
.product-image-card .woocommerce-product-gallery {
    width: 100%;
    margin: 0;
    max-width: 100%;
}

.product-image-card .onsale-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 10;
    background: #e74c3c;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Product Info Styles */
.product-info-wrapper {
    padding: 30px;
}

.product-badge {
    margin-bottom: 15px;
}

.badge {
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bg-primary {
    background-color: #e74c3c !important;
}

.product-title {
    font-size: 24px;
    font-weight: 400;
    margin-bottom: 20px;
    color: #333;
}

.product-price {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.price-amount {
    font-size: 24px;
    font-weight: 600;
    color: #333;
}

/* Variations */
.variations {
    margin-bottom: 20px;
}

.variation-row {
    margin-bottom: 15px;
}

.attribute-label, .size-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
    font-weight: 500;
}

.size-required-notice {
    display: inline-block;
    color: #e74c3c;
    font-size: 13px;
    font-weight: 400;
    margin-left: 10px;
}

.attribute-select {
    width: 100%;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0 10px;
    background-color: #f5f5f5;
    color: #333;
}

/* Out of Stock Message */
.out-of-stock-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
    font-size: 16px;
}

/* Size Selector - Circular Choice Chips */
.size-control {
    margin-bottom: 25px;
}

.required {
    color: #e74c3c;
}

.size-options {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 10px;
}

.size-option {
    width: 46px;
    height: 46px;
    background-color: transparent;
    border: 2px solid #333;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    transition: all 0.2s ease-in-out;
}

.size-option:hover:not(.out-of-stock) {
    border-color: #e74c3c;
    transform: scale(1.05);
}

.size-option.selected {
    background-color: #e74c3c;
    color: white;
    border-color: #e74c3c;
    transform: scale(1.05);
}

.size-option.out-of-stock {
    background-color: #f4f4f4;
    color: #bbb;
    border-color: #ddd;
    cursor: not-allowed;
    position: relative;
    opacity: 0.8;
}

.size-option.out-of-stock::before,
.size-option.out-of-stock::after {
    content: '';
    position: absolute;
    width: 28px;
    height: 2px;
    background-color: #bbb;
    border-radius: 1px;
}

.size-option.out-of-stock::before {
    transform: rotate(45deg);
}

.size-option.out-of-stock::after {
    transform: rotate(-45deg);
}

/* Optional tooltip for out-of-stock items */
.size-option.out-of-stock:hover::after {
    content: 'Изчерпано';
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0,0,0,0.7);
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    white-space: nowrap;
    z-index: 10;
}

.validation-message {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 8px;
    display: none;
    font-weight: 500;
}

/* Quantity Control */
.quantity-control {
    margin-bottom: 25px;
}

.quantity-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    max-width: 120px;
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

.quantity-input {
    width: 45px;
    height: 32px;
    text-align: center;
    border: 1px solid #ddd;
    margin: 0 5px;
}

/* Add to Cart Button */
.cart-button-wrapper {
    margin-bottom: 30px;
}

.btn-add-to-cart {
    width: 100%;
    padding: 12px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 4px;
    background-color: #e74c3c;
    border-color: #c0392b;
}

.btn-add-to-cart:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}

.btn-add-to-cart.disabled {
    background-color: #95a5a6;
    border-color: #7f8c8d;
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-add-to-cart.disabled:hover {
    background-color: #95a5a6;
    border-color: #7f8c8d;
}

/* Product Features */
.product-features {
    margin-top: 30px;
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px 10px;
    transition: transform 0.2s ease;
}

.feature-item:hover {
    transform: translateY(-3px);
}

.feature-icon-wrapper {
    width: 60px;
    height: 60px;
    background-color: #e74c3c;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.2);
}

.feature-icon-wrapper i {
    color: white;
    font-size: 22px;
}

.feature-text {
    line-height: 1.4;
    text-align: center;
}

.feature-title {
    font-size: 15px;
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.feature-subtitle {
    font-size: 13px;
    color: #777;
}

/* Description Section */
.product-description-section {
    padding: 20px 0;
}

.description-card {
    background-color: #fff;
    border-radius: 8px;
    padding: 25px 30px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
}

.section-title {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e74c3c;
    color: #333;
    position: relative;
    display: inline-block;
}

.description-content {
    color: #555;
    line-height: 1.7;
    font-size: 15px;
}

.description-content p {
    margin-bottom: 15px;
}

.description-content ul, 
.description-content ol {
    padding-left: 20px;
    margin-bottom: 15px;
}

.description-content ul li, 
.description-content ol li {
    margin-bottom: 8px;
}

/* Related Products */
.product-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.product-details {
    padding: 15px;
}

.product-card h4 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #333;
}

@media (max-width: 767px) {
    .product-info-wrapper {
        padding: 20px;
    }
    
    .product-title {
        font-size: 20px;
    }
    
    .price-amount {
        font-size: 20px;
    }
    
    .feature-title {
        font-size: 13px;
    }
    
    .feature-subtitle {
        font-size: 11px;
    }
    
    .size-option {
        width: 38px;
        height: 38px;
        font-size: 12px;
        border-width: 1.5px;
    }
    
    .feature-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .feature-icon-wrapper i {
        font-size: 18px;
    }
    
    .description-card {
        padding: 20px;
    }
    
    .section-title {
        font-size: 18px;
    }
}

/* Tablet and mobile layout for product images */
@media (max-width: 991px) {
    .product-layout .row {
        flex-direction: column;
    }
    
    .product-layout .col-md-8,
    .product-layout .col-md-4 {
        width: 100%;
        max-width: 100%;
        flex: 0 0 100%;
    }
    
    .product-image-card {
        margin-bottom: 30px;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Size selection handling
    const sizeOptions = document.querySelectorAll('.size-option:not(.out-of-stock)');
    const variationIdField = document.querySelector('.variation_id');
    const addToCartButton = document.querySelector('.btn-add-to-cart');
    const validationMessage = document.querySelector('.validation-message');
    
    // Disable add to cart button initially if size options exist
    if (sizeOptions.length > 0 && addToCartButton) {
        addToCartButton.classList.add('disabled');
        addToCartButton.setAttribute('disabled', 'disabled');
        
        // Show validation message initially
        if (validationMessage) {
            validationMessage.style.display = 'block';
        }
    }
    
    // Handle size selection
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            sizeOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Update hidden variation ID field
            if (variationIdField) {
                variationIdField.value = this.getAttribute('data-variation-id');
            }
            
            // Update attribute field
            const attributeName = this.getAttribute('data-attribute');
            const attributeValue = this.getAttribute('data-value');
            const attributeField = document.querySelector(`[name="attribute_${attributeName}"]`);
            
            if (!attributeField) {
                // Create hidden field if it doesn't exist
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = `attribute_${attributeName}`;
                hiddenField.value = attributeValue;
                document.querySelector('.variations_form').appendChild(hiddenField);
            } else {
                attributeField.value = attributeValue;
            }
            
            // Enable add to cart button
            if (addToCartButton) {
                addToCartButton.classList.remove('disabled');
                addToCartButton.removeAttribute('disabled');
            }
            
            // Hide validation message
            if (validationMessage) {
                validationMessage.style.display = 'none';
            }
        });
    });
    
    // Quantity buttons functionality
    const quantityInput = document.querySelector('.quantity-input');
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    
    if (minusBtn && plusBtn && quantityInput) {
        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });
    }
    
    // Form submission validation
    const variationForm = document.querySelector('.variations_form');
    if (variationForm) {
        variationForm.addEventListener('submit', function(e) {
            // Check if size is selected for variable products with size options
            if (sizeOptions.length > 0 && (!variationIdField || !variationIdField.value)) {
                e.preventDefault();
                
                // Show validation message
                if (validationMessage) {
                    validationMessage.style.display = 'block';
                }
                
                // Scroll to size options
                const sizeControl = document.querySelector('.size-control');
                if (sizeControl) {
                    sizeControl.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
            }
        });
    }
    
    // Translate stock status text
    const stockStatus = document.querySelector('.stock');
    if (stockStatus) {
        if (stockStatus.classList.contains('in-stock')) {
            stockStatus.textContent = stockStatus.textContent.replace('In stock', 'В наличност');
        } else if (stockStatus.classList.contains('out-of-stock')) {
            stockStatus.textContent = stockStatus.textContent.replace('Out of stock', 'Изчерпан');
        }
    }
    
    // Translate "Add to cart" button
    const addToCartBtn = document.querySelector('.single_add_to_cart_button');
    if (addToCartBtn) {
        if (addToCartBtn.textContent.includes('Add to cart')) {
            addToCartBtn.textContent = 'ДОБАВИ В КОЛИЧКАТА';
        }
    }
    
    // Translate "Description" tab
    const descTab = document.querySelector('.wc-tabs li.description_tab a');
    if (descTab) {
        descTab.textContent = 'Описание';
    }
    
    // Translate "Additional Information" tab
    const infoTab = document.querySelector('.wc-tabs li.additional_information_tab a');
    if (infoTab) {
        infoTab.textContent = 'Допълнителна информация';
    }
    
    // Translate "Reviews" tab
    const reviewsTab = document.querySelector('.wc-tabs li.reviews_tab a');
    if (reviewsTab) {
        reviewsTab.textContent = 'Отзиви';
    }
    
    // Translate "Related products" heading
    const relatedHeading = document.querySelector('.related.products h2');
    if (relatedHeading && relatedHeading.textContent === 'Related products') {
        relatedHeading.textContent = 'Подобни продукти';
    }
    
    // Translate quantity label
    const qtyLabel = document.querySelector('.quantity .screen-reader-text');
    if (qtyLabel) {
        qtyLabel.textContent = qtyLabel.textContent.replace('Quantity', 'Количество');
    }
    
    // Translate "SKU" text
    const skuText = document.querySelector('.sku_wrapper');
    if (skuText) {
        skuText.innerHTML = skuText.innerHTML.replace('SKU:', 'Код:');
    }
    
    // Translate "Category" text
    const catText = document.querySelector('.posted_in');
    if (catText) {
        catText.innerHTML = catText.innerHTML.replace('Category:', 'Категория:');
    }
});
</script>

<?php
/**
 * woocommerce_after_main_content hook.
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
?>