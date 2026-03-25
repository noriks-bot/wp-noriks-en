<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" style="padding-top: 0 !important;" class="site-main" role="main">

			<?php
			/**
			 * Functions hooked in to homepage action
			 *
			 * @hooked storefront_homepage_content      - 10
			 * @hooked storefront_product_categories    - 20
			 * @hooked storefront_recent_products       - 30
			 * @hooked storefront_featured_products     - 40
			 * @hooked storefront_popular_products      - 50
			 * @hooked storefront_on_sale_products      - 60
			 * @hooked storefront_best_selling_products - 70
			 */
			//do_action( 'homepage' );
			?>
			
  
  <section id="hero-section" class="comparison-section ">
     
 <div class="comp-container container">
    <div class="section">
      
      
      <div class="section-text">
         <p class="subheading" style="color: #f5a623; font-weight: bold;">
              <?php echo get_field("homepage_section_left_t1"); ?>
            </p>
        <h2><?php echo get_field("homepage_section_left_t2"); ?></h2>
        <p><?php echo get_field("homepage_section_left_t3"); ?></p>
        
        <div style=" background: transparent; padding: 0;    justify-content: left; "class="cta-button">
<a class="cta-button2 button button--xl" style="text-align: left; background: #f5a623; font-family: 'Roboto', sans-serif;   color: white; text-transform: none;     font-size: 15px;
    padding: 10px 25px 10px 25px;" href="<?php echo get_field("homepage_section_left_l4"); ?>"><?php echo get_field("homepage_section_left_t4"); ?></a></div>
    
    
     <div style="margin-top: 50px; text-align: left;    justify-content: left;" class="reviews-rating">
      <span class="stars">★★★★★</span>
      <span><?php echo get_field("homepage_section_left_t5"); ?></span>
    </div>
        
      </div>
      
      
      <div class="section-image">
        <div class="video-wrapper">
            <!--
                <div class="video-16x9">
  <iframe
    src="https://player.vimeo.com/video/1115486877?h=649cce4aec&autoplay=1&muted=1&loop=1&background=1&playsinline=1"
    frameborder="0"
    allow="autoplay; fullscreen; picture-in-picture"
    allowfullscreen
  ></iframe>
</div>

<style>
.video-16x9 {
  width: 100%;
  aspect-ratio: 1 / 1; /* locks height to width (no left/right bars) */
  background: #000;
  position: relative;
  overflow: hidden;
}

.video-16x9 iframe {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}
</style>
       -->     
        <video 
          autoplay muted loop playsinline 
          class="why-video">
          <source src="https://noriks.com/wp-content/uploads/2025/09/noriks_gif_en_2-1.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        
      </div>
      </div>
    </div>
  </div>
    

  </section>
  		

		
		<section class="features-section">
  <div class="features">
    <div class="feature">
      <img src="<?php echo get_field("homepage_section_1_icon_1"); ?>" alt="Perfect Fit">
      <h4><?php echo get_field("homepage_section_1_icon_t1"); ?></h4>
    </div>
    <div class="feature">
      <img src="<?php echo get_field("homepage_section_1_icon_2"); ?>" alt="Ultra Soft">
      <h4><?php echo get_field("homepage_section_1_icon_t2"); ?></h4>
    </div>
    <div class="feature">
      <img style="" src="<?php echo get_field("homepage_section_1_icon_3"); ?>" alt="Premium Quality">
      <h4><?php echo get_field("homepage_section_1_icon_t3"); ?></h4>
    </div>
    <div class="feature">
      <img src="<?php echo get_field("homepage_section_1_icon_4"); ?>" alt="Breathable & Lightweight">
      <h4><?php echo get_field("homepage_section_1_icon_t4"); ?></h4>
    </div>
    <div class="feature">
      <img src="<?php echo get_field("homepage_section_1_icon_5"); ?>" alt="Wrinkles Minimal">
      <h4><?php echo get_field("homepage_section_1_icon_t1"); ?></h4>
    </div>
    <div class="feature">
      <img src="<?php echo get_field("homepage_section_1_icon_6"); ?>" alt="Honest Price">
      <h4><?php echo get_field("homepage_section_1_icon_t6"); ?></h4>
    </div>
  </div>
  

