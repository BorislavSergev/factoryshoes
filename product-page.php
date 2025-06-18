<?php get_header(); ?>

<div class="container product-container py-5">
    <!-- Breadcrumbs -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Начало</a></li>
                    <li class="breadcrumb-item"><a href="#">Обувки</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Product Images - Left Column -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="product-gallery position-relative">
                <!-- Main Product Image -->
                <div class="main-image-container mb-4">
                    <div class="product-badges">
                        <?php if (is_on_sale()) : ?>
                            <span class="sale-badge">-<?php echo get_savings_percentage(); ?>%</span>
                        <?php endif; ?>
                        <button class="wishlist-toggle" aria-label="Добави в любими">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="main-product-image">
                        <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                    </div>
                    <button class="image-nav prev-image" aria-label="Предишна снимка">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="image-nav next-image" aria-label="Следваща снимка">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                
                <!-- Thumbnail Gallery -->
                <div class="thumbnail-gallery">
                    <?php
                    // Assuming you have a function to get product gallery images
                    $gallery_images = get_product_gallery_images();
                    if ($gallery_images) :
                        foreach ($gallery_images as $index => $image) : ?>
                            <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo esc_url($image); ?>" alt="Снимка на продукта <?php echo $index + 1; ?>">
                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Product Details - Right Column -->
        <div class="col-lg-6">
            <div class="product-info-card">
                <!-- Product Brand & Title -->
                <div class="product-header mb-3">
                    <span class="product-brand badge bg-primary mb-2">Nike</span>
                    <h1 class="product-title"><?php the_title(); ?></h1>
                    
                    <!-- Product Rating -->
                    <div class="product-rating d-flex align-items-center">
                        <div class="stars me-2">
                            <div class="star-rating">
                                <?php echo get_star_rating(); ?>
                            </div>
                        </div>
                        <span class="rating-text">
                            <?php echo get_rating_count(); ?> отзива
                        </span>
                    </div>
                </div>
                
                <!-- Price Section -->
                <div class="price-section mb-4">
                    <div class="current-price d-flex align-items-center mb-2">
                        <?php if (is_on_sale()) : ?>
                            <span class="sale-price"><?php echo get_sale_price(); ?></span>
                            <span class="regular-price-crossed"><?php echo get_regular_price(); ?></span>
                        <?php else : ?>
                            <span class="regular-price-main"><?php echo get_regular_price(); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (is_on_sale()) : ?>
                        <span class="savings-info">
                            Спестявате: <?php echo get_savings_amount(); ?> (<?php echo get_savings_percentage(); ?>%)
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Product Description -->
                <div class="product-short-description mb-4">
                    <p><?php echo get_the_excerpt(); ?></p>
                </div>
                
                <!-- Variations -->
                <div class="product-variations mb-4">
                    <!-- Size Selector -->
                    <div class="form-group mb-4">
                        <label class="form-label">Изберете размер <span class="text-danger">*</span></label>
                        <div class="size-selector required">
                            <?php
                            // EU shoe sizes
                            $eu_sizes = array('35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46');
                            foreach ($eu_sizes as $size) : ?>
                                <div class="size-option" data-size="<?php echo esc_attr($size); ?>">
                                    <?php echo esc_html($size); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="validation-message" id="size-validation-message">Моля, изберете размер</div>
                    </div>
                    
                    <!-- Color Selector if needed -->
                    <div class="form-group mb-4">
                        <label class="form-label">Изберете цвят <span class="text-danger">*</span></label>
                        <div class="color-selector">
                            <div class="color-option black active" data-color="черен" style="background-color: #000;"></div>
                            <div class="color-option red" data-color="червен" style="background-color: #e74c3c;"></div>
                            <div class="color-option blue" data-color="син" style="background-color: #3182ce;"></div>
                            <div class="color-option green" data-color="зелен" style="background-color: #38a169;"></div>
                        </div>
                        <div class="validation-message" id="color-validation-message">Моля, изберете цвят</div>
                    </div>
                </div>
                
                <!-- Quantity and Actions -->
                <div class="product-actions mb-4">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Количество</label>
                            <div class="quantity-controls">
                                <button class="qty-btn decrease" aria-label="Намали количеството">-</button>
                                <input type="number" class="qty-input" value="1" min="1" max="99">
                                <button class="qty-btn increase" aria-label="Увеличи количеството">+</button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <button class="btn btn-primary btn-add-to-cart w-100">
                                <i class="bi bi-cart-plus"></i> Добави в кошницата
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product Features -->
                <div class="product-features">
                    <div class="feature-item">
                        <i class="bi bi-truck"></i>
                        <div class="feature-content">
                            <strong></strong>
                            <span>За поръчки над 250 лв.</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <div class="feature-content">
                            <strong>Гаранция за Качество</strong>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-shield-check"></i>
                        <div class="feature-content">
                            <strong>Гаранция</strong>
                            <span>1 година гаранция</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="product-tabs">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Описание</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Спецификации</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Отзиви</button>
                    </li>
                </ul>
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="product-description">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                        <div class="product-specifications">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Марка</th>
                                        <td>Nike</td>
                                    </tr>
                                    <tr>
                                        <th>Модел</th>
                                        <td>Air Max</td>
                                    </tr>
                                    <tr>
                                        <th>Материал</th>
                                        <td>Текстил, Синтетика</td>
                                    </tr>
                                    <tr>
                                        <th>Сезон</th>
                                        <td>Всесезонни</td>
                                    </tr>
                                    <tr>
                                        <th>Тегло</th>
                                        <td>340g</td>
                                    </tr>
                                    <tr>
                                        <th>Произход</th>
                                        <td>Виетнам</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="product-reviews">
                            <?php comments_template(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Препоръчани Продукти Section -->
    <section class="recommended-products mt-5 pt-4">
        <div class="section-header">
            <h2 class="section-title">Препоръчани Продукти</h2>
        </div>
        
        <div class="row">
            <?php
            // Assuming you have a function to get featured products
            $featured_products = get_featured_products(4);
            if ($featured_products) :
                foreach ($featured_products as $product) : ?>
                    <div class="col-6 col-md-3 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo esc_url($product['image']); ?>" class="img-fluid" alt="<?php echo esc_attr($product['title']); ?>">
                                <?php if (isset($product['sale']) && $product['sale']) : ?>
                                    <span class="product-badge sale">-<?php echo esc_html($product['discount']); ?>%</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="<?php echo esc_url($product['url']); ?>"><?php echo esc_html($product['title']); ?></a>
                                </h3>
                                <div class="product-price">
                                    <?php if (isset($product['sale_price'])) : ?>
                                        <span class="current-price"><?php echo esc_html($product['sale_price']); ?> лв.</span>
                                        <span class="old-price"><?php echo esc_html($product['regular_price']); ?> лв.</span>
                                    <?php else : ?>
                                        <span class="current-price"><?php echo esc_html($product['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-actions-buttons">
                                    <a href="<?php echo esc_url($product['url']); ?>" class="btn btn-view">Детайли</a>
                                    <button class="btn btn-add-cart">
                                        <i class="bi bi-cart-plus"></i> Купи
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary rounded-pill">Виж всички <i class="bi bi-arrow-right"></i></a>
        </div>
    </section>
</div>

<?php get_footer(); ?> 