<?php
/**
 * The template for displaying all single posts
 *
 * @package Shoes_Store_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container" style="padding: 4rem 0;">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail" style="margin-bottom: 2rem; text-align: center;">
                        <?php the_post_thumbnail('large', array('style' => 'max-width: 100%; height: auto; border-radius: 10px;')); ?>
                    </div>
                <?php endif; ?>

                <header class="entry-header" style="text-align: center; margin-bottom: 2rem;">
                    <h1 class="entry-title" style="font-size: 2.5rem; font-weight: 700; color: #2c3e50; margin-bottom: 1rem;">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="entry-meta" style="color: #666; font-size: 0.9rem;">
                        <span class="posted-on">
                            <?php echo get_the_date(); ?>
                        </span>
                        <span class="byline">
                            <?php _e('by', 'shoes-store'); ?> <?php the_author(); ?>
                        </span>
                        
                        <?php 
                        $price = get_post_meta(get_the_ID(), 'product_price', true);
                        if ($price) :
                        ?>
                            <div class="product-price" style="font-size: 1.8rem; font-weight: 700; color: #e74c3c; margin: 1rem 0;">
                                $<?php echo esc_html($price); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="entry-content" style="line-height: 1.7; font-size: 1.1rem; max-width: 800px; margin: 0 auto;">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'shoes-store'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <?php if (get_the_tags()) : ?>
                    <footer class="entry-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #eee; text-align: center;">
                        <div class="tags-links">
                            <?php the_tags('<span class="tags-label">' . __('Tags: ', 'shoes-store') . '</span>', ', '); ?>
                        </div>
                    </footer>
                <?php endif; ?>

            </article>

            <?php
            // Navigation between posts
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'shoes-store') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'shoes-store') . '</span> <span class="nav-title">%title</span>',
            ));

            // Load comments template if comments are open or we have at least one comment
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

        <!-- Related Products -->
        <?php
        $related_posts = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'orderby' => 'rand'
        ));

        if ($related_posts->have_posts()) :
        ?>
            <section class="related-products" style="margin-top: 4rem; padding-top: 3rem; border-top: 2px solid #f0f0f0;">
                <h2 style="text-align: center; font-size: 2rem; margin-bottom: 2rem; color: #2c3e50;">
                    <?php _e('You Might Also Like', 'shoes-store'); ?>
                </h2>
                
                <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                        <div class="product-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', array('class' => 'product-image')); ?>
                                </a>
                            <?php else : ?>
                                <div class="product-image" style="background: linear-gradient(45deg, #f0f0f0, #e0e0e0); display: flex; align-items: center; justify-content: center; color: #999; font-size: 3rem; height: 250px;">
                                    👟
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: #2c3e50;">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <?php 
                                $price = get_post_meta(get_the_ID(), 'product_price', true);
                                if ($price) :
                                ?>
                                    <div class="product-price">$<?php echo esc_html($price); ?></div>
                                <?php endif; ?>
                                
                                <p class="product-description">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </p>
                                
                                <a href="<?php the_permalink(); ?>" class="btn">
                                    <?php _e('View Details', 'shoes-store'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php 
        endif; 
        wp_reset_postdata();
        ?>
    </div>
</main>

<style>
.single-post .entry-content h2,
.single-post .entry-content h3,
.single-post .entry-content h4 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.single-post .entry-content p {
    margin-bottom: 1.5rem;
}

.single-post .entry-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.post-navigation {
    margin: 3rem 0;
    padding: 2rem 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.post-navigation .nav-links {
    display: flex;
    justify-content: space-between;
    gap: 2rem;
}

.post-navigation .nav-subtitle {
    font-size: 0.9rem;
    color: #666;
    display: block;
}

.post-navigation .nav-title {
    font-weight: 600;
    color: #2c3e50;
}

@media (max-width: 768px) {
    .post-navigation .nav-links {
        flex-direction: column;
    }
    
    .single-post .entry-title {
        font-size: 2rem !important;
    }
}
</style>

<?php get_footer(); ?> 