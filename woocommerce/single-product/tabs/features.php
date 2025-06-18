<?php
/**
 * Product Features Tab
 *
 * This template displays product features in a clean, organized format.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

global $product;

// Get product attributes
$attributes = $product->get_attributes();

if (!empty($attributes)) : ?>
    <div class="product-features-wrapper">
        <div class="features-list">
            <?php foreach ($attributes as $attribute) :
                // Skip variations
                if ($attribute->get_variation()) {
                    continue;
                }
                
                $name = wc_attribute_label($attribute->get_name());
                
                if ($attribute->is_taxonomy()) :
                    $terms = wp_get_post_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                    if (!is_wp_error($terms) && !empty($terms)) : ?>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title"><?php echo esc_html($name); ?></h4>
                                <div class="feature-value"><?php echo esc_html(implode(', ', $terms)); ?></div>
                            </div>
                        </div>
                    <?php endif;
                else :
                    $value = $attribute->get_options();
                    if (!empty($value)) : ?>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title"><?php echo esc_html($name); ?></h4>
                                <div class="feature-value"><?php echo esc_html(implode(', ', $value)); ?></div>
                            </div>
                        </div>
                    <?php endif;
                endif;
            endforeach; ?>
        </div>
    </div>
<?php else : ?>
    <p><?php esc_html_e('No product features available.', 'shoes-store'); ?></p>
<?php endif; ?> 