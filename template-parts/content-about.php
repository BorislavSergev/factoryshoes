<?php
/**
 * Template part for displaying the About Us section
 *
 * @package Shoes_Store_Theme
 */

// Get customizer settings
$about_title = get_theme_mod('about_title', __('За Нашия Магазин', 'shoes-store'));
$about_description = get_theme_mod('about_description', __('Във Factory Shoes ние не сме просто онлайн магазин; ние сме общност за истинските любители на маратонките. Ето защо се отличаваме:', 'shoes-store'));

// Features
$features = [
    [
        'icon' => 'fa-certificate',
        'title' => __('Гарантирана автентичност', 'shoes-store'),
        'description' => __('Няма място за съмнение. Всеки чифт Nike, който предлагаме, е преминал строга проверка и е гарантирано оригинален. Пазарувай с пълно спокойствие.', 'shoes-store')
    ],
    [
        'icon' => 'fa-star',
        'title' => __('Ексклузивни колекции', 'shoes-store'),
        'description' => __('Открийте най-новите модели, култови класики и трудно откриваеми предложения. Бъдете винаги една крачка напред със селекция, която улавя погледите.', 'shoes-store')
    ],
    [
        'icon' => 'fa-tag',
        'title' => __('Конкурентни цени', 'shoes-store'),
        'description' => __('Вярваме, че качеството и стилът трябва да бъдат достъпни. Предлагаме конкурентни цени, за да можеш лесно да добавиш мечтания чифт Nike към колекцията си.', 'shoes-store')
    ],
    [
        'icon' => 'fa-truck-fast',
        'title' => __('Бърза и сигурна доставка', 'shoes-store'),
        'description' => __('Няма нужда да чакаш. Обработваме поръчките бързо и доставяме сигурно, за да можеш да обуеш новите си Nike възможно най-скоро.', 'shoes-store')
    ]
];

// Store statistics
$years_experience = 5;
?>

<section id="about" class="modern-about">
    <div class="container">
        <div class="about-wrapper">
            <div class="about-content">
                <div class="section-badge">
                    <i class="fas fa-award"></i>
                    <span><?php _e('Опит и Традиция', 'shoes-store'); ?></span>
                </div>
                
                <h2 class="about-title"><?php echo esc_html($about_title); ?></h2>
                <p class="about-description"><?php echo esc_html($about_description); ?></p>
                
                <div class="about-features">
                    <?php foreach ($features as $feature) : ?>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas <?php echo esc_attr($feature['icon']); ?>"></i>
                            </div>
                            <div class="feature-info">
                                <h3><?php echo esc_html($feature['title']); ?></h3>
                                <p><?php echo esc_html($feature['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('shop')) : '#'; ?>" class="about-cta-button">
                    <?php _e('Разгледайте Колекцията', 'shoes-store'); ?> 
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="about-image">
                <div class="image-container">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/store.jpg'); ?>" alt="<?php _e('За Нас', 'shoes-store'); ?>" onerror="this.src='https://via.placeholder.com/600x400?text=<?php _e('За Нас', 'shoes-store'); ?>'">
                    <div class="shape-decoration"></div>
                </div>
                
                <div class="experience-badge">
                    <span class="years"><?php echo esc_html($years_experience); ?></span>
                    <span class="text"><?php _e('Години Опит', 'shoes-store'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section> 