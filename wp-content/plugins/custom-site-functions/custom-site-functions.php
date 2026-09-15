<?php
/*
Plugin Name: Custom Site Functions
Description: Custom theme modifications for Stylish
Version: 1.0
Author: Your Name
*/

// ضع كل أكوادك هنا بدون تغيير
/**
 * Load Styles
 */
add_action('wp_enqueue_scripts', function() {
    // Load Parent (Shopire) Theme Styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Load Ekart Theme Styles
    wp_enqueue_style('ekart-style', get_stylesheet_directory_uri() . '/../ekart/style.css');
});







add_action('wp_enqueue_scripts', function () {

    wp_register_script('ajax-global', false, [], null, true);
    wp_enqueue_script('ajax-global');

    wp_localize_script('ajax-global', 'ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax_nonce'),
    ]);

}, 1);



/*
add_action('wp_enqueue_scripts', function() {

    $file = get_stylesheet_directory() . '/js/custom.js';

    if ( file_exists( $file ) ) {

        wp_enqueue_script(
            'custom-ajax',
            get_stylesheet_directory_uri() . '/js/custom.js',
            ['jquery'],
            filemtime( $file ),
            true
        );

        wp_localize_script('custom-ajax', 'ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ajax_nonce')
        ]);

    }

});
*/




/********************************* site speed ************************************/
/* add_action('wp_head', function() {
    ?>
    <!-- Preload Hero Images -->
    <link rel="preload" as="image" href="https://styliiiish.com/wp-content/uploads/2025/12/banner-img-2.webp" fetchpriority="high">
    <link rel="preload" as="image" href="https://styliiiish.com/wp-content/uploads/2025/12/banner-img-1.webp" fetchpriority="high">
    <link rel="preload" as="image" href="https://styliiiish.com/wp-content/uploads/2025/12/banner-img-3.webp" fetchpriority="high">
    <?php
});  */









///////////////////////////*  Load The Slider Fisrt Forces *******************/
add_action('wp_footer', function(){ ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var slider = jQuery('.wf_owl_carousel');

    if (slider.length && typeof slider.owlCarousel === "function") {
        slider.owlCarousel(slider.data("owl-options"));
        console.log("Owl Carousel initialized normally.");
    }
});
</script>
<?php });



/* ===========================================
   ADD USED BADGE ON PRODUCT CARDS (Styliiiish)
=========================================== */

add_action('woocommerce_before_shop_loop_item_title', 'styliiiish_add_used_badge', 8);

function styliiiish_add_used_badge() {
    global $product;

    if (!$product) return;

    // قراءة سمة حالة المنتج
    $terms = wp_get_post_terms(
        $product->get_id(),
        'pa_product-condition',
        ['fields' => 'slugs']
    );

    if (empty($terms)) return;

    // Slugs الخاصة بالمستعمل
    $used_slugs = ['used-very-good-customer-owned', 'used-customer-owned', 'used-very-good-styliiiish-certified', 'used'];

    foreach ($terms as $slug) {
        if (in_array($slug, $used_slugs)) {

            echo '<span class="styliiiish-used-badge">Used</span>';
            // لو عايزها باللغة الإنجليزية:
            // echo '<span class="styliiiish-used-badge">Used</span>';

            break;
        }
    }
}

/* =======================================================
   USED BADGE under SALE! inside the product image
======================================================= */

add_action('woocommerce_before_single_product_summary', 'styliiiish_single_used_badge_under_sale', 11);

function styliiiish_single_used_badge_under_sale() {

    global $product;

    if (!$product) return;

    // قراءة attribute حالة المنتج
    $terms = wp_get_post_terms(
        $product->get_id(),
        'pa_product-condition',
        ['fields' => 'slugs']
    );

    if (empty($terms)) return;

    $used_slugs = ['used-very-good-customer-owned', 'used-customer-owned', 'used-very-good-styliiiish-certified', 'used'];

    foreach ($terms as $slug) {
        if (in_array($slug, $used_slugs)) {

            echo '<span class="styliiiish-used-badge-image">Used</span>';
            break;
        }
    }
}