</section>


<?php 
/************ get products by category homepage  ************/
$products = array();

// Check if the repeater field has rows
if (have_rows('homepage_section_2_product_list')) {
    while (have_rows('homepage_section_2_product_list')) {
        the_row();

        // Get the product field (post object)
        $product_post = get_sub_field('product');
        
        if ($product_post && $product_post instanceof WP_Post) {
            $product = wc_get_product($product_post->ID);
            if ($product instanceof WC_Product) {
                $products[] = $product;
            }
        }
    }
}


/************ get products by category homepage  ************/
?>



<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize Slick Carousel (Mobile only)
    if (window.innerWidth <= 768) {
        
      jQuery('.slider-mobile').not('.slick-initialized').slick({
        slidesToShow: 1,
        centerMode: true,
        centerPadding: '60px',
        arrows: false,
        dots: true,
        infinite: false
      });
    }

    // Delay Glide init slightly to ensure Slick is fully ready
    setTimeout(function () {
      document.querySelectorAll('.glide').forEach(function (el) {
        new Glide(el, {
          type: 'carousel',
          perView: 1,
          autoplay: false,
          gap: 0
        }).mount();
      });
    }, 100); // slight delay ensures load order, adjust if needed
  });
</script>

<style>

.slider-mobile .slick-list {
  padding-left: 0 !important;
      margin-left: 0px;
 
}

.slider-mobile {
  overflow: visible;
}

.slick-slide {
  transition: all 0.3s ease;
   margin-right: 20px !important;
       margin-left: -1px;

}

.slick-list {
  overflow: visible; /* important! */
}


  /* Full-width horizontal dot container */
.slick-dots {
  display: flex !important;
  justify-content: space-between; /* spread out across the width */
  align-items: center;
  width: 100%;
  margin: 10px 0 0;
  padding: 0;
  list-style: none;
}

/* Optional: give some padding or spacing on the sides */
.slick-dots li {
  flex: 1; /* evenly distribute */
  text-align: center;
}

/* Square dots */
.slick-dots li button {
  width: 95%;
  height: 7px;
  border-radius: 1px;
  background: #ccc;
  border: none;
  padding: 0;
  cursor: pointer;
  font-size: 0;
  margin: 0 auto;
}

/* Active dot style */
.slick-dots li.slick-active button {
  background: #333;
}
  
  .slider-mobile {
  width: 100%;
}
  
</style>

<style>



.glide__arrow--left {
    margin-left: 10px;
 
}

.glide__arrow--right {
    margin-right: 10px;
 
}


.glide__arrows {
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translateY(-50%);
  display: flex;
  justify-content: space-between;
  pointer-events: none;
  z-index: 99999;

}

.glide__arrow {
  background: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  font-size: 18px;
  padding: 8px 12px;
  cursor: pointer;
  pointer-events: all;
   z-index: 99999;
     border-radius: 50%;
}

/* Ensure each grid item (product card) behaves correctly */
.product-card {
  display: flex;
  flex-direction: column;
  height: 100%; /* allow children to fill */
  background: #fff;
}

.image-wrapper {
  width: 100%;
  overflow: hidden;
  position: relative;
}



/* Glide container should stretch inside grid item */
.glide {
  width: 100%;
}

/* Track and slides behave like block layout (not inline) */
.glide__track {
  width: 100%;
}

.glide__slides {

}

.glide__slide {


}

/* Image styles */
.glide__slide img {
    width: 100%;
  height: auto;
 
}

/* Arrows fix */
.glide__arrows {
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  transform: translateY(-50%);
  pointer-events: none;
}
.glide__arrow {
 pointer-events: all;
    background: rgba(0, 0, 0, 0.13);
    color: white;
    border: none;
    padding: 5px 5px;
    font-size: 0;
    cursor: pointer;
}

.glide__bullets {
    text-align: center;
     z-index: 999909;
}

