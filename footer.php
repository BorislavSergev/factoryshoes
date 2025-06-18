<?php
/**
 * The template for displaying the footer
 *
 * @package Shoes_Store_Theme
 */
?>

<footer class="site-footer">
    <div class="container">
        <div class="row">
            <!-- Logo and Description Column -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 text-center text-lg-start">
                <div class="footer-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                        <i class="fas fa-shoe-prints"></i>
                        <?php echo esc_html(get_theme_mod('site_title', 'factoryshoes')); ?>
                    </a>
                </div>
                <div class="footer-description mt-3">
                    <p><?php echo get_bloginfo('description'); ?></p>
                </div>
            </div>

            <!-- Links Column -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h5 class="footer-heading"><?php _e('Бързи връзки', 'shoes-store'); ?></h5>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Начало', 'shoes-store'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/cart')); ?>"><?php _e('Количка', 'shoes-store'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#about')); ?>"><?php _e('За нас', 'shoes-store'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#products')); ?>"><?php _e('Обувки', 'shoes-store'); ?></a></li>
                </ul>
            </div>

            <!-- Contacts Column -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h5 class="footer-heading"><?php _e('Контакти', 'shoes-store'); ?></h5>
                <div class="footer-contacts">
                    <!-- Address -->
                    <div class="contact-item d-flex mb-3 justify-content-center justify-content-lg-start">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h6><?php _e('Адрес', 'shoes-store'); ?></h6>
                            <p><?php _e('Русе България', 'shoes-store'); ?></p>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="contact-item d-flex mb-3 justify-content-center justify-content-lg-start">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h6><?php _e('Телефон', 'shoes-store'); ?></h6>
                            <p>+359 87 7555149</p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="contact-item d-flex justify-content-center justify-content-lg-start">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h6><?php _e('Имейл', 'shoes-store'); ?></h6>
                            <p>support@factoryshoes.bg</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Column -->
            <div class="col-lg-3 col-md-6 text-center text-lg-start">
                <h5 class="footer-heading"><?php _e('Методи на плащане', 'shoes-store'); ?></h5>
                <div class="payment-methods">
                    <div class="payment-method mx-auto mx-lg-0">
                        <i class="fas fa-money-bill-wave"></i>
                        <span><?php _e('Само в брой', 'shoes-store'); ?></span>
                    </div>
                    <p class="payment-note mt-2"><?php _e('Приемаме само плащане в брой при доставка.', 'shoes-store'); ?></p>
                </div>
            </div>
        </div>
        
        <hr class="footer-divider">
        
        <div class="footer-bottom text-center">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Всички права запазени.', 'shoes-store'); ?></p>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
