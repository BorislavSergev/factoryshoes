/**
 * Product Page Scripts for Factory Shoes
 * Modern interactive elements with smooth transitions
 */
jQuery(document).ready(function($) {
    
    // Size selector
    $('.size-option').on('click', function() {
        $('.size-option').removeClass('selected');
        $(this).addClass('selected');
        $('#size-validation-message').removeClass('show');
    });
    
    // Color selector
    $('.color-option').on('click', function() {
        $('.color-option').removeClass('active');
        $(this).addClass('active');
        $('#color-validation-message').removeClass('show');
    });
    
    // Quantity selector
    $('.qty-btn.decrease').on('click', function() {
        var input = $(this).siblings('.qty-input');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });
    
    $('.qty-btn.increase').on('click', function() {
        var input = $(this).siblings('.qty-input');
        var value = parseInt(input.val());
        if (value < 99) {
            input.val(value + 1);
        }
    });
    
    // Prevent manual entry of invalid quantity values
    $('.qty-input').on('change', function() {
        var value = parseInt($(this).val());
        if (isNaN(value) || value < 1) {
            $(this).val(1);
        } else if (value > 99) {
            $(this).val(99);
        }
    });
    
    // Thumbnail gallery
    $('.thumbnail-item').on('click', function() {
        $('.thumbnail-item').removeClass('active');
        $(this).addClass('active');
        
        // Update the main image
        var imageSrc = $(this).find('img').attr('src');
        $('.main-product-image img').attr('src', imageSrc).hide().fadeIn(300);
    });
    
    // Wishlist toggle
    $('.wishlist-toggle').on('click', function() {
        $(this).toggleClass('active');
        
        if ($(this).hasClass('active')) {
            $(this).html('<i class="bi bi-heart-fill"></i>');
            showToast('Продуктът е добавен в любими');
        } else {
            $(this).html('<i class="bi bi-heart"></i>');
            showToast('Продуктът е премахнат от любими');
        }
    });
    
    // Image navigation for gallery
    let currentImageIndex = 0;
    const $galleryImages = $('.thumbnail-item');
    
    $('.image-nav.next-image').on('click', function() {
        if (currentImageIndex < $galleryImages.length - 1) {
            currentImageIndex++;
            $galleryImages.eq(currentImageIndex).trigger('click');
        }
    });
    
    $('.image-nav.prev-image').on('click', function() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            $galleryImages.eq(currentImageIndex).trigger('click');
        }
    });
    
    // Add to Cart button - simulate loading state
    $('.btn-add-to-cart').on('click', function() {
        const $button = $(this);
        const originalText = $button.html();
        let isValid = true;
        
        // Check if a size is selected
        if ($('.size-option.selected').length === 0) {
            isValid = false;
            $('#size-validation-message').addClass('show');
            $('.size-selector').addClass('shake');
            setTimeout(function() {
                $('.size-selector').removeClass('shake');
            }, 500);
            showToast('Моля, изберете размер', 'warning');
        } else {
            $('#size-validation-message').removeClass('show');
        }
        
        // Check if a color is selected
        if ($('.color-option.active').length === 0) {
            isValid = false;
            $('#color-validation-message').addClass('show');
            $('.color-selector').addClass('shake');
            setTimeout(function() {
                $('.color-selector').removeClass('shake');
            }, 500);
        } else {
            $('#color-validation-message').removeClass('show');
        }
        
        // If validation fails, stop here
        if (!isValid) {
            $('html, body').animate({
                scrollTop: $('.product-variations').offset().top - 100
            }, 500);
            return;
        }
        
        // Get selected options
        const selectedSize = $('.size-option.selected').data('size');
        const selectedColor = $('.color-option.active').data('color');
        const quantity = parseInt($('.qty-input').val());
        
        // Loading state
        $button.prop('disabled', true).addClass('loading')
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Добавяне...');
        
        // Simulate AJAX delay
        setTimeout(function() {
            $button.removeClass('loading').addClass('success')
                .html('<i class="bi bi-check-lg"></i> Добавено');
            
            showToast(`Продуктът е добавен в кошницата! (Размер: ${selectedSize}, Цвят: ${selectedColor}, Количество: ${quantity})`, 'success');
            
            // Update cart icon with animation
            $('.cart-count').addClass('pulse').text(function(i, text) {
                return (parseInt(text) || 0) + quantity;
            });
            
            setTimeout(function() {
                $('.cart-count').removeClass('pulse');
            }, 1000);
            
            // Reset button after a delay
            setTimeout(function() {
                $button.prop('disabled', false).removeClass('success').html(originalText);
            }, 2000);
        }, 1000);
    });
    
    // Add to cart for recommended products
    $('.btn-add-cart').on('click', function() {
        const $button = $(this);
        const originalText = $button.html();
        const productName = $button.closest('.product-card').find('.product-title a').text();
        
        // Loading state
        $button.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        
        // Simulate AJAX delay
        setTimeout(function() {
            $button.html('<i class="bi bi-check-lg"></i>');
            
            showToast(productName + ' е добавен в кошницата!', 'success');
            
            // Update cart icon with animation
            $('.cart-count').addClass('pulse').text(function(i, text) {
                return (parseInt(text) || 0) + 1;
            });
            
            setTimeout(function() {
                $('.cart-count').removeClass('pulse');
            }, 1000);
            
            // Reset button after a delay
            setTimeout(function() {
                $button.prop('disabled', false).html(originalText);
            }, 1500);
        }, 800);
    });
    
    // Tabs initialization - ensure correct content is shown
    $('#productTabs button').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    // Helper function to show toast notifications
    function showToast(message, type = 'info') {
        // If no toast container exists, create one
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
        }
        
        // Set the appropriate background color based on type
        let bgClass = 'bg-primary';
        if (type === 'success') bgClass = 'bg-success';
        if (type === 'warning') bgClass = 'bg-warning text-dark';
        if (type === 'danger') bgClass = 'bg-danger';
        
        // Create toast HTML
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Add toast to container
        $('#toast-container').append(toastHtml);
        
        // Initialize and show the toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        toast.show();
        
        // Remove toast from DOM after it's hidden
        $(toastElement).on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
    
    // Image zoom on hover effect
    let touchDevice = false;
    
    // Check if we're on a touch device
    window.addEventListener('touchstart', function() {
        touchDevice = true;
    });
    
    if (!touchDevice) {
        $('.main-product-image').on('mousemove', function(e) {
            const $img = $(this).find('img');
            const offsetX = e.pageX - $(this).offset().left;
            const offsetY = e.pageY - $(this).offset().top;
            
            // Calculate percentage position
            const x = offsetX / $(this).width() * 100;
            const y = offsetY / $(this).height() * 100;
            
            // Move the background image
            $img.css({
                'transform-origin': x + '% ' + y + '%',
                'transform': 'scale(1.2)'
            });
        }).on('mouseleave', function() {
            const $img = $(this).find('img');
            $img.css({
                'transform-origin': 'center center',
                'transform': 'scale(1)'
            });
        });
    }
    
    // Add smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if(target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}); 