.glide__bullet--active  {
   background: rgba(0, 0, 0, 0.22);
   
}
.glide__bullet  {
   padding: .4em .4em;
   margin-left: 2px;
    margin-right: 2px;
     z-index: 999909;
     z-index: 999909;
    position: relative;
    border-radius: 50%;
   
}

.link-to-pp {
    position: absolute;
    right: 10px;
    bottom: 41px;
    z-index: 99;
    background: #f5a622;
    width: 40px;
    height: 40px;
    z-index: 999999999;
}

a:focus,
a:hover {
    outline: none !important;
    box-shadow: none !important;
    color: inherit !important; /* optional: keeps color unchanged */
}

</style>

         


<section class="most-popular">
  <div class="container">
    <h2 class="section-title"><?php echo get_field("homepage_section_2_t1"); ?></h2>
    <p class="section-subtitle"><?php echo get_field("homepage_section_2_t2"); ?></p>

    <div class="products-grid slider-mobile">
      <?php foreach ($products as $index => $product): ?>
      
       
        
      
        <?php
        $product_id = $product->get_id();
        $product_link = get_permalink($product_id);
        $product_name = $product->get_name();
       
     // Get featured image
        $product_image = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
        
        // Initialize array with featured image
        $all_images = array();
        
        if ($product_image) {
            $all_images[] = $product_image;
        }
        
        // Get gallery image IDs
        $gallery_image_ids = $product->get_gallery_image_ids();
        
        if (!empty($gallery_image_ids)) {
            foreach ($gallery_image_ids as $gallery_id) {
                $image_url = wp_get_attachment_image_url($gallery_id, 'woocommerce_thumbnail');
                if ($image_url) {
                    $all_images[] = $image_url;
                }
            }
        }

        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        
        if ($product->is_type('variable')) {
            $regular_price = $product->get_variation_regular_price('min', true);
            $sale_price = $product->get_variation_sale_price('min', true);
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
        }
                
        
        $is_on_sale = $product->is_on_sale();
        
        $discount = ($is_on_sale && $regular_price) ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
        
        
        
        
        
        ?>
        
         
        <div class="product-card" style="position: relative;">
           <a href="<?php echo $product_link; ?>"  style="position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 99;
    height: 100%;">
               
              <div class="image-wrapper">
                  
                  
      
<?php

$shirt_count = get_field('number_of_shirts_in_this_product', $product->get_id());
$alt_output = false;
$alt_output_text = get_field("singlepp_priceper_alternative_1piece","options");


if (empty($shirt_count) || $shirt_count == 0) {
    $shirt_count = 1;
}


$tmp_price = 0;


if ($product->is_type('variable')) {
    $tmp_price = $product->get_variation_sale_price('min', true);
} else {
    $tmp_price = $product->get_sale_price();
}

$tmp_price = $tmp_price / $shirt_count;
//$tmp_price = ceil($tmp_price * 100) / 100; // result: 18.99
$tmp_price = ceil($tmp_price * 100) / 100; // result: 18.99



if($shirt_count == 1 ) {
    $alt_output = true;
    $alt_output_text = get_field("singlepp_priceper_alternative_1piece","options");
}


// extra check if is multipack


if( get_field('multipack_option_1', get_the_ID())  == true  ) {
    $alt_output = true;
    $alt_output_text = get_field("singlepp_priceper_alternative_multipack","options");
}


$topseler_text =  get_field("singlepp_bestseller_text", "options");

 if( $shirt_count != 1): 

     if($alt_output == false): 
      $topseler_text =  get_field("singlepp_priceper_before","options") . " " . $tmp_price . " ".  get_field("singlepp_priceper_after","options"); 
    else:
    
     endif;

 endif; 
 