/***************** Images Height One Product Page ****************/
    add_action('wp_head', function() {
        ?>
        <style>
    /***************** Keep Slidar Section Heights ****************/
            #wf_slider .owl-carousel {
                min-height: 430px !important;
                display: block !important;
                opacity: 1 !important;
            
            }
            #wf_slider .wf_slider-item img {
                height: auto !important;
                display: block !important;
            }
    /***************** Keep Slidar Section Heights ****************/
        /* إصلاح العرض الزائد في اللغة العربية على الموبايل */
            html[lang="ar"] body {
              overflow-x: hidden; /* يمنع scroll الأفقي */
            }
            /* تأكد أن جميع الحاويات لا تتجاوز عرض الشاشة */
            html[lang="ar"] .container,
            html[lang="ar"] .elementor-section,
            html[lang="ar"] .row {
              max-width: 100vw !important;
              box-sizing: border-box !important;
            }
            /* ظبط ابعاد المينيو */
            html[lang="ar"] .wf_mobilenav-mainmenu-wrap {
                    padding-right: 20px !important;
                    padding-left: 20px !important;
                }
            html[lang="ar"] .wf_navbar-cart-item {
            	padding: 0px 15px 0px 15px !important;
            }
            html[lang="ar"] .wf_navbar-shopcart {
            	margin-right: -200px !important;
            }
            /* بعض Sliders تعمل بشكل أفضل بهذه الطريقة */
            html[lang="ar"] .wf_owl_carousel.owl-theme.owl-carousel.slider.owl-loaded.owl-drag {
                direction: ltr; /* بعض Sliders تعمل بشكل أفضل بهذه الطريقة */
            }
            html[lang="ar"] .title  {
            	padding-right: 15px !important;
            }
            html[lang="ar"] .description {
            	padding-right: 15px !important;
            }
            html[lang="ar"] .dropdown-menu{
            	/*margin-right:250px !important;
            	direction:ltr !important;*/
            }
            html[lang="ar"] .dropdown-menu{
            		margin-left: -80% !important;
            		margin-top: 18% !important;
            		/*direction:rtl !important;*/
            }
            /* Fix slider arrows in RTL (Arabic) */
            /* Fix Owl arrows in RTL */
                html[dir="rtl"] .owl-prev i {
                    transform: rotate(180deg);
                }
                
                html[dir="rtl"] .owl-next i {
                    transform: rotate(180deg);
                }



            
            /* === موازنة ارتفاع كروت WooCommerce بشكل احترافي === */
    
    
            .product-single {
            	flex: 1;
            	display: flex;
              flex-direction: column;
              justify-content: space-between;
              min-height: 480px; 
              max-height: 520px; 
              background: #fff;
              border: 2px solid #eee;
              border-radius: 15px;
              padding: 2px;
              box-sizing: border-box;
              overflow: hidden;
            }
            .product-content {
              overflow: hidden;
              text-overflow: ellipsis;
              display: -webkit-box;
              -webkit-line-clamp: 10; 
              -webkit-box-orient: vertical;
            }
    
            .woocommerce-tabs.wc-tabs-wrapper {
            	padding-top: 15%;
            }
            html[lang="ar"] span.onsale {
            	width: 70px !important;
            	margin-top:-25px;
            	margin-right: 15px ;
            }
            a {
            	text-decoration: none !important;
            }
    
            /* ==== Fix WooCommerce product image sizes (consistent height) ==== */
            .woocommerce ul.products li.product a img,
            .woocommerce div.product div.images img {
                aspect-ratio: 4/5; /* يمكنك تغييرها 3/4 أو 1/1 حسب رغبتك */
                object-fit: cover;
                width: 100%;
                height: auto;
                display: block;
            }
            
            .woocommerce div.product div.images img,
            .woocommerce div.product div.thumbnails img {
                aspect-ratio: 4/5;
                object-fit: cover;
            }
            
            .woocommerce ul.products li.product a img {
                width: 100%;
                height: auto;
                aspect-ratio: 4/5;
                object-fit: cover;
                background: #f8f8f8; /* لمنع الوميض في التحميل */
            }
     
            
            
            /*تخصيص زر تخفيض للموبايل  */
            @media (max-width: 768px) {
            	html[lang="ar"] span.onsale {
            	width: 20% !important;
            	margin-top:;
            	margin-right: 15px ;
            } 
                 /*  حقل الوصف للموبايل */
                .woocommerce-tabs.wc-tabs-wrapper {
            	padding-top: 0px !important;
            }
                
                .product-single {
            	flex: 1;
            	display: flex;
                flex-direction: column;
                justify-content: space-between;*/
                height: 400px !important ;
                min-height: 400px !important;
                max-height: 400px !important; 
                border: 2px solid #eee;
                border-radius: 15px;
                padding: 0px;
                box-sizing: border-box;
                overflow: hidden;
              }
            }        
            
            /* حجز مساحة للسلايدر قبل تحميل الصور */
    
    
    
    
        .styliiiish-used-badge {
            position: absolute;
            top: 40px; 
            left: 15px;
        
            background: linear-gradient(135deg, #2c2c2c, #000); /* جراديانت قوي وأنيق */
            color: #fff;
        
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
        
            border-radius: 30px; /* شكل كبسولة */
            
            box-shadow: 0 4px 10px rgba(0,0,0,0.25); /* ظل احترافي */
            letter-spacing: 0.3px;
        
            z-index: 30;
            display: inline-block;
        
            border: 1px solid rgba(255,255,255,0.2); /* لمعة خفيفة */
            backdrop-filter: blur(3px); /* تأثير زجاجي بسيط */
        }
        /* Badge inside the product image under SALE */
        .styliiiish-used-badge-image {
            position: absolute;
            top: 65px; /* تحت Badge Sale */
            left: 30px;
        
            background: linear-gradient(135deg, #2c2c2c, #000);
            color: #fff;
        
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
        
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
        
            letter-spacing: 0.3px;
        
            z-index: 50;
        }
            
            
            
            
            
            
            
            
            
        /* ===============================
           Size Guide Modal – FINAL CLEAN UI
        ================================ */
        /* ===== Lock page scroll ===== */
        html.sty-modal-open,
        body.sty-modal-open {
            overflow: hidden !important;
            height: 100%;
        }
        
        /* ===== Modal Box ===== */
        .sty-size-guide-box {
            position: relative;
            background: #ffffff;
            color: #333;
        
            max-width: 760px;
            width: calc(100% - 40px);
            max-height: 85vh;
        
            display: flex;
            flex-direction: column;
        
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0,0,0,.2);
            overflow: hidden;
        }
        
        /* ===== Title ===== */
        .sty-size-guide-box h3 {
            font-size: 22px;
            font-weight: 700;
            margin: 20px;
            color: #111;
        }
        
        /* ===== Close Button ===== */
        .sty-close {
            position: absolute;
            top: 12px;
            right: 12px;
        
            width: 36px;
            height: 36px;
        
            display: flex;
            align-items: center;
            justify-content: center;
        
            font-size: 26px;
            line-height: 1;
        
            background: #f3f4f6;
            color: #111;
        
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
        
            transition: background .2s ease, transform .2s ease;
        }
        
        .sty-close:hover {
            background: #e5e7eb;
            transform: scale(1.05);
        }
        
        /* ===== Footer Note ===== */
        .sty-size-note {
            padding: 14px 16px;
            border-top: 1px solid #eee;
        
            font-size: 13px;
            color: #6b7280;
            text-align: center;
            background: #fff;
        }
        
        
        /* ===== Modal Wrapper ===== */
        #sty-size-guide-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
        
            display: flex;
            align-items: center;
            justify-content: center;
        
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        
            background: rgba(17, 17, 17, 0.7);
            transition: opacity .25s ease, visibility .25s ease;
        
            overflow: hidden;
            overscroll-behavior: contain;
        }
        #sty-size-guide-modal.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        /* ===== Scrollbar ===== */
        /* ===== Table ===== */
        #sty-size-table {
            flex: 1;
            overflow-y: auto;
        
            margin: 0 auto;
            width: 95%;
        
            border-collapse: collapse;
            font-size: 14px;
        }
        #sty-size-table thead th {
            background: #f3f4f6;
            color: #111;
            font-weight: 600;
            padding: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }
        #sty-size-table td {
            padding: 11px;
            border: 1px solid #e5e7eb;
            color: #374151;
            text-align: center;
        }
        #sty-size-table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        #sty-size-table tbody tr:hover {
            background: #f0f0f0;
        }
        #sty-size-table tr.active-size {
            background: #111 !important;
        }
        #sty-size-table tr.active-size td {
            color: #fff;
            font-weight: 600;
        }
        
        
        #sty-size-table::-webkit-scrollbar {
            width: 6px;
        }
        #sty-size-table::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 10px;
        }
        #sty-size-table::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
        
        /* =================================
           Mobile ONLY
        ================================= */
        @media (max-width: 768px) {
        
            .sty-size-guide-box {
                width: 100%;
                height: 80vh;
                max-height: 80vh;
                border-radius: 0;
            }
        
            .sty-size-guide-box h3 {
                font-size: 20px;
                margin: 18px 16px 12px;
                text-align: center;
            }
        
            .sty-close {
                width: 44px;
                height: 44px;
                font-size: 30px;
            }
            
            .sty-size-note {
                font-size: 12px;
                padding: 12px 14px;
            }
        
            #sty-size-table {
                width: 100%;
                font-size: 13px;
            }
            #sty-size-table th,
            #sty-size-table td {
                padding: 10px 6px;
            }
        
        
        }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        #sty-size-hint {
                margin: 10px 0 14px;
                padding: 10px 14px;
    
                background: #fdf6e3;
                border: 1px solid #f1e2b7;
                border-radius: 8px;
    
                font-size: 13px;
                color: #7a5c00;
    
                display: flex;
                align-items: center;
                gap: 8px;
            }
    
        #sty-size-hint::before {
                content: "ℹ️";
                font-size: 16px;
            }
    
