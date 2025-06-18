<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

global $product;

$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    )
);

// Get gallery images
$attachment_ids = $product->get_gallery_image_ids();
$has_gallery = !empty($attachment_ids);

// If we have a main image, add it to the gallery array at the beginning
if ($post_thumbnail_id) {
    array_unshift($attachment_ids, $post_thumbnail_id);
}

$total_images = count($attachment_ids);
?>

<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>">
    <?php if ($product->is_on_sale()) : ?>
        <span class="onsale-badge"><?php echo esc_html__('Sale!', 'shoes-store'); ?></span>
    <?php endif; ?>
    
    <!-- Wishlist button removed -->
    
    <div class="product-gallery-wrapper">
        <!-- Preloader -->
        <div class="gallery-preloader">
            <div class="spinner"></div>
        </div>
        
        <!-- Main Image Slider -->
        <div class="product-image-main">
            <?php if ($total_images > 0) : ?>
                <div class="main-image-slider">
                    <?php foreach ($attachment_ids as $index => $attachment_id) : 
                        $full_size_image = wp_get_attachment_image_src($attachment_id, 'full');
                        $thumbnail = wp_get_attachment_image_src($attachment_id, 'woocommerce_single');
                        $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                        
                        if (!$thumbnail) {
                            continue;
                        }
                    ?>
                        <div class="gallery-image-item">
                                                            <img 
                                src="<?php echo esc_url($thumbnail[0]); ?>" 
                                data-full-img="<?php echo esc_url($full_size_image[0]); ?>"
                                alt="<?php echo esc_attr($image_alt ? $image_alt : get_the_title($attachment_id)); ?>"
                                width="<?php echo esc_attr($thumbnail[1]); ?>"
                                height="<?php echo esc_attr($thumbnail[2]); ?>"
                                class="wp-post-image"
                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                            />
                            <?php if ($total_images > 0) : ?>
                                <a href="<?php echo esc_url($full_size_image[0]); ?>" class="zoom-btn" title="<?php echo esc_attr__('Click to zoom', 'shoes-store'); ?>">
                                    <i class="fas fa-search-plus"></i>
                                    <span class="zoom-indicator">Zoom</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($total_images > 1) : ?>
                    <!-- Navigation Arrows -->
                    <button type="button" class="gallery-nav prev-image">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="gallery-nav next-image">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                <?php endif; ?>
            <?php else : ?>
                <div class="woocommerce-product-gallery__image">
                    <img src="<?php echo esc_url(wc_placeholder_img_src('woocommerce_single')); ?>" alt="<?php echo esc_attr__('Placeholder', 'shoes-store'); ?>" />
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($total_images > 1) : ?>
            <!-- Image Counter -->
            <div class="gallery-counter">
                <span class="current-count">1</span> / <span class="total-count"><?php echo esc_html($total_images); ?></span>
            </div>
            
            <!-- Thumbnails -->
            <div class="product-thumbnails">
                <div class="thumbnails-slider">
                    <?php foreach ($attachment_ids as $index => $attachment_id) : 
                        $thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                        
                        if (!$thumbnail) {
                            continue;
                        }
                        
                        $active_class = ($index === 0) ? ' active' : '';
                    ?>
                        <div class="thumbnail-item<?php echo esc_attr($active_class); ?>" data-index="<?php echo esc_attr($index); ?>">
                            <?php echo wp_get_attachment_image($attachment_id, 'thumbnail'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($total_images > 4) : ?>
                    <button type="button" class="thumbnails-nav prev-thumb">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="thumbnails-nav next-thumb">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Lightbox Modal -->
    <div class="product-gallery-lightbox" id="gallery-lightbox">
        <div class="lightbox-container">
            <button type="button" class="lightbox-close">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="lightbox-content">
                <div class="lightbox-image">
                    <!-- Image will be inserted here via JavaScript -->
                </div>
                
                <?php if ($total_images > 1) : ?>
                    <button type="button" class="lightbox-nav prev-lightbox">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="lightbox-nav next-lightbox">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="lightbox-counter">
                        <span class="current-index">1</span> / <span class="total-images"><?php echo esc_html($total_images); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gallery Styling */
    .product-gallery-wrapper {
        position: relative;
        max-width: 100%;
        width: 100%;
    }

    .main-image-slider {
        position: relative;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        height: 600px; /* Further increased height */
        width: 100%; /* Full width */
    }

    .gallery-image-item {
        position: relative;
        display: none;
        width: 100%;
        height: 100%;
        overflow: hidden;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-image-item:first-child {
        display: block;
    }

    .gallery-image-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
        padding: 20px; /* Add padding to prevent image from touching the edges */
        max-height: 100%;
        max-width: 100%;
    }

    .gallery-image-item:hover img {
        transform: scale(1.08);
    }

    .zoom-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.95);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        font-size: 18px;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
        z-index: 5;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .gallery-image-item:hover .zoom-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .zoom-btn:hover {
        background: #3b82f6;
        color: #fff;
        transform: scale(1.15);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }
    
    .zoom-indicator {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
        white-space: nowrap;
        font-weight: 500;
    }
    
    .zoom-btn:hover .zoom-indicator {
        opacity: 1;
    }

    /* Gallery Navigation */
    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0;
        z-index: 5;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .prev-image {
        left: 20px;
    }

    .next-image {
        right: 20px;
    }

    .product-image-main:hover .gallery-nav {
        opacity: 1;
    }

    .gallery-nav:hover {
        background: #3b82f6;
        color: #fff;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    /* Gallery Counter */
    .gallery-counter {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        z-index: 5;
    }
    
    /* Thumbnails */
    .product-thumbnails {
        position: relative;
        margin-bottom: 20px;
    }

    .thumbnails-slider {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 10px 5px;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
        scroll-behavior: smooth;
        justify-content: center;
    }

    .thumbnails-slider::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    .thumbnail-item {
        flex: 0 0 120px;
        height: 120px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        opacity: 0.7;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin: 0 8px;
    }

    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbnail-item:hover {
        opacity: 1;
        transform: translateY(-4px);
    }

    .thumbnail-item.active {
        border-color: #3b82f6;
        opacity: 1;
        transform: translateY(-4px);
    }

    /* Thumbnails Navigation */
    .thumbnails-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        background: #fff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .prev-thumb {
        left: -10px;
    }

    .next-thumb {
        right: -10px;
    }

    .thumbnails-nav:hover {
        background: #3b82f6;
        color: #fff;
    }

    /* Lightbox */
    .product-gallery-lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
        backdrop-filter: blur(5px);
    }

    .product-gallery-lightbox.active {
        opacity: 1;
        visibility: visible;
    }

    .lightbox-container {
        position: relative;
        width: 95%;
        max-width: 1200px;
        height: 95%;
    }

    .lightbox-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-close:hover {
        background: #3b82f6;
        color: #fff;
        transform: scale(1.1);
    }

    .lightbox-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.25);
        border: none;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }

    .prev-lightbox {
        left: -80px;
    }

    .next-lightbox {
        right: -80px;
    }

    .lightbox-nav:hover {
        background: rgba(59, 130, 246, 0.8);
        transform: translateY(-50%) scale(1.1);
    }

    .lightbox-counter {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        background: rgba(0, 0, 0, 0.5);
        padding: 8px 16px;
        border-radius: 20px;
        min-width: 80px;
        text-align: center;
    }

    /* Mobile Responsive */
    @media (max-width: 1200px) {
        .main-image-slider {
            height: 550px;
        }
    }
    
    @media (max-width: 992px) {
        .main-image-slider {
            height: 500px;
        }
    }
    
    @media (max-width: 768px) {
        .main-image-slider {
            height: 400px;
            max-width: 100%;
            margin: 0 auto;
        }
        
        .gallery-nav {
            width: 45px;
            height: 45px;
            font-size: 16px;
            opacity: 1;
        }
        
        .zoom-btn {
            opacity: 1;
            transform: translateY(0);
            width: 45px;
            height: 45px;
        }
        
        .thumbnail-item {
            flex: 0 0 100px;
            height: 100px;
        }
        
        .lightbox-nav {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .prev-lightbox {
            left: 10px;
        }
        
        .next-lightbox {
            right: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .main-image-slider {
            height: 350px;
        }
        
        .thumbnail-item {
            flex: 0 0 80px;
            height: 80px;
        }
        
        .gallery-nav {
            width: 40px;
            height: 40px;
        }
    }
    
    /* Preloader */
    .gallery-preloader {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    
    .gallery-preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(59, 130, 246, 0.2);
        border-top-color: #3b82f6;
        border-radius: 50%;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Theme-specific overrides */
    .product-image-card .woocommerce-product-gallery {
        width: 100%;
        margin: 0;
        padding: 0;
    }
    
    /* Wishlist button removed */
    
    .onsale-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        background: #e74c3c;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const mainSlider = document.querySelector('.main-image-slider');
        const galleryItems = document.querySelectorAll('.gallery-image-item');
        const thumbnailItems = document.querySelectorAll('.thumbnail-item');
        const prevButton = document.querySelector('.prev-image');
        const nextButton = document.querySelector('.next-image');
        const prevThumb = document.querySelector('.prev-thumb');
        const nextThumb = document.querySelector('.next-thumb');
        const lightbox = document.getElementById('gallery-lightbox');
        const lightboxImage = document.querySelector('.lightbox-image');
        const lightboxClose = document.querySelector('.lightbox-close');
        const prevLightbox = document.querySelector('.prev-lightbox');
        const nextLightbox = document.querySelector('.next-lightbox');
        const currentIndexElement = document.querySelector('.current-index');
        const currentCountElement = document.querySelector('.current-count');
        const zoomButtons = document.querySelectorAll('.zoom-btn');
        const thumbnailsSlider = document.querySelector('.thumbnails-slider');
        const preloader = document.querySelector('.gallery-preloader');
        
        // Variables
        let currentIndex = 0;
        const totalImages = galleryItems.length;
        
        // Function to show image at given index
        function showImage(index) {
            // Hide all images with fade effect
            galleryItems.forEach(item => {
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 200);
            });
            
            // Show the selected image with fade effect
            if (galleryItems[index]) {
                setTimeout(() => {
                    galleryItems[index].style.display = 'block';
                    setTimeout(() => {
                        galleryItems[index].style.opacity = '1';
                    }, 50);
                }, 200);
            }
            
            // Update thumbnails
            thumbnailItems.forEach(item => item.classList.remove('active'));
            if (thumbnailItems[index]) {
                thumbnailItems[index].classList.add('active');
                
                // Scroll thumbnail into view if needed
                if (thumbnailsSlider) {
                    const scrollPosition = thumbnailItems[index].offsetLeft - thumbnailsSlider.offsetWidth / 2 + thumbnailItems[index].offsetWidth / 2;
                    thumbnailsSlider.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                }
            }
            
            // Update current index
            currentIndex = index;
            
            // Update counters
            if (currentIndexElement) {
                currentIndexElement.textContent = index + 1;
            }
            
            if (currentCountElement) {
                currentCountElement.textContent = index + 1;
            }
        }
        
        // Initialize gallery if we have images
        if (totalImages > 0) {
            // Hide preloader after images are loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    if (preloader) {
                        preloader.classList.add('hidden');
                    }
                }, 500);
            });
            
            showImage(0);
            
            // Set up thumbnail clicks
            thumbnailItems.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', () => showImage(index));
            });
            
            // Set up navigation buttons
            if (prevButton && nextButton && totalImages > 1) {
                prevButton.addEventListener('click', () => {
                    let newIndex = currentIndex - 1;
                    if (newIndex < 0) newIndex = totalImages - 1;
                    showImage(newIndex);
                });
                
                nextButton.addEventListener('click', () => {
                    let newIndex = currentIndex + 1;
                    if (newIndex >= totalImages) newIndex = 0;
                    showImage(newIndex);
                });
            }
            
            // Thumbnails navigation
            if (prevThumb && nextThumb && thumbnailsSlider) {
                prevThumb.addEventListener('click', () => {
                    thumbnailsSlider.scrollBy({
                        left: -150,
                        behavior: 'smooth'
                    });
                });
                
                nextThumb.addEventListener('click', () => {
                    thumbnailsSlider.scrollBy({
                        left: 150,
                        behavior: 'smooth'
                    });
                });
            }
            
            // Lightbox functionality
            if (lightbox && lightboxImage) {
                // Open lightbox
                zoomButtons.forEach((button, index) => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        
                        const fullImage = galleryItems[index].querySelector('img').getAttribute('data-full-img');
                        lightboxImage.innerHTML = `<img src="${fullImage}" alt="Product image" />`;
                        
                        lightbox.classList.add('active');
                        document.body.style.overflow = 'hidden';
                        
                        showImage(index);
                    });
                });
                
                // Close lightbox
                if (lightboxClose) {
                    lightboxClose.addEventListener('click', () => {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                }
                
                // Lightbox navigation
                if (prevLightbox && nextLightbox && totalImages > 1) {
                    prevLightbox.addEventListener('click', () => {
                        let newIndex = currentIndex - 1;
                        if (newIndex < 0) newIndex = totalImages - 1;
                        
                        const fullImage = galleryItems[newIndex].querySelector('img').getAttribute('data-full-img');
                        lightboxImage.innerHTML = `<img src="${fullImage}" alt="Product image" />`;
                        
                        showImage(newIndex);
                    });
                    
                    nextLightbox.addEventListener('click', () => {
                        let newIndex = currentIndex + 1;
                        if (newIndex >= totalImages) newIndex = 0;
                        
                        const fullImage = galleryItems[newIndex].querySelector('img').getAttribute('data-full-img');
                        lightboxImage.innerHTML = `<img src="${fullImage}" alt="Product image" />`;
                        
                        showImage(newIndex);
                    });
                }
                
                // Close lightbox when clicking outside
                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
                
                // Close lightbox with ESC key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
                
                // Navigate with arrow keys
                document.addEventListener('keydown', (e) => {
                    if (lightbox.classList.contains('active') && totalImages > 1) {
                        if (e.key === 'ArrowLeft') {
                            let newIndex = currentIndex - 1;
                            if (newIndex < 0) newIndex = totalImages - 1;
                            
                            const fullImage = galleryItems[newIndex].querySelector('img').getAttribute('data-full-img');
                            lightboxImage.innerHTML = `<img src="${fullImage}" alt="Product image" />`;
                            
                            showImage(newIndex);
                        } else if (e.key === 'ArrowRight') {
                            let newIndex = currentIndex + 1;
                            if (newIndex >= totalImages) newIndex = 0;
                            
                            const fullImage = galleryItems[newIndex].querySelector('img').getAttribute('data-full-img');
                            lightboxImage.innerHTML = `<img src="${fullImage}" alt="Product image" />`;
                            
                            showImage(newIndex);
                        }
                    }
                });
            }
            
            // Swipe functionality for mobile
            if (mainSlider && totalImages > 1) {
                let touchStartX = 0;
                let touchEndX = 0;
                
                mainSlider.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, false);
                
                mainSlider.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }, false);
                
                function handleSwipe() {
                    const swipeThreshold = 50;
                    if (touchEndX < touchStartX - swipeThreshold) {
                        // Swipe left - next image
                        let newIndex = currentIndex + 1;
                        if (newIndex >= totalImages) newIndex = 0;
                        showImage(newIndex);
                    } else if (touchEndX > touchStartX + swipeThreshold) {
                        // Swipe right - previous image
                        let newIndex = currentIndex - 1;
                        if (newIndex < 0) newIndex = totalImages - 1;
                        showImage(newIndex);
                    }
                }
            }
        }
    });
</script>