?>
    
    
    <?php if( $shirt_count != 1):  ?>
      <div class="top-liked"><?php echo $topseler_text; ?></div>
      <?php endif; ?>
                 <?php
            $discount = 0;
            
            if ( $product->is_type( 'variable' ) ) {
                $regular_price = (float) $product->get_variation_regular_price( 'min', true );
                $sale_price    = (float) $product->get_variation_sale_price( 'min', true );
            } else {
                $regular_price = (float) $product->get_regular_price();
                $sale_price    = (float) $product->get_sale_price();
            }
            
            if ( $sale_price && $regular_price && $regular_price > $sale_price ) {
                $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                echo '<span class="badge">-' . esc_html( $discount ) .'' . get_field("singlepp_discount_text","options") .' </span>';
            }
            ?>
                
                  
                    <a style="display:none;" href="<?php echo $product_link; ?>" class="link-to-pp"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="2" fill="#F5A623"></rect>
                              <g clip-path="url(#clip0_24310_7887)">
                                <path d="M24 16C24 14.9391 23.5786 13.9217 22.8284 13.1716C22.0783 12.4214 21.0609 12 20 12C18.9391 12 17.9217 12.4214 17.1716 13.1716C16.4214 13.9217 16 14.9391 16 16H12V26C12 26.5304 12.2107 27.0391 12.5858 27.4142C12.9609 27.7893 13.4696 28 14 28H26C26.5304 28 27.0391 27.7893 27.4142 27.4142C27.7893 27.0391 28 26.5304 28 26V16H24ZM20 13.3333C20.7072 13.3333 21.3855 13.6143 21.8856 14.1144C22.3857 14.6145 22.6667 15.2928 22.6667 16H17.3333C17.3333 15.2928 17.6143 14.6145 18.1144 14.1144C18.6145 13.6143 19.2928 13.3333 20 13.3333Z" fill="white"></path>
                                </g>
                                <defs>
                                <clipPath id="clip0_24310_7887">
                                <rect width="16" height="16" fill="white" transform="translate(12 12)"></rect>
                              </clipPath>
                            </defs>
                          </svg></a>
                                      
                  
                  
                     <div class="glide">
                      <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                         
                                                <?php 
                                                foreach ($all_images as $image_url) {
                                                    echo '<li  class="glide__slide"><img style="" src="' . esc_url($image_url) . '"></li>';
                                                }
                                                ?>                   
                                                 </ul>
                                                
                                                <div class="glide__arrows" data-glide-el="controls">
                                                <button class="glide__arrow glide__arrow--left" data-glide-dir="<">  <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 6L9 12L15 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg></button>
                                                <button class="glide__arrow glide__arrow--right" data-glide-dir=">">  <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 6L15 12L9 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg></button>
                                              </div>
                                              
                                           <div class="glide__bullets" data-glide-el="controls[nav]">
                            <?php 
                            foreach ($all_images as $index => $image_url) {
                                echo '<button class="glide__bullet" data-glide-dir="=' . $index . '"></button>';
                            }
                            ?>
                        </div>
                                              
                                              </div>
                                            </div>
            </div>
            
            
            
              <div class="product-info">
                  <!--
                <div class="rating">
                  <div class="stars">★★★★★</div>
                     <div class="reviews"><?php echo get_field('singlepp_reviews_text_1', 'option'); ?> <?php echo get_field('singlepp_reviews_text_2', 'option'); ?> </div>
                </div>
                -->
                <h3 style="font-size: 15px;
    margin-bottom: 0px;
    font-weight: 500;">NORIKS</h3>
                <h3 class="product-name"><?php echo esc_html($product_name); ?></h3>
                <div class="price">
                  <?php if ($is_on_sale): ?>
                    <span class="old-price"><?php echo wc_price($regular_price); ?></span>
                    <span class="current-price"><?php echo wc_price($sale_price); ?></span>
                  <?php else: ?>
                    <span class="current-price"><?php echo wc_price($regular_price); ?></span>
                  <?php endif; ?>
                </div>
              </div>
              
            </a>
              
              
              
        </div>
        
     
     
    
        
      <?php endforeach; ?>
    </div>

    <div class="container container--my-button ">
      <div style="background: transparent; padding: 0; justify-content: left;" class="cta-button">
        <a class="cta-button2 button button--xl"
           style="margin: 0 auto; text-align: left; background: black; font-family: 'Roboto', sans-serif; color: white; text-transform: none; font-size: 15px; padding: 10px 25px 10px 25px;"
           href="<?php echo get_field("homepage_section_2_b1_link"); ?>"><?php echo get_field("homepage_section_2_b1_text"); ?></a>
      </div>
    </div>
  </div>
