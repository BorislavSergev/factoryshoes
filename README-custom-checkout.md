# Custom Checkout with Econt Integration

This document provides instructions on how to set up and use the custom checkout system that seamlessly integrates with the Econt delivery plugin.

## Overview

The custom checkout system offers a streamlined, user-friendly checkout experience that fully integrates with Econt delivery. It provides:

- Beautiful, responsive design that matches your site
- Customizable fields to collect order information
- Seamless integration with Econt delivery options
- Support for various payment methods
- Full WooCommerce compatibility

## Setup Instructions

### 1. Create a Custom Checkout Page

1. Go to WordPress admin → Pages → Add New
2. Enter a title (e.g., "Custom Checkout")
3. From the Page Attributes box, select "Custom Checkout" template
4. Publish the page

### 2. Configure the Checkout Settings

1. Go to WordPress admin → Appearance → Customize
2. Navigate to the "Checkout Options" section
3. Select your newly created page as the "Custom Checkout Page"
4. Save your changes

### 3. Test the Checkout Flow

1. Add products to your cart
2. Proceed to checkout
3. You should be redirected to your custom checkout page
4. Complete the form and test the process

## Features

### Econt Integration

The custom checkout is designed to work seamlessly with the Econt delivery plugin:

- When a user selects Econt as the shipping method, the Econt delivery form automatically appears
- The form is styled to match your site's design
- All Econt delivery data is properly captured and stored with the order

### Custom Fields

The checkout collects the following information:

- Customer name
- Contact details (email, phone)
- Address information
- Order notes
- Payment method

### WooCommerce Integration

All data collected through the custom checkout is properly stored in WooCommerce:

- Orders appear in the WooCommerce Orders dashboard
- All customer data is associated with the order
- Payment is processed through WooCommerce payment gateways
- Emails and notifications work as expected

## Customization

### Styling

You can customize the appearance of the checkout page by editing the following files:

- `assets/css/custom-checkout.css` - Styles for the checkout page
- `page-custom-checkout.php` - The checkout template itself

### Fields

To modify the fields collected during checkout, edit:

- `page-custom-checkout.php` - Modify the form fields section
- `functions.php` - Update the `shoes_store_save_custom_checkout_fields` function to handle any new fields

## Troubleshooting

### Common Issues

1. **Checkout page not showing custom template**
   - Make sure you selected "Custom Checkout" as the template in the page editor

2. **Econt fields not appearing**
   - Ensure the Econt plugin is properly installed and activated
   - Check that shipping is enabled in WooCommerce settings

3. **Orders not being created**
   - Check WooCommerce logs for any errors
   - Verify that all required WooCommerce settings are configured

4. **Styling issues**
   - Check browser console for any CSS errors
   - Verify that the custom-checkout.css file is being loaded

## Support

If you need assistance with the custom checkout system, please contact the theme developer with the following information:

1. WordPress version
2. WooCommerce version
3. Econt plugin version
4. Description of the issue
5. Screenshots if applicable 