.styliiiish-seo-h1 {
    position: absolute;
    left: -9999px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

    
    
    
    

    
    

    
    
    
    
    
    
    
    /* =========================
   Image-only product card
========================= */

.woocommerce ul.products li.product {
    position: relative;
    overflow: hidden;
}

/* الصورة */
.woocommerce ul.products li.product a img {
    width: 100%;
    height: 420px; /* عدل الارتفاع */
    object-fit: cover;
    display: block;
}

/* الـ Overlay */
.sty-hover-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;

    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    pointer-events: none;
}

/* عند الـ Hover */
.woocommerce ul.products li.product:hover .sty-hover-overlay {
    opacity: 1;
    transform: translateY(0);
}

/* اسم المنتج */
.sty-hover-title {
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

/* السعر */
.sty-hover-price {
    color: #fff;
    font-size: 16px;
    opacity: 0.9;
}
.woocommerce ul.products li.product {
    position: relative;
}

.sty-hover-overlay {
    position: absolute;
    inset: 0;
    z-index: 99; /* أعلى من الصورة */
    pointer-events: none; /* مهم جدًا */
}

/* إخفاء wishlist icon من كارت المنتج */
body.post-type-archive-product .product-content-outer {
    display: none !important;
}
.page-id-781 .product-content-outer {
    display: none !important;
}
/* ============================
   FIX empty space under product image (HOME only)
============================ */

.home .product-single {
    min-height: auto !important;
    max-height: none !important;
    height: auto !important;

    display: block !important;
    padding: 0 !important;
}
body.post-type-archive-product .product-single {
    min-height: auto !important;
    max-height: none !important;
    height: auto !important;

    display: block !important;
    padding: 0 !important;
}




/* ============================
   MOBILE: Static bottom overlay
============================ */
@media (max-width: 768px) {

    /* إلغاء تأثير الهوفر */
    .woocommerce ul.products li.product:hover .sty-hover-overlay {
        opacity: 1;
        transform: none;
    }

    /* ضبط شكل الـ overlay */
    .sty-hover-overlay {
        opacity: 1 !important;
        transform: none !important;

        top: auto;
        bottom: 0;
        height: 30%;

        background: linear-gradient(
            to top,
            rgba(0,0,0,0.75),
            rgba(0,0,0,0.35),
            rgba(0,0,0,0)
        );

        justify-content: flex-end;
        padding: 12px 10px;
        text-align: center;
    }

    /* تحسين النص */
    .sty-hover-title {
        font-size: 14px;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .sty-hover-price {
        font-size: 13px;
        opacity: 0.9;
    }
}
@media (max-width: 768px) {
    .sty-hover-overlay {
        text-shadow: 0 2px 6px rgba(0,0,0,0.6);
    }
}








    
    
    
    
    
    
    
    
    
            
        </style>
        <?php
    });





