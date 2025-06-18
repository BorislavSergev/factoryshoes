/**
 * Econt Price Calculator Enhancement
 * Transforms the Econt price calculation button into a more integrated, elegant UI
 */
(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        const EcontCalculator = {
            init: function() {
                this.enhanceCalculatorUI();
                this.attachEventHandlers();
                this.observeDOMChanges();
            },

            enhanceCalculatorUI: function() {
                // Find Econt calculation button and enhance it
                this.transformCalculateButton();
                this.styleCalculatorContainer();
                this.addAutoCalculationNote();
            },

            transformCalculateButton: function() {
                // Find the calculate price button
                const $calculateBtn = $('button[name="calc_shipping"], .shipping-calculator-button, ' +
                                     'button:contains("Calculate"), button:contains("Изчисли"), ' + 
                                     'button.econt-calculate-price, button.econt-calculate-btn');
                
                if ($calculateBtn.length) {
                    // Add icon to button
                    if (!$calculateBtn.find('i').length) {
                        $calculateBtn.prepend('<i class="fas fa-calculator"></i> ');
                    }
                    
                    // Add new styling classes
                    $calculateBtn.addClass('econt-calculate-btn');
                }
            },
            
            styleCalculatorContainer: function() {
                // Find calculator container
                const $calculatorForm = $('.shipping-calculator-form, form.econt-calculator, .econt-calculator');
                
                if ($calculatorForm.length) {
                    // Add container class
                    $calculatorForm.addClass('econt-calculator');
                    
                    // Wrap in a styled container if not already wrapped
                    if (!$calculatorForm.parent().hasClass('econt-calculator-container')) {
                        $calculatorForm.wrap('<div class="econt-calculator-container"></div>');
                    }
                    
                    // Style inputs and selects
                    $calculatorForm.find('input, select').addClass('form-control');
                    
                    // Style price results container
                    const $priceResults = $calculatorForm.next('.shipping-costs, .econt-price-results');
                    if ($priceResults.length) {
                        $priceResults.addClass('econt-price-results');
                    }
                }
            },
            
            addAutoCalculationNote: function() {
                // Add note about automatic calculation where appropriate
                const $calculatorContainer = $('.econt-calculator-container');
                
                if ($calculatorContainer.length && !$calculatorContainer.find('.econt-auto-calculate-note').length) {
                    $calculatorContainer.append('<div class="econt-auto-calculate-note">Цената ще се изчисли автоматично след попълване на данните</div>');
                }
            },

            attachEventHandlers: function() {
                // Auto-calculate on address field changes
                $(document).on('change', '.econt-calculator select, .econt-calculator input', function() {
                    // Add a small delay to allow for field validation
                    setTimeout(function() {
                        EcontCalculator.triggerAutoCalculation();
                    }, 300);
                });
                
                // Clean up UI when calculation starts
                $(document).on('click', '.econt-calculate-btn', function() {
                    // Show loading animation
                    if (!$(this).find('.econt-calculator-loading').length) {
                        $(this).prepend('<span class="econt-calculator-loading"></span>');
                        $(this).find('i').hide();
                    }
                    
                    // Remove note temporarily
                    $('.econt-auto-calculate-note').fadeOut(200);
                });
            },
            
            triggerAutoCalculation: function() {
                // Check if all required fields are filled
                const $calculator = $('.econt-calculator');
                let allFieldsFilled = true;
                
                $calculator.find('select, input').each(function() {
                    if ($(this).prop('required') && !$(this).val()) {
                        allFieldsFilled = false;
                        return false; // break
                    }
                });
                
                // If all required fields are filled, trigger calculation
                if (allFieldsFilled) {
                    const $calculateBtn = $('.econt-calculate-btn');
                    
                    // Show loading indicator
                    if (!$calculateBtn.find('.econt-calculator-loading').length) {
                        $calculateBtn.prepend('<span class="econt-calculator-loading"></span>');
                        $calculateBtn.find('i').hide();
                    }
                    
                    // Trigger click on the calculate button
                    $calculateBtn.trigger('click');
                }
            },
            
            observeDOMChanges: function() {
                // Watch for DOM changes to handle dynamically loaded elements
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length) {
                            // Check if the added nodes contain our target elements
                            $(mutation.addedNodes).each(function() {
                                const $node = $(this);
                                
                                if ($node.find('button[name="calc_shipping"], .shipping-calculator-button').length ||
                                    $node.find('.shipping-calculator-form').length) {
                                    // Enhance UI after a short delay
                                    setTimeout(function() {
                                        EcontCalculator.enhanceCalculatorUI();
                                    }, 200);
                                }
                                
                                // Remove loading animation once calculation complete
                                if ($node.hasClass('shipping-costs') || $node.hasClass('econt-price-results')) {
                                    $('.econt-calculate-btn').find('.econt-calculator-loading').remove();
                                    $('.econt-calculate-btn').find('i').show();
                                    $('.econt-auto-calculate-note').fadeIn(200);
                                    
                                    // Style the results
                                    $node.addClass('econt-price-results');
                                }
                            });
                        }
                    });
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        };

        // Initialize
        EcontCalculator.init();
    });

})(jQuery); 