</section>




	
  <section class="reviews-section">
    
     <div class="container" style="width: 100%;
    max-width: 1440px;
    margin: 0 auto;">
    <div class="reviews-rating">
      <!--<span class="stars stars2">★★★★★</span>-->
      <span style="color: #333;"><?php echo get_field("homepage_section_3_t1"); ?></span>
    </div>

    <h2><?php echo get_field("homepage_section_3_t2"); ?></h2>

    <div class="reviews-grid">

    <?php 
    $bigreviews_reviews_fields = get_field("bigreviews_reviews_fields", "option");
    //var_dump($header_nav);
    ?>
    

      <?php if ($bigreviews_reviews_fields): ?>
    <?php foreach ($bigreviews_reviews_fields as $item): ?>
      <!-- Review 1 -->
      <div class="review-card">
        <img src="<?php echo $item['img']; ?>" alt="" class="review-image">
        <div class="review-content">
          <div class="review-meta">
            <div class="review-name"><?php echo $item['name']; ?></div>
            <div class="verified"><?php echo $item['t1']; ?></div>
          </div>
          <div class="review-text"><?php echo $item['t2']; ?></div>
          <div class="review-product">
            <img src="<?php echo $item['img2']; ?>" alt="Shirt Pack">
            <a href="<?php echo $item['link']; ?>"><?php echo $item['t3']; ?></a>
          </div>
        </div>
      </div>
     <?php endforeach; ?>
  <?php endif; ?>
    
    

    </div>

 </div>
  </section>
  
  <style>
  .review-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    will-change: transform;
  }

  .review-card:hover {
    transform: scale(1.1);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    z-index: 2;
  }
</style>

  


<section class="comparison-table-section">
    
    <div class="comparison-container comparison-intro-container">
    <div class="comparison-intro">
     <!-- <h4 class="highlight"><?php echo get_field("_comp_table_t1", "options"); ?></h4>-->
      <h1 style="color:white;"><?php echo get_field("_comp_table_t2", "options"); ?></h1>
      <p class="note"><?php echo get_field("_comp_table_t3", "options"); ?></p>
    </div>
     </div>
    
    
  <div class="comparison-container comparison-table-container">
    <table class="comparison-table">
      <thead>
        <tr>
          <th></th>
          <th class="brand-column">
                <?php echo get_field("_comp_table_inside_1", "options"); ?><br>
            <div class="price"><?php echo get_field("_comp_table_inside_3", "options"); ?></div>
          </th>
          <th class="other-brand"><?php echo get_field("_comp_table_inside_2", "options"); ?><br><span><?php echo get_field("_comp_table_inside_4", "options"); ?></span></th>
        </tr>
      </thead>
      <tbody>
          
          <?php
          $_comp_table_fieldlines = get_field("_comp_table_fieldlines","options");
          ?>
          
            <?php if ($_comp_table_fieldlines): ?>
             <?php foreach ($_comp_table_fieldlines as $item): ?>
          
                    <tr>
                      <td><?php echo $item['text']; ?></td>
                      <td class="bg-best"><span style="    background: #496d8f;" class="checkmark">✔</span></td>
                      <td class="bg-bad"><span class="crossmark">✖</span></td>
                    </tr>
                    
            <?php endforeach; ?>
        <?php endif; ?>
       
       
      </tbody>
    </table>

    <p class="small-note">
      <?php echo get_field("_comp_table_bottom_text", "options"); ?>
    </p>
  </div>
</section>



<!-- BUY THE PACKC ********************************** -->


<?php 
/************ get products by category homepage  ************/
$products = array();

// Check if the repeater field has rows
if (have_rows('homepage_section_5_product_list')) {
    while (have_rows('homepage_section_5_product_list')) {
        the_row();

        // Get the product field (post object)
        $product_post = get_sub_field('product');
        
        if ($product_post && $product_post instanceof WP_Post) {
            $product = wc_get_product($product_post->ID);
            if ($product instanceof WC_Product) {
                $products[] = $product;
            }
        }
    }
}