/**
 * Make product card image-only (shop loop)
 */
add_action( 'wp', function () {

    // إزالة كل العناصر النصية من الكارت
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
});
/**
 * Add hover overlay (title + price) on product image
 */
add_action( 'woocommerce_before_shop_loop_item_title', function () {
    global $product;
    ?>
    <div class="sty-hover-overlay">
        <h3 class="sty-hover-title"><?php echo esc_html( $product->get_name() ); ?></h3>
        <div class="sty-hover-price"><?php echo $product->get_price_html(); ?></div>
    </div>
    <?php
}, 20 );























add_action( 'wp_body_open', function () {

    if ( ! is_front_page() ) {
        return;
    }

    $is_ar = ( get_locale() === 'ar' );
    ?>
    <h1 class="styliiiish-seo-h1" data-no-translation>
        <?php echo $is_ar
            ? 'فساتين سهرة وزفاف – ستايلش'
            : 'Evening & Wedding Dresses – Styliiiish';
        ?>
    </h1>
    <?php
});



/**
 * Change Homepage Schema Type to Store
 */
add_filter( 'wpseo_schema_webpage', function ( $data ) {

    if ( is_front_page() ) {
        $data['@type'] = 'Store';
        $data['name']  = 'Styliiiish';
    }

    return $data;
});



add_filter( 'wpseo_schema_webpage', function ( $data ) {

    if ( is_front_page() && isset( $data['@type'] ) && $data['@type'] === 'Store' ) {
        $data['priceRange'] = '$$';
    }

    return $data;
});








/**
 * Enhance Store schema (optional fields)
 */
add_filter( 'wpseo_schema_webpage', function ( $data ) {

    if ( is_front_page() && isset( $data['@type'] ) && $data['@type'] === 'Store' ) {

        // Phone number (optional)
        $data['telephone'] = '+201050874255';

        // Store image (optional)
        $data['image'] = [
            '@type' => 'ImageObject',
            'url'   => 'https://styliiiish.com/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png',
        ];

        // Address (optional – add only if real)
        $data['address'] = [
            '@type'           => 'PostalAddress',
            'streetAddress'  => ' 1 Nabil Khalil Street, off Hassanein Heikal Street',
            'addressLocality'=> 'Nasr City',
            'addressRegion'  => 'Cairo',
            'addressCountry' => 'EG',
        ];
    }

    return $data;
});


add_filter( 'wpseo_schema_webpage', function ( $data ) {

    if ( is_front_page() && isset( $data['@type'] ) && $data['@type'] === 'Store' ) {

        if ( isset( $data['address'] ) && is_array( $data['address'] ) ) {
            $data['address']['postalCode'] = '11371'; // مثال فقط
        }
    }

    return $data;
});






































add_filter( 'woocommerce_checkout_fields', function ( $fields ) {

    // Billing postcode
    unset( $fields['billing']['billing_postcode'] );

    // Shipping postcode (لو مستخدم شحن مختلف)
    unset( $fields['shipping']['shipping_postcode'] );

    return $fields;
});






/**
 * Replace ReadAction with SearchAction on Homepage
 */
add_filter( 'wpseo_schema_webpage', function ( $data ) {

    if ( ! is_front_page() ) {
        return $data;
    }

    $data['potentialAction'] = [
        '@type' => 'SearchAction',
        'target' => [
            '@type'       => 'EntryPoint',
            'urlTemplate' => 'https://styliiiish.com/?s={search_term_string}'
        ],
        'query-input' => [
            '@type'      => 'PropertyValueSpecification',
            'valueRequired' => true,
            'valueName'  => 'search_term_string'
        ],
    ];

    return $data;
});




















/* كل المنتجات المستعملة تذهب الى قسم مستعمل */
add_action('save_post_product', 'assign_user_used_products_to_category', 20, 1);

function assign_user_used_products_to_category($product_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

    $product = wc_get_product($product_id);
    if ( ! $product ) return;

    // ✅ نمنع منتجات Styliiiish (اللي فيها variations)
    if ( $product->is_type('variable') ) return;

    $terms = wp_get_post_terms($product_id, 'pa_product-condition', ['fields' => 'slugs']);
    if ( empty($terms) ) return;

    $used_slugs = ['used', 'used-very-good'];

    foreach ($terms as $slug) {
        if ( in_array($slug, $used_slugs, true) ) {
            wp_set_post_terms($product_id, ['used-dress'], 'product_cat', true);
            break;
        }
    }
}








/**
 * Hide products in "used-dress" category
 * everywhere EXCEPT inside that category
 */
