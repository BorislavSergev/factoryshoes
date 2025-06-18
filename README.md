# Shoes Store WordPress Theme

A modern, responsive WordPress theme designed specifically for shoe stores and footwear retailers. This theme features a mobile-friendly design with extensive customization options for text content.

## Features

### 🎨 Design & UI
- **Modern, responsive design** that looks great on all devices
- **Mobile-first approach** with touch-friendly interfaces
- **Smooth animations** and hover effects
- **Professional product showcase** with card-based layout
- **Gradient hero section** with customizable content

### 📱 Mobile-Friendly Features
- **Responsive grid layouts** that adapt to all screen sizes
- **Touch-optimized navigation** with hamburger menu
- **Swipe gestures** and touch feedback
- **Fast loading** with optimized assets
- **Mobile-specific interactions** and animations

### ⚙️ Customization Options
- **WordPress Customizer integration** for live preview editing
- **Customizable text content** for all sections:
  - Hero section title and subtitle
  - Product section headings
  - About section content
  - Newsletter text
  - Footer information
- **Social media links** (Facebook, Twitter, Instagram, YouTube)
- **Custom logo support**
- **Color scheme customization**
- **Typography options**

### 🛍️ E-commerce Ready
- **Product showcase** with featured products
- **Custom product meta fields** (price, featured status)
- **Product categories** and filtering
- **Related products** display
- **Newsletter signup** integration
- **SEO-friendly structure**

## Installation

1. **Download the theme** files to your WordPress themes directory
2. **Navigate** to `wp-content/themes/` in your WordPress installation
3. **Upload** the theme folder or install via WordPress admin
4. **Activate** the theme from Appearance → Themes

## Quick Setup

### 1. Customize Your Content
Go to **Appearance → Customize** in your WordPress admin to modify:

- **Hero Section**: Change the main heading, subtitle, and call-to-action button
- **Products Section**: Update section title and "View All Products" button
- **About Section**: Customize your store's story and information
- **Newsletter**: Edit signup form text and description
- **Footer**: Add contact information and social media links

### 2. Add Your Logo
- Go to **Appearance → Customize → Site Identity**
- Upload your logo image
- The theme will automatically display it in the header

### 3. Create Your Menu
- Go to **Appearance → Menus**
- Create a new menu and assign it to "Primary Menu"
- Add pages like Home, Shop, About, Contact

### 4. Add Featured Products
When creating posts that represent products:
1. **Add a featured image** for the product photo
2. **Fill in the product price** in the Product Details meta box
3. **Check "Featured Product"** to display it on the homepage
4. **Write a compelling description** in the post content

## Customization Guide

### Text Customization
All text content can be customized through the WordPress Customizer:

#### Hero Section
```php
// Available customizer options:
- hero_title (default: "Step Into Style")
- hero_subtitle (default: "Discover the perfect pair...")
- hero_button_text (default: "Shop Now")
- hero_button_url (default: "#products")
```

#### Products Section
```php
- products_section_title (default: "Featured Shoes")
- view_all_products_text (default: "View All Products")
- view_all_products_url (default: "/shop")
```

#### About Section
```php
- about_title (default: "About Our Store")
- about_description (default: "We are passionate...")
- about_button_text (default: "Learn More")
- about_button_url (default: "/about")
```

#### Footer Settings
```php
- footer_about_title (default: "About Us")
- footer_about_text (default: "We are dedicated...")
- footer_phone (default: "Phone: (555) 123-4567")
- footer_email (default: "Email: info@shoesstore.com")
- footer_address (default: "Address: 123 Shoe Street...")
```

### Social Media Links
Add your social media URLs in the customizer:
- Facebook URL
- Twitter URL
- Instagram URL
- YouTube URL

### Color Customization
The theme uses CSS custom properties for easy color changes. Edit these in `style.css`:

```css
:root {
  --primary-color: #e74c3c;      /* Main brand color */
  --secondary-color: #2c3e50;    /* Dark text color */
  --background-color: #ffffff;   /* Main background */
  --light-bg: #f8f9fa;          /* Section backgrounds */
}
```

### Typography
The theme uses Inter font family. You can change it by modifying the Google Fonts import in `functions.php`:

```php
wp_enqueue_style('shoes-store-fonts', 'https://fonts.googleapis.com/css2?family=YourFont:wght@300;400;500;600;700;800&display=swap');
```

## Mobile Optimization

### Responsive Breakpoints
- **Mobile**: 480px and below
- **Tablet**: 768px and below
- **Desktop**: 769px and above

### Mobile-Specific Features
- **Collapsible navigation** with animated hamburger menu
- **Touch-friendly buttons** with proper sizing (44px minimum)
- **Optimized images** with responsive sizing
- **Fast loading** with minimized assets
- **Scroll effects** and smooth animations

### Performance Optimization
- **Lazy loading** for images
- **Minified CSS and JavaScript**
- **Optimized font loading**
- **Efficient grid layouts**
- **Mobile-first CSS** approach

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## File Structure

```
shoes-store-theme/
├── style.css              # Main stylesheet with theme info
├── index.php              # Main template file
├── header.php             # Header template
├── footer.php             # Footer template
├── single.php             # Single post template
├── functions.php          # Theme functions and customizer
├── assets/
│   └── js/
│       └── main.js        # JavaScript for interactions
└── README.md              # This file
```

## Customization Examples

### Changing the Hero Background
Add this to your `style.css`:

```css
.hero-section {
    background: linear-gradient(135deg, #your-color 0%, #your-color-2 100%);
}
```

### Adding Custom Product Fields
Add to `functions.php`:

```php
function add_custom_product_field() {
    add_meta_box('custom_product_field', 'Additional Product Info', 'custom_product_callback', 'post');
}
add_action('add_meta_boxes', 'add_custom_product_field');
```

### Custom CSS for Specific Sections
Use the customizer's Additional CSS section:

```css
/* Custom styles for your specific needs */
.products-section {
    background: your-custom-background;
}

.product-card:hover {
    transform: scale(1.05);
}
```

## SEO Features

- **Semantic HTML5** markup
- **Proper heading structure** (H1, H2, H3)
- **Alt text support** for images
- **Meta description** support
- **Schema markup** ready
- **Fast loading times**
- **Mobile-friendly** (Google ranking factor)

## Accessibility

The theme follows WCAG 2.1 guidelines:
- **Keyboard navigation** support
- **Screen reader** friendly
- **Color contrast** compliance
- **Focus indicators** for interactive elements
- **ARIA labels** where appropriate

## Support

For theme support and customization help:
1. Check the **WordPress Customizer** for built-in options
2. Review this **README** for common customizations
3. Refer to **WordPress documentation** for general WordPress questions

## License

This theme is licensed under the GPL v2 or later.

## Credits

- **Font**: Inter (Google Fonts)
- **Icons**: Emoji icons for simplicity and universal support
- **Framework**: Built with modern CSS Grid and Flexbox
- **JavaScript**: Vanilla JavaScript for better performance

---

**Happy selling!** 👟✨ #   f a c t o r y s h o e s  
 