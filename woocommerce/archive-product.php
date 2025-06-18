<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
// Remove breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
do_action('woocommerce_before_main_content');
?>

<div class="modern-shop-container">
    <div class="container py-5">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="shop-sidebar">
                    <div class="filter-section">
                        <h3 class="filter-title">Филтър продукти</h3>
                        
                        <?php if (is_active_sidebar('shop-sidebar')) : ?>
                            <div class="shop-widgets">
                                <?php dynamic_sidebar('shop-sidebar'); ?>
                            </div>
                        <?php else : ?>
                            <!-- Fallback filters if no sidebar widgets are set -->
                            <div class="filter-group">
                                <h4>Категории</h4>
                                <ul class="filter-list">
                                    <?php 
                                    $terms = get_terms([
                                        'taxonomy' => 'product_cat',
                                        'hide_empty' => true,
                                    ]);
                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        foreach ($terms as $term) {
                                            echo '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            
                            <div class="filter-group">
                                <h4>Ценови диапазон</h4>
                                <div class="price-filter">
                                    <div class="price-inputs">
                                        <input type="text" id="min-price" placeholder="Мин. цена">
                                        <span>-</span>
                                        <input type="text" id="max-price" placeholder="Макс. цена">
                                    </div>
                                    <button class="btn btn-sm btn-filter" id="filter-price">Филтрирай</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Products -->
            <div class="col-lg-9">
                <div class="shop-products-area">
                    <!-- Background shapes -->
                    <div class="background-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                    </div>
                    
                    <!-- Shop header -->
                    <div class="products-header">
                        <?php if (is_product_category()): ?>
                        <div class="section-badge">
                            <i class="fas fa-store"></i>
                            <span><?php echo single_cat_title('', false); ?></span>
                        </div>
                        <h2 class="section-title">
                            <?php echo esc_html__('Разгледайте нашите', 'shoes-store') . ' ' . single_cat_title('', false); ?>
                        </h2>
                        <?php else: ?>
                        <h2 class="section-title">
                            <?php echo esc_html__('Разгледайте всички продукти', 'shoes-store'); ?>
                        </h2>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    // Remove the shop controls section that displays the result count and ordering dropdown
                    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
                    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
                    
                    // Custom hook for notices only
                    add_action('custom_woocommerce_notices', 'woocommerce_output_all_notices', 10);
                    
                    // Call only the notices hook
                    do_action('custom_woocommerce_notices');
                    
                    // Add shop controls with wrapper
                    woocommerce_result_count();
                    woocommerce_catalog_ordering();
                    echo '</div>';
                    ?>

                    <?php
                    if (woocommerce_product_loop()) {
                        /**
                         * Remove default opening/closing of product elements
                         */
                        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
                        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
                        
                        /**
                         * Start the product loop
                         */
                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();

                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            }
                        }

                        woocommerce_product_loop_end();

                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action('woocommerce_after_shop_loop');
                    } else {
                        /**
                         * Hook: woocommerce_no_products_found.
                         *
                         * @hooked wc_no_products_found - 10
                         */
                        do_action('woocommerce_no_products_found');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Shop Styles */
.modern-shop-container {
    background-color: #f9f9f9;
    padding: 40px 0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
}

/* Hide the result count and ordering */
.woocommerce-result-count,
.woocommerce-ordering {
    display: none !important;
}

/* Hide the shop controls container if empty */
.shop-controls:empty {
    display: none !important;
}

/* Page Title */
.woocommerce-products-header__title {
    font-size: 32px;
    font-weight: 700;
    color: #222;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
}

.woocommerce-products-header__title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background-color: #e74c3c;
}

/* Sidebar Filters */
.shop-sidebar {
    background-color: #fff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.filter-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #222;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.filter-group {
    margin-bottom: 25px;
}

.filter-group h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.filter-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.filter-list li {
    margin-bottom: 10px;
}

.filter-list a {
    color: #555;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s ease;
    display: block;
    padding: 5px 0;
}

.filter-list a:hover {
    color: #e74c3c;
    transform: translateX(5px);
}