add_action( 'pre_get_posts', 'sty_hide_used_marketplace_products', 20 );

function sty_hide_used_marketplace_products( $query ) {

    // ✅ Frontend فقط
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // ✅ لو إحنا جوه Category used-dress → نسيب كل حاجة تظهر
    if ( $query->is_tax( 'product_cat', 'used-dress' ) ) {
        return;
    }

    // ✅ نطبّق على كل الأماكن التانية
    if (
        $query->is_shop() ||
        $query->is_product_category() ||
        $query->is_product_tag() ||
        $query->is_search() ||
        $query->is_home() ||
        $query->is_archive()
    ) {

        $tax_query = (array) $query->get( 'tax_query' );

        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => [ 'used-dress' ],
            'operator' => 'NOT IN',
        ];

        $query->set( 'tax_query', $tax_query );
    }
}



















// Shop Status Shortcode
function fable_shop_status_shortcode() {
    date_default_timezone_set('Africa/Cairo');

    $open_hour  = 11;
    $open_min   = 0;
    $close_hour = 19;
    $close_min  = 0;

    $current_time = current_time('timestamp');
    $current_hour = intval(date('H', $current_time));
    $current_min  = intval(date('i', $current_time));

    $current_total = $current_hour * 60 + $current_min;
    $open_total    = $open_hour * 60 + $open_min;
    $close_total   = $close_hour * 60 + $close_min;

    if ($current_total >= $open_total && $current_total <= $close_total) {
        $status = '<span style="color:green;font-weight:bold;">Open</span>';
    } else {
        $status = '<span style="color:red;font-weight:bold;">Closed</span>';
    }

    return $status;
}
add_shortcode('shop_status', 'fable_shop_status_shortcode');


// بدء جلسة للضيف - Wishlist
add_action('init', function() {
    if (!session_id()) {
        session_start();
    }
}, 1);


// اخفاء قسم التحميلات
add_filter( 'woocommerce_account_menu_items', 'remove_downloads_from_my_account' );
function remove_downloads_from_my_account( $items ) {
    unset( $items['downloads'] );
    return $items;
}


/* SEO تعديلات */
add_filter('wpseo_schema_graph_pieces', function($pieces) {
    foreach ($pieces as $piece) {
        if (get_locale() === 'ar') {

            if ($piece instanceof Yoast\WP\SEO\Generators\Schema\Organization && isset($piece->context)) {
                $piece->context['name'] = 'ستايلش';
                unset($piece->context['alternateName']);
                $piece->context['url'] = home_url('/');
            }

            if ($piece instanceof Yoast\WP\SEO\Generators\Schema\WebSite && isset($piece->context)) {
                $piece->context['name'] = 'ستايلش';
                unset($piece->context['alternateName']);
                $piece->context['url'] = home_url('/');
            }
        }
    }

    return $pieces;
}, 20, 1);


// Canonical للرئيسية العربية
add_filter('wpseo_canonical', function($canonical) {
    if (get_locale() === 'ar' && is_front_page()) {
        return home_url('/');
    }
    return $canonical;
});

add_filter('wpseo_opengraph_url', function($url) {
    if (get_locale() === 'ar' && is_front_page()) {
        return home_url('/');
    }
    return $url;
});



/*** Product attributes box before Add to Cart */
add_action('woocommerce_single_product_summary', 'styliiiish_attributes_box', 26);

function styliiiish_attributes_box() {
    global $product;
    if ( ! $product ) return;

    $attributes = $product->get_attributes();
    if ( empty( $attributes ) ) return;

    echo '<div class="styliiiish-attr-box" style="
        padding:15px;
        margin-bottom:20px;
        border:1px solid #eee;
        border-radius:10px;
        background:#fafafa;
    ">';

    echo '<h4 style="margin-bottom:12px;font-size:18px;">Dress Details ✨</h4>';

    foreach ( $attributes as $attribute ) {

        if ( ! $attribute->is_taxonomy() ) continue;

        $taxonomy = $attribute->get_name();
        $terms    = wp_get_post_terms(
            $product->get_id(),
            $taxonomy,
            array( 'fields' => 'names' )
        );

        if ( empty( $terms ) ) continue;

        /* ===============================
           Custom professional labels
        =============================== */
        if ( $taxonomy === 'pa_size' ) {
            $label = 'Available Sizes';
        } elseif ( $taxonomy === 'pa_product-condition' ) {
            $label = 'Available Conditions';
        } else {
            $label = wc_attribute_label( $taxonomy );
        }

        echo '<p style="margin:6px 0;font-size:15px;">
                <strong>' . esc_html( $label ) . ':</strong> 
                <span>' . esc_html( implode(', ', $terms) ) . '</span>
              </p>';
    }

    /* ===============================
       Delivery Time Notice (NEW)
    =============================== */
    echo '<div style="
        margin-top:12px;
        padding-top:12px;
        border-top:1px dashed #ddd;
        font-size:14.5px;
        line-height:1.7;
    ">
        <strong>Delivery Time 🚚</strong>
        <p style="margin:6px 0 0;">
            This dress is available in <strong>one ready size</strong>.
            If another size is requested, the item will be <strong>made to order</strong>.
        </p>
        <ul style="margin:6px 0 0 18px;">
            <li><strong>Ready size:</strong> Delivery within <strong>2–4 business days</strong></li>
            <li><strong>Custom size (Made-to-Order):</strong> Delivery within <strong>7–10 business days</strong></li>
        </ul>
        <p style="margin-top:6px;font-size:13px;">
  For full shipping details, please review our
  <a href="/shipping-delivery-policy/" target="_blank">
    Shipping & Delivery Policy
  </a>.
</p>

    </div>';

    echo '</div>';
}