/************ get products by category homepage  ************/
?>


<section  style="background: white;"  class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
            <img style="display: block; margin: auto;" src="<?php echo get_field("homepage_section_45_img1"); ?>">
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;  "><?php echo get_field("homepage_section_45_h1"); ?></h2>

      <div style="    margin-left: 20px;" class="why-point">
        <p> <strong><?php echo get_field("homepage_section_45_t_1"); ?></strong></p>
        <p class="description"><?php echo get_field("homepage_section_45_t_2"); ?></p>
      </div>

      <div style="    margin-left: 20px;" class="why-point">
        <p> <strong><?php echo get_field("homepage_section_45_t_3"); ?></strong></p>
        <p class="description"><?php echo get_field("homepage_section_45_t_4"); ?></p>
      </div>

      <div style="    margin-left: 20px;" class="why-point">
        <p> <strong><?php echo get_field("homepage_section_45_t_5"); ?></strong></p>
        <p class="description"><?php echo get_field("homepage_section_45_t_6"); ?>.</p>
      </div>
    </div>

  </div>
</section>



<section class="most-popular" style="" >
  <div class="container">
    <h2 class="section-title"><?php echo get_field("homepage_section_5_t1"); ?></h2>
    <p class="section-subtitle"><?php echo get_field("homepage_section_5_t2"); ?></p>

    <div class="products-grid slider-mobile">
      <?php foreach ($products as $index => $product): ?>
      
       
        
      
        <?php
        $product_id = $product->get_id();
        $product_link = get_permalink($product_id);
        $product_name = $product->get_name();
       
     // Get featured image
        $product_image = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
        
        // Initialize array with featured image
        $all_images = array();
        
        if ($product_image) {
            $all_images[] = $product_image;
        }
        
        // Get gallery image IDs
        $gallery_image_ids = $product->get_gallery_image_ids();
        
        if (!empty($gallery_image_ids)) {
            foreach ($gallery_image_ids as $gallery_id) {
                $image_url = wp_get_attachment_image_url($gallery_id, 'woocommerce_thumbnail');
                if ($image_url) {
                    $all_images[] = $image_url;
                }
            }
        }

        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        
        if ($product->is_type('variable')) {
            $regular_price = $product->get_variation_regular_price('min', true);
            $sale_price = $product->get_variation_sale_price('min', true);
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
        }
                
        
        $is_on_sale = $product->is_on_sale();
        
        $discount = ($is_on_sale && $regular_price) ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
        
        ?>
        
         
        <div class="product-card" style="position: relative;">
           <a href="<?php echo $product_link; ?>"  style="position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 99;
    height: 100%;">
               
              <div class="image-wrapper">
                  
                  
 
<?php

$shirt_count = get_field('number_of_shirts_in_this_product', $product->get_id());
$alt_output = false;
$alt_output_text = get_field("singlepp_priceper_alternative_1piece","options");


if (empty($shirt_count) || $shirt_count == 0) {
    $shirt_count = 1;
}


$tmp_price = 0;


if ($product->is_type('variable')) {
    $tmp_price = $product->get_variation_sale_price('min', true);
} else {
    $tmp_price = $product->get_sale_price();
}

$tmp_price = $tmp_price / $shirt_count;
//$tmp_price = ceil($tmp_price * 100) / 100; // result: 18.99

$tmp_price = ceil($tmp_price * 100) / 100; // result: 18.99



if($shirt_count == 1 ) {
    $alt_output = true;
    $alt_output_text = get_field("singlepp_priceper_alternative_1piece","options");
}


// extra check if is multipack


if( get_field('multipack_option_1', get_the_ID())  == true  ) {
    $alt_output = true;
    $alt_output_text = get_field("singlepp_priceper_alternative_multipack","options");
}


$topseler_text =  get_field("singlepp_bestseller_text", "options");

 if( $shirt_count != 1): 

     if($alt_output == false): 
      $topseler_text =  get_field("singlepp_priceper_before","options") . " " . $tmp_price . " ".  get_field("singlepp_priceper_after","options"); 
    else:
    
     endif;

 endif; 
 


