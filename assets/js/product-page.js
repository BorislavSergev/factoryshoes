/**
 * Modern Product Page JavaScript
 * Enhanced interactions and animations
 */

(function($) {
    'use strict';

    // Global variables
    let currentImageIndex = 0;
    let thumbnails = [];
    let mainImage = null;
    let selectedSize = null;
    let isProcessing = false;

    // Initialize when document is ready
    $(document).ready(function() {
        initializeProductPage();
    });

    function initializeProductPage() {
        // Cache DOM elements
        thumbnails = $('.thumbnail-item');
        mainImage = $('#mainProductImage');
        
        // Initialize features
        initializeImageGallery();
        initializeSizeSelector();
        initializeQuantityControls();
        initializeWishlist();
        initializeAddToCart();
        initializeAnimations();
        initializeMobileOptimizations();
    }

    // Image Gallery Functions
    function initializeImageGallery() {
        // Thumbnail click events
        thumbnails.on('click', function() {
            const $this = $(this);
            const imageUrl = $this.find('img').attr('src');
            
            if (imageUrl) {
                // Get larger version of image
                const largeImageUrl = imageUrl.replace('-150x150', '-600x600').replace('-300x300', '-600x600');
                selectImage($this, largeImageUrl);
            }
        });

        // Keyboard navigation for thumbnails
        thumbnails.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });

        // Add loading states
        if (mainImage.length) {
            mainImage.on('load', function() {
                $(this).closest('.main-image-container').removeClass('loading-shimmer');
            });
        }
    }

    // Global function for image selection
    window.selectImage = function(thumbnail, imageUrl) {
        const $thumbnail = $(thumbnail);
        
        // Remove active class from all thumbnails
        thumbnails.removeClass('active');
        
        // Add active class to clicked thumbnail
        $thumbnail.addClass('active');
        
        // Update main image with loading state
        if (mainImage.length) {
            mainImage.closest('.main-image-container').addClass('loading-shimmer');
            
            // Preload image
            const img = new Image();
            img.onload = function() {
                mainImage.attr('src', imageUrl);
                currentImageIndex = thumbnails.index($thumbnail);
            };
            img.src = imageUrl;
        }
    };

    // Global function for image navigation
    window.changeImage = function(direction) {
        if (thumbnails.length === 0) return;
        
        if (direction === 'next') {
            currentImageIndex = (currentImageIndex + 1) % thumbnails.length;
        } else {
            currentImageIndex = currentImageIndex === 0 ? thumbnails.length - 1 : currentImageIndex - 1;
        }
        
        const targetThumbnail = thumbnails.eq(currentImageIndex);
        if (targetThumbnail.length) {
            targetThumbnail.click();
        }
    };

    // Size Selector Functions
    function initializeSizeSelector() {
        const sizeOptions = $('.size-option-modern');
        
        sizeOptions.on('click', function() {
            const $this = $(this);
            
            // Remove selected class from all options
            sizeOptions.removeClass('selected');
            
            // Add selected class to clicked option
            $this.addClass('selected');
            
            // Store selected size
            selectedSize = $this.data('size');
            
            // Add animation
            $this.addClass('size-selected');
            setTimeout(() => $this.removeClass('size-selected'), 300);
            
            // Enable add to cart if size was required
            updateAddToCartState();
        });

        // Keyboard navigation
        sizeOptions.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                e.preventDefault();
                const currentIndex = sizeOptions.index(this);
                const nextIndex = e.key === 'ArrowRight' 
                    ? (currentIndex + 1) % sizeOptions.length 
                    : currentIndex === 0 ? sizeOptions.length - 1 : currentIndex - 1;
                sizeOptions.eq(nextIndex).focus().click();
            }
        });
    }

    // Quantity Controls
    function initializeQuantityControls() {
        const qtyInput = $('#productQuantity');
        const minusBtn = $('.qty-btn.minus');
        const plusBtn = $('.qty-btn.plus');

        // Plus/Minus button events
        minusBtn.on('click', function() {
            changeQuantity(-1);
        });

        plusBtn.on('click', function() {
            changeQuantity(1);
        });

        // Input validation
        qtyInput.on('input', function() {
            let value = parseInt($(this).val()) || 1;
            value = Math.max(1, Math.min(10, value));
            $(this).val(value);
        });

        // Keyboard shortcuts
        qtyInput.on('keydown', function(e) {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                changeQuantity(1);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                changeQuantity(-1);
            }
        });
    }

    // Global quantity change function
    window.changeQuantity = function(change) {
        const qtyInput = $('#productQuantity');
        if (!qtyInput.length) return;
        
        const currentValue = parseInt(qtyInput.val()) || 1;
        const newValue = currentValue + change;
        
        if (newValue >= 1 && newValue <= 10) {
            qtyInput.val(newValue);
            
            // Add animation
            qtyInput.addClass('qty-changed');
            setTimeout(() => qtyInput.removeClass('qty-changed'), 200);
        }
    };

    // Wishlist Functions
    function initializeWishlist() {
        const wishlistBtn = $('.wishlist-toggle');
        
        wishlistBtn.on('click', function() {
            const $this = $(this);
            const productId = $this.data('product-id');
            
            $this.toggleClass('active');
            
            // Update icon
            const icon = $this.find('i');
            if ($this.hasClass('active')) {
                icon.removeClass('far').addClass('fas');
                showNotification('Added to wishlist', 'success');
            } else {
                icon.removeClass('fas').addClass('far');
                showNotification('Removed from wishlist', 'info');
            }
            
            // Add animation
            $this.addClass('wishlist-animated');
            setTimeout(() => $this.removeClass('wishlist-animated'), 300);
        });
    }

    // Add to Cart Functions
    function initializeAddToCart() {
        const addToCartBtn = $('#addToCartBtn');
        const buyNowBtn = $('.btn-buy-now');
        
        addToCartBtn.on('click', function() {
            if (isProcessing) return;
            
            const sizeOptions = $('.size-option-modern');
            
            // Check if size is required and selected
            if (sizeOptions.length > 0 && !selectedSize) {
                showNotification('Please select a size', 'error');
                // Highlight size selector
                $('.size-selector-modern').addClass('size-required');
                setTimeout(() => $('.size-selector-modern').removeClass('size-required'), 2000);
                return;
            }
            
            processAddToCart($(this));
        });

        buyNowBtn.on('click', function() {
            const sizeOptions = $('.size-option-modern');
            
            if (sizeOptions.length > 0 && !selectedSize) {
                showNotification('Please select a size', 'error');
                return;
            }
            
            // Simulate buy now process
            showNotification('Redirecting to checkout...', 'info');
        });
    }

    function processAddToCart($button) {
        if (isProcessing) return;
        isProcessing = true;
        
        // Get form data
        const quantity = $('#productQuantity').val() || 1;
        
        // Update button state
        $button.addClass('loading');
        $button.html('<i class="fas fa-spinner fa-spin"></i> ADDING...');
        
        // Simulate AJAX request
        setTimeout(() => {
            // Success state
            $button.removeClass('loading').addClass('success');
            $button.html('<i class="fas fa-check"></i> ADDED TO CART');
            
            showNotification('Product added to cart successfully!', 'success');
            
            // Reset button after delay
            setTimeout(() => {
                $button.removeClass('success');
                $button.html('<i class="fas fa-shopping-bag"></i> ADD TO CART');
                isProcessing = false;
            }, 3000);
            
        }, 1200);
    }

    function updateAddToCartState() {
        const addToCartBtn = $('#addToCartBtn');
        const sizeOptions = $('.size-option-modern');
        
        if (sizeOptions.length > 0 && !selectedSize) {
            addToCartBtn.addClass('disabled');
        } else {
            addToCartBtn.removeClass('disabled');
        }
    }

    // Animation Functions
    function initializeAnimations() {
        // Intersection Observer for scroll animations
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observe elements for animation
            $('.product-features-modern .feature-item').each(function() {
                observer.observe(this);
            });

            $('.related-product-card').each(function() {
                observer.observe(this);
            });
        }

        // Add hover effects
        addHoverEffects();
    }

    function addHoverEffects() {
        // Product card hover effects
        $('.related-product-card').on('mouseenter', function() {
            $(this).addClass('card-hover');
        }).on('mouseleave', function() {
            $(this).removeClass('card-hover');
        });

        // Button hover effects
        $('.btn-add-to-cart, .btn-buy-now').on('mouseenter', function() {
            $(this).addClass('btn-hover');
        }).on('mouseleave', function() {
            $(this).removeClass('btn-hover');
        });
    }

    // Mobile Optimizations
    function initializeMobileOptimizations() {
        if (window.innerWidth <= 768) {
            // Touch-friendly interactions
            $('.size-option-modern').on('touchend', function(e) {
                e.preventDefault();
                $(this).click();
            });

            // Prevent zoom on input focus
            $('input[type="number"]').on('focus', function() {
                $(this).attr('readonly', true);
                setTimeout(() => $(this).removeAttr('readonly'), 100);
            });
        }
    }

    // Notification System
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        $('.product-notification').remove();
        
        const notification = $(`
            <div class="product-notification notification-${type}">
                <div class="notification-content">
                    <i class="fas fa-${getNotificationIcon(type)}"></i>
                    <span>${message}</span>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        // Animate in
        setTimeout(() => notification.addClass('show'), 100);
        
        // Auto remove
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    function getNotificationIcon(type) {
        switch(type) {
            case 'success': return 'check-circle';
            case 'error': return 'exclamation-circle';
            case 'warning': return 'exclamation-triangle';
            default: return 'info-circle';
        }
    }

    // Keyboard Navigation
    $(document).on('keydown', function(e) {
        // ESC key to close notifications
        if (e.key === 'Escape') {
            $('.product-notification').removeClass('show');
        }
        
        // Arrow keys for image navigation
        if ($('.main-image-container').is(':focus-within') || $('.thumbnail-gallery').is(':focus-within')) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                changeImage('prev');
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                changeImage('next');
            }
        }
    });

    // Window resize handler
    $(window).on('resize', function() {
        clearTimeout(window.resizeTimer);
        window.resizeTimer = setTimeout(() => {
            if (window.innerWidth <= 768) {
                initializeMobileOptimizations();
            }
        }, 250);
    });

})(jQuery);

// Add CSS for animations and notifications
const additionalStyles = `
<style>
.product-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid #3b82f6;
    max-width: 350px;
}