/*** Auto-generate Short Description (first 80 chars) from full description*/
add_action('save_post_product', 'styliiiish_generate_short_description', 15, 3);
function styliiiish_generate_short_description($post_ID, $post, $update) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($post->post_type !== 'product') return;

    $full_content = wp_strip_all_tags($post->post_content);
    if (empty($full_content)) return;

    $excerpt = mb_substr($full_content, 0, 80, 'UTF-8');

    if (mb_strlen($full_content, 'UTF-8') > 80) {
        $excerpt .= '...';
    }

    remove_action('save_post_product', 'styliiiish_generate_short_description', 15);

    wp_update_post(array(
        'ID'           => $post_ID,
        'post_excerpt' => $excerpt
    ));

    add_action('save_post_product', 'styliiiish_generate_short_description', 15, 3);
}


/* ============================================================
   SIMPLE PRODUCT ADD PAGE FOR ADMINS ONLY
   ============================================================ */

// 1. إنشاء صفحة داخل لوحة التحكم
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Add Simple Product',
        'Add Simple Product',
        'manage_options',
        'add-simple-product',
        'styliiiish_simple_product_page'
    );
});

// 2. صفحة الفورم
function styliiiish_simple_product_page() {
    ?>
    <div class="wrap">
        <h1 style="margin-bottom:20px;">🛍 Add Product (Simple Mode)</h1>

        <form method="POST" enctype="multipart/form-data">

            <label><strong>Product Name:</strong></label><br>
            <input type="text" name="sp_name" style="width:100%; margin-bottom:15px;" required>

            <label><strong>Price:</strong></label><br>
            <input type="number" name="sp_price" style="width:100%; margin-bottom:15px;" required>

            <label><strong>Weight (optional):</strong></label><br>
            <input type="text" name="sp_weight" style="width:100%; margin-bottom:15px;">

            <label><strong>Description:</strong></label><br>
            <textarea name="sp_desc" rows="6" style="width:100%; margin-bottom:15px;" required></textarea>

            <label><strong>Color:</strong></label><br>
            <select name="sp_color" style="width:100%; margin-bottom:15px;" required>
                <option value="">Choose Color</option>
                <option value="Black">Black</option>
                <option value="Red">Red</option>
                <option value="Blue">Blue</option>
                <option value="Green">Green</option>
                <option value="Pink">Pink</option>
            </select>

            <label><strong>Size:</strong></label><br>
            <select name="sp_size" style="width:100%; margin-bottom:15px;" required>
                <option value="">Choose Size</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            </select>

            <label><strong>Category:</strong></label><br>
            <?php
            wp_dropdown_categories(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'name'       => 'sp_category',
                'orderby'    => 'name',
                'show_option_none' => 'Choose Category'
            ));
            ?>
            <br><br>

            <label><strong>Images (you can upload multiple):</strong></label><br>
            <input type="file" name="sp_images[]" multiple style="margin-bottom:15px;" required>

            <input type="submit" name="sp_submit" value="Add Product" class="button button-primary button-large">
        </form>
    </div>
    <?php

    // 3. تنفيذ الإضافة
    if (isset($_POST['sp_submit'])) styliiiish_create_simple_product();
}

// 4. دالة إنشاء المنتج
function styliiiish_create_simple_product() {
    $name  = sanitize_text_field($_POST['sp_name']);
    $price = sanitize_text_field($_POST['sp_price']);
    $desc  = wp_kses_post($_POST['sp_desc']);
    $color = sanitize_text_field($_POST['sp_color']);
    $size  = sanitize_text_field($_POST['sp_size']);
    $weight = sanitize_text_field($_POST['sp_weight']);
    $catID = intval($_POST['sp_category']);

    // إنشاء المنتج
    $product_id = wp_insert_post(array(
        'post_title'   => $name,
        'post_content' => $desc,
        'post_excerpt' => mb_substr(strip_tags($desc), 0, 80) . '...',
        'post_status'  => 'publish',
        'post_type'    => 'product'
    ));

    // سعر المنتج
    update_post_meta($product_id, '_regular_price', $price);
    update_post_meta($product_id, '_price', $price);

    // وزن
    if ($weight != "") update_post_meta($product_id, '_weight', $weight);

    // القسم
    wp_set_object_terms($product_id, $catID, 'product_cat');

    // رفع الصور
    if (!empty($_FILES['sp_images']['name'][0])) {
        $image_ids = array();

        foreach ($_FILES['sp_images']['name'] as $key => $value) {
            $file = array(
                'name'     => $_FILES['sp_images']['name'][$key],
                'type'     => $_FILES['sp_images']['type'][$key],
                'tmp_name' => $_FILES['sp_images']['tmp_name'][$key],
                'error'    => $_FILES['sp_images']['error'][$key],
                'size'     => $_FILES['sp_images']['size'][$key]
            );

            $_FILES['upload_file'] = $file;
            $upload = media_handle_upload('upload_file', $product_id);

            if (!is_wp_error($upload)) {
                $image_ids[] = $upload;
            }
        }

        // الصورة الأساسية
        if (!empty($image_ids)) {
            set_post_thumbnail($product_id, $image_ids[0]);
            update_post_meta($product_id, '_product_image_gallery', implode(',', $image_ids));
        }
    }

    // attributes (color + size)
    wp_set_object_terms($product_id, $color, 'pa_color');
    wp_set_object_terms($product_id, $size, 'pa_size');

    echo "<div class='updated'><p>Product Added Successfully ✔</p></div>";
}