?>
    
    
    
    <?php if( $shirt_count != 1):  ?>
      <div class="top-liked"><?php echo $topseler_text; ?></div>
      <?php endif; ?>
                <?php
            $discount = 0;
            
            if ( $product->is_type( 'variable' ) ) {
                $regular_price = (float) $product->get_variation_regular_price( 'min', true );
                $sale_price    = (float) $product->get_variation_sale_price( 'min', true );
            } else {
                $regular_price = (float) $product->get_regular_price();
                $sale_price    = (float) $product->get_sale_price();
            }
            
            if ( $sale_price && $regular_price && $regular_price > $sale_price ) {
                $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                echo '<span class="badge">-' . esc_html( $discount ) .'' . get_field("singlepp_discount_text","options") .' </span>';
            }
            ?>
                
                  
                    <a style="display:none;" href="<?php echo $product_link; ?>" class="link-to-pp"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="2" fill="#F5A623"></rect>
                              <g clip-path="url(#clip0_24310_7887)">
                                <path d="M24 16C24 14.9391 23.5786 13.9217 22.8284 13.1716C22.0783 12.4214 21.0609 12 20 12C18.9391 12 17.9217 12.4214 17.1716 13.1716C16.4214 13.9217 16 14.9391 16 16H12V26C12 26.5304 12.2107 27.0391 12.5858 27.4142C12.9609 27.7893 13.4696 28 14 28H26C26.5304 28 27.0391 27.7893 27.4142 27.4142C27.7893 27.0391 28 26.5304 28 26V16H24ZM20 13.3333C20.7072 13.3333 21.3855 13.6143 21.8856 14.1144C22.3857 14.6145 22.6667 15.2928 22.6667 16H17.3333C17.3333 15.2928 17.6143 14.6145 18.1144 14.1144C18.6145 13.6143 19.2928 13.3333 20 13.3333Z" fill="white"></path>
                                </g>
                                <defs>
                                <clipPath id="clip0_24310_7887">
                                <rect width="16" height="16" fill="white" transform="translate(12 12)"></rect>
                              </clipPath>
                            </defs>
                          </svg></a>
                                      
                  
                  
                     <div class="glide">
                      <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                         
                                                <?php 
                                                foreach ($all_images as $image_url) {
                                                    echo '<li  class="glide__slide"><img style="" src="' . esc_url($image_url) . '"></li>';
                                                }
                                                ?>                   
                                                 </ul>
                                                
                                                <div class="glide__arrows" data-glide-el="controls">
                                                <button class="glide__arrow glide__arrow--left" data-glide-dir="<">  <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 6L9 12L15 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg></button>
                                                <button class="glide__arrow glide__arrow--right" data-glide-dir=">">  <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 6L15 12L9 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg></button>
                                              </div>
                                              
                                           <div class="glide__bullets" data-glide-el="controls[nav]">
                            <?php 
                            foreach ($all_images as $index => $image_url) {
                                echo '<button class="glide__bullet" data-glide-dir="=' . $index . '"></button>';
                            }
                            ?>
                        </div>
                                              
                                              </div>
                                            </div>
            </div>
            
            
            
              <div class="product-info">
                  <!--
                <div class="rating">
                    
                  <div class="stars">★★★★★</div>
                     <div class="reviews"><?php echo get_field('singlepp_reviews_text_1', 'option'); ?> <?php echo get_field('singlepp_reviews_text_2', 'option'); ?> </div>
                </div>
                -->
                <h3 style="font-size: 15px;
    margin-bottom: 0px;
    font-weight: 500;">NORIKS</h3>
                <h3 class="product-name"><?php echo esc_html($product_name); ?></h3>
                <div class="price">
                  <?php if ($is_on_sale): ?>
                    <span class="old-price"><?php echo wc_price($regular_price); ?></span>
                    <span class="current-price"><?php echo wc_price($sale_price); ?></span>
                  <?php else: ?>
                    <span class="current-price"><?php echo wc_price($regular_price); ?></span>
                  <?php endif; ?>
                </div>
              </div>
              
            </a>
              
              
              
        </div>
        
     
     
    
        
      <?php endforeach; ?>
    </div>

   <div class="container container--my-button ">
      <div style="background: transparent; padding: 0; justify-content: left;" class="cta-button">
        <a class="cta-button2 button button--xl"
           style="margin: 0 auto; text-align: left; background: black; font-family: 'Roboto', sans-serif; color: white; text-transform: none; font-size: 15px; padding: 10px 25px 10px 25px;"
           href="<?php echo get_field('homepage_section_5_b1_link'); ?>"><?php echo get_field('homepage_section_5_b1_text'); ?></a>
      </div>
    </div>
  </div>