.product-notification.show {
    transform: translateX(0);
}

.product-notification.notification-success {
    border-left-color: #10b981;
}

.product-notification.notification-error {
    border-left-color: #ef4444;
}

.product-notification.notification-warning {
    border-left-color: #f59e0b;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}

.notification-content i {
    font-size: 18px;
    flex-shrink: 0;
}

.notification-success .notification-content i {
    color: #10b981;
}

.notification-error .notification-content i {
    color: #ef4444;
}

.notification-warning .notification-content i {
    color: #f59e0b;
}

.notification-info .notification-content i {
    color: #3b82f6;
}

.size-required {
    animation: shake 0.5s ease-in-out;
    border-color: #ef4444 !important;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.size-selected {
    animation: bounce 0.3s ease;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-2px) scale(1.05); }
}

.qty-changed {
    animation: pulse-qty 0.2s ease;
}

@keyframes pulse-qty {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.wishlist-animated {
    animation: heart-beat 0.3s ease;
}

@keyframes heart-beat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

.animate-in {
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}

.btn-hover {
    transform: translateY(-3px) !important;
}

.btn-add-to-cart.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.size-option-modern {
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

.size-option-modern:hover {
    transform: translateY(-2px);
}

.thumbnail-item {
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

.main-image-container.loading-shimmer::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@media (max-width: 768px) {
    .product-notification {
        top: 10px;
        right: 10px;
        left: 10px;
        max-width: none;
        transform: translateY(-100%);
    }
    
    .product-notification.show {
        transform: translateY(0);
    }
    
    .notification-content {
        padding: 12px 16px;
        font-size: 13px;
    }
    
    .notification-content i {
        font-size: 16px;
    }
}

/* Focus indicators for accessibility */
.size-option-modern:focus,
.qty-btn:focus,
.thumbnail-item:focus,
.btn-add-to-cart:focus,
.btn-buy-now:focus,
.wishlist-toggle:focus {
    outline: 3px solid #3b82f6;
    outline-offset: 2px;
    z-index: 100;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Loading state for buttons */
.btn-add-to-cart.loading {
    pointer-events: none;
}

.btn-add-to-cart.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
`;

// Inject additional styles
document.head.insertAdjacentHTML('beforeend', additionalStyles);