/***************** Site Speed ****************/
add_action( 'init', function() {
    // Disable Emoji Scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Disable oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Remove RSD/WLW/Generator
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
});

















add_action('wp_enqueue_scripts', function () {

    $base_css = plugin_dir_url(__FILE__) . 'assets/css/';
    $base_js  = plugin_dir_url(__FILE__) . 'assets/js/';

    /*
     * =========================
     * Shared UI (site-wide)
     * =========================
     */
    wp_enqueue_style(
        'csf-shared-ui',
        $base_css . 'shared-ui.css',
        [],
        '1.0'
    );

    /*
     * =========================
     * Legal Pages
     * =========================
     */
    if (is_page([
        2451,
        2460,
        2454,
        2457
    ])) {
        wp_enqueue_style(
            'csf-legal-pages',
            $base_css . 'legal-pages.css',
            ['csf-shared-ui'],
            '1.0'
        );
    }

    /*
     * =========================
     * Contact Page
     * =========================
     */
    if (is_page(486)) {
        wp_enqueue_style(
            'csf-contact-page',
            $base_css . 'contact-page.css',
            ['csf-shared-ui'],
            '1.2'
        );

        wp_enqueue_style(
            'csf-forms',
            $base_css . 'forms.css',
            ['csf-contact-page'],
            '1.0'
        );
    }

    /*
     * =========================
     * Product Page JS (Size Guide)
     * =========================
     */
    if (is_product()) {

        // تأكيد تحميل jQuery
        wp_enqueue_script('jquery');

        wp_enqueue_script(
            'sty-size-guide',
            $base_js . 'size-guide.js',
            ['jquery'],
            '2.3',
            true
        );
    }

});








// ===============================
// Size Guide Popup Button
// ===============================
add_action( 'woocommerce_before_add_to_cart_form', function () {
    echo '<a href="#" id="sty-size-guide-btn">📏 Size Guide</a>';
}, 15 );


// ===============================
// Popup HTML
// ===============================
add_action( 'wp_footer', function () {
    if ( ! is_product() ) return;
    ?>
    <div id="sty-size-guide-modal">
        <div class="sty-size-guide-box">
            <span class="sty-close">&times;</span>

            <h3>Dress Size Guide</h3>

            <table id="sty-size-table">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Body Weight (kg)</th>
                        <th>Bust (cm)</th>
                        <th>Waist (cm)</th>
                        <th>Hips (cm)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-size="xs"><td>XS</td><td>40 – 48</td><td>78 – 82</td><td>60 – 64</td><td>86 – 90</td></tr>
                    <tr data-size="s"><td>S</td><td>48 – 60</td><td>84 – 90</td><td>66 – 72</td><td>92 – 98</td></tr>
                    <tr data-size="m"><td>M</td><td>60 – 72</td><td>92 – 100</td><td>74 – 82</td><td>100 – 108</td></tr>
                    <tr data-size="l"><td>L</td><td>72 – 85</td><td>102 – 110</td><td>84 – 92</td><td>110 – 118</td></tr>
                    <tr data-size="xl"><td>XL</td><td>85 – 100</td><td>112 – 120</td><td>94 – 104</td><td>120 – 128</td></tr>
                    <tr data-size="xxl"><td>XXL</td><td>100 – 110</td><td>122 – 130</td><td>106 – 114</td><td>130 – 138</td></tr>
                    <tr data-size="3xl"><td>3XL</td><td>110 – 120</td><td>132 – 140</td><td>116 – 124</td><td>140 – 148</td></tr>
                </tbody>
            </table>

            <p class="sty-size-note">
                Sizes are based on body measurements.  
                If you are between two sizes, choose the larger size.
            </p>
        </div>
    </div>
    <?php
});




/**
 * STY: Auto SKU + Stock + Weight & Dimensions for variations
 * Admin-safe – بدون Loops – يشتغل لما تحفظ الـ variations
 */
add_action( 'woocommerce_admin_process_variation_object', 'sty_admin_auto_variation_fields', 20, 2 );

