/**
 * Custom Checkout JavaScript
 * Handles form validation, Econt integration, and dynamic UI updates
 */
(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        const CustomCheckout = {
            init: function() {
                this.setupFormValidation();
                this.setupShippingMethodToggle();
                this.setupPaymentMethodToggle();
                this.setupEcontIntegration();
                this.setupFormSubmission();
            },

            setupFormValidation: function() {
                // Validate on blur
                $('[required]').on('blur', function() {
                    const $input = $(this);
                    
                    if ($input.val() === '') {
                        $input.addClass('is-invalid').removeClass('is-valid');
                    } else {
                        $input.removeClass('is-invalid').addClass('is-valid');
                    }
                });
                
                // Clear validation on input
                $('[required]').on('input', function() {
                    $(this).removeClass('is-invalid');
                    $('.checkout-error-message').remove();
                });
            },

            setupShippingMethodToggle: function() {
                // Highlight selected shipping method
                $('input[name="shipping_method"]').on('change', function() {
                    const $parent = $(this).closest('.shipping-method-option');
                    
                    $parent.addClass('selected').siblings().removeClass('selected');
                    
                    // Show or hide Econt container based on selection
                    if ($(this).val().indexOf('econt') > -1) {
                        $('#econt-delivery-container').slideDown(300);
                    } else {
                        $('#econt-delivery-container').slideUp(300);
                    }
                });
                
                // Initialize with default selected value
                $('input[name="shipping_method"]:checked').each(function() {
                    $(this).closest('.shipping-method-option').addClass('selected');
                    
                    // Show Econt container if Econt is selected by default
                    if ($(this).val().indexOf('econt') > -1) {
                        $('#econt-delivery-container').show();
                    } else {
                        $('#econt-delivery-container').hide();
                    }
                });
                
                // If no shipping method is selected, select the first one
                if ($('input[name="shipping_method"]:checked').length === 0) {
                    $('input[name="shipping_method"]').first().prop('checked', true).trigger('change');
                }
            },

            setupPaymentMethodToggle: function() {
                // Highlight selected payment method
                $('input[name="payment_method"]').on('change', function() {
                    const $parent = $(this).closest('.payment-method-option');
                    
                    $parent.addClass('selected').siblings().removeClass('selected');
                });
                
                // Initialize with default selected value
                $('input[name="payment_method"]:checked').each(function() {
                    $(this).closest('.payment-method-option').addClass('selected');
                });
                
                // If no payment method is selected, select the first one
                if ($('input[name="payment_method"]:checked').length === 0) {
                    $('input[name="payment_method"]').first().prop('checked', true).trigger('change');
                }
            },

            setupEcontIntegration: function() {
                // Move Econt elements to our container
                const moveEcontElements = function() {
                    const $econtElements = $('.woocommerce-checkout-review-order .woocommerce-shipping-fields');
                    
                    if ($econtElements.length && !$('#econt-delivery-container').find('.woocommerce-shipping-fields').length) {
                        $econtElements.appendTo('#econt-delivery-container');
                    }
                };
                
                // Style Econt elements
                const styleEcontElements = function() {
                    // Style delivery type selectors
                    $('.econt-delivery-type-radio').parent().addClass('econt-delivery-type-option');
                    
                    // Add icon to delivery options based on type
                    $('.econt-delivery-type-option').each(function() {
                        const optionText = $(this).text().toLowerCase();
                        let icon = "";
                        
                        if (optionText.indexOf('офис') > -1) {
                            icon = '<i class="fas fa-building"></i>';
                        } else if (optionText.indexOf('автомат') > -1) {
                            icon = '<i class="fas fa-box"></i>';
                        } else {
                            icon = '<i class="fas fa-home"></i>';
                        }
                        
                        if (!$(this).find('i').length) {
                            $(this).prepend(icon);
                        }
                    });
                    
                    // Style select dropdowns
                    $('.econt-select').addClass('form-control');
                    
                    // Style input fields
                    $('.econt-input').addClass('form-control');
                    
                    // Style labels
                    $('.econt-label').addClass('form-label');
                    
                    // Style wrappers
                    $('.econt-field').addClass('mb-3');
                };
                
                // Watch for DOM changes to handle dynamically loaded Econt elements
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length) {
                            moveEcontElements();
                            styleEcontElements();
                        }
                    });
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
                
                // Run initially
                setTimeout(function() {
                    moveEcontElements();
                    styleEcontElements();
                }, 500);
                
                // Also run when shipping method changes
                $('input[name="shipping_method"]').on('change', function() {
                    setTimeout(function() {
                        moveEcontElements();
                        styleEcontElements();
                    }, 500);
                });
                
                // Handle Econt form events
                $(document).on('change', '.econt-delivery-type-radio', function() {
                    const $parent = $(this).closest('.econt-delivery-type-option');
                    
                    $parent.addClass('selected').siblings().removeClass('selected');
                });
                
                // Handle Econt office selection
                $(document).on('change', '.econt-office-select', function() {
                    styleEcontElements();
                });
            },

            setupFormSubmission: function() {
                // Show loading indicator on form submit
                $('#custom-checkout-form').on('submit', function(e) {
                    // Validate form
                    let isValid = true;
                    
                    // Check required fields
                    $(this).find('[required]').each(function() {
                        if ($(this).val() === '') {
                            $(this).addClass('is-invalid');
                            isValid = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });
                    
                    // Check if shipping method is selected
                    if ($('input[name="shipping_method"]:checked').length === 0) {
                        $('.shipping-methods').addClass('is-invalid');
                        isValid = false;
                    }
                    
                    // Check if payment method is selected
                    if ($('input[name="payment_method"]:checked').length === 0) {
                        $('.payment-methods').addClass('is-invalid');
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: $('.is-invalid').first().offset().top - 100
                        }, 500);
                        
                        // Show error message
                        if (!$('.checkout-error-message').length) {
                            $('.checkout-header').after('<div class="alert alert-danger checkout-error-message">Моля, попълнете всички задължителни полета.</div>');
                        }
                        
                        return false;
                    }
                    
                    // If valid, show processing state
                    $(this).addClass('processing');
                    $('#place_order').html('<i class="fas fa-spinner fa-spin"></i> Обработка...');
                    
                    return true;
                });
            }
        };

        // Initialize the CustomCheckout object
        CustomCheckout.init();
        
        // Trigger resize to fix any layout issues
        setTimeout(function() {
            $(window).trigger('resize');
        }, 1000);
    });

})(jQuery);