<?php
/**
 * Shoes Store Theme functions and definitions
 *
 * @package Shoes_Store_Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function shoes_store_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add WooCommerce support with enhanced features
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 4,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 6,
        ),
    ));
    
    // Enable product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Add custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add custom background support
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Register navigation menus
    register_nav_menus(array(
        'menu-1' => esc_html__('Primary Menu', 'shoes-store'),
        'footer'  => esc_html__('Footer Menu', 'shoes-store'),
    ));

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Load text domain for translations
    load_theme_textdomain('shoes-store', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'shoes_store_setup');

/**
 * Check if WooCommerce is active
 */
function shoes_store_is_woocommerce_activated() {
    return class_exists('WooCommerce');
}

/**
 * Create size attribute for shoes
 */
function shoes_store_create_size_attribute() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Check if size attribute already exists
    $size_attribute = wc_get_attribute(wc_attribute_taxonomy_id_by_name('size'));
    
    if (!$size_attribute) {
        // Create size attribute
        $attribute_data = array(
            'name' => 'Size',
            'slug' => 'size',
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => false,
        );
        
        $attribute_id = wc_create_attribute($attribute_data);
        
        if (!is_wp_error($attribute_id)) {
            // Add common shoe sizes
            $sizes = array('35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45');
            
            foreach ($sizes as $size) {
                wp_insert_term($size, 'pa_size', array(
                    'slug' => $size
                ));
            }
        }
    }
}
add_action('init', 'shoes_store_create_size_attribute');

/**
 * WooCommerce product categories are used instead of custom taxonomy
 */

/**
 * Enqueue scripts and styles
 */
function shoes_store_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('shoes-store-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue responsive fixes
    wp_enqueue_style('shoes-store-responsive-fixes', get_template_directory_uri() . '/responsive-fixes.css', array('shoes-store-style'), '1.0.0');
    
    // Enqueue all products styles
    wp_enqueue_style('shoes-store-all-products', get_template_directory_uri() . '/assets/css/all-products.css', array('shoes-store-style'), '1.0.0');

    // Enqueue Google Fonts
    wp_enqueue_style('shoes-store-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Enqueue main JavaScript
    wp_enqueue_script('shoes-store-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Enqueue cart script
    wp_enqueue_script('shoes-store-cart', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), '1.0.0', true);
    
    // Enqueue WooCommerce AJAX cart script
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-cart-fragments');
        wp_enqueue_script('wc-add-to-cart');
    }

    // Enqueue perfect product page assets for WooCommerce product pages
    if (is_product() && class_exists('WooCommerce')) {
        wp_enqueue_style('shoes-store-product-page', get_template_directory_uri() . '/assets/css/product-page.css', array('shoes-store-style'), '1.0.1');
        wp_enqueue_script('shoes-store-product-page', get_template_directory_uri() . '/assets/js/product-page.js', array('jquery', 'wc-add-to-cart-variation'), '1.0.1', true);
        
        // Localize product page script
        wp_localize_script('shoes-store-product-page', 'shoes_store_product_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
            'cart_url' => wc_get_cart_url(),
            'nonce' => wp_create_nonce('shoes_store_product_nonce'),
        ));
        
        // Remove default WooCommerce breadcrumbs
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }

    // Localize script with AJAX parameters
    $wc_params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'update_cart_nonce' => wp_create_nonce('woocommerce-cart'),
        'remove_from_cart_nonce' => wp_create_nonce('woocommerce-cart'),
        'add_to_cart_nonce' => wp_create_nonce('woocommerce-add-to-cart'),
        'fragment_nonce' => wp_create_nonce('wc_fragment_refresh') // Add fragment nonce for cart operations
    );
    
    // Add WooCommerce specific parameters if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $wc_params['wc_ajax_url'] = WC_AJAX::get_endpoint('%%endpoint%%');
        $wc_params['cart_url'] = wc_get_cart_url();
        $wc_params['is_cart'] = is_cart();
        $wc_params['cart_redirect_after_add'] = get_option('woocommerce_cart_redirect_after_add');
    }
    
    wp_localize_script('shoes-store-script', 'woocommerce_params', $wc_params);

    // Enqueue comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'shoes_store_scripts');

/**
 * Theme Customizer
 */
