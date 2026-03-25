<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>


<style>

.product_type_simple {
   display:none;
    }


.woocommerce-ordering {
   display:none;
    }


 .shop-filter-container {
      max-width: 60%;
    margin: 0 auto;
    padding: 20px 10px;
    float: left;
   
    }

     .button-link {
     display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    border: 1px solid #ccc;
    border-radius: 2px;
    padding: 1em 1.3em;
    color: black;
    margin: 0.25em;
    transition: background 0.3s, color 0.3s;   
    font-family: 'Inter', sans-serif;
    }

    .button-link:hover {
      background-color: #f5a623;
    }


    .post-type-archive-product .woocommerce-products-header {
        display: none;
    }
    
    .storefront-sorting  .woocommerce-ordering select{
        
        padding: 20px;
        font-size: 1rem;
        color: black;
        font-family: 'Inter', sans-serif;
        
    }
    
    
    .woocommerce-ordering {
        margin-right: 0;
      
    }
    
    .woocommerce-result-count  {
        display: none;
    }
    
    .post-type-archive-product .storefront-sorting {
          max-width: 50%;
      margin: 0 auto;
      float: right;
      padding: 20px 20px 10px 20px;
    }
    
    .post-type-archive-product  .products {
      max-width: 1440px;
      margin: 0 auto;
      padding: 0px 20px;
    }
    
    .post-type-archive-product .product_type_variable {
        display: none;
    }
    
    ul.products li.product, ul.products .wc-block-grid__product, .wc-block-grid__products li.product, .wc-block-grid__products .wc-block-grid__product{
       margin-bottom: 0 !important;
    }
    
    
    ul.products li.product img, ul.products .wc-block-grid__product img, .wc-block-grid__products li.product img, .wc-block-grid__products .wc-block-grid__product img {
    display: block;
    margin: 0 auto 10px;
    }
    
    .woocommerce-loop-product__title {
        font-family: 'Roboto', sans-serif;
        text-align: left;

        font-weight: 600 !important;
    margin-bottom: 0px !important;
    margin-top: -6px !important;
    
    text-align: center;
    }
    .onsale  {
        display: none;
    }
    .price {
      
        text-align: center;
         margin-bottom: 22px !important;
    }


    
    
    .woocommerce ul.products li.product {
  position: relative;
  overflow: hidden;
}

.woocommerce ul.products li.product img {
  display: block;
  width: 100%;
  transition: opacity 0.3s ease;
  backface-visibility: hidden; /* avoid blur on transition */
}

.secondary-image {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  width: 100%;
  transition: opacity 0.3s ease;
  z-index: 1;
}

.woocommerce ul.products li.product:hover .secondary-image {
  opacity: 1;
}

.woocommerce ul.products li.product:hover .woocommerce-loop-product__link img:first-child {
  opacity: 0;
}

.top-liked, .badge   {
   z-index: 999;
   opacity: 1;
   font-size: 10px;
}


.shop-filter-buttons .active {
        background:  #f5a623;

      }


@media (min-width: 767px) {
    
    .one-banner-shop-mobile {
        display:none;
    }
}


@media (max-width: 768px) {
    
    .reviews  {
        text-align: left;
        font-size: 12px !important;
        line-height: 1!important;
    }
    
    .one-banner-shop {
        display:none;
    }

    .storefront-sorting {
        display:none;
    }

    .shop-filter-container {
        max-width: 100%;
    }
    
    
    .button-link {
      padding: 6px 1.3em;
    }
    
    
    .top-liked, .badge {
        font-size: 8px !important;
    }
    
    
}
    





</style>



<section style=" max-width: 1440px;
    margin: 0 auto; margin-top: 20px;padding: 20px;" class="one-banner-shop">
    
    <img style=" display: block; margin: 0 auto; width: 100%;" src="<?php echo get_field("banner-desktop", "options");?> ">
     
     <!--<img style=" display: block; margin: 0 auto; width: 100%;" src="<?php echo get_field("banner-mobile", "options");?> ">-->
    
    
    </section>


<section style=" max-width: 1440px;
    margin: 0 auto; margin-top: 20px;padding: 0px 20px;" class="one-banner-shop-mobile">
    
    
     
     <img style=" display: block; margin: 0 auto; width: 100%;" src="<?php echo get_field("banner-mobile", "options");?> ">
    
    
    </section>



<script>
  function initMobileSlider() {
    const isMobile = window.innerWidth < 768; // Change breakpoint as needed
    const $slider = jQuery('.your-slider');

    if (isMobile && !$slider.hasClass('slick-initialized')) {
      $slider.slick({
               dots: false,
               arrows: false,
              infinite: false,
              speed: 300,
              slidesToShow: 1,
              centerMode: false,
              variableWidth: true
      });
    } else if (!isMobile && $slider.hasClass('slick-initialized')) {
      $slider.slick('unslick');
    }
  }

  jQuery(document).ready(function () {
    initMobileSlider();
    jQuery(window).on('resize', initMobileSlider);
  });
</script>



<style>
.shop-filter-buttons {
    margin-left: 10px !important;
}
    
</style>

<?php 
$shop_filter_fields = get_field("shop_filter_fields", "option");
$current_url = $_SERVER['REQUEST_URI'];

//var_dump($header_nav);
?>

<section  class="shop-filter-buttons">
    
    <div class="container" style=" max-width: 1440px;
    margin: 0 auto; width: 100%; display: block;">
    
    <div class="shop-filter-container your-slider">
            <?php if ($shop_filter_fields): ?>
                <?php foreach ($shop_filter_fields as $item): ?>
                
                <?php
                    // Check if the current URL contains the item's link
                    $is_active = strpos($current_url, $item['link']) !== false ? 'active' : '';
                ?>
                    
                  <a href="<?php echo $item['link']; ?>" class="button-link <?php echo $is_active; ?>"><?php echo $item['text']; ?></a>
              <?php endforeach; ?>
            <?php endif; ?>
    </div>
    
</div>



    
</section>



<?php




/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
do_action( 'woocommerce_shop_loop_header' );




if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );
	
	
	?>



<?php


	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