</section>

<!-- BUY THE PACKC ********************************** -->


<?php 
$faq_list = get_field('faq_list', 'option');
$faq_list2 = get_field('faq_list_2', 'option');
$faq_list3 = get_field('faq_list_3', 'option');
?>




<section class="faq-section">
  <h2><?php echo get_field('faq_main_title', 'option'); ?></h2>
  

   <!-- first faq container --> 
      <div class="faq-container">
         <h4 style="text-align:left; font-size: 1rem;
            font-weight: 700;
            color: #222223;
            margin-bottom: 10px; "><?php echo get_field('faq_title_1', 'option'); ?></h4>
            <?php 
              if( $faq_list && is_array($faq_list) ): 
                      foreach( $faq_list as $faq_item ):
              ?>
                    <div class="faq-item">
                      <button class="faq-question">
                         <?php echo $faq_item["questioon"]; ?>
                        <span class="arrow">&#9660;</span>
                      </button>
                      <div class="faq-answer">
                        <p>  <?php echo $faq_item["answer"]; ?></p>
                      </div>
                    </div>
          <?php endforeach;
            endif;
            ?>
      </div>
    <!-- first faq container --> 
  
     <!-- 2 faq container --> 
      <div class="faq-container">
          <br/>
         <h4 style="text-align:left; font-size: 1rem;
            font-weight: 700;
            color: #001e36;
            margin-bottom: 10px; "><?php echo get_field('faq_title_2', 'option'); ?></h4>
            <?php 
              if( $faq_list2 && is_array($faq_list2) ): 
                      foreach( $faq_list2 as $faq_item ):
              ?>
                    <div class="faq-item">
                      <button class="faq-question">
                         <?php echo $faq_item["questioon"]; ?>
                        <span class="arrow">&#9660;</span>
                      </button>
                      <div class="faq-answer">
                        <p>  <?php echo $faq_item["answer"]; ?></p>
                      </div>
                    </div>
          <?php endforeach;
            endif;
            ?>
      </div>
        <!-- 2 faq container --> 
  
     <!-- 3 faq container --> 
      <div class="faq-container">
          <br/>
         <h4 style="text-align:left; font-size: 1rem;
            font-weight: 700;
            color: #001e36;
            margin-bottom: 10px; "><?php echo get_field('faq_title_3', 'option'); ?></h4>
            <?php 
              if( $faq_list3 && is_array($faq_list3) ): 
                      foreach( $faq_list3 as $faq_item ):
              ?>
                    <div class="faq-item">
                      <button class="faq-question">
                         <?php echo $faq_item["questioon"]; ?>
                        <span class="arrow">&#9660;</span>
                      </button>
                      <div class="faq-answer">
                        <p>  <?php echo $faq_item["answer"]; ?></p>
                      </div>
                    </div>
          <?php endforeach;
            endif;
            ?>
      </div>
  <!-- 3 faq container --> 
  
</section>


<script>
  document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const faqAnswer = button.nextElementSibling;
      const arrow = button.querySelector('.arrow');

      if (faqAnswer.style.maxHeight) {
        faqAnswer.style.maxHeight = null;
        arrow.style.transform = 'rotate(0deg)';
      } else {
        document.querySelectorAll('.faq-answer').forEach(item => {
          item.style.maxHeight = null;
        });
        document.querySelectorAll('.arrow').forEach(item => {
          item.style.transform = 'rotate(0deg)';
        });
        faqAnswer.style.maxHeight = faqAnswer.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
      }
    });
  });
</script>
			


		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();



