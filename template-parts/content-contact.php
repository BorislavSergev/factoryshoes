<?php
/**
 * Template part for displaying the Contact Information section
 *
 * @package Shoes_Store_Theme
 */

// Contact information array
$contact_info = [
    [
        'icon' => 'fa-map-marker-alt',
        'title' => __('Адрес', 'shoes-store'),
        'content' => __('Русе България', 'shoes-store')
    ],
    [
        'icon' => 'fa-phone-alt',
        'title' => __('Телефон', 'shoes-store'),
        'content' => '+359 87 7555149'
    ],
    [
        'icon' => 'fa-envelope',
        'title' => __('Имейл', 'shoes-store'),
        'content' => 'support@factoryshoes.bg'
    ],
];

// Social media array
$social_media = [
    [
        'icon' => 'fa-facebook-f',
        'url' => '#',
        'name' => 'Facebook'
    ],
    [
        'icon' => 'fa-twitter',
        'url' => '#',
        'name' => 'Twitter'
    ],
    [
        'icon' => 'fa-instagram',
        'url' => '#',
        'name' => 'Instagram'
    ],
    [
        'icon' => 'fa-youtube',
        'url' => '#',
        'name' => 'YouTube'
    ]
];
?>

<section id="contact" class="contact-section">
    <div class="container">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-headset"></i>
                <span><?php _e('Свържете се с нас', 'shoes-store'); ?></span>
            </div>
            <h2 class="section-title"><?php _e('Контактна Информация', 'shoes-store'); ?></h2>
            <p class="section-subtitle"><?php _e('Ние сме на разположение да отговорим на всички ваши въпроси и да ви помогнем', 'shoes-store'); ?></p>
        </div>
        
        <div class="contact-cards">
            <?php foreach ($contact_info as $info) : ?>
                <div class="contact-card">
                    <div class="card-icon">
                        <i class="fas <?php echo esc_attr($info['icon']); ?>"></i>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?php echo esc_html($info['title']); ?></h3>
                        <div class="card-text"><?php echo wp_kses_post($info['content']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="contact-social">
            <h3 class="social-title"><?php _e('Последвайте ни в социалните мрежи', 'shoes-store'); ?></h3>
            <div class="social-icons">
                <?php foreach ($social_media as $social) : ?>
                    <a href="<?php echo esc_url($social['url']); ?>" class="social-icon" aria-label="<?php echo esc_attr($social['name']); ?>" target="_blank" rel="noopener">
                        <i class="fab <?php echo esc_attr($social['icon']); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section> 