function sty_admin_auto_variation_fields( $variation, $i ) {

    // نتأكد إنه variation فعلاً
    if ( ! $variation instanceof WC_Product_Variation ) {
        return;
    }

    $product_id = $variation->get_parent_id();

    /* =========================
       READ ATTRIBUTES (ROBUST)
    ========================= */
    $attrs = $variation->get_attributes();

    // size: نحاول pa_size ثم size
    $size = '';
    if ( isset( $attrs['pa_size'] ) && $attrs['pa_size'] ) {
        $size = $attrs['pa_size'];
    } elseif ( isset( $attrs['size'] ) && $attrs['size'] ) {
        $size = $attrs['size'];
    }

    // condition: نحاول pa_product-condition ثم product-condition
    $condition = '';
    if ( isset( $attrs['pa_product-condition'] ) && $attrs['pa_product-condition'] ) {
        $condition = $attrs['pa_product-condition'];
    } elseif ( isset( $attrs['product-condition'] ) && $attrs['product-condition'] ) {
        $condition = $attrs['product-condition'];
    }

    // نشتغل على الـ slugs lowercase
    $size_slug      = strtolower( $size );
    $condition_slug = strtolower( $condition );

    $changed = false;

    /* =========================
       1) AUTO SKU (لو فاضى فقط)
    ========================= */
    if ( ! $variation->get_sku() ) {

        $sku_parts = array_filter( [
            'STY',
            $product_id,
            strtoupper( $size_slug ),
            strtoupper( $condition_slug ),
        ] );

        $sku = implode( '-', $sku_parts );
        $variation->set_sku( $sku );
        $changed = true;
    }

    /* =========================
       2) STOCK RULES (USED / NEW)
    ========================= */

    $used_conditions = [
        'used',
        'used-very-good-styliiiish-certified',
        'used-very-good-customer-owned',
        'used-customer-owned',
    ];

    $new_conditions = [
        'new',
        'new-customer-owned',
    ];

    // USED → قطعة واحدة
    if ( in_array( $condition_slug, $used_conditions, true ) ) {

        if ( ! $variation->get_manage_stock()
             || $variation->get_stock_quantity() !== 1
             || $variation->get_backorders() !== 'no'
        ) {
            $variation->set_manage_stock( true );
            $variation->set_stock_quantity( 1 );
            $variation->set_backorders( 'no' );
            $variation->set_stock_status( 'instock' );
            $changed = true;
        }

    // NEW → stock غير محدود
    } elseif ( in_array( $condition_slug, $new_conditions, true ) ) {

        if ( $variation->get_manage_stock() || ! is_null( $variation->get_stock_quantity() ) ) {
            $variation->set_manage_stock( false );
            $variation->set_stock_quantity( null );
            $changed = true;
        }
    }

    /* =========================
       3) WEIGHT & DIMENSIONS BY SIZE
    ========================= */

    $size_map = [
        'xs'  => [ 'weight' => 0.6, 'l' => 35, 'w' => 25, 'h' => 5 ],
        's'   => [ 'weight' => 0.7, 'l' => 36, 'w' => 26, 'h' => 5 ],
        'm'   => [ 'weight' => 0.8, 'l' => 37, 'w' => 27, 'h' => 6 ],
        'l'   => [ 'weight' => 0.9, 'l' => 38, 'w' => 28, 'h' => 6 ],
        'xl'  => [ 'weight' => 1.0, 'l' => 40, 'w' => 30, 'h' => 7 ],
        'xxl' => [ 'weight' => 1.1, 'l' => 42, 'w' => 32, 'h' => 7 ],
        '3xl' => [ 'weight' => 1.2, 'l' => 44, 'w' => 34, 'h' => 8 ],
    ];

    if ( isset( $size_map[ $size_slug ] ) ) {

        $map = $size_map[ $size_slug ];

        // وزن: نكتب بس لو فاضى
        if ( ! $variation->get_weight() ) {
            $variation->set_weight( $map['weight'] );
            $changed = true;
        }

        // الأبعاد: نكتب بس لو التلاتة فاضيين
        if ( ! $variation->get_length() && ! $variation->get_width() && ! $variation->get_height() ) {
            $variation->set_length( $map['l'] );
            $variation->set_width( $map['w'] );
            $variation->set_height( $map['h'] );
            $changed = true;
        }
    }

    /* =========================
       4) SAVE (Woo نفسه هيعمل save)
    ========================= */

    // مهم: فى الهوك ده مش محتاج تستدعى ->save()
    // WooCommerce هيحفظ الـ $variation بعد ما كل الفلاتر دى تخلص
}
/**
 * STY: Auto SKU for Variable Parent Product
 * Generates SKU only if empty
 */
add_action( 'save_post_product', 'sty_auto_parent_sku', 20, 1 );

function sty_auto_parent_sku( $product_id ) {

    // منع autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    $product = wc_get_product( $product_id );
    if ( ! $product || $product->get_type() !== 'variable' ) return;

    // لو الـ SKU موجود خلاص → نخرج
    if ( $product->get_sku() ) return;

    $sku = 'STY-' . $product_id;

    $product->set_sku( $sku );
    $product->save();
}

 
 
 
 
 
 
 
 
 
 


/**
 * UX Hint: Select size to preview image
 */
add_action( 'woocommerce_after_variations_table', 'sty_add_size_preview_hint' );

function sty_add_size_preview_hint() {
    ?>
    <div id="sty-size-hint" style="display:none;">
        Please select size to preview the exact dress image
    </div>
    <?php
}
add_action( 'wp_footer', 'sty_size_hint_js' );
function sty_size_hint_js() {
    if ( ! is_product() ) return;
    ?>
    <script>
        jQuery(function ($) {

            const $hint = $('#sty-size-hint');

            function checkHint() {
                const color = $('select[name="attribute_pa_color"]').val();
                const size  = $('select[name="attribute_pa_size"]').val();

                if (color && !size) {
                    $hint.fadeIn(150);
                } else {
                    $hint.fadeOut(150);
                }
            }

            $(document).on('change', 'select[name^="attribute_"]', checkHint);

            $(document).on('click', '.reset_variations', function () {
                $hint.hide();
            });
        });
    </script>
    <?php
}



add_action( 'after_setup_theme', function() {

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

});









