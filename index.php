<?php
/**
 * The main template file
 *
 * @package Shoes_Store_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    
    <!-- Hero Section -->
    <section class="hero-section modern-hero">
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <div class="container">
            <div class="hero-wrapper">
                <!-- Left Content -->
                <div class="hero-content">
                    <div class="sale-badge-wrapper">
                        <div class="sale-badge">
                            <i class="fas fa-tags fa-sm"></i>
                            <span><?php echo esc_html(get_theme_mod('sale_label', __('Продукти в Промоция', 'shoes-store'))); ?></span>
                        </div>
                    </div>
                    
                    <h1 class="hero-title">
                        <?php echo esc_html(get_theme_mod('hero_title', __('Улови погледите. Намери своите Nike тук', 'shoes-store'))); ?>
                    </h1>
                    
                    <p class="hero-subtitle">
                        <?php echo esc_html(get_theme_mod('hero_subtitle', __('Най-актуалните модели и вечни класики, които ще подчертаят твоята индивидуалност.', 'shoes-store'))); ?>
                    </p>
                    
                    <!-- Buttons moved back inside hero-content -->
                    <div class="hero-buttons">
                        <a href="<?php echo esc_url(get_theme_mod('hero_button_url', '#products')); ?>" class="hero-cta-button">
                            <?php echo esc_html(get_theme_mod('hero_button_text', __('Намери своя чифт', 'shoes-store'))); ?>
                        </a>
                    </div>
                    
                    <div class="hero-features">
                        <div class="hero-feature">
                            <i class="fas fa-truck-fast"></i>
                            <span><?php _e('Безплатна Доставка Над 250лв', 'shoes-store'); ?></span>
                        </div>
                        <div class="hero-feature">
                            <i class="fas fa-shield-alt"></i>
                            <span><?php _e('Гаранция за Качество', 'shoes-store'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Hero Product -->
                <div class="hero-product">
                    <div class="hero-product-image">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero.png" 
                             alt="Featured Shoes" 
                             class="featured-shoe-img"
                             loading="lazy"
                             onerror="this.src='<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero.png'; this.onerror=null;">
                        <div class="hero-product-badge">
                            <span class="hero-product-discount">-60%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="scroll-indicator mobile-hidden">
                <a href="#products" aria-label="<?php _e('Scroll Down', 'shoes-store'); ?>">
                    <div class="mouse">
                        <div class="wheel"></div>
                    </div>
                    <div class="arrows">
                        <span class="arrow-down"></span>
                        <span class="arrow-down"></span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <?php get_template_part('template-parts/content', 'about'); ?>

    <!-- All Products Section -->
    <section class="all-products-section" id="products">
        <div class="container">
            <div class="products-header">
                <h2 class="section-title"><?php _e('Всички Продукти', 'shoes-store'); ?></h2>
            </div>
            
            <?php
            // Query to get all products
            $all_products_args = array(
                'post_type' => 'product',
                'posts_per_page' => -1, // Get all products
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            );
            
            $all_products_query = new WP_Query($all_products_args);
            
            // Check if products exist
            if ($all_products_query->have_posts()) : 
            ?>
            
            <div class="products-grid modern-grid all-products-grid">
                <?php 
                // Loop through products
                while ($all_products_query->have_posts()) : $all_products_query->the_post(); 
                    $product = wc_get_product(get_the_ID());
                    if (!$product) continue;
                ?>
                    <div class="product-card modern-card">
                        <div class="product-image-wrapper">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('product-thumbnail', array('class' => 'product-image')); ?>
                                </a>
                            <?php else : ?>
                                <div class="product-image-placeholder">
                                    <i class="fas fa-shoe-prints shoe-icon"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($product->is_on_sale()) : ?>
                                <div class="sale-badge">
                                    <span><?php _e('Sale!', 'shoes-store'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <?php 
                            $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
                            if ($product_cats && !is_wp_error($product_cats)) : 
                            ?>
                                <div class="product-brand"><?php echo esc_html($product_cats[0]->name); ?></div>
                            <?php endif; ?>
                            
                            <h3 class="product-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php if (get_theme_mod('show_product_ratings', true) && $product->get_average_rating()) : ?>
                                <div class="product-rating">
                                    <?php echo shoes_store_get_star_rating($product->get_average_rating()); ?>
                                    <span class="rating-count">(<?php echo $product->get_rating_count(); ?>)</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-price-action">
                                <div class="product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="view-product-btn add-to-cart-btn-style" style="text-decoration: none; background-color: #e74c3c; border-color: #e74c3c; color: white;" aria-label="<?php esc_attr_e('Преглед', 'shoes-store'); ?>">
                                    <i class="fas fa-eye"></i>
                                    <span><?php _e('Преглед', 'shoes-store'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <?php 
            wp_reset_postdata();
            else : 
                // No products found message
                if (class_exists('WooCommerce')) :
            ?>
                <div class="no-products-message">
                    <div class="message-container">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3><?php _e('Няма намерени продукти', 'shoes-store'); ?></h3>
                        <p><?php _e('В момента няма добавени продукти. Моля, добавете продукти от административния панел.', 'shoes-store'); ?></p>
                    </div>
                </div>
            <?php
                endif;
            endif; 
            ?>
        </div>
    </section>

    <!-- Contact Information Section -->
    <?php get_template_part('template-parts/content', 'contact'); ?>

</main>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Върни се в началото', 'shoes-store'); ?>">
    <i class="fas fa-arrow-up"></i>
</button>

<?php get_footer(); ?> 