function shoes_store_customize_register($wp_customize) {
    
    // Hero Section
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Section', 'shoes-store'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('hero_title', array(
        'default'           => __('Make your move up to 50% off', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_title', array(
        'label'   => __('Hero Title', 'shoes-store'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => __('First lifestyle Air Max returns with a vibrant color which sure to turn heads', 'shoes-store'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label'   => __('Hero Subtitle', 'shoes-store'),
        'section' => 'hero_section',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('hero_button_text', array(
        'default'           => __('Shop Now', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_button_text', array(
        'label'   => __('Hero Button Text', 'shoes-store'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_button_url', array(
        'default'           => '#products',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('hero_button_url', array(
        'label'   => __('Hero Button URL', 'shoes-store'),
        'section' => 'hero_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('sale_label', array(
        'default'           => __('Sale Products', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('sale_label', array(
        'label'   => __('Sale Label', 'shoes-store'),
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    // About Section
    $wp_customize->add_section('about_section', array(
        'title'    => __('За Нас Секция', 'shoes-store'),
        'priority' => 31,
    ));

    $wp_customize->add_setting('about_title', array(
        'default'           => __('За Нашия Магазин', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('about_title', array(
        'label'   => __('Заглавие', 'shoes-store'),
        'section' => 'about_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('about_description', array(
        'default'           => __('Ние сме страстни по предоставянето на висококачествени обувки, които съчетават стил, комфорт и издръжливост. Нашата внимателно подбрана колекция включва обувки за всеки начин на живот и повод.', 'shoes-store'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('about_description', array(
        'label'   => __('Описание', 'shoes-store'),
        'section' => 'about_section',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('about_button_text', array(
        'default'           => __('Научи Повече', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('about_button_text', array(
        'label'   => __('Текст на Бутона', 'shoes-store'),
        'section' => 'about_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('about_button_url', array(
        'default'           => '/about',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('about_button_url', array(
        'label'   => __('URL на Бутона', 'shoes-store'),
        'section' => 'about_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('about_image', array(
        'default'           => get_template_directory_uri() . '/assets/images/store.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_image', array(
        'label'   => __('Изображение', 'shoes-store'),
        'section' => 'about_section',
        'settings' => 'about_image',
    )));

    // Home Products Section
    $wp_customize->add_section('home_products_section', array(
        'title'    => __('Настройки за Продукти на Началната Страница', 'shoes-store'),
        'priority' => 32,
    ));

    $wp_customize->add_setting('products_section_title', array(
        'default'           => __('Препоръчани Продукти', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('products_section_title', array(
        'label'   => __('Заглавие на Секцията с Продукти', 'shoes-store'),
        'section' => 'home_products_section',
        'type'    => 'text',
    ));

    // Get all product categories for the dropdown
    $category_choices = array(
        'new_arrivals' => __('Нови Постъпления (По подразбиране)', 'shoes-store'),
        '' => __('Всички Категории', 'shoes-store'),
        'featured' => __('Препоръчани Продукти', 'shoes-store'),
        'sale' => __('Продукти в Промоция', 'shoes-store'),
        'bestsellers' => __('Най-продавани', 'shoes-store'),
        'top_rated' => __('Най-добре Оценени', 'shoes-store'),
        'coming_soon' => __('Очаквайте Скоро', 'shoes-store'),
    );

    if (shoes_store_is_woocommerce_activated()) {
        $product_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ));

        if (!is_wp_error($product_categories)) {
            foreach ($product_categories as $category) {
                $category_choices[$category->term_id] = $category->name;
            }
        }
    }

    $wp_customize->add_setting('home_product_category', array(
        'default'           => 'new_arrivals',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('home_product_category', array(
        'label'   => __('Показвай Продукти От', 'shoes-store'),
        'section' => 'home_products_section',
        'type'    => 'select',
        'choices' => $category_choices,
        'description' => __('Изберете коя категория да се показва на началната страница. По подразбиране се показват нови постъпления.', 'shoes-store'),
    ));

    $wp_customize->add_setting('products_per_page', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('products_per_page', array(
        'label'   => __('Брой Продукти за Показване', 'shoes-store'),
        'section' => 'home_products_section',
        'type'    => 'number',
        'input_attrs' => array(
            'min' => 3,
            'max' => 12,
            'step' => 3,
        ),
    ));

    $wp_customize->add_setting('show_product_ratings', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('show_product_ratings', array(
        'label'   => __('Показвай Оценки на Продуктите', 'shoes-store'),
        'section' => 'home_products_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('view_all_products_text', array(
        'default'           => __('Виж Всички Продукти', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('view_all_products_text', array(
        'label'   => __('Текст на Бутона "Виж Всички"', 'shoes-store'),
        'section' => 'home_products_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('view_all_products_url', array(
        'default'           => '/shop',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('view_all_products_url', array(
        'label'   => __('URL на Бутона "Виж Всички"', 'shoes-store'),
        'section' => 'home_products_section',
        'type'    => 'url',
    ));

    // Newsletter Section
    $wp_customize->add_section('newsletter_section', array(
        'title'    => __('Newsletter Section', 'shoes-store'),
        'priority' => 33,
    ));

    $wp_customize->add_setting('newsletter_title', array(
        'default'           => __('Stay Updated', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('newsletter_title', array(
        'label'   => __('Newsletter Title', 'shoes-store'),
        'section' => 'newsletter_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('newsletter_description', array(
        'default'           => __('Subscribe to our newsletter and be the first to know about new arrivals and exclusive offers.', 'shoes-store'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('newsletter_description', array(
        'label'   => __('Newsletter Description', 'shoes-store'),
        'section' => 'newsletter_section',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('newsletter_button_text', array(
        'default'           => __('Subscribe', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('newsletter_button_text', array(
        'label'   => __('Newsletter Button Text', 'shoes-store'),
        'section' => 'newsletter_section',
        'type'    => 'text',
    ));

    // Contact Section
    $wp_customize->add_section('contact_section', array(
        'title'    => __('Контакти Секция', 'shoes-store'),
        'priority' => 33,
    ));

    $wp_customize->add_setting('contact_title', array(
        'default'           => __('Винаги сме насреща да помогнем', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_title', array(
        'label'   => __('Заглавие', 'shoes-store'),
        'section' => 'contact_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('contact_subtitle', array(
        'default'           => __('Имате въпроси или се нуждаете от съвет? Свържете се с нашия екип.', 'shoes-store'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('contact_subtitle', array(
        'label'   => __('Подзаглавие', 'shoes-store'),
        'section' => 'contact_section',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('store_hours', array(
        'default'           => __('Пон-Пет: 9:00 - 18:00, Съб: 10:00 - 16:00', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('store_hours', array(
        'label'   => __('Работно Време', 'shoes-store'),
        'section' => 'contact_section',
        'type'    => 'text',
    ));

    // Footer Section
    $wp_customize->add_section('footer_section', array(
        'title'    => __('Footer Settings', 'shoes-store'),
        'priority' => 34,
    ));

    $wp_customize->add_setting('footer_about_title', array(
        'default'           => __('About Us', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_about_title', array(
        'label'   => __('Footer About Title', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_about_text', array(
        'default'           => __('We are dedicated to providing high-quality shoes that combine style, comfort, and durability for every customer.', 'shoes-store'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('footer_about_text', array(
        'label'   => __('Footer About Text', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'textarea',
    ));

    // Contact Information
    $wp_customize->add_setting('footer_phone', array(
        'default'           => __('Phone: (555) 123-4567', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_phone', array(
        'label'   => __('Phone Number', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_email', array(
        'default'           => __('Email: info@shoesstore.com', 'shoes-store'),
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('footer_email', array(
        'label'   => __('Email Address', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'email',
    ));

    $wp_customize->add_setting('footer_address', array(
        'default'           => __('Address: 123 Shoe Street, City, State 12345', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_address', array(
        'label'   => __('Address', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    // Social Media Links
    $wp_customize->add_setting('facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('facebook_url', array(
        'label'   => __('Facebook URL', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('twitter_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('twitter_url', array(
        'label'   => __('Twitter URL', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('instagram_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('instagram_url', array(
        'label'   => __('Instagram URL', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('youtube_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('youtube_url', array(
        'label'   => __('YouTube URL', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'url',
    ));

    // Copyright Settings
    $wp_customize->add_setting('footer_copyright', array(
        'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_copyright', array(
        'label'   => __('Copyright Text', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_copyright_text', array(
        'default'           => __('All rights reserved.', 'shoes-store'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_copyright_text', array(
        'label'   => __('Additional Copyright Text', 'shoes-store'),
        'section' => 'footer_section',
        'type'    => 'text',
    ));
}
add_action('customize_register', 'shoes_store_customize_register');

/**
 * WooCommerce handles product data (price, brand, rating, etc.)
 * No custom meta boxes needed
 */

/**
 * Add responsive image sizes
 */
function shoes_store_image_sizes() {
    add_image_size('product-thumbnail', 300, 300, true);
    add_image_size('product-large', 600, 600, true);
    add_image_size('hero-product', 500, 400, true);
}
add_action('after_setup_theme', 'shoes_store_image_sizes');

/**
 * Content width for responsive embeds
 */
function shoes_store_content_width() {
    $GLOBALS['content_width'] = apply_filters('shoes_store_content_width', 1200);
}
add_action('after_setup_theme', 'shoes_store_content_width', 0);

/**
 * Add editor styles for better customization preview
 */
function shoes_store_add_editor_styles() {
    add_editor_style('style.css');
}
add_action('admin_init', 'shoes_store_add_editor_styles');

/**
 * Custom excerpt length
 */
function shoes_store_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'shoes_store_excerpt_length');

/**
 * Custom excerpt more text
 */
function shoes_store_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'shoes_store_excerpt_more');

/**
 * Generate star rating HTML for WooCommerce
 */
function shoes_store_get_star_rating($rating) {
    $rating = floatval($rating);
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;
    
    $stars = '';
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $stars .= '<span class="star filled"><i class="fas fa-star"></i></span>';
    }
    
    // Half star
    if ($half_star) {
        $stars .= '<span class="star half"><i class="fas fa-star-half-alt"></i></span>';
    }
    
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        $stars .= '<span class="star"><i class="far fa-star"></i></span>';
    }
    
    return $stars;
}

/**
 * Get WooCommerce product data
 */
function shoes_store_get_product_data($product_id) {
    if (!shoes_store_is_woocommerce_activated()) {
        return false;
    }
    
    $product = wc_get_product($product_id);
    if (!$product) {
        return false;
    }
    
    return array(
        'price' => $product->get_price_html(),
        'regular_price' => $product->get_regular_price(),
        'sale_price' => $product->get_sale_price(),
        'on_sale' => $product->is_on_sale(),
        'featured' => $product->is_featured(),
        'rating' => $product->get_average_rating(),
        'rating_count' => $product->get_rating_count(),
        'categories' => wp_get_post_terms($product_id, 'product_cat'),
        'tags' => wp_get_post_terms($product_id, 'product_tag'),
    );
}

/**
 * Get products for homepage display using WooCommerce
 */
function shoes_store_get_homepage_products() {
    if (!shoes_store_is_woocommerce_activated()) {
        return new WP_Query(array('post_type' => 'post', 'posts_per_page' => 0));
    }

    $category = get_theme_mod('home_product_category', 'new_arrivals');
    $posts_per_page = get_theme_mod('products_per_page', 6);
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
    );
    
    // Handle different product categories
    if ($category === 'featured') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ),
        );
    } elseif ($category === 'sale') {
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => '_sale_price',
                'value' => '',
                'compare' => '!='
            ),
            array(
                'key' => '_min_variation_sale_price',
                'value' => '',
                'compare' => '!='
            )
        );
    } elseif ($category === 'bestsellers') {
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ($category === 'new_arrivals' || empty($category)) {
        // Default to new arrivals - most recent products
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($category === 'top_rated') {
        $args['meta_key'] = '_wc_average_rating';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ($category === 'coming_soon') {
        // Show upcoming products
        $args['meta_query'] = array(
            array(
                'key' => '_coming_soon',
                'value' => 'yes',
                'compare' => '='
            )
        );
    } elseif (!empty($category) && is_numeric($category)) {
        // Handle specific WooCommerce product categories
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => intval($category),
            )
        );
    }
    
    $query = new WP_Query($args);
    
    // If no products found with specified criteria, try fallback to latest products
    if (!$query->have_posts() && ($category !== 'new_arrivals' && !empty($category))) {
        // Remove specific criteria and get latest products as fallback
        $fallback_args = array(
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query = new WP_Query($fallback_args);
    }
    
    return $query;
}

/**
 * WooCommerce Cart AJAX functions
 */
function shoes_store_add_cart_ajax_functions() {
    // Only if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Get cart contents
    add_action('wp_ajax_get_cart_contents', 'shoes_store_get_cart_contents');
    add_action('wp_ajax_nopriv_get_cart_contents', 'shoes_store_get_cart_contents');

    // Add to cart
    add_action('wp_ajax_add_to_cart', 'shoes_store_add_to_cart');
    add_action('wp_ajax_nopriv_add_to_cart', 'shoes_store_add_to_cart');

    // Update cart item quantity
    add_action('wp_ajax_update_cart_item_quantity', 'shoes_store_update_cart_item_quantity');
    add_action('wp_ajax_nopriv_update_cart_item_quantity', 'shoes_store_update_cart_item_quantity');
    
    // Remove cart item
    add_action('wp_ajax_remove_cart_item', 'shoes_store_remove_cart_item');
    add_action('wp_ajax_nopriv_remove_cart_item', 'shoes_store_remove_cart_item');
    
    // Check if product is in cart
    add_action('wp_ajax_check_product_in_cart', 'shoes_store_check_product_in_cart');
    add_action('wp_ajax_nopriv_check_product_in_cart', 'shoes_store_check_product_in_cart');
}
add_action('init', 'shoes_store_add_cart_ajax_functions');

/**
 * AJAX handler for getting cart contents
 */
function shoes_store_get_cart_contents() {
    // Make sure WooCommerce is available
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error('WooCommerce not available');
        die();
    }

    // Get cart data
    $cart_items = WC()->cart->get_cart();
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_total = WC()->cart->get_cart_subtotal();

    // Start HTML output
    ob_start();

    if (count($cart_items) > 0) {
        foreach ($cart_items as $cart_item_key => $cart_item) {
            $product = $cart_item['data']; 
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $price = WC()->cart->get_product_price($product);
            $subtotal = WC()->cart->get_product_subtotal($product, $quantity);
            $thumbnail = $product->get_image('thumbnail');
            ?>
            <div class="cart-item" data-item-key="<?php echo esc_attr($cart_item_key); ?>">
                <div class="cart-item-image">
                    <?php echo $thumbnail; ?>
                </div>
                <div class="cart-item-details">
                    <h4><?php echo esc_html($product->get_name()); ?></h4>
                    <div class="cart-item-price">
                        <?php echo $price; ?> × <?php echo esc_html($quantity); ?>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="cart-item-dec" data-item-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                        <span><?php echo esc_html($quantity); ?></span>
                        <button class="cart-item-inc" data-item-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                    </div>
                </div>
                <div class="cart-item-subtotal">
                    <?php echo $subtotal; ?>
                </div>
                <button class="cart-item-remove" data-item-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="<?php esc_attr_e('Премахни', 'shoes-store'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <p><?php _e('Количката е празна', 'shoes-store'); ?></p>
        </div>
        <?php
    }

    // Get the HTML output
    $html = ob_get_clean();

    wp_send_json_success(array(
        'html' => $html,
        'total' => $cart_total,
        'count' => $cart_count,
        'cart_hash' => WC()->cart->get_cart_hash()
    ));

    die();
}

/**
 * AJAX handler for adding product to cart
 */
function shoes_store_add_to_cart() {
    // Check if we have product ID and quantity
    if (!isset($_POST['product_id'])) {
        wp_send_json_error('Missing product ID');
        die();
    }

    $product_id = absint($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;

    // Make sure WooCommerce is available
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error('WooCommerce not available');
        die();
    }

    // Check if product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found');
        die();
    }

    // Check if product is already in cart
    $cart_item_key = WC()->cart->find_product_in_cart(WC()->cart->generate_cart_id($product_id, $variation_id));
    
    if ($cart_item_key) {
        // Product already in cart - increase quantity
        $current_quantity = WC()->cart->cart_contents[$cart_item_key]['quantity'];
        $new_quantity = $current_quantity + $quantity;
        
        // Update quantity instead of adding new item
        WC()->cart->set_quantity($cart_item_key, $new_quantity);
        
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_cart_subtotal();
        $fragments = apply_filters('woocommerce_add_to_cart_fragments', array());
        
        wp_send_json_success(array(
            'cart_hash' => WC()->cart->get_cart_hash(),
            'cart_count' => $cart_count,
            'cart_total' => $cart_total,
            'message' => 'Product quantity updated in cart',
            'already_in_cart' => true,
            'new_quantity' => $new_quantity,
            'fragments' => $fragments
        ));
    } else {
        // Add new item to cart
        $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);

        if ($result) {
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_total = WC()->cart->get_cart_subtotal();
            $fragments = apply_filters('woocommerce_add_to_cart_fragments', array());
            
            wp_send_json_success(array(
                'cart_hash' => WC()->cart->get_cart_hash(),
                'cart_count' => $cart_count,
                'cart_total' => $cart_total,
                'message' => 'Product added to cart',
                'already_in_cart' => false,
                'fragments' => $fragments
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Failed to add product to cart',
                'product_id' => $product_id
            ));
        }
    }

    die();
}

/**
 * AJAX handler for updating cart item quantity
 */
function shoes_store_update_cart_item_quantity() {
    // Check nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'wc_fragment_refresh')) {
        wp_send_json_error(['message' => 'Invalid security token']);
        die();
    }
    
    // Check if we have the cart item key and quantity
    if (!isset($_POST['cart_item_key']) || !isset($_POST['quantity'])) {
        wp_send_json_error('Missing required parameters');
        die();
    }
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = absint($_POST['quantity']);
    
    // Check if WooCommerce is active and cart is available
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error(['message' => 'WooCommerce not available']);
        die();
    }
    
    // Check if the item exists in cart
    if (!WC()->cart->get_cart_item($cart_item_key)) {
        wp_send_json_error(['message' => 'Item not found in cart']);
        die();
    }
    
    // Get item data before quantity update for comparison
    $item_before = WC()->cart->get_cart_item($cart_item_key);
    $product = $item_before['data'];
    
    // Update cart
    WC()->cart->set_quantity($cart_item_key, $quantity);
    
    // Get updated item data after quantity change
    $updated_item = WC()->cart->get_cart_item($cart_item_key);
    $updated_product = $updated_item['data'];
    
    // Get updated cart data
    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_subtotal = WC()->cart->get_cart_subtotal();
    
    // Build item data for the response
    $item_data = array(
        'name' => $updated_product->get_name(),
        'price' => WC()->cart->get_product_price($updated_product),
        'quantity' => $updated_item['quantity'],
        'subtotal' => WC()->cart->get_product_subtotal($updated_product, $updated_item['quantity']),
        'line_total' => $updated_item['line_total'],
        'product_id' => $updated_item['product_id']
    );
    
    // Get fragments for live updates
    $fragments = apply_filters('woocommerce_add_to_cart_fragments', array());
    
    // Success response with detailed information
    wp_send_json_success(array(
        'cart_hash' => WC()->cart->get_cart_hash(),
        'cart_count' => $cart_count,
        'count' => $cart_count,
        'cart_total' => $cart_subtotal,
        'fragments' => $fragments,
        'item_data' => $item_data
    ));
    
    die();
}

/**
 * AJAX handler for removing cart item
 */
function shoes_store_remove_cart_item() {
    // Check nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'wc_fragment_refresh')) {
        wp_send_json_error(['message' => 'Invalid security token']);
        die();
    }
    
    // Check if we have the cart item key
    if (!isset($_POST['cart_item_key'])) {
        wp_send_json_error(['message' => 'Missing cart item key']);
        die();
    }
    
    // Check if WooCommerce is active and cart is available
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error(['message' => 'WooCommerce not available']);
        die();
    }
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    
    // Check if the item exists in cart
    if (!WC()->cart->get_cart_item($cart_item_key)) {
        wp_send_json_error(['message' => 'Item not found in cart']);
        die();
    }
    
    // Get item data before removal for the response
    $item = WC()->cart->get_cart_item($cart_item_key);
    $product = $item['data'];
    $product_name = $product->get_name();
    
    // Remove from cart
    $removed = WC()->cart->remove_cart_item($cart_item_key);
    
    if ($removed) {
        // Get updated cart data
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_subtotal = WC()->cart->get_cart_subtotal();
        
        // Get fragments for live updates
        $fragments = apply_filters('woocommerce_add_to_cart_fragments', array());
        
        // Success response with detailed information
        wp_send_json_success(array(
            'cart_hash' => WC()->cart->get_cart_hash(),
            'cart_count' => $cart_count,
            'count' => $cart_count,
            'cart_total' => $cart_subtotal,
            'fragments' => $fragments,
            'removed_item' => array(
                'key' => $cart_item_key,
                'name' => $product_name
            ),
            'is_cart_empty' => ($cart_count === 0)
        ));
    } else {
        wp_send_json_error(['message' => 'Failed to remove item from cart']);
    }
    
    die();
}

/**
 * AJAX handler for checking if product is in cart
 */
function shoes_store_check_product_in_cart() {
    // Check if we have product ID
    if (!isset($_POST['product_id'])) {
        wp_send_json_error('Missing product ID');
        die();
    }

    $product_id = absint($_POST['product_id']);

    // Make sure WooCommerce is available
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error('WooCommerce not available');
        die();
    }

    // Check if product is in cart
    $cart_item_key = WC()->cart->find_product_in_cart(WC()->cart->generate_cart_id($product_id));
    
    if ($cart_item_key) {
        $quantity = WC()->cart->cart_contents[$cart_item_key]['quantity'];
        wp_send_json_success(array(
            'in_cart' => true,
            'quantity' => $quantity,
            'cart_item_key' => $cart_item_key
        ));
    } else {
        wp_send_json_success(array(
            'in_cart' => false,
            'quantity' => 0
        ));
    }

    die();
}

/**
 * Add WooCommerce cart fragment for cart count
 */
function shoes_store_add_to_cart_fragments($fragments) {
    // Add cart count to fragments - target all cart count elements
    $fragments['.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    
    // Add mobile cart count
    $fragments['.mobile-cart-count'] = '<span class="mobile-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    
    // Add cart total to fragments
    $fragments['.cart-total-amount'] = WC()->cart->get_cart_total();
    
    // Update cart total in the footer
    $fragments['#cart-total'] = '<span id="cart-total">' . WC()->cart->get_cart_subtotal() . '</span>';
    
    // Add cart items HTML to fragments
    ob_start();
    $cart_items = WC()->cart->get_cart();
    if (count($cart_items) > 0) {
        foreach ($cart_items as $cart_item_key => $cart_item) {
            $product = $cart_item['data']; 
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $price = WC()->cart->get_product_price($product);
            $subtotal = WC()->cart->get_product_subtotal($product, $quantity);
            $thumbnail = $product->get_image('thumbnail');
            ?>
            <div class="cart-item" data-item-key="<?php echo esc_attr($cart_item_key); ?>">
                <div class="cart-item-image">
                    <?php echo $thumbnail; ?>
                </div>
                <div class="cart-item-details">
                    <h4><?php echo esc_html($product->get_name()); ?></h4>
                    <div class="cart-item-price">
                        <?php echo $price; ?> × <?php echo esc_html($quantity); ?>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="cart-item-dec" data-item-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                        <span><?php echo esc_html($quantity); ?></span>
                        <button class="cart-item-inc" data-item-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                    </div>
                </div>
                <div class="cart-item-subtotal">
                    <?php echo $subtotal; ?>
                </div>
                <button class="cart-item-remove" data-item-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="<?php esc_attr_e('Премахни', 'shoes-store'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <p><?php _e('Количката е празна', 'shoes-store'); ?></p>
        </div>
        <?php
    }
    $fragments['#cart-items'] = '<div id="cart-items">' . ob_get_clean() . '</div>';
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'shoes_store_add_to_cart_fragments');

/**
 * Custom product meta display
 */
function shoes_store_custom_product_meta() {
    global $product;
    
    echo '<div class="product-meta">';
    
    // Display SKU only if available and not N/A
    if ($product->get_sku()) {
        echo '<span class="sku-wrapper">SKU: <span class="sku">' . esc_html($product->get_sku()) . '</span></span>';
    }
    
    // Display categories with better styling, but only if not Uncategorized
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $category_names = wp_list_pluck($categories, 'name');
        
        // Only show categories if there are categories other than Uncategorized
        if (!empty($category_names) && !(count($category_names) === 1 && in_array('Uncategorized', $category_names))) {
            echo '<span class="posted-in">Category: ';
            $cat_links = array();
            foreach ($categories as $category) {
                if ($category->name !== 'Uncategorized') {
                    $cat_links[] = '<a href="' . esc_url(get_term_link($category)) . '" rel="tag">' . esc_html($category->name) . '</a>';
                }
            }
            echo implode(', ', $cat_links);
            echo '</span>';
        }
    }
    
    echo '</div>';
}

// Remove standard meta
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Add custom meta
add_action('woocommerce_single_product_summary', 'shoes_store_custom_product_meta', 40);

/**
 * Display product features in a more visually appealing way
 */
function shoes_store_display_product_features() {
    global $product;
    
    // Get product attributes
    $attributes = $product->get_attributes();
    
    if (!empty($attributes)) {
        echo '<div class="product-features">';
        echo '<h3>' . esc_html__('Product Features', 'shoes-store') . '</h3>';
        echo '<ul class="features-list">';
        
        foreach ($attributes as $attribute) {
            // Skip variations
            if ($attribute->get_variation()) {
                continue;
            }
            
            $name = wc_attribute_label($attribute->get_name());
            
            if ($attribute->is_taxonomy()) {
                $terms = wp_get_post_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                if (!is_wp_error($terms) && !empty($terms)) {
                    echo '<li><span class="feature-name">' . esc_html($name) . ':</span> ' . esc_html(implode(', ', $terms)) . '</li>';
                }
            } else {
                $value = $attribute->get_options();
                if (!empty($value)) {
                    echo '<li><span class="feature-name">' . esc_html($name) . ':</span> ' . esc_html(implode(', ', $value)) . '</li>';
                }
            }
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

// Add product features to product tabs
add_filter('woocommerce_product_tabs', 'shoes_store_add_features_tab', 20);
function shoes_store_add_features_tab($tabs) {
    // Add a custom tab for features
    $tabs['features'] = array(
        'title'    => __('Features', 'shoes-store'),
        'priority' => 15,
        'callback' => 'shoes_store_features_tab_content'
    );
    
    return $tabs;
}

/**
 * Callback function for features tab content
 */
function shoes_store_features_tab_content() {
    wc_get_template('single-product/tabs/features.php');
}

/**
 * Enqueue scripts and styles for the theme
 */
function theme_enqueue_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style(
        'bootstrap', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', 
        array(), 
        '5.3.0'
    );
    
    // Enqueue Bootstrap Icons
    wp_enqueue_style(
        'bootstrap-icons', 
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css', 
        array(), 
        '1.10.0'
    );

    // Enqueue our main stylesheet
    wp_enqueue_style(
        'theme-style', 
        get_stylesheet_uri(), 
        array('bootstrap'), 
        '1.0.0'
    );
    
    // Conditionally load product page styles
    if (is_product()) {
        wp_enqueue_style(
            'bootstrap-product-page', 
            get_template_directory_uri() . '/assets/css/bootstrap-product-page.css', 
            array('bootstrap'), 
            '1.0.0'
        );
    }
    
    // Conditionally load shop page styles
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_enqueue_style(
            'custom-shop-styles', 
            get_template_directory_uri() . '/assets/css/custom-shop.css', 
            array('theme-style'), 
            '1.0.0'
        );
    }

    // Shopping cart styles
    wp_enqueue_style(
        'shopping-cart-styles',
        get_template_directory_uri() . '/assets/css/shopping-cart.css',
        array('bootstrap'),
        '1.0.0'
    );

    // Enqueue Bootstrap JS with Popper
    wp_enqueue_script(
        'bootstrap', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', 
        array('jquery'), 
        '5.3.0', 
        true
    );
    
    // Enqueue our custom scripts
    wp_enqueue_script(
        'theme-scripts', 
        get_template_directory_uri() . '/assets/js/scripts.js', 
        array('jquery', 'bootstrap'), 
        '1.0.0', 
        true
    );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

/**
 * Helper functions for product pages
 */
 
// Get product category (if this is a WooCommerce site)
function get_product_category() {
    if (function_exists('wc_get_product')) {
        global $product;
        $product_id = $product->get_id();
        $categories = wc_get_product_category_list($product_id);
        if ($categories) {
            $categories = strip_tags($categories);
            $categories_array = explode(', ', $categories);
            return $categories_array[0];
        }
    }
    return 'Category';
}

// Check if product is on sale (if this is a WooCommerce site)
function is_on_sale() {
    if (function_exists('wc_get_product')) {
        global $product;
        return $product->is_on_sale();
    }
    return false; // For demo purposes
}

// Get product rating stars HTML (if this is a WooCommerce site)
function get_star_rating() {
    if (function_exists('wc_get_product')) {
        global $product;
        $rating = $product->get_average_rating();
        return wc_get_rating_html($rating);
    }
    // Demo star rating
    return '<i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>';
}

// Get product rating count (if this is a WooCommerce site)
function get_rating_count() {
    if (function_exists('wc_get_product')) {
        global $product;
        return $product->get_rating_count();
    }
    return 24; // Demo count
}

// Get product sale price (if this is a WooCommerce site)
function get_sale_price() {
    if (function_exists('wc_get_product')) {
        global $product;
        return wc_price($product->get_sale_price());
    }
    return '$59.99'; // Demo price
}

// Get product regular price (if this is a WooCommerce site)
function get_regular_price() {
    if (function_exists('wc_get_product')) {
        global $product;
        return wc_price($product->get_regular_price());
    }
    return '$79.99'; // Demo price
}

// Get savings amount (if this is a WooCommerce site)
function get_savings_amount() {
    if (function_exists('wc_get_product')) {
        global $product;
        $regular = $product->get_regular_price();
        $sale = $product->get_sale_price();
        if ($regular && $sale) {
            return wc_price($regular - $sale);
        }
    }
    return '$20.00'; // Demo savings
}

// Get savings percentage (if this is a WooCommerce site)
function get_savings_percentage() {
    if (function_exists('wc_get_product')) {
        global $product;
        $regular = $product->get_regular_price();
        $sale = $product->get_sale_price();
        if ($regular && $sale) {
            return round(($regular - $sale) / $regular * 100);
        }
    }
    return 25; // Demo percentage
}

// Get available sizes (demo function)
function get_available_sizes() {
    // For WooCommerce, you'd typically get this from product attributes
    return array('XS', 'S', 'M', 'L', 'XL', 'XXL');
}

// Get product gallery images (if this is a WooCommerce site)
function get_product_gallery_images() {
    if (function_exists('wc_get_product')) {
        global $product;
        $attachment_ids = $product->get_gallery_image_ids();
        $images = array();
        
        // Add featured image first
        $featured_image = get_the_post_thumbnail_url($product->get_id(), 'large');
        if ($featured_image) {
            $images[] = $featured_image;
        }
        
        // Add gallery images
        foreach ($attachment_ids as $attachment_id) {
            $images[] = wp_get_attachment_url($attachment_id);
        }
        
        return $images;
    }
    
    // Demo images
    return array(
        get_template_directory_uri() . '/assets/images/product-1.jpg',
        get_template_directory_uri() . '/assets/images/product-2.jpg',
        get_template_directory_uri() . '/assets/images/product-3.jpg',
        get_template_directory_uri() . '/assets/images/product-4.jpg'
    );
}

// Get featured products (if this is a WooCommerce site)
function get_featured_products($count = 4) {
    if (function_exists('wc_get_products')) {
        $args = array(
            'featured' => true,
            'limit' => $count,
            'orderby' => 'rand'
        );
        
        $products = wc_get_products($args);
        $featured = array();
        
        foreach ($products as $product) {
            $featured[] = array(
                'title' => $product->get_name(),
                'url' => get_permalink($product->get_id()),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_url($product->get_image_id())
            );
        }
        
        return $featured;
    }
    
    // Demo featured products
    return array(
        array(
            'title' => 'Modern T-Shirt',
            'url' => '#',
            'price' => '$29.99',
            'image' => get_template_directory_uri() . '/assets/images/product-1.jpg'
        ),
        array(
            'title' => 'Classic Polo',
            'url' => '#',
            'price' => '$39.99',
            'image' => get_template_directory_uri() . '/assets/images/product-2.jpg'
        ),
        array(
            'title' => 'Designer Jacket',
            'url' => '#',
            'price' => '$89.99',
            'image' => get_template_directory_uri() . '/assets/images/product-3.jpg'
        ),
        array(
            'title' => 'Premium Hoodie',
            'url' => '#',
            'price' => '$59.99',
            'image' => get_template_directory_uri() . '/assets/images/product-4.jpg'
        )
    );
}

/**
 * Remove "Product has been added to your cart" messages
 */
function shoes_store_remove_cart_notices() {
    // Remove added to cart notices
    if (function_exists('wc_clear_notices')) {
        wc_clear_notices();
    }
}
add_action('woocommerce_add_to_cart', 'shoes_store_remove_cart_notices', 99);
add_action('woocommerce_ajax_added_to_cart', 'shoes_store_remove_cart_notices', 99);

// Remove added to cart message completely
function shoes_store_remove_cart_message($message, $products, $show_qty) {
    return '';
}
add_filter('wc_add_to_cart_message_html', 'shoes_store_remove_cart_message', 10, 3);

// This will disable the View Cart link after adding a product
function shoes_store_disable_cart_redirect($url) {
    return false;
}
add_filter('woocommerce_add_to_cart_redirect', 'shoes_store_disable_cart_redirect');

/**
 * Register widget areas
 */
function shoes_store_widgets_init() {
    // Main sidebar
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'shoes-store'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'shoes-store'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Shop sidebar
    register_sidebar(array(
        'name'          => esc_html__('Shop Sidebar', 'shoes-store'),
        'id'            => 'shop-sidebar',
        'description'   => esc_html__('Add widgets here to appear in your shop sidebar for filters.', 'shoes-store'),
        'before_widget' => '<div class="filter-group widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    // Footer widgets
    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'shoes-store'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'shoes-store'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'shoes-store'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'shoes-store'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'shoes-store'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'shoes-store'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 4', 'shoes-store'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'shoes-store'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'shoes_store_widgets_init');

/**
 * Modify WooCommerce shop loop
 */
function shoes_store_modify_woocommerce_shop() {
    // Use 4 columns for shop page
    add_filter('loop_shop_columns', function() { return 4; });
    
    // Set products per page (4 rows × 4 columns = 16 products)
    add_filter('loop_shop_per_page', function() { return 16; });
    
    // Remove default result count and catalog ordering hooks
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    
    // Add them back inside a wrapper div for better styling
    add_action('woocommerce_before_shop_loop', function() {
        echo '<div class="shop-controls-wrapper">';
        woocommerce_result_count();
        woocommerce_catalog_ordering();
        echo '</div>';
    }, 25);
    
    // Remove default opening/closing of product elements
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    
    // Remove default add to cart button location and rating
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    
    // Add more classes to product elements
    add_filter('woocommerce_post_class', 'shoes_store_add_product_classes', 10, 2);
}
add_action('init', 'shoes_store_modify_woocommerce_shop');

/**
 * Add custom classes to products
 */
function shoes_store_add_product_classes($classes, $product) {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $classes[] = 'modern-product-item';
    }
    return $classes;
}

/**
 * Modify WooCommerce breadcrumb
 */
function shoes_store_woocommerce_breadcrumb_defaults($args) {
    $args['delimiter'] = '<span class="breadcrumb-separator"> / </span>';
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb modern-breadcrumb" itemprop="breadcrumb">';
    $args['wrap_after'] = '</nav>';
    return $args;
}
add_filter('woocommerce_breadcrumb_defaults', 'shoes_store_woocommerce_breadcrumb_defaults');

/**
 * Translate WooCommerce strings to Bulgarian
 */
function shoes_store_translate_woocommerce_strings($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($text) {
            // Shop page translations
            case 'Shop':
                return 'Магазин';
            case 'Add to cart':
            case 'Add to Cart':
                return 'ДОБАВИ';
            case 'View cart':
                return 'Виж количката';
            case 'View Cart':
                return 'Виж количката';
            case 'Proceed to checkout':
            case 'Checkout':
                return 'Плащане';
            case 'Your cart is currently empty.':
                return 'Вашата количка е празна.';
            case 'Return to shop':
                return 'Обратно към магазина';
            case 'Related products':
                return 'Подобни продукти';
            case 'Filter':
                return 'Филтрирай';
            case 'Show':
                return 'Покажи';
            case 'Out of stock':
                return 'Изчерпан';
            case 'Showing all %d results':
                return 'Показани всички %d резултата';
            case 'Showing %1$d&ndash;%2$d of %3$d results':
                return 'Показани %1$d&ndash;%2$d от %3$d резултата';
            case 'Price':
                return 'Цена';
            case 'Default sorting':
                return 'Подреди по подразбиране';
            case 'Sort by popularity':
                return 'Подреди по популярност';
            case 'Sort by average rating':
                return 'Подреди по рейтинг';
            case 'Sort by latest':
                return 'Най-нови';
            case 'Sort by price: low to high':
                return 'Подреди по цена: възходяща';
            case 'Sort by price: high to low':
                return 'Подреди по цена: низходяща';
            case 'Categories':
                return 'Категории';
            case 'Category':
                return 'Категория';
            case 'Uncategorized':
                return 'Некатегоризирани';
            case 'Product Features':
                return 'Характеристики на продукта';
            case 'Features':
                return 'Характеристики';
            case 'Description':
                return 'Описание';
            case 'Additional information':
                return 'Допълнителна информация';
            case 'Reviews':
                return 'Отзиви';
            case 'Sale!':
                return 'Промоция!';
        }
    }
    return $translated_text;
}
add_filter('gettext', 'shoes_store_translate_woocommerce_strings', 20, 3);
add_filter('ngettext', 'shoes_store_translate_woocommerce_strings', 20, 3);

/**
 * Set number of products per row in shop
 */
function shoes_store_loop_shop_columns() {
    return 4; // Show 4 products per row
}
add_filter('loop_shop_columns', 'shoes_store_loop_shop_columns');

/**
 * Set number of products per page in shop
 */
function shoes_store_loop_shop_per_page() {
    return 16; // 4 rows × 4 columns
}
add_filter('loop_shop_per_page', 'shoes_store_loop_shop_per_page');

/**
 * Remove default shop result count and catalog ordering
 * Add them back with custom wrapper for better styling
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

function shoes_store_shop_controls() {
    echo '<div class="shop-controls">';
    woocommerce_result_count();
    woocommerce_catalog_ordering();
    echo '</div>';
}
add_action('woocommerce_before_shop_loop', 'shoes_store_shop_controls', 25);

/**
 * Set number of products per page to 16 (4x4 grid)
 */
add_filter('loop_shop_per_page', function() {
    return 16; // 4 rows x 4 columns
}, 25);

/**
 * Add missing WooCommerce translations
 */
function custom_woocommerce_translations($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($text) {
            case 'Sale!':
                return 'Промоция!';
            case 'In stock':
                return 'В наличност';
            case 'Out of stock':
                return 'Изчерпан';
            case 'Add to cart':
                return 'ДОБАВИ';
            case 'SKU:':
                return 'Код:';
            case 'N/A':
                return 'Няма';
            case 'Description':
                return 'Описание';
            case 'Reviews':
                return 'Отзиви';
            case 'Additional information':
                return 'Допълнителна информация';
            case 'Related products':
                return 'Подобни продукти';
            case 'Uncategorized':
                return 'UNCATEGORIZED';
        }
    }
    return $translated_text;
}
add_filter('gettext', 'custom_woocommerce_translations', 20, 3);

/**
 * Remove specific checkout fields that will be handled by Econt
 */
function shoes_store_remove_checkout_fields($fields) {
    // Keep all billing fields - we need these for payment processing
    
    // Remove shipping fields that will be provided by Econt
    if (isset($fields['shipping'])) {
        unset($fields['shipping']['shipping_first_name']);
        unset($fields['shipping']['shipping_last_name']);
        unset($fields['shipping']['shipping_company']);
        unset($fields['shipping']['shipping_address_1']);
        unset($fields['shipping']['shipping_address_2']);
        unset($fields['shipping']['shipping_city']);
        unset($fields['shipping']['shipping_postcode']);
        unset($fields['shipping']['shipping_country']);
        unset($fields['shipping']['shipping_state']);
        unset($fields['shipping']['shipping_phone']);
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'shoes_store_remove_checkout_fields', 99);

/**
 * Enqueue compatibility scripts for Econt delivery plugin
 */
function shoes_store_enqueue_econt_scripts() {
    if (is_checkout() && class_exists('Econt_Woo_Delivery')) {
        // Add custom JavaScript to enhance Econt plugin integration
        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                // Ensure proper styling for Econt elements
                function styleEcontElements() {
                    // Style delivery type selectors
                    $(".econt-delivery-type-radio").parent().addClass("econt-delivery-type-option");
                    
                    // Add icon to delivery options based on type
                    $(".econt-delivery-type-option").each(function() {
                        var optionText = $(this).text().toLowerCase();
                        var icon = "";
                        
                        if (optionText.indexOf("офис") > -1) {
                            icon = "<i class=\"fas fa-building\"></i>";
                        } else if (optionText.indexOf("автомат") > -1) {
                            icon = "<i class=\"fas fa-box\"></i>";
                        } else {
                            icon = "<i class=\"fas fa-home\"></i>";
                        }
                        
                        $(this).prepend(icon);
                    });
                    
                    // Style select dropdowns
                    $(".econt-select").addClass("form-control");
                    
                    // Trigger a custom event to notify our template
                    $(document).trigger("econt_delivery_form_loaded");
                }
                
                // Run initially and also when Econt updates the DOM
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length) {
                            styleEcontElements();
                        }
                    });
                });
                
                // Start observing the document for changes
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
                
                // Also run on shipping method change
                $(document.body).on("updated_checkout", function() {
                    setTimeout(styleEcontElements, 500);
                });
            });
        ');
    }
}
add_action('wp_enqueue_scripts', 'shoes_store_enqueue_econt_scripts', 100);

/**
 * Custom Checkout Support
 */
function shoes_store_custom_checkout_support() {
    // Only run on frontend
    if (is_admin()) {
        return;
    }
    
    // Handle custom checkout process
    add_action('template_redirect', 'shoes_store_handle_custom_checkout');
    
    // Add necessary scripts and styles for custom checkout
    if (is_page_template('page-custom-checkout.php')) {
        add_action('wp_enqueue_scripts', 'shoes_store_enqueue_custom_checkout_scripts', 100);
    }
}
add_action('init', 'shoes_store_custom_checkout_support');

/**
 * Handle the custom checkout process
 */
function shoes_store_handle_custom_checkout() {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // If we have a custom checkout order ID in session, process it
    $order_id = WC()->session->get('custom_checkout_order_id');
    if (!empty($order_id) && is_numeric($order_id)) {
        $order = wc_get_order($order_id);
        
        if ($order && !$order->has_status('cancelled')) {
            // Clear the session data
            WC()->session->set('custom_checkout_order_id', null);
            WC()->session->set('custom_checkout_data', null);
            
            // Check if we're already on the thank you page
            global $wp;
            $current_order_id = isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
            
            // Only redirect if we're not already on the thank you page for this order
            if (!is_wc_endpoint_url('order-received') || $current_order_id !== $order_id) {
                wp_redirect($order->get_checkout_order_received_url());
                exit;
            }
        }
    }
}

/**
 * Enqueue scripts and styles for custom checkout
 */
function shoes_store_enqueue_custom_checkout_scripts() {
    // Font Awesome for icons
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
    
    // Custom checkout styles
    wp_enqueue_style(
        'custom-checkout-styles',
        get_template_directory_uri() . '/assets/css/custom-checkout.css',
        array(),
        '1.0.0'
    );
    
    // Custom checkout scripts
    wp_enqueue_script(
        'custom-checkout-scripts',
        get_template_directory_uri() . '/assets/js/custom-checkout.js',
        array('jquery'),
        '1.0.0',
        true
    );
}

/**
 * Save custom fields from Econt plugin
 */
function shoes_store_save_custom_checkout_fields($order_id) {
    // Check if we have custom checkout data in session
    $customer_data = WC()->session->get('custom_checkout_data');
    
    if (!empty($customer_data)) {
        // Update order meta with our custom fields
        foreach ($customer_data as $key => $value) {
            if (!empty($value)) {
                update_post_meta($order_id, '_customer_' . $key, $value);
            }
        }
    }
    
    // Get Econt shipping data from POST if available
    if (isset($_POST['econt_delivery'])) {
        $econt_data = wc_clean($_POST['econt_delivery']);
        
        if (is_array($econt_data)) {
            foreach ($econt_data as $key => $value) {
                if (!empty($value)) {
                    update_post_meta($order_id, '_econt_' . $key, $value);
                }
            }
        }
    }
}
add_action('woocommerce_checkout_update_order_meta', 'shoes_store_save_custom_checkout_fields');
add_action('woocommerce_thankyou', 'shoes_store_save_custom_checkout_fields');

/**
 * Display custom fields on order admin screen
 */
function shoes_store_display_custom_checkout_fields_in_admin($order) {
    echo '<h3>Детайли от поръчката</h3>';
    echo '<div class="order_data_column">';
    
    // Get custom checkout data
    $fields = array(
        '_customer_first_name' => 'Име',
        '_customer_last_name' => 'Фамилия',
        '_customer_phone' => 'Телефон',
        '_customer_email' => 'Имейл',
        '_customer_address' => 'Адрес',
        '_customer_city' => 'Град',
        '_customer_postcode' => 'Пощенски код',
        '_customer_order_notes' => 'Бележки към поръчката'
    );
    
    // Display custom fields
    foreach ($fields as $meta_key => $label) {
        $value = get_post_meta($order->get_id(), $meta_key, true);
        if (!empty($value)) {
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</p>';
        }
    }
    
    // Display Econt delivery data if available
    $econt_fields = array();
    
    // Get all post meta
    $post_meta = get_post_meta($order->get_id());
    
    // Filter for Econt fields
    foreach ($post_meta as $key => $values) {
        if (strpos($key, '_econt_') === 0) {
            $label = str_replace('_econt_', '', $key);
            $label = ucfirst(str_replace('_', ' ', $label));
            $econt_fields[$key] = $label;
        }
    }
    
    if (!empty($econt_fields)) {
        echo '<h4>Детайли за доставка с Econt</h4>';
        
        foreach ($econt_fields as $meta_key => $label) {
            $value = get_post_meta($order->get_id(), $meta_key, true);
            if (!empty($value)) {
                echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</p>';
            }
        }
    }
    
    echo '</div>';
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'shoes_store_display_custom_checkout_fields_in_admin');

/**
 * Redirect standard WooCommerce checkout to our custom checkout page
 */
function shoes_store_redirect_to_custom_checkout() {
    // Only redirect if we're on the checkout page and not on any WooCommerce endpoint
    // Specifically make sure we're not on the order-received endpoint
    if (is_checkout() && !is_wc_endpoint_url() && !is_wc_endpoint_url('order-received')) {
        // Get the custom checkout page ID
        $custom_checkout_page_id = get_option('shoes_store_custom_checkout_page_id');
        
        // If we have a custom checkout page ID
        if (!empty($custom_checkout_page_id)) {
            // Redirect to our custom checkout page
            wp_redirect(get_permalink($custom_checkout_page_id));
            exit;
        }
    }
}
add_action('template_redirect', 'shoes_store_redirect_to_custom_checkout', 5);

/**
 * Add a settings section to the Customizer for checkout options
 */
function shoes_store_add_checkout_customizer_section($wp_customize) {
    // Add checkout section
    $wp_customize->add_section('checkout_options', array(
        'title'    => __('Checkout Options', 'shoes-store'),
        'priority' => 35,
    ));
    
    // Get all pages for the dropdown
    $pages = get_pages();
    $page_options = array(
        '0' => __('Use Standard WooCommerce Checkout', 'shoes-store'),
    );
    
    foreach ($pages as $page) {
        $page_options[$page->ID] = $page->post_title;
    }
    
    // Add setting for custom checkout page
    $wp_customize->add_setting('shoes_store_custom_checkout_page_id', array(
        'default'           => '0',
        'sanitize_callback' => 'absint',
        'type'              => 'option',
    ));
    
    // Add control for custom checkout page
    $wp_customize->add_control('shoes_store_custom_checkout_page_id', array(
        'label'       => __('Custom Checkout Page', 'shoes-store'),
        'description' => __('Select a page that uses the Custom Checkout template. Select "Use Standard WooCommerce Checkout" to use the default WooCommerce checkout.', 'shoes-store'),
        'section'     => 'checkout_options',
        'type'        => 'select',
        'choices'     => $page_options,
    ));
}
add_action('customize_register', 'shoes_store_add_checkout_customizer_section');

/**
 * Remove product reviews completely
 */
function shoes_store_remove_reviews() {
    // Remove review tab from product tabs
    add_filter('woocommerce_product_tabs', function($tabs) {
        unset($tabs['reviews']);
        return $tabs;
    }, 98);
    
    // Remove review form
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    
    // Disable review form completely
    function shoes_store_remove_review_form() {
        return false;
    }
    add_filter('woocommerce_product_review_comment_form_args', 'shoes_store_remove_review_form');
    
    // Remove rating from product single page
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    
    // Close comments on products
    add_filter('comments_open', function($open, $post_id) {
        $post = get_post($post_id);
        if ($post->post_type == 'product') {
            $open = false;
        }
        return $open;
    }, 10, 2);
}
add_action('init', 'shoes_store_remove_reviews');

/**
 * Enqueue Econt calculator enhancement scripts and styles
 */
function shoes_store_enqueue_econt_calculator_enhancements() {
    // Only on checkout and cart pages
    if ((is_checkout() || is_cart()) && class_exists('Econt_Woo_Delivery')) {
        // Enqueue calculator enhancement styles
        wp_enqueue_style(
            'econt-calculator-enhancements',
            get_template_directory_uri() . '/assets/css/econt-enhancements.css',
            array(),
            '1.0.0'
        );
        
        // Enqueue calculator enhancement scripts
        wp_enqueue_script(
            'econt-calculator',
            get_template_directory_uri() . '/assets/js/econt-calculator.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'shoes_store_enqueue_econt_calculator_enhancements', 101);

/**
 * Disable order notes field to completely remove "Additional Information" section
 */
function shoes_store_disable_order_notes() {
    return 'no';
}
add_filter('woocommerce_enable_order_comments', 'shoes_store_disable_order_notes');

/**
 * Make sure the cart count is always accurate by checking session
 */
function shoes_store_ensure_cart_count_accuracy() {
    if (!class_exists('WooCommerce') || !WC()->cart) {
        return;
    }
    
    // Force cart calculation to ensure accurate count
    WC()->cart->calculate_totals();
    
    // Add JavaScript to verify and correct cart count on page load
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Get the actual cart count from the server
        function verifyCartCount() {
            if (typeof wc_add_to_cart_params === 'undefined') {
                return;
            }
            
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: 'get_cart_count',
                    security: woocommerce_params.fragment_nonce || ''
                },
                success: function(response) {
                    if (response && response.success) {
                        const count = parseInt(response.data.count) || 0;
                        const currentDisplayed = parseInt($('.cart-count').first().text()) || 0;
                        
                        // If displayed count doesn't match actual count, update all counters
                        if (count !== currentDisplayed) {
                            console.log('Correcting cart count from ' + currentDisplayed + ' to ' + count);
                            if (typeof updateAllCartCounters === 'function') {
                                updateAllCartCounters(count);
                            } else {
                                $('.cart-count').text(count);
                                $('.mobile-cart-count').text(count);
                            }
                        }
                    }
                }
            });
        }
        
        // Verify cart count on page load and after cart fragments are refreshed
        verifyCartCount();
        $(document.body).on('wc_fragments_refreshed', verifyCartCount);
        $(document.body).on('added_to_cart', verifyCartCount);
        $(document.body).on('removed_from_cart', verifyCartCount);
    });
    </script>
    <?php
}
add_action('wp_footer', 'shoes_store_ensure_cart_count_accuracy');

/**
 * AJAX handler for getting current cart count
 */
function shoes_store_get_cart_count() {
    // Check nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'wc_fragment_refresh')) {
        wp_send_json_error(['message' => 'Invalid security token']);
        die();
    }
    
    // Make sure WooCommerce is available
    if (!class_exists('WooCommerce') || !WC()->cart) {
        wp_send_json_error(['message' => 'WooCommerce not available']);
        die();
    }
    
    // Force cart calculation to ensure accurate count
    WC()->cart->calculate_totals();
    
    // Get the current cart count
    $cart_count = WC()->cart->get_cart_contents_count();
    
    wp_send_json_success([
        'count' => $cart_count,
        'cart_hash' => WC()->cart->get_cart_hash(),
        'cart_total' => WC()->cart->get_cart_subtotal()
    ]);
    
    die();
}
add_action('wp_ajax_get_cart_count', 'shoes_store_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'shoes_store_get_cart_count');

/**
 * Add cart fragments to update cart count via AJAX
 */
function shoes_store_cart_fragments($fragments) {
    if (!class_exists('WooCommerce') || !WC()->cart) {
        return $fragments;
    }
    
    // Force cart calculation to ensure accurate count
    WC()->cart->calculate_totals();
    
    // Get the current cart count
    $cart_count = WC()->cart->get_cart_contents_count();
    
    // Create fragment for cart count element
    $fragments['.cart-count'] = '<span class="cart-count' . ($cart_count > 0 ? ' has-items' : '') . '">' . $cart_count . '</span>';
    $fragments['.mobile-cart-count'] = '<span class="mobile-cart-count' . ($cart_count > 0 ? ' has-items' : '') . '">' . $cart_count . '</span>';
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'shoes_store_cart_fragments');

/**
 * Add custom CSS for the order received page
 */
function shoes_store_order_received_styles() {
    if (is_wc_endpoint_url('order-received')) {
        ?>
        <style>
            /* Order Received Page Styling */
            .woocommerce-order {
                max-width: 1000px;
                margin: 0 auto;
                padding: 40px 20px;
                font-family: inherit;
            }

            /* Success Icon */
            .order-icon-container {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 30px;
            }

            .order-icon-container.success {
                background-color: #e74c3c;
                color: white;
                box-shadow: 0 10px 20px rgba(231, 76, 60, 0.2);
            }

            .order-icon-container.failed {
                background-color: #e74c3c;
                color: white;
                box-shadow: 0 10px 20px rgba(231, 76, 60, 0.2);
            }

            .order-icon-container.not-found {
                background-color: #f39c12;
                color: white;
                box-shadow: 0 10px 20px rgba(243, 156, 18, 0.2);
            }

            .order-icon-container i {
                font-size: 32px;
            }

            /* Success Message */
            .order-success-title,
            .order-failed-title,
            .order-not-found-title {
                text-align: center;
                font-size: 28px;
                margin-bottom: 30px;
                color: #2c3e50;
                font-weight: 600;
            }

            /* Order Details Container */
            .order-details-container {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
                padding: 30px;
                margin-bottom: 40px;
            }

            /* Order Details Table */
            .order-details-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .order-detail-row {
                display: flex;
                justify-content: space-between;
                padding: 15px 0;
                border-bottom: 1px solid #f1f1f1;
            }

            .order-detail-row:last-child {
                border-bottom: none;
            }

            .order-detail-label {
                font-weight: 600;
                color: #555;
            }

            .order-detail-value {
                color: #333;
                font-weight: 500;
            }

            /* Order Items Section */
            .order-details-section {
                margin-bottom: 40px;
            }

            .order-details-section h3,
            .order-addresses-section h3,
            .order-note-container h3 {
                font-size: 20px;
                margin-bottom: 20px;
                color: #2c3e50;
                font-weight: 600;
                position: relative;
                padding-bottom: 10px;
            }

            .order-details-section h3:after,
            .order-addresses-section h3:after,
            .order-note-container h3:after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 50px;
                height: 3px;
                background-color: #e74c3c;
            }

            /* Order Items Table */
            .order-items-container {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
                padding: 20px;
                overflow-x: auto;
            }

            .order-items-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .order-items-table thead th {
                background-color: #f8f8f8;
                padding: 15px;
                text-align: left;
                font-weight: 600;
                color: #555;
                text-transform: uppercase;
                font-size: 14px;
                letter-spacing: 0.5px;
            }

            .order-items-table thead th:first-child {
                border-top-left-radius: 8px;
            }

            .order-items-table thead th:last-child {
                border-top-right-radius: 8px;
                text-align: right;
            }

            .order-items-table tbody td {
                padding: 15px;
                border-bottom: 1px solid #f1f1f1;
                color: #333;
            }

            .order-items-table tbody tr:last-child td {
                border-bottom: none;
            }

            .order-items-table .product-total {
                text-align: right;
                font-weight: 500;
            }

            .order-items-table tfoot th {
                padding: 15px;
                text-align: left;
                font-weight: 600;
                color: #555;
            }

            .order-items-table tfoot td {
                padding: 15px;
                text-align: right;
                font-weight: 500;
            }

            .order-items-table tfoot tr:last-child th,
            .order-items-table tfoot tr:last-child td {
                color: #e74c3c;
                font-weight: 700;
                font-size: 18px;
            }

            /* Addresses Section */
            .order-addresses-section {
                margin-bottom: 40px;
            }

            .addresses-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }

            @media (max-width: 768px) {
                .addresses-container {
                    grid-template-columns: 1fr;
                }
            }

            .billing-address,
            .shipping-address {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
                padding: 25px;
            }

            .billing-address h3,
            .shipping-address h3 {
                margin-top: 0;
            }

            address {
                font-style: normal;
                line-height: 1.6;
                color: #555;
            }

            .address-phone,
            .address-email {
                margin: 10px 0 0;
            }

            /* Order Note */
            .order-note-container {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
                padding: 25px;
                margin-bottom: 40px;
            }

            .order-note-container p {
                color: #555;
                line-height: 1.6;
            }

            /* Action Buttons */
            .order-actions-container {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin-top: 40px;
            }

            .button {
                display: inline-block;
                padding: 12px 25px;
                border-radius: 30px;
                text-decoration: none;
                font-weight: 600;
                font-size: 15px;
                transition: all 0.3s ease;
                text-align: center;
            }

            .continue-shopping {
                background-color: #e74c3c;
                color: white;
                box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2);
            }

            .continue-shopping:hover {
                background-color: #c0392b;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
            }

            .view-account {
                background-color: #f8f8f8;
                color: #555;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            }

            .view-account:hover {
                background-color: #eee;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            }

            /* Failed Order */
            .order-failed-container {
                text-align: center;
                padding: 30px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            }

            .order-failed-message {
                color: #555;
                margin-bottom: 30px;
            }

            .order-failed-container .button.pay {
                background-color: #e74c3c;
                color: white;
            }

            .order-failed-container .button.account {
                background-color: #f8f8f8;
                color: #555;
            }

            /* Not Found Order */
            .order-not-found-container {
                text-align: center;
                padding: 30px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            }

            .order-not-found-message {
                color: #555;
                margin-bottom: 30px;
            }

            .order-not-found-container .button.return-shop {
                background-color: #e74c3c;
                color: white;
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .woocommerce-order {
                    padding: 20px 10px;
                }

                .order-details-container,
                .order-items-container,
                .billing-address,
                .shipping-address,
                .order-note-container {
                    padding: 15px;
                }

                .order-items-table thead th,
                .order-items-table tbody td,
                .order-items-table tfoot th,
                .order-items-table tfoot td {
                    padding: 10px;
                    font-size: 14px;
                }

                .order-success-title,
                .order-failed-title,
                .order-not-found-title {
                    font-size: 24px;
                }

                .order-actions-container {
                    flex-direction: column;
                }

                .button {
                    width: 100%;
                    margin-bottom: 10px;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'shoes_store_order_received_styles');

/**
 * Note: Font Awesome is already enqueued earlier in the file
 */

/**
 * Debug function to check if order-received endpoint is working properly
 */
function shoes_store_debug_order_received() {
    // Check if we're on the order-received endpoint
    if (is_wc_endpoint_url('order-received')) {
        // Get the order ID from URL
        global $wp;
        $order_id = isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
        
        if ($order_id > 0) {
            // Log to error log for debugging
            error_log('Order received endpoint accessed for order ID: ' . $order_id);
            
            // Make sure we're not redirecting away from the thank you page
            remove_action('template_redirect', 'shoes_store_redirect_to_custom_checkout', 5);
            
            // Force the endpoint to be recognized
            add_filter('woocommerce_is_endpoint', function($is_endpoint, $endpoint) {
                if ($endpoint === 'order-received') {
                    return true;
                }
                return $is_endpoint;
            }, 10, 2);
        }
    }
}
add_action('template_redirect', 'shoes_store_debug_order_received', 1);

/**
 * Make sure the order-received endpoint is properly registered
 */
function shoes_store_add_order_received_endpoint() {
    add_rewrite_endpoint('order-received', EP_ROOT | EP_PAGES);
}
add_action('init', 'shoes_store_add_order_received_endpoint');

/**
 * Ensure the thank you page loads properly by preserving the order data
 */
function shoes_store_preserve_order_data() {
    if (is_wc_endpoint_url('order-received')) {
        // Get the order ID from URL
        global $wp;
        $order_id = isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
        
        if ($order_id > 0) {
            // Make sure the order exists and is accessible
            $order = wc_get_order($order_id);
            if ($order) {
                // Set a session flag to indicate we're on a valid thank you page
                WC()->session->set('on_valid_thank_you_page', true);
                
                // Store the order ID in the session for reference
                WC()->session->set('current_thank_you_order_id', $order_id);
            }
        }
    } else {
        // Clear the flag when not on the thank you page
        WC()->session->set('on_valid_thank_you_page', false);
        WC()->session->set('current_thank_you_order_id', null);
    }
}
add_action('template_redirect', 'shoes_store_preserve_order_data', 2);

/**
 * Force WooCommerce to load the thank you template when needed
 */
function shoes_store_force_thankyou_template($template, $template_name, $template_path) {
    if (is_wc_endpoint_url('order-received') && $template_name === 'checkout/thankyou.php') {
        // Get the order ID from URL
        global $wp;
        $order_id = isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
        
        if ($order_id > 0) {
            // Check if our custom template exists
            $theme_template = get_stylesheet_directory() . '/woocommerce/checkout/thankyou.php';
            if (file_exists($theme_template)) {
                return $theme_template;
            }
        }
    }
    
    return $template;
}
add_filter('woocommerce_locate_template', 'shoes_store_force_thankyou_template', 10, 3);

/**
 * Direct template override for order-received endpoint
 */
function shoes_store_override_empty_cart_on_thankyou() {
    // Check if we're on the order-received endpoint by URL
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout/order-received/') !== false) {
        global $wp;
        
        // Get order ID and key from URL
        $order_id = isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
        $order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';
        
        if ($order_id > 0) {
            // Try to get the order
            $order = wc_get_order($order_id);
            
            if ($order && $order->get_order_key() === $order_key) {
                // This is a valid order - force the template to load
                add_filter('template_include', function($template) {
                    // Set WooCommerce endpoint
                    WC()->query->is_wc_endpoint_url = true;
                    WC()->query->endpoint_vars['order-received'] = true;
                    
                    // Load our thank you template
                    $thankyou_template = get_stylesheet_directory() . '/woocommerce/checkout/thankyou.php';
                    
                    if (file_exists($thankyou_template)) {
                        // Include WooCommerce template functions
                        if (!function_exists('wc_get_template_part')) {
                            include_once WC()->plugin_path() . '/includes/wc-template-functions.php';
                        }
                        
                        // Set up global variables
                        global $order;
                        
                        // Output the template
                        include($thankyou_template);
                        exit;
                    }
                    
                    return $template;
                }, 999);
            }
        }
    }
}
add_action('template_redirect', 'shoes_store_override_empty_cart_on_thankyou', 5);

/**
 * Handle the order-received URL pattern directly
 */
function shoes_store_handle_order_received_url() {
    // Check if we're on the order-received endpoint by URL
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout/order-received/') !== false) {
        // Extract order ID from URL
        preg_match('/\/checkout\/order-received\/(\d+)/', $_SERVER['REQUEST_URI'], $matches);
        $order_id = isset($matches[1]) ? absint($matches[1]) : 0;
        
        if ($order_id > 0) {
            // Get order key from query string
            $order_key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';
            
            // Set up the global $wp variable
            global $wp;
            $wp->query_vars['order-received'] = $order_id;
            
            // Tell WooCommerce we're on the order-received endpoint
            if (class_exists('WC_Query') && isset(WC()->query)) {
                WC()->query->is_order_received = true;
            }
            
            // Force the thankyou template to be loaded
            add_filter('template_include', function($template) {
                // Load the page-order-received.php template if it exists
                $order_received_template = get_stylesheet_directory() . '/page-order-received.php';
                if (file_exists($order_received_template)) {
                    return $order_received_template;
                }
                return $template;
            }, 100);
            
            // Make sure WooCommerce doesn't redirect away
            remove_all_actions('template_redirect');
            add_action('template_redirect', function() {
                // Re-add only essential template_redirect actions
                if (class_exists('WC_Template_Loader')) {
                    WC_Template_Loader::unsupported_theme_init();
                }
            }, 10);
        }
    }
}
add_action('template_redirect', 'shoes_store_handle_order_received_url', 1);

/**
 * Fix encoding issues on the thank you page
 */
function shoes_store_fix_thankyou_encoding() {
    // Check if we're on the order-received endpoint by URL
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout/order-received/') !== false) {
        // Make sure PHP files are processed correctly
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }
    }
}
add_action('init', 'shoes_store_fix_thankyou_encoding', 1);

/**
 * Add custom CSS for the thank you page
 */
function shoes_store_thankyou_styles() {
    // Only add these styles on the order-received page
    if (is_wc_endpoint_url('order-received') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout/order-received/') !== false)) {
        ?>
        <style>
            .woocommerce-order {
                max-width: 1000px;
                margin: 0 auto;
                padding: 30px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            
            .order-icon-container {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .order-icon-container i {
                font-size: 48px;
                padding: 20px;
                border-radius: 50%;
                margin-bottom: 20px;
            }
            
            .order-icon-container.success i {
                color: white;
                background-color: #e74c3c;
            }
            
            .order-icon-container.failed i {
                color: white;
                background-color: #e74c3c;
            }
            
            .order-icon-container.not-found i {
                color: white;
                background-color: #e74c3c;
            }
            
            .order-success-title,
            .order-failed-title,
            .order-not-found-title {
                font-size: 24px;
                text-align: center;
                margin-bottom: 20px;
                color: #333;
            }
            
            .order-details-container {
                background-color: #f8f8f8;
                border-radius: 5px;
                padding: 20px;
                margin: 20px 0;
            }
            
            .order-details-table {
                width: 100%;
            }
            
            .order-detail-row {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border-bottom: 1px solid #eee;
            }
            
            .order-detail-row:last-child {
                border-bottom: none;
            }
            
            .order-detail-label {
                font-weight: bold;
                color: #555;
            }
            
            .order-note-container {
                background-color: #f8f8f8;
                border-radius: 5px;
                padding: 20px;
                margin: 20px 0;
            }
            
            .order-note-container h3 {
                margin-top: 0;
                color: #333;
            }
            
            .order-details-section,
            .order-addresses-section {
                margin: 30px 0;
            }
            
            .order-details-section h3,
            .order-addresses-section h3 {
                border-bottom: 2px solid #e74c3c;
                padding-bottom: 10px;
                color: #333;
            }
            
            .order-items-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            
            .order-items-table th,
            .order-items-table td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #eee;
            }
            
            .order-items-table th {
                background-color: #f8f8f8;
                font-weight: bold;
                color: #555;
            }
            
            .addresses-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }
            
            .billing-address,
            .shipping-address {
                flex: 1;
                min-width: 250px;
                background-color: #f8f8f8;
                border-radius: 5px;
                padding: 20px;
            }
            
            .billing-address h3,
            .shipping-address h3 {
                margin-top: 0;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }
            
            address {
                font-style: normal;
                line-height: 1.6;
            }
            
            .address-phone,
            .address-email {
                margin: 5px 0;
            }
            
            .order-actions-container {
                margin-top: 30px;
                text-align: center;
            }
            
            .order-actions-container .button {
                display: inline-block;
                padding: 12px 24px;
                margin: 0 10px;
                background-color: #e74c3c;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                transition: background-color 0.3s;
            }
            
            .order-actions-container .button:hover {
                background-color: #c0392b;
            }
            
            .order-failed-message,
            .order-not-found-message {
                text-align: center;
                margin-bottom: 20px;
                color: #555;
            }
            
            .order-actions {
                text-align: center;
            }
            
            .order-actions .button {
                display: inline-block;
                padding: 12px 24px;
                margin: 0 10px;
                background-color: #e74c3c;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                transition: background-color 0.3s;
            }
            
            .order-actions .button:hover {
                background-color: #c0392b;
            }
            
            @media (max-width: 768px) {
                .woocommerce-order {
                    padding: 15px;
                }
                
                .addresses-container {
                    flex-direction: column;
                }
                
                .order-items-table {
                    font-size: 14px;
                }
                
                .order-actions-container .button {
                    display: block;
                    margin: 10px auto;
                    width: 80%;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'shoes_store_thankyou_styles');

/**
 * Enqueue Font Awesome for the thank you page and other pages
 */
function shoes_store_enqueue_font_awesome() {
    // Always load Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
}
add_action('wp_enqueue_scripts', 'shoes_store_enqueue_font_awesome', 5);

/**
 * Force the proper template for the order-received endpoint
 * NOTE: This function has been commented out to avoid duplication with the function at line 2865
 */
// function shoes_store_force_thankyou_template($template) {
//     if (is_wc_endpoint_url('order-received') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout/order-received/') !== false)) {
//         // Get the order ID from the URL
//         $order_id = isset($_GET['order']) ? wc_clean($_GET['order']) : 0;
//         
//         // If we have an order ID, make sure WooCommerce knows about it
//         if ($order_id) {
//             global $wp;
//             if (!isset($wp->query_vars['order-received'])) {
//                 $wp->query_vars['order-received'] = $order_id;
//             }
//         }
//         
//         // Try to load the template from the theme
//         $new_template = locate_template(array('woocommerce/checkout/thankyou.php'));
//         
//         if ($new_template) {
//             return $new_template;
//         }
//     }
//     
//     return $template;
// }
// add_filter('template_include', 'shoes_store_force_thankyou_template', 99);

/**
 * Make sure WooCommerce knows we're on the order-received endpoint
 */
function shoes_store_set_order_received_endpoint() {
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout/order-received/') !== false) {
        // Extract order ID from URL
        preg_match('/\/checkout\/order-received\/(\d+)/', $_SERVER['REQUEST_URI'], $matches);
        $order_id = isset($matches[1]) ? $matches[1] : 0;
        
        if ($order_id) {
            // Set the endpoint
            global $wp;
            $wp->query_vars['order-received'] = $order_id;
            
            // Set the endpoint flag
            WC()->query->is_order_received = true;
        }
    }
}
add_action('wp', 'shoes_store_set_order_received_endpoint', 5);
?> 