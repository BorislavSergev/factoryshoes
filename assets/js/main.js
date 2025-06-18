/**
 * Main JavaScript for Shoes Store Theme
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all functionality
    initMobileMenu();
    initCartOffcanvas();
    initSearchOverlay();
    initAddToCart();
    initLazyLoading();
    initSmoothScrolling();
    initCartPersistence();
    initToastNotifications();
    
    /**
     * Cart Persistence with Local Storage
     */
    function initCartPersistence() {
        // Load cart from localStorage on page load
        loadCartFromStorage();
        
        // Save cart to localStorage periodically and on cart changes
        setInterval(saveCartToStorage, 5000); // Save every 5 seconds
        
        // Save on page unload
        window.addEventListener('beforeunload', saveCartToStorage);
    }
    
    function saveCartToStorage() {
        if (!window.woocommerce_params || !WC().cart) return;
        
        fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_cart_contents'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success && data.data) {
                localStorage.setItem('shoes_store_cart', JSON.stringify({
                    items: data.data.items,
                    total: data.data.total,
                    count: data.data.count,
                    timestamp: Date.now()
                }));
            }
        })
        .catch(error => console.log('Cart save error:', error));
    }
    
    function loadCartFromStorage() {
        const savedCart = localStorage.getItem('shoes_store_cart');
        if (!savedCart) return;
        
        try {
            const cartData = JSON.parse(savedCart);
            // Check if data is not too old (24 hours)
            if (Date.now() - cartData.timestamp > 24 * 60 * 60 * 1000) {
                localStorage.removeItem('shoes_store_cart');
                return;
            }
            
            // Update cart count immediately
            updateCartCount(cartData.count);
            
        } catch (error) {
            console.error('Error loading cart from storage:', error);
            localStorage.removeItem('shoes_store_cart');
        }
    }
    
    /**
     * Toast Notifications System
     */
    function initToastNotifications() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }
    }
    
    function showToast(message, type = 'success', duration = 3000) {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) return;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
    
    /**
     * Mobile Menu Functionality
     */
    function initMobileMenu() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        
        if (!mobileMenuToggle) return;
        
        // Open mobile menu
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenuToggle.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close mobile menu
        function closeMobileMenu() {
            mobileMenuToggle.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }
        
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);
        }
        
        // Close on menu item click
        const mobileMenuLinks = document.querySelectorAll('.mobile-nav-menu a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    }
    
    /**
     * Enhanced Cart Offcanvas Functionality
     */
    function initCartOffcanvas() {
        const cartToggles = document.querySelectorAll('.cart-toggle');
        const cartOverlay = document.getElementById('cart-overlay');
        const cartOffcanvas = document.getElementById('cart-offcanvas');
        const cartClose = document.querySelector('.cart-close');
        
        if (!cartOffcanvas) return;
        
        // Open cart
        cartToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                cartOverlay.classList.add('active');
                cartOffcanvas.classList.add('active');
                document.body.style.overflow = 'hidden';
                fetchCartContents(); // Fetch cart contents when opening cart
                
                // Check product states when opening cart
                setTimeout(() => {
                    checkAllProductsInCart();
                }, 1000);
            });
        });
        
        // Close cart
        function closeCart() {
            cartOverlay.classList.remove('active');
            cartOffcanvas.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (cartClose) {
            cartClose.addEventListener('click', closeCart);
        }
        
        if (cartOverlay) {
            cartOverlay.addEventListener('click', closeCart);
        }
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && cartOffcanvas.classList.contains('active')) {
                closeCart();
            }
        });
    }
    
    /**
     * Search Overlay Functionality
     */
    function initSearchOverlay() {
        const searchToggles = document.querySelectorAll('.search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchClose = document.querySelector('.search-close');
        const searchField = document.querySelector('.search-overlay .search-field');
        
        if (!searchOverlay) return;
        
        // Open search
        searchToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                searchOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (searchField) {
                    setTimeout(() => searchField.focus(), 300);
                }
            });
        });
        
        // Close search
        function closeSearch() {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (searchClose) {
            searchClose.addEventListener('click', closeSearch);
        }
        
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    closeSearch();
                }
            });
        }
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearch();
            }
        });
    }
    
    /**
     * Enhanced WooCommerce Cart Management
     */
    function updateCartCount(count = null) {
        const cartCounts = document.querySelectorAll('.cart-count');
        
        if (count === null) {
            // If count not provided, fetch from WC fragments if available
            if (typeof wc_cart_fragments_params !== 'undefined') {
                const fragments = JSON.parse(sessionStorage.getItem(wc_cart_fragments_params.fragment_name));
                if (fragments && fragments['div.widget_shopping_cart_content']) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = fragments['div.widget_shopping_cart_content'];
                    count = tempDiv.querySelectorAll('.cart_item').length;
                }
            }
        }
        
        if (count !== null) {
            cartCounts.forEach(countElement => {
                countElement.textContent = count;
                countElement.style.display = count > 0 ? 'flex' : 'none';
                
                // Add bounce animation for count changes
                countElement.classList.add('cart-count-updated');
                setTimeout(() => countElement.classList.remove('cart-count-updated'), 600);
            });
        }
    }
    
    function fetchCartContents() {
        const cartItems = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');
        const cartContent = document.querySelector('.cart-content');
        
        if (!cartItems) return;
        
        // Show loading state with animation
        cartContent.classList.add('loading');
        cartItems.innerHTML = `
            <div class="cart-loading">
                <div class="loading-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                </div>
                <p>Зареждане на количката...</p>
            </div>
        `;
        
        // Fetch cart data via AJAX
        fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_cart_contents'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            cartContent.classList.remove('loading');
            
            if (data && data.success && data.data) {
                const cartData = data.data;
                
                if (cartData.items.length === 0) {
                    // Empty cart with animation
                    cartItems.innerHTML = `
                        <div class="empty-cart fade-in">
                            <div class="empty-cart-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h4>Количката е празна</h4>
                            <p>Добавете продукти, за да започнете пазаруването</p>
                        </div>
                    `;
                } else {
                    // Format and display cart items with animations
                    let cartHTML = '';
                    
                    cartData.items.forEach((item, index) => {
                        cartHTML += `
                            <div class="cart-item fade-in" data-product-id="${item.key}" style="animation-delay: ${index * 0.1}s;">
                                <div class="cart-item-image">
                                    ${item.image ? 
                                        `<img src="${item.image}" alt="${item.name}" loading="lazy" />` : 
                                        `<div class="cart-item-placeholder"><i class="fas fa-shoe-prints"></i></div>`
                                    }
                                </div>
                                <div class="cart-item-details">
                                    <h4 class="cart-item-name">${item.name}</h4>
                                    <div class="cart-item-price">${item.price_html}</div>
                                    <div class="cart-item-controls">
                                        <button class="quantity-btn minus" data-product-id="${item.key}" ${item.quantity <= 1 ? 'disabled' : ''}>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="quantity">${item.quantity}</span>
                                        <button class="quantity-btn plus" data-product-id="${item.key}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="cart-item-actions">
                                    <button class="remove-item" data-product-id="${item.key}" aria-label="Премахни от количката">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    
                    cartItems.innerHTML = cartHTML;
                    
                    // Update cart count with animation
                    updateCartCount(cartData.count);
                    
                    // Update cart total with animation
                    if (cartTotal) {
                        cartTotal.innerHTML = cartData.total_html;
                        cartTotal.classList.add('total-updated');
                        setTimeout(() => cartTotal.classList.remove('total-updated'), 600);
                    }
                    
                    // Save to localStorage
                    localStorage.setItem('shoes_store_cart', JSON.stringify({
                        items: cartData.items,
                        total: cartData.total,
                        count: cartData.count,
                        timestamp: Date.now()
                    }));
                    
                    // Bind cart events
                    bindCartEvents();
                    
                    // Update button states for all products
                    checkAllProductsInCart();
                }
            } else {
                throw new Error('Invalid cart data received');
            }
        })
        .catch(error => {
            console.error('Error fetching cart:', error);
            cartContent.classList.remove('loading');
            cartItems.innerHTML = `
                <div class="empty-cart error-state fade-in">
                    <div class="empty-cart-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4>Грешка при зареждане</h4>
                    <p>Моля, опитайте отново</p>
                    <button class="retry-btn" onclick="fetchCartContents()">Опитай отново</button>
                </div>
            `;
        });
    }
    
    function bindCartEvents() {
        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;
                
                const productId = this.dataset.productId;
                const isPlus = this.classList.contains('plus');
                const quantityElement = this.parentElement.querySelector('.quantity');
                const currentQuantity = parseInt(quantityElement.textContent);
                const cartItem = this.closest('.cart-item');
                
                if (!productId) return;
                
                // Show loading state
                cartItem.classList.add('updating');
                this.disabled = true;
                
                // Calculate new quantity
                const newQuantity = isPlus ? currentQuantity + 1 : Math.max(0, currentQuantity - 1);
                
                if (newQuantity === 0) {
                    // Remove item
                    removeCartItem(productId, cartItem);
                } else {
                    // Update quantity
                    updateCartItemQuantity(productId, newQuantity, cartItem);
                }
            });
        });
        
        // Remove item buttons
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const cartItem = this.closest('.cart-item');
                
                if (!productId) return;
                
                // Show confirmation for remove
                if (confirm('Сигурни ли сте, че искате да премахнете този продукт от количката?')) {
                    removeCartItem(productId, cartItem);
                }
            });
        });
    }
    
    function updateCartItemQuantity(productId, quantity, cartItemElement) {
        fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'woocommerce_update_cart_item_quantity',
                cart_item_key: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity display
                const quantityElement = cartItemElement.querySelector('.quantity');
                quantityElement.textContent = quantity;
                
                // Update cart count
                updateCartCount(data.data.cart_count);
                
                // Re-fetch cart to update totals
                setTimeout(() => {
                    fetchCartContents();
                    showToast('Количеството е обновено', 'success');
                    
                    // Update button states for all products
                    checkAllProductsInCart();
                }, 500);
                
            } else {
                console.error('Update quantity error:', data);
                showToast('Грешка при обновяване на количеството', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            showToast('Грешка при обновяване на количеството', 'error');
        })
        .finally(() => {
            // Remove loading state
            cartItemElement.classList.remove('updating');
            cartItemElement.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.disabled = false;
            });
        });
    }
    
    function removeCartItem(productId, cartItemElement) {
        // Animate item removal
        cartItemElement.classList.add('removing');
        
        fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'woocommerce_remove_cart_item',
                cart_item_key: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove element with animation
                setTimeout(() => {
                    cartItemElement.style.height = cartItemElement.offsetHeight + 'px';
                    cartItemElement.style.overflow = 'hidden';
                    
                    setTimeout(() => {
                        cartItemElement.style.height = '0';
                        cartItemElement.style.margin = '0';
                        cartItemElement.style.padding = '0';
                        
                        setTimeout(() => {
                            cartItemElement.remove();
                            
                            // Update cart count
                            updateCartCount(data.data.cart_count);
                            
                            // Re-fetch cart to update totals
                            fetchCartContents();
                            showToast('Продуктът е премахнат от количката', 'success');
                            
                            // Update button states for all products
                            setTimeout(() => {
                                checkAllProductsInCart();
                            }, 500);
                        }, 300);
                    }, 50);
                }, 100);
                
            } else {
                console.error('Remove item error:', data);
                cartItemElement.classList.remove('removing');
                showToast('Грешка при премахване на продукта', 'error');
            }
        })
        .catch(error => {
            console.error('Error removing item:', error);
            cartItemElement.classList.remove('removing');
            showToast('Грешка при премахване на продукта', 'error');
        });
    }
    
    /**
     * Enhanced Add to Cart Functionality with Bulgarian Labels
     */
    function initAddToCart() {
        // Check cart status for all products on page load
        checkAllProductsInCart();
        
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-to-cart-btn')) {
                e.preventDefault();
                
                const button = e.target.closest('.add-to-cart-btn');
                const productId = parseInt(button.dataset.productId);
                const productName = button.dataset.productName || 'Продукт';
                const quantity = parseInt(button.dataset.quantity) || 1;
                
                if (!productId) return;
                
                // Skip if already in cart and disabled
                if (button.classList.contains('in-cart') && button.disabled) {
                    return;
                }
                
                // Enhanced visual feedback
                button.disabled = true;
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Добавяне...';
                button.classList.add('loading');
                
                // Add to cart via AJAX
                fetch(woocommerce_params.ajax_url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'add_to_cart',
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        // Update cart count with animation
                        updateCartCount(data.data.cart_count);
                        
                        if (data.data.already_in_cart) {
                            // Product was already in cart - quantity updated
                            button.innerHTML = '<i class="fas fa-plus-circle"></i> Количеството е увеличено';
                            showToast(`Количеството на ${productName} е увеличено`, 'success');
                        } else {
                            // New product added to cart
                            button.innerHTML = '<i class="fas fa-check-circle"></i> Добавено!';
                            showToast(`${productName} е добавен в количката`, 'success');
                        }
                        
                        button.classList.remove('loading');
                        button.classList.add('success');
                        button.style.background = '#2ecc71';
                        
                        // Save to localStorage
                        saveCartToStorage();
                        
                        // Add bounce animation to button
                        button.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            button.style.transform = '';
                        }, 200);
                        
                        // Set to "in cart" state after success
                        setTimeout(() => {
                            setButtonInCartState(button);
                        }, 2000);
                        
                    } else {
                        throw new Error(data.data || 'Failed to add to cart');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    
                    // Error feedback with enhanced animation
                    button.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Грешка';
                    button.classList.remove('loading');
                    button.classList.add('error');
                    button.style.background = '#e74c3c';
                    
                    // Show error toast
                    showToast('Грешка при добавяне в количката. Моля, опитайте отново.', 'error');
                    
                    // Reset button after delay
                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.style.background = '';
                        button.disabled = false;
                        button.classList.remove('success', 'error');
                    }, 3000);
                });
            }
        });
        
        // Initialize cart count on page load
        updateCartCount();
        
        // If cart fragment refreshing is enabled, listen for storage events
        if (typeof wc_cart_fragments_params !== 'undefined') {
            window.addEventListener('storage', function(e) {
                if (e.key === wc_cart_fragments_params.fragment_name) {
                    updateCartCount();
                    checkAllProductsInCart(); // Re-check product states
                }
            });
        }
    }
    
    /**
     * Check if all products on page are in cart
     */
    function checkAllProductsInCart() {
        const buttons = document.querySelectorAll('.add-to-cart-btn');
        
        buttons.forEach(button => {
            const productId = parseInt(button.dataset.productId);
            if (productId) {
                checkProductInCart(productId, button);
            }
        });
    }
    
    /**
     * Check if specific product is in cart
     */
    function checkProductInCart(productId, button) {
        fetch(woocommerce_params.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'check_product_in_cart',
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                if (data.data.in_cart) {
                    setButtonInCartState(button, data.data.quantity);
                } else {
                    setButtonDefaultState(button);
                }
            }
        })
        .catch(error => {
            console.error('Error checking cart status:', error);
        });
    }
    
    /**
     * Set button to "in cart" state
     */
    function setButtonInCartState(button, quantity = null) {
        const quantityBadge = quantity && quantity > 1 ? `<span class="quantity-badge">${quantity}</span>` : '';
        button.innerHTML = '<i class="fas fa-check-circle"></i> Добавено' + quantityBadge;
        button.classList.add('in-cart');
        button.classList.remove('success', 'error', 'loading');
        button.disabled = true;
        button.style.background = '';
        button.style.color = '';
        button.style.cursor = 'default';
        button.title = quantity && quantity > 1 ? 
            `Този продукт е добавен в количката (${quantity} бр.)` : 
            'Този продукт е добавен в количката';
    }
    
    /**
     * Set button to default state
     */
    function setButtonDefaultState(button) {
        button.innerHTML = '<i class="fas fa-cart-plus"></i> Добави';
        button.classList.remove('in-cart', 'success', 'error', 'loading');
        button.disabled = false;
        button.style.background = '';
        button.style.color = '';
        button.style.cursor = '';
        button.title = '';
    }
    
    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        }
    }
    
    /**
     * Smooth Scrolling for Anchor Links
     */
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    /**
     * Handle window resize for responsive adjustments
     */
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Close all modals/overlays on resize
            const mobileMenu = document.getElementById('mobile-menu');
            const cartOffcanvas = document.getElementById('cart-offcanvas');
            const searchOverlay = document.getElementById('search-overlay');
            
            if (mobileMenu && mobileMenu.classList.contains('active')) {
                document.querySelector('.mobile-menu-close')?.click();
            }
            
            if (cartOffcanvas && cartOffcanvas.classList.contains('active')) {
                document.querySelector('.cart-close')?.click();
            }
            
            if (searchOverlay && searchOverlay.classList.contains('active')) {
                document.querySelector('.search-close')?.click();
            }
        }, 250);
    });
    
    /**
     * Handle touch gestures for mobile
     */
    let touchStartX = 0;
    let touchStartY = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    });
    
    document.addEventListener('touchend', function(e) {
        if (!touchStartX || !touchStartY) return;
        
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        
        const deltaX = touchStartX - touchEndX;
        const deltaY = touchStartY - touchEndY;
        
        const mobileMenu = document.getElementById('mobile-menu');
        const cartOffcanvas = document.getElementById('cart-offcanvas');
        
        // Swipe left to close mobile menu
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            if (deltaX > 50 && Math.abs(deltaY) < 100) {
                document.querySelector('.mobile-menu-close')?.click();
            }
        }
        
        // Swipe right to close cart
        if (cartOffcanvas && cartOffcanvas.classList.contains('active')) {
            if (deltaX < -50 && Math.abs(deltaY) < 100) {
                document.querySelector('.cart-close')?.click();
            }
        }
        
        touchStartX = 0;
        touchStartY = 0;
    });
    
    /**
     * Product card animations
     */
    function initProductAnimations() {
        const cards = document.querySelectorAll('.product-card, .modern-card');
        
        if ('IntersectionObserver' in window) {
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 100);
                        cardObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                cardObserver.observe(card);
            });
        }
    }
    
    // Initialize product animations
    initProductAnimations();
    
    // Console message for developers
    console.log('🏃‍♂️ Factory Shoes Theme - Loaded successfully!');
    
});

// Add CSS classes for animations
const style = document.createElement('style');
style.textContent = `
    .fade-in {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    
    .header-hidden {
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }
    
    .scrolled {
        box-shadow: 0 2px 20px rgba(0,0,0,0.15) !important;
    }
    
    .loading {
        position: relative;
        color: transparent !important;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .touch-active {
        transform: scale(0.95);
        transition: transform 0.1s ease;
    }
    
    .focus-visible {
        outline: 2px solid #e74c3c;
        outline-offset: 2px;
    }
    
    .error {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2) !important;
    }
    
    .menu-open {
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .main-navigation.active {
            animation: slideDown 0.3s ease;
        }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;

document.head.appendChild(style); 