.price-inputs {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.price-inputs input {
    width: 80px;
    height: 36px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0 10px;
    font-size: 14px;
}

.price-inputs span {
    margin: 0 10px;
    color: #999;
}

.btn-filter {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 15px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    background-color: #c0392b;
}

/* Shop Controls */
.shop-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.woocommerce-result-count {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.woocommerce-ordering {
    margin: 0;
}

.woocommerce-ordering select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px 30px 8px 10px;
    font-size: 14px;
    appearance: none;
    background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="6"><path d="M0 0h12L6 6z" fill="%23555"/></svg>') no-repeat right 10px center;
    cursor: pointer;
}

/* Shop Products Area */
.shop-products-area {
    background-color: transparent;
}

/* Products Grid - Force 4 columns per row */
ul.products {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 25px !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
    width: 100% !important;
}

/* Override WooCommerce default column classes */
.woocommerce ul.products.columns-1,
.woocommerce ul.products.columns-2,
.woocommerce ul.products.columns-3,
.woocommerce ul.products.columns-4,
.woocommerce ul.products.columns-5,
.woocommerce ul.products.columns-6 {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    width: 100% !important;
    margin: 0 !important;
}

.woocommerce ul.products li.product, 
.woocommerce-page ul.products li.product {
    float: none !important;
    margin: 0 !important;
    width: 100% !important;
    padding: 0 !important;
    clear: none !important;
}

/* Override default WooCommerce product styling */
.woocommerce ul.products li.product .woocommerce-loop-product__title,
.woocommerce ul.products li.product .price,
.woocommerce ul.products li.product .button {
    /* Reset these styles to let our custom styles in content-product.php take over */
    margin: 0 !important;
    padding: 0 !important;
    font-size: inherit !important;
    text-align: inherit !important;
    display: block !important;
}

/* Reset link styling */
.woocommerce ul.products li.product a img {
    margin: 0 !important;
    box-shadow: none !important;
    width: auto !important;
    height: auto !important;
    max-height: 180px !important;
    max-width: 100% !important;
}

/* Reset button styling */
.woocommerce ul.products li.product .button {
    margin-top: 0 !important;
    background-color: #e74c3c !important;
    color: #fff !important;
    border-radius: 5px !important;
    text-transform: uppercase !important;
    font-weight: 600 !important;
}

.woocommerce a.added_to_cart {
    display: none !important;
}

@media (max-width: 1199px) {
    ul.products,
    .woocommerce ul.products.columns-1,
    .woocommerce ul.products.columns-2,
    .woocommerce ul.products.columns-3,
    .woocommerce ul.products.columns-4,
    .woocommerce ul.products.columns-5,
    .woocommerce ul.products.columns-6 {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}

@media (max-width: 991px) {
    ul.products,
    .woocommerce ul.products.columns-1,
    .woocommerce ul.products.columns-2,
    .woocommerce ul.products.columns-3,
    .woocommerce ul.products.columns-4,
    .woocommerce ul.products.columns-5,
    .woocommerce ul.products.columns-6 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 575px) {
    ul.products,
    .woocommerce ul.products.columns-1,
    .woocommerce ul.products.columns-2,
    .woocommerce ul.products.columns-3,
    .woocommerce ul.products.columns-4,
    .woocommerce ul.products.columns-5,
    .woocommerce ul.products.columns-6 {
        grid-template-columns: 1fr !important;
    }
}

/* Empty shop message */
.woocommerce-info {
    background-color: #f8f9fa;
    border-left: 4px solid #3498db;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.woocommerce-info:before {
    color: #3498db;
}

/* Pagination */
.woocommerce-pagination {
    margin-top: 40px;
    text-align: center;
}

.woocommerce-pagination ul {
    display: inline-flex;
    list-style: none;
    padding: 0;
    margin: 0;
    border: none;
}

.woocommerce-pagination ul li {
    margin: 0 5px;
}

.woocommerce-pagination ul li a,
.woocommerce-pagination ul li span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 5px;
    background-color: #fff;
    color: #333;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.woocommerce-pagination ul li a:hover {
    background-color: #f0f0f0;
    color: #e74c3c;
}

.woocommerce-pagination ul li span.current {
    background-color: #e74c3c;
    color: #fff;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .shop-sidebar {
        margin-bottom: 30px;
    }
    
    .shop-controls {
        margin-bottom: 20px;
    }
}

@media (max-width: 767px) {
    .woocommerce-products-header__title {
        font-size: 28px;
    }
    
    .shop-controls {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .woocommerce-result-count,
    .woocommerce-ordering {
        margin-bottom: 15px;
    }
    
    .woocommerce-pagination ul li a,
    .woocommerce-pagination ul li span {
        width: 35px;
        height: 35px;
    }
}

/* Mobile filter toggle */
.filter-toggle {
    display: none;
    width: 100%;
    padding: 10px;
    background-color: #e74c3c;
    color: #fff;
    border: none;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: 600;
    text-align: center;
}

@media (max-width: 991px) {
    .filter-toggle {
        display: block;
    }
    
    .shop-sidebar {
        display: none;
    }
    
    .shop-sidebar.show {
        display: block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove any remaining result counts
    const resultCount = document.querySelector('.woocommerce-result-count');
    if (resultCount) {
        resultCount.style.display = 'none';
    }
    
    // Remove any ordering dropdowns
    const orderingSelect = document.querySelector('.woocommerce-ordering');
    if (orderingSelect) {
        orderingSelect.style.display = 'none';
    }
    
    // Simple price filter functionality
    const filterBtn = document.getElementById('filter-price');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const minPrice = document.getElementById('min-price').value;
            const maxPrice = document.getElementById('max-price').value;
            
            if (minPrice || maxPrice) {
                let currentUrl = new URL(window.location.href);
                
                if (minPrice) {
                    currentUrl.searchParams.set('min_price', minPrice);
                } else {
                    currentUrl.searchParams.delete('min_price');
                }
                
                if (maxPrice) {
                    currentUrl.searchParams.set('max_price', maxPrice);
                } else {
                    currentUrl.searchParams.delete('max_price');
                }
                
                window.location.href = currentUrl.toString();
            }
        });
    }
    
    // Mobile filter toggle
    const filterToggle = document.createElement('button');
    filterToggle.classList.add('filter-toggle', 'btn', 'btn-primary', 'd-lg-none', 'mb-3');
    filterToggle.textContent = 'Покажи филтри';
    
    const shopSidebar = document.querySelector('.shop-sidebar');
    if (shopSidebar) {
        shopSidebar.before(filterToggle);
        
        filterToggle.addEventListener('click', function() {
            shopSidebar.classList.toggle('show');
            this.textContent = shopSidebar.classList.contains('show') ? 'Скрий филтри' : 'Покажи филтри';
        });
    }
});
</script>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// We're handling the sidebar ourselves, so we don't need this hook
// do_action('woocommerce_sidebar');

get_footer('shop'); 