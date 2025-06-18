
function initCartActions() {
    console.log('Initializing cart actions');
    jQuery(document).off('click', '.cart-item-dec').on('click', '.cart-item-dec', function() {
        const itemKey = jQuery(this).attr('data-item-key');
        const cartItem = jQuery(this).closest('.cart-item');
        const quantitySpan = cartItem.find('.cart-item-quantity span');
        let currentQty = parseInt(quantitySpan.text());
        console.log('Decrease button clicked:', itemKey, currentQty);
        if (currentQty > 1) {
            cartItem.addClass('updating');
            updateCartItemQuantity(itemKey, currentQty - 1);
        }
    });

    jQuery(document).off('click', '.cart-item-inc').on('click', '.cart-item-inc', function() {
        const itemKey = jQuery(this).attr('data-item-key');
        const cartItem = jQuery(this).closest('.cart-item');
        const quantitySpan = cartItem.find('.cart-item-quantity span');
        let currentQty = parseInt(quantitySpan.text());
        console.log('Increase button clicked:', itemKey, currentQty);
        cartItem.addClass('updating');
        updateCartItemQuantity(itemKey, currentQty + 1);
    });

    jQuery(document).off('click', '.cart-item-remove').on('click', '.cart-item-remove', function() {
        const itemKey = jQuery(this).attr('data-item-key');
        const cartItem = jQuery(this).closest('.cart-item');
        console.log('Remove button clicked:', itemKey);
        cartItem.addClass('updating');
        removeCartItem(itemKey);
    });
}


jQuery(document).ready(function($) {
    initCartActions();

   
    $(document).on('click', '.cart-toggle', function() {
        updateCartContents();
        // Reinitialize cart actions after cart contents are updated
        setTimeout(() => {
            initCartActions();
        }, 500);
    });



    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn:not(.single_variation_wrap .button)');

    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const quantity = this.getAttribute('data-quantity') || 1;
            const variationId = this.getAttribute('data-variation-id') || 0;
            const originalText = this.innerHTML;
            // Show loading state
            this.innerHTML = ' Добавяне...';
            this.disabled = true;

            $(document.body).trigger('adding_to_cart', [$(this), {}]);
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                data: {
                    product_id: productId,
                    variation_id: variationId,
                    quantity: quantity
                },
                success: (response) => {
                    if (response.error) {
                  
                        this.innerHTML = ' Грешка';
                        this.style.background = 'linear-gradient(45deg, #e74c3c, #c0392b)';
                        console.error('Error adding to cart:', response.message);
                    } else {
                        
                        this.innerHTML = ' Добавено!';
                        this.style.background = 'linear-gradient(45deg, #27ae60, #2ecc71)';
                       
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $(this)]);
                      
                        if (response.cart_count) {
                            updateAllCartCounters(response.cart_count);
                        }
                        
                        const cartOffcanvas = document.getElementById('cart-offcanvas');
                        const cartOverlay = document.getElementById('cart-overlay');
                        if (cartOffcanvas && cartOverlay) {
                            setTimeout(() => {
                                cartOffcanvas.classList.add('active');
                                cartOverlay.classList.add('active');
                                document.body.style.overflow = 'hidden';
                           
                                setTimeout(() => {
                                    initCartActions();
                                }, 100);
                            }, 300);
                        }
                    }
                   
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.background = '';
                        this.disabled = false;
                    }, 2000);
                },
                error: (error) => {
                    console.error('Error:', error);
                   
                    this.innerHTML = ' Грешка';
                    this.style.background = 'linear-gradient(45deg, #e74c3c, #c0392b)';
                   
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.background = '';
                        this.disabled = false;
                    }, 2000);
                }
            });
            
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });


    const header = document.querySelector('.site-header');
    const scrollThreshold = 50;

    function handleScroll() {
        if (window.scrollY > scrollThreshold) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll(); 

   
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
           
            if (targetId && targetId !== '#' && document.querySelector(targetId)) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                const headerHeight = header.offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                window.scrollTo({
                    top: targetPosition - headerHeight - 20, // 20px extra padding
                    behavior: 'smooth'
                });
            }
        });
    });

  
    document.querySelectorAll('.products-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            const isHomePage = window.location.pathname === '/' || window.location.pathname === '/index.php' || window.location.href === window.location.origin + '/' || window.location.href === window.location.origin;
         
            if (isHomePage) {
                e.preventDefault();
                const targetId = href.split('#')[1];
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    const headerHeight = header.offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                    window.scrollTo({
                        top: targetPosition - headerHeight - 20,
                        behavior: 'smooth'
                    });
                }
            }
          
        });
    });

 
    const backToTopButton = document.getElementById('back-to-top');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    
    function removeCartItem(key) {
        const $item = $('.cart-item[data-item-key="' + key + '"]');
        $item.addClass('updating');
        console.log('Removing cart item with key:', key);
      
        if (typeof wc_add_to_cart_params === 'undefined') {
            console.error('Error: wc_add_to_cart_params is undefined');
            $item.removeClass('updating');
            return;
        }
      
        const fragmentNonce = woocommerce_params.fragment_nonce || '';
        
        $item.css('opacity', '0.5');
        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: {
                action: 'remove_cart_item',
                cart_item_key: key,
                security: fragmentNonce
            },
            success: function(response) {
                console.log('Remove cart item response:', response);
                if (!response || response.error) {
                    console.error('Error removing item:', response ? response.message : 'Unknown error');
                    $item.css('opacity', '1');
                    $item.removeClass('updating');
                    return;
                }
               
                $item.slideUp(300, function() {
                    $item.remove();
                   
                    if (response.is_cart_empty || response.count === 0) {
                        const $cartItems = $('#cart-items');
                        $cartItems.html('<p class="woocommerce-mini-cart__empty-message">Количката е празна.</p>');
                    }
                });
               
                const count = response.count || 0;
                updateAllCartCounters(count);
                
                if (response.cart_total && $('#cart-total').length) {
                    $('#cart-total').html(response.cart_total);
                }
                
                $(document.body).trigger('removed_from_cart');
               
                updateCartContents();
            },
            error: function(error) {
                console.error('AJAX Error removing item:', error);
                $item.css('opacity', '1');
                $item.removeClass('updating');
                
                updateCartContents();
            }
        });
    }

   
    function updateAllCartCounters(count) {
        count = parseInt(count) || 0;
      
        const currentCount = parseInt($('.cart-count').first().text()) || 0;
       
        $('.cart-count').text(count);
        $('.mobile-cart-count').text(count);
       
        if (count > 0) {
            $('.cart-count, .mobile-cart-count').addClass('has-items');
        } else {
            $('.cart-count, .mobile-cart-count').removeClass('has-items');
        }
      
        if (count !== currentCount) {
            $('.cart-count, .mobile-cart-count').addClass('updated');
           
            setTimeout(function() {
                $('.cart-count, .mobile-cart-count').removeClass('updated');
            }, 400);
        }
    }
});
