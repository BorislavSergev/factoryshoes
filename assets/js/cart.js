/**
 * Cart functionality for Shoes Store Theme
 * This file provides a complete cart implementation using localStorage
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart.js loaded and initialized');
    
    // Cart functionality
    const Cart = {
        // Initialize cart from localStorage or create empty cart
        init: function() {
            try {
                let cart = localStorage.getItem('shoestore_cart');
                console.log('Cart init called, localStorage cart:', cart);
                if (cart) {
                    try {
                        return JSON.parse(cart);
                    } catch (e) {
                        console.error('Error parsing cart data:', e);
                        return {items: [], total: 0, count: 0};
                    }
                }
                return {items: [], total: 0, count: 0};
            } catch (e) {
                console.error('Error initializing cart:', e);
                return {items: [], total: 0, count: 0};
            }
        },
        
        // Save cart to localStorage
        save: function(cart) {
            try {
                localStorage.setItem('shoestore_cart', JSON.stringify(cart));
                console.log('Cart saved to localStorage:', cart);
                this.updateCartUI(cart);
            } catch (e) {
                console.error('Error saving cart:', e);
            }
        },
        
        // Add item to cart
        addItem: function(product) {
            try {
                console.log('Adding product to cart:', product);
                
                if (!product || !product.id) {
                    console.error('Invalid product data:', product);
                    return this.init();
                }
                
                // First add to WooCommerce cart via AJAX
                if (typeof wc_add_to_cart_params !== 'undefined') {
                    // Show loading indicator
                    const addToCartBtn = document.getElementById('add-to-cart-btn');
                    if (addToCartBtn) {
                        const originalText = addToCartBtn.innerHTML;
                        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Добавяне...</span>';
                        addToCartBtn.disabled = true;
                    }
                    
                    // Trigger WooCommerce adding_to_cart event
                    jQuery(document.body).trigger('adding_to_cart', [jQuery('#add-to-cart-btn'), {}]);
                    
                    // Add to WooCommerce cart
                    jQuery.ajax({
                        type: 'POST',
                        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                        data: {
                            product_id: product.productId,
                            variation_id: 0, // No variation for now
                            quantity: product.quantity,
                            'attribute_pa_size': product.size // Pass size as attribute
                        },
                        success: (response) => {
                            if (response.error) {
                                console.error('Error adding to WooCommerce cart:', response.message);
                                
                                // Still add to localStorage as fallback
                                const cart = this.init();
                                this._addToLocalCart(cart, product);
                            } else {
                                console.log('Successfully added to WooCommerce cart');
                                
                                // Update localStorage cart to match WooCommerce cart
                                this._syncWithWooCommerceCart();
                                
                                // Trigger WooCommerce added_to_cart event
                                jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                            }
                            
                            // Reset button state
                            if (addToCartBtn) {
                                setTimeout(() => {
                                    addToCartBtn.innerHTML = '<i class="fas fa-check"></i> <span>Добавено!</span>';
                                    
                                    setTimeout(() => {
                                        addToCartBtn.innerHTML = originalText;
                                        addToCartBtn.disabled = false;
                                    }, 1500);
                                }, 500);
                            }
                            
                            // Show cart offcanvas
                            const cartOffcanvas = document.getElementById('cart-offcanvas');
                            const cartOverlay = document.getElementById('cart-overlay');
                            if (cartOffcanvas && cartOverlay) {
                                cartOffcanvas.classList.add('active');
                                cartOverlay.classList.add('active');
                                document.body.style.overflow = 'hidden';
                            }
                        },
                        error: (error) => {
                            console.error('AJAX error adding to WooCommerce cart:', error);
                            
                            // Add to localStorage as fallback
                            const cart = this.init();
                            this._addToLocalCart(cart, product);
                            
                            // Reset button state
                            if (addToCartBtn) {
                                setTimeout(() => {
                                    addToCartBtn.innerHTML = originalText;
                                    addToCartBtn.disabled = false;
                                }, 500);
                            }
                        }
                    });
                    
                    return this.init(); // Return current cart state
                } else {
                    // Fallback to localStorage only if WooCommerce is not available
                    console.warn('WooCommerce params not found, using localStorage only');
                    const cart = this.init();
                    return this._addToLocalCart(cart, product);
                }
            } catch (e) {
                console.error('Error adding item to cart:', e);
                return this.init();
            }
        },
        
        // Helper method to add item to local cart
        _addToLocalCart: function(cart, product) {
            // Check if product already exists in cart
            const existingItem = cart.items.find(item => 
                item.id === product.id && item.size === product.size
            );
            
            if (existingItem) {
                console.log('Product already exists in cart, updating quantity');
                existingItem.quantity += product.quantity;
            } else {
                console.log('Adding new product to cart');
                cart.items.push(product);
            }
            
            // Update cart totals
            this.updateTotals(cart);
            this.save(cart);
            return cart;
        },
        
        // Helper method to sync localStorage cart with WooCommerce cart
        _syncWithWooCommerceCart: function() {
            if (typeof wc_add_to_cart_params === 'undefined') {
                return;
            }
            
            jQuery.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: 'get_cart_contents'
                },
                success: (response) => {
                    if (response && response.success) {
                        // Create a new cart object from WooCommerce data
                        const wcCart = {
                            items: [],
                            total: 0,
                            count: 0
                        };
                        
                        // Process WooCommerce cart items
                        if (response.data && response.data.items) {
                            wcCart.items = response.data.items.map(item => ({
                                id: item.product_id + '-' + (item.variation_id || '0'),
                                productId: item.product_id,
                                title: item.product_name,
                                image: item.product_image,
                                price: parseFloat(item.product_price),
                                quantity: item.quantity,
                                size: item.variation ? item.variation.attribute_pa_size : '',
                                url: item.product_permalink
                            }));
                            
                            wcCart.total = parseFloat(response.data.cart_total || 0);
                            wcCart.count = parseInt(response.data.cart_count || 0);
                        }
                        
                        // Save the synchronized cart
                        this.save(wcCart);
                    }
                },
                error: (error) => {
                    console.error('Error syncing with WooCommerce cart:', error);
                }
            });
        },
        
        // Remove item from cart
        removeItem: function(itemId) {
            try {
                console.log('Removing item from cart:', itemId);
                const cart = this.init();
                const item = cart.items.find(item => item.id === itemId);
                
                if (!item) {
                    console.warn('Item not found in cart:', itemId);
                    return cart;
                }
                
                // First remove from WooCommerce cart via AJAX
                if (typeof wc_add_to_cart_params !== 'undefined') {
                    // Extract product ID from the item ID
                    const productId = item.productId || itemId.split('-')[0];
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart'),
                        data: {
                            cart_item_key: productId,
                            security: woocommerce_params ? woocommerce_params.remove_from_cart_nonce : ''
                        },
                        success: (response) => {
                            if (response.error) {
                                console.error('Error removing item from WooCommerce cart:', response.message);
                                
                                // Still remove from localStorage as fallback
                                this._removeFromLocalCart(cart, itemId);
                            } else {
                                console.log('Successfully removed from WooCommerce cart');
                                
                                // Update localStorage cart to match WooCommerce cart
                                this._syncWithWooCommerceCart();
                                
                                // Trigger WooCommerce removed_from_cart event
                                if (response.fragments) {
                                    jQuery(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash]);
                                }
                            }
                        },
                        error: (error) => {
                            console.error('AJAX error removing from WooCommerce cart:', error);
                            
                            // Remove from localStorage as fallback
                            this._removeFromLocalCart(cart, itemId);
                        }
                    });
                    
                    return cart; // Return current cart state
                } else {
                    // Fallback to localStorage only if WooCommerce is not available
                    return this._removeFromLocalCart(cart, itemId);
                }
            } catch (e) {
                console.error('Error removing item from cart:', e);
                return this.init();
            }
        },
        
        // Helper method to remove item from local cart
        _removeFromLocalCart: function(cart, itemId) {
            cart.items = cart.items.filter(item => item.id !== itemId);
            this.updateTotals(cart);
            this.save(cart);
            return cart;
        },
        
        // Update item quantity
        updateQuantity: function(itemId, quantity) {
            try {
                console.log('Updating item quantity:', itemId, quantity);
                const cart = this.init();
                const item = cart.items.find(item => item.id === itemId);
                
                if (!item) {
                    console.warn('Item not found in cart:', itemId);
                    return cart;
                }
                
                // First update in WooCommerce cart via AJAX
                if (typeof wc_add_to_cart_params !== 'undefined') {
                    // Extract product ID from the item ID
                    const productId = item.productId || itemId.split('-')[0];
                    
                    jQuery.ajax({
                        type: 'POST',
                        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_item_quantity'),
                        data: {
                            cart_item_key: productId,
                            cart_item_qty: quantity,
                            security: woocommerce_params ? woocommerce_params.update_cart_nonce : ''
                        },
                        success: (response) => {
                            if (response.error) {
                                console.error('Error updating quantity in WooCommerce cart:', response.message);
                                
                                // Still update in localStorage as fallback
                                this._updateLocalQuantity(cart, itemId, quantity);
                            } else {
                                console.log('Successfully updated quantity in WooCommerce cart');
                                
                                // Update localStorage cart to match WooCommerce cart
                                this._syncWithWooCommerceCart();
                                
                                // Trigger WooCommerce updated_cart_totals event
                                jQuery(document.body).trigger('updated_cart_totals');
                            }
                        },
                        error: (error) => {
                            console.error('AJAX error updating quantity in WooCommerce cart:', error);
                            
                            // Update in localStorage as fallback
                            this._updateLocalQuantity(cart, itemId, quantity);
                        }
                    });
                    
                    return cart; // Return current cart state
                } else {
                    // Fallback to localStorage only if WooCommerce is not available
                    return this._updateLocalQuantity(cart, itemId, quantity);
                }
            } catch (e) {
                console.error('Error updating item quantity:', e);
                return this.init();
            }
        },
        
        // Helper method to update quantity in local cart
        _updateLocalQuantity: function(cart, itemId, quantity) {
            const item = cart.items.find(item => item.id === itemId);
            
            if (item) {
                item.quantity = quantity;
                this.updateTotals(cart);
                this.save(cart);
            }
            
            return cart;
        },
        
        // Update cart totals
        updateTotals: function(cart) {
            cart.total = cart.items.reduce((total, item) => {
                const price = parseFloat(item.price) || 0;
                return total + (price * item.quantity);
            }, 0);
            
            cart.count = cart.items.reduce((count, item) => {
                return count + item.quantity;
            }, 0);
        },
        
        // Update cart UI elements
        updateCartUI: function(cart) {
            try {
                if (!cart) {
                    console.error('Invalid cart data in updateCartUI');
                    cart = {items: [], total: 0, count: 0};
                }
                
                // Update cart count
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.textContent = cart.count || '0';
                });
                
                // Update cart items in the offcanvas
                const cartItemsContainer = document.querySelector('#cart-items');
                if (cartItemsContainer) {
                    if (!cart.items || cart.items.length === 0) {
                        cartItemsContainer.innerHTML = `
                            <div class="empty-cart">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Количката е празна</p>
                            </div>
                        `;
                    } else {
                        let itemsHtml = '';
                        cart.items.forEach(item => {
                            if (!item) return; // Skip invalid items
                            
                            itemsHtml += `
                                <div class="cart-item" data-item-id="${item.id || ''}">
                                    <div class="cart-item-image">
                                        <img src="${item.image || 'https://via.placeholder.com/60x60'}" alt="${item.title || 'Product'}">
                                    </div>
                                    <div class="cart-item-details">
                                        <h4>${item.title || 'Unknown Product'}</h4>
                                        <div class="cart-item-meta">
                                            ${item.size ? `<span>Размер: ${item.size}</span>` : ''}
                                        </div>
                                        <div class="cart-item-price">
                                            ${item.price ? item.price.toFixed(2) : '0.00'} лв. × ${item.quantity || 1}
                                        </div>
                                        <div class="cart-item-quantity">
                                            <button class="cart-item-dec" data-item-id="${item.id || ''}">-</button>
                                            <span>${item.quantity || 1}</span>
                                            <button class="cart-item-inc" data-item-id="${item.id || ''}">+</button>
                                        </div>
                                    </div>
                                    <div class="cart-item-subtotal">
                                        ${item.price ? (item.price * (item.quantity || 1)).toFixed(2) : '0.00'} лв.
                                    </div>
                                    <button class="cart-item-remove" data-item-id="${item.id || ''}" aria-label="Премахни">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                        });
                        cartItemsContainer.innerHTML = itemsHtml;
                        
                        // Initialize cart item actions
                        this.initCartActions();
                    }
                    
                    // Update cart total
                    const cartTotalElement = document.getElementById('cart-total');
                    if (cartTotalElement) {
                        cartTotalElement.textContent = (cart.total ? cart.total.toFixed(2) : '0.00') + ' лв.';
                    }
                }
                
                // Also update any mini cart dropdowns that might exist
                const miniCartItems = document.querySelectorAll('.header-cart-dropdown .cart-items');
                miniCartItems.forEach(container => {
                    if (!cart.items || cart.items.length === 0) {
                        container.innerHTML = `
                            <div class="empty-cart">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Количката е празна</p>
                            </div>
                        `;
                    } else {
                        let itemsHtml = '';
                        cart.items.forEach(item => {
                            if (!item) return; // Skip invalid items
                            
                            itemsHtml += `
                                <div class="mini-cart-item">
                                    <div class="mini-cart-image">
                                        <img src="${item.image || 'https://via.placeholder.com/50x50'}" alt="${item.title || 'Product'}">
                                    </div>
                                    <div class="mini-cart-details">
                                        <h5>${item.title || 'Unknown Product'}</h5>
                                        <div class="mini-cart-meta">
                                            ${item.size ? `<span>Размер: ${item.size}</span>` : ''}
                                        </div>
                                        <div class="mini-cart-price">
                                            ${item.price ? item.price.toFixed(2) : '0.00'} лв. × ${item.quantity || 1}
                                        </div>
                                    </div>
                                    <button class="mini-cart-remove" data-item-id="${item.id || ''}">×</button>
                                </div>
                            `;
                        });
                        container.innerHTML = itemsHtml;
                        
                        // Add event listeners to mini cart remove buttons
                        const self = this;
                        container.querySelectorAll('.mini-cart-remove').forEach(btn => {
                            btn.addEventListener('click', e => {
                                const itemId = btn.getAttribute('data-item-id');
                                self.removeItem(itemId);
                            });
                        });
                    }
                    
                    // Update mini cart total
                    const miniCartTotal = document.querySelector('.header-cart-dropdown .cart-total-amount');
                    if (miniCartTotal) {
                        miniCartTotal.textContent = (cart.total ? cart.total.toFixed(2) : '0.00') + ' лв.';
                    }
                });
            } catch (e) {
                console.error('Error updating cart UI:', e);
            }
        },
        
        // Initialize cart item action buttons
        initCartActions: function() {
            const self = this;
            
            // Quantity decrease buttons
            document.querySelectorAll('.cart-item-dec').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-item-id');
                    const cart = self.init();
                    const item = cart.items.find(item => item.id === itemId);
                    
                    if (item && item.quantity > 1) {
                        item.quantity -= 1;
                        self.updateTotals(cart);
                        self.save(cart);
                    } else if (item) {
                        cart.items = cart.items.filter(i => i.id !== itemId);
                        self.updateTotals(cart);
                        self.save(cart);
                    }
                });
            });
            
            // Quantity increase buttons
            document.querySelectorAll('.cart-item-inc').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-item-id');
                    const cart = self.init();
                    const item = cart.items.find(item => item.id === itemId);
                    
                    if (item) {
                        item.quantity += 1;
                        self.updateTotals(cart);
                        self.save(cart);
                    }
                });
            });
            
            // Remove item buttons
            document.querySelectorAll('.cart-item-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-item-id');
                    self.removeItem(itemId);
                });
            });
        },
        
        // Clear cart
        clear: function() {
            const cart = {items: [], total: 0, count: 0};
            this.save(cart);
            return cart;
        }
    };
    
    // Initialize the cart on page load
    const cart = Cart.init();
    
    // Sync with WooCommerce cart on page load
    if (typeof wc_add_to_cart_params !== 'undefined') {
        console.log('Syncing with WooCommerce cart on page load');
        Cart._syncWithWooCommerceCart();
    } else {
        // If WooCommerce is not available, just update the UI with localStorage cart
        console.log('WooCommerce not available, using localStorage cart');
        Cart.updateCartUI(cart);
    }
    
    // Open cart when cart toggle is clicked
    document.querySelectorAll('.cart-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            // Refresh cart data in case it was updated on another page
            const currentCart = Cart.init();
            Cart.updateCartUI(currentCart);
            
            // Show cart offcanvas
            const cartOffcanvas = document.getElementById('cart-offcanvas');
            const cartOverlay = document.getElementById('cart-overlay');
            if (cartOffcanvas && cartOverlay) {
                cartOffcanvas.classList.add('active');
                cartOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    // Handle cart action buttons (View Cart and Checkout)
    const viewCartBtn = document.querySelector('.view-cart-btn');
    const checkoutBtn = document.querySelector('.checkout-btn');
    
    if (viewCartBtn) {
        viewCartBtn.addEventListener('click', function(e) {
            // Prevent default link behavior
            e.preventDefault();
            
            // Close the cart offcanvas
            const cartOffcanvas = document.getElementById('cart-offcanvas');
            const cartOverlay = document.getElementById('cart-overlay');
            if (cartOffcanvas && cartOverlay) {
                cartOffcanvas.classList.remove('active');
                cartOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Get the URL from the href attribute
            const cartUrl = this.getAttribute('href');
            if (cartUrl) {
                console.log('Navigating to cart page:', cartUrl);
                window.location.href = cartUrl;
            } else {
                console.log('Cart URL not found, using default');
                window.location.href = '/cart';
            }
        });
    }
    
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            // Prevent default link behavior
            e.preventDefault();
            
            // Close the cart offcanvas
            const cartOffcanvas = document.getElementById('cart-offcanvas');
            const cartOverlay = document.getElementById('cart-overlay');
            if (cartOffcanvas && cartOverlay) {
                cartOffcanvas.classList.remove('active');
                cartOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Get the URL from the href attribute
            const checkoutUrl = this.getAttribute('href');
            if (checkoutUrl) {
                console.log('Navigating to checkout page:', checkoutUrl);
                window.location.href = checkoutUrl;
            } else {
                console.log('Checkout URL not found, using default');
                window.location.href = '/checkout';
            }
        });
    }
    
    // Toggle mini cart dropdown when clicking cart icon
    const headerCartDropdown = document.querySelector('.header-cart-dropdown');
    const miniCartClose = document.querySelector('.mini-cart-close');
    
    if (headerCartDropdown) {
        // Close mini cart when clicking close button
        if (miniCartClose) {
            miniCartClose.addEventListener('click', function() {
                headerCartDropdown.classList.remove('active');
            });
        }
        
        // Close mini cart when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.header-cart-dropdown') && !e.target.closest('.cart-toggle')) {
                headerCartDropdown.classList.remove('active');
            }
        });
    }
    
    // Make Cart object available globally
    window.ShoeStoreCart = Cart;
    console.log('ShoeStoreCart global object created:', window.ShoeStoreCart);
}); 