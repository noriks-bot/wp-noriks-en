<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
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

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;  "><?php echo get_field("singlepp_content_part_h1","options"); ?></h2>

      <div style="    margin-left: 20px;"  class="why-point">
        <p> <strong><?php echo get_field("singlepp_content_part_t_1","options"); ?></strong></p>
        <p class="description"><?php echo get_field("singlepp_content_part_t_2","options"); ?></p>
      </div>

      <div style="    margin-left: 20px;" class="why-point">
        <p> <strong><?php echo get_field("singlepp_content_part_t_3","options"); ?></strong></p>
        <p class="description"><?php echo get_field("singlepp_content_part_t_4","options"); ?></p>
      </div>

      <div  style="    margin-left: 20px;" class="why-point">
        <p> <strong><?php echo get_field("singlepp_content_part_t_5","options"); ?></strong></p>
        <p class="description"><?php echo get_field("singlepp_content_part_t_6","options"); ?></p>
      </div>
    </div>

  </div>
</section>
<style>
  
  </style>

<!--
  <section class="reviews-section">
      <div class="container" style="width: 100%;
    max-width: 1100px;
    margin: 0 auto;">
    <div class="reviews-rating">
      <span class="stars" style="margin-right: 0;">★★★★★</span>
      <span><?php echo get_field("singlepp_content_bigrevies_t1", "option") ?></span>
    </div>

    <h2><?php echo get_field("singlepp_content_bigrevies_t2", "option") ?></h2>

    
    <div class="reviews-grid">

    <?php 
    $bigreviews_reviews_fields = get_field("bigreviews_reviews_fields", "option");
    //var_dump($header_nav);
    ?>
    

      <?php if ($bigreviews_reviews_fields): ?>
    <?php foreach ($bigreviews_reviews_fields as $item): ?>

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
  -->
  
  
<!-- table section -->

  
  
<section class="comparison-section" style="padding-top: 30px;" >
    <div class="comparison-intro">
     <!-- <h4 class="highlight"><?php echo get_field("_comp_table_t1", "options"); ?></h4>-->
      <h1 style="color:white;"><?php echo get_field("_comp_table_t2", "options"); ?></h1>
      <p style="    opacity: 0.6;" class="note"><?php echo get_field("_comp_table_t3", "options"); ?></p>
    </div>
  </section>
  
  
<section class="comparison-table-section">
 
 <div class="comparison-container">
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
                      <td class="bg-best"><span  style="background: #496d8f;" class="checkmark">✔</span></td>
                      <td class="bg-bad"><span class="crossmark">✖</span></td>
                    </tr>
                    
            <?php endforeach; ?>
        <?php endif; ?>
       
       
      </tbody>
    </table>

    <p style="    opacity: 0.6;" class="small-note">
      <?php echo get_field("_comp_table_bottom_text", "options"); ?>
    </p>
  </div>
</section>


  
<!-- table section -->







<?php 
/************ get products by category homepage  ************/
$products = array();

// Check if the repeater field has rows
if (have_rows('homepage_section_2_product_list', 76)) {
    while (have_rows('homepage_section_2_product_list', 76)) {
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


//var_dump($products);

/************ get products by category homepage  ************/
?>



<style>
    
    
    
    @media (min-width: 769px) {
.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* 4 columns on desktop */
  gap: 15px;
  justify-content: center;
}
}



.product-card {
  background: #fff;
  border-radius: 0px;
  overflow: hidden;
  text-align: left;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.image-wrapper {
  background: transparent;
  position: relative;

  display: flex;
  align-items: center;
  justify-content: center;
}

.image-wrapper img {

}

.badge {
  position: absolute;
  top: 10px;
  right: 10px;
 background-color: #971b1b;
    color: white;
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    font-size: 10px;
    padding: 2px 8px 1px 8px;
    border-radius: 2px;
    text-transform: uppercase;
     z-index: 9999999999;
     
}

.top-liked {
  position: absolute;
  top: 10px;
  left: 10px;
 background-color: #496d8f;
    color: white;
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    font-size: 10px;
    padding: 2px 8px 1px 8px;
    border-radius: 2px;
    text-transform: uppercase;
    z-index: 99999999999;
}

.product-info {
  padding: 15px 15px 20px 15px;
  text-align: center;
}

.stars {
  font-size: 14px;
  color: #ffc107;
  margin-bottom: 0px;
}

.reviews {
  color: #333;
  font-weight: 400;
  margin-left: 5px;
}

.product-name {
     font-family: 'Roboto', sans-serif;
    text-align: left;
    font-weight: 600 !important;
    margin-bottom: 0px !important;
    font-size: 1rem;
    text-align: center;
}

.price {
  margin-top: 0px;
}



.current-price {
  font-size: 16px;
  color: #971b1b;
  margin-right: 8px;
}

.old-price {
  font-size: 16px;
  color: black;
  text-decoration: line-through;
  margin-right: 5px;
}




    
    
</style>


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
     z-index: 99999;
}

.glide__bullet--active  {
   background: rgba(0, 0, 0, 0.22);
   
}
.glide__bullet  {
   padding: .4em .4em;
   margin-left: 2px;
    margin-right: 2px;
     z-index: 99999;

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


.section-title {

    font-size: 1.7rem;
        font-weight: 800;
        line-height: 1.1;
        text-align:center;

}


.section-subtitle   {
text-align:center;

}

</style>



<section style="display: block;
    max-width: 1440px;
    margin: 0 auto;
    padding-bottom: 30px;" class="most-popular">
  <div class="container" style="padding-left: 10px; padding-right: 10px;">
    <h2 class="section-title" style="margin-bottom: 20px;">Prepared combinations – simple and fast</h2>
    <!--<p class="section-subtitle">Pogledaj i ove produkte</p>-->

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
                
                <h3 style="font-size: 15px; margin-bottom: 0px;

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

  
  
  </div>
</section>


















  
  
  <style>
      
      .comparison-section-gray  {
         border-radius: 5px;
        }
              
      .comparison-intro-gray  {
           margin-bottom: 0;
        }
      
  </style>
  <div  style="background: #f9f9f9; padding-top: 30px;" >
<section style="background: #f9f9f9; max-width: 1440px;" class="comparison-section comparison-section-gray">
    <div style="background: #f9f9f9;padding: 0;padding-left: 10px;
    padding-right: 10px;" class="comparison-intro comparison-intro-gray ">
      <!--<h4 style="" class="highlight"><?php echo get_field("singlepp_content_standard_reviews_t1","options"); ?></h4>-->
      <h1 style="color:black;     margin-bottom: 4px;"><?php echo get_field("singlepp_content_standard_reviews_t2","options"); ?></h1>
    <p class="note" style="color: black; margin-top: 0px; margin-bottom: 5px;"><?php echo get_field("singlepp_content_standard_reviews_t3","options"); ?></p>
    </div>
  </section>
  </div>
  
  
  <style>
      @media (max-width: 768px) {
          
          .basic-reviews-section  {
               padding-left: 20px;
               padding-right: 20px;
            }
            .review .content {
                font-size: 13px;
            }
            .review .info {
                font-size: 13px;
                line-height: 1.3;
            }
            .review {
  
                padding-bottom: 15px;
                margin-bottom: 16px;

            }
      }
  </style>
  
  
  <style>
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #f5a623;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 0.8s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.extra-review-group {
  opacity: 0;
  transition: opacity 0.5s ease;
}

.extra-review-group.show {
  opacity: 1;
}
</style>




<?php 
  // ===== CONFIG: LANGUAGE & DATA =====
  $reviews_language = get_field("webshop_language", "options");
  if (!$reviews_language) { $reviews_language = "EN"; }

  include get_stylesheet_directory() . '/auto_reviews/'.$reviews_language.'.php';
  include get_stylesheet_directory() . '/auto_reviews/'.$reviews_language.'-2.php';

  // Ensure arrays exist
  $auto_reviews_en   = is_array($auto_reviews_en)   ? $auto_reviews_en   : [];
  $auto_reviews_ship = isset($auto_reviews_ship) && is_array($auto_reviews_ship) ? $auto_reviews_ship : [];

  // ===== HELPERS: STABLE DAILY RANDOMIZATION =====

  /**
   * Get WP/Woo timezone (fallback Europe/Ljubljana).
   */
  function reviews_wp_tz(): DateTimeZone {
    $tz_string = function_exists('wp_timezone_string') ? wp_timezone_string() : (get_option('timezone_string') ?: 'Europe/Ljubljana');
    return new DateTimeZone($tz_string ?: 'Europe/Ljubljana');
  }

  /**
   * Deterministic "random" integer in [0, $mod-1] from a seed string.
   */
  function stable_mod_index(string $seed, int $mod): int {
    if ($mod <= 0) return 0;
    $h = substr(sha1($seed), 0, 8);            // 32-bit slice
    $n = hexdec($h);                            // unsigned int
    return (int) ($n % $mod);
  }

  /**
   * Deterministic shuffle based on a seed string. (Stable for a given seed.)
   */
  function shuffle_with_seed(array $arr, string $seed): array {
    if (empty($arr)) return $arr;
    $keys = array_keys($arr);
    usort($keys, function($a, $b) use ($seed) {
      $ha = sha1($seed . ':' . $a);
      $hb = sha1($seed . ':' . $b);
      return strcmp($ha, $hb);
    });
    $out = [];
    foreach ($keys as $k) { $out[] = $arr[$k]; }
    return $out;
  }

  /**
   * Build/caches a pool of products: [['title'=>..., 'url'=>...], ...]
   */
  function get_wc_product_pool($transient_key = 'reviews_product_pool_cache', $ttl = 12 * HOUR_IN_SECONDS) {
    if (function_exists('get_transient')) {
      $cached = get_transient($transient_key);
      if (!empty($cached) && is_array($cached)) return $cached;
    }

    if (!function_exists('wc_get_products')) return [];

    $ids = wc_get_products([
      'status'  => 'publish',
      'limit'   => -1,
      'return'  => 'ids',
      'orderby' => 'date',
      'order'   => 'DESC',
    ]);

    $pool = [];
    if (!empty($ids)) {
      foreach ($ids as $pid) {
        $title = get_the_title($pid);
        $url   = get_permalink($pid);
        if ($title && $url) $pool[] = ['title'=>$title, 'url'=>$url];
      }
    }

    if (function_exists('set_transient')) set_transient($transient_key, $pool, $ttl);
    return $pool;
  }

  /**
   * Assign a deterministic product (title+url) to each review for the day.
   * Stable per day AND per review index.
   */
  function assign_products_stable(array $reviews, array $product_pool, string $daily_seed): array {
    $count = count($product_pool);
    foreach ($reviews as $i => &$r) {
      if ($count > 0) {
        $pick = $product_pool[ stable_mod_index($daily_seed . ':prod:' . $i, $count) ];
        $r['product_title'] = $pick['title'];
        $r['product_url']   = $pick['url'];
      } else {
        $r['product_title'] = $r['product_title'] ?? '';
        $r['product_url']   = $r['product_url']   ?? '';
      }
    }
    return $reviews;
  }

  /**
   * Distribute review dates backward from today to a cutoff date (inclusive),
   * with a deterministic per-day count using the daily seed.
   * Cutoff date format uses 'j.n.Y' (e.g., '20.6.2025').
   */
  function assign_dates_stable(array $reviews, string $cutoff_date_string = '20.6.2025', int $min_per_day = 2, int $max_per_day = 9, string $display_format = 'j.n.Y'): array {
    if (empty($reviews)) return $reviews;

    $tz      = reviews_wp_tz();
    $today   = new DateTime('today', $tz);
    $cutoff  = DateTime::createFromFormat('j.n.Y', $cutoff_date_string, $tz) ?: new DateTime('20.6.2025', $tz);
    if ($cutoff > $today) $cutoff = clone $today;

    // Deterministic order of reviews for the day
    $daily_seed = $today->format('Y-m-d');
    $reviews    = shuffle_with_seed($reviews, 'reviews-order:' . $daily_seed);

    $total    = count($reviews);
    $assigned = 0;
    $day_off  = 0;

    while ($assigned < $total) {
      $date = (clone $today)->modify("-{$day_off} days");
      if ($date < $cutoff) $date = clone $cutoff;

      // Deterministic "random" per-day bucket size.
      $span   = max(0, $max_per_day - $min_per_day);
      $add    = ($span > 0) ? (stable_mod_index('perday:'.$daily_seed.':'.$day_off, $span + 1)) : 0;
      $perday = $min_per_day + $add;

      $take = min($perday, $total - $assigned);
      for ($i = 0; $i < $take; $i++) {
        $reviews[$assigned]['assigned_date'] = $date->format($display_format);
        $assigned++;
      }

      $day_off++;
      if ($date == $cutoff && $assigned >= $total) break;
    }

    foreach ($reviews as &$r) {
      if (empty($r['assigned_date'])) $r['assigned_date'] = $cutoff->format($display_format);
    }
    return $reviews;
  }

  // ===== BUILD FOR TODAY =====
  $tz         = reviews_wp_tz();
  $today_obj  = new DateTime('today', $tz);
  $daily_seed = $today_obj->format('Y-m-d'); // stable for the whole day

  $product_pool = get_wc_product_pool();

  // 1) Stable daily shuffle of review pools
  $auto_reviews_en   = shuffle_with_seed($auto_reviews_en,   'pool-en:'   . $daily_seed);
  $auto_reviews_ship = shuffle_with_seed($auto_reviews_ship, 'pool-ship:' . $daily_seed);

  // 2) Stable product assignment for the day
  $auto_reviews_en   = assign_products_stable($auto_reviews_en,   $product_pool, $daily_seed);
  $auto_reviews_ship = assign_products_stable($auto_reviews_ship, $product_pool, $daily_seed);

  // 3) Deterministic date distribution back to cutoff 20.06.2025
  $auto_reviews_en   = assign_dates_stable($auto_reviews_en,   '20.6.2025', 2, 9, 'j.n.Y');
  $auto_reviews_ship = assign_dates_stable($auto_reviews_ship, '20.6.2025', 2, 9, 'j.n.Y');

  // ===== PAGINATION CHUNKS =====
  $initial_count = 18;   // show on load
  $load_count    = 9;    // per "load more"

  $initial_product   = array_slice($auto_reviews_en, 0, $initial_count);
  $remaining_product = array_slice($auto_reviews_en, $initial_count);
  $chunks_product    = array_chunk($remaining_product, $load_count);

  $initial_ship   = array_slice($auto_reviews_ship, 0, $initial_count);
  $remaining_ship = array_slice($auto_reviews_ship, $initial_count);
  $chunks_ship    = array_chunk($remaining_ship, $load_count);

  // Dynamic counts
  $prod_count = count($auto_reviews_en);
  $ship_count = count($auto_reviews_ship);
?>

<section id="reviews-section" class="basic-reviews-section" style="margin-bottom:40px!important;padding-bottom:40px!important;">
  <div class="container basic-reviews-section-container" style="width:100%;max-width:1440px;padding-top:20px!important;margin:0 auto;padding-left: 10px; padding-right: 10px;">

    <!-- Tabs -->
    <div class="reviews-tabs" style="display:flex;gap:18px;border-bottom:1px solid #cbc8c8;margin-bottom:18px;">
      <button type="button" class="reviews-tab is-active" data-tab="product"
        style="appearance:none;background:#00000008;border:1px solid #cbc8c8;border-bottom:0;padding:8px 14px;border-radius:0;font-weight:700;">
        <?php echo get_field("singlepp_content_standard_reviews_PRODUCT_reviews","options"); ?> (692)
      </button>
      <button type="button" class="reviews-tab" data-tab="shipping"
        style="appearance:none;background:transparent;border:1px solid transparent;border-bottom:0;padding:8px 14px;border-radius:0;font-weight:700;">
        <?php echo get_field("singlepp_content_standard_reviews_SERVICE_reviews","options"); ?> (389)
      </button>
    </div>

    <!-- PRODUCT GRID (default visible) -->
    <div class="reviews-grid" id="reviews-grid-product">
      <?php if (!empty($initial_product)) : foreach ($initial_product as $review) :
        $name  = $review['name'] ?? 'Anonymní';
        $text  = $review['text'] ?? '';
        $title = !empty($review['product_title']) ? $review['product_title'] : 'Jedna Siva Majica';
        $url   = !empty($review['product_url'])   ? $review['product_url']   : '#';
        $stars = '★★★★★';
        $date_display = $review['assigned_date'] ?? '';
      ?>
        <article class="review-card">
          <div class="card-top">
            <h3 class="product-title"><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($title); ?></a></h3>
            <div class="date">
              <?php echo esc_html($date_display); ?>
            </div>
          </div>
          <div class="stars"><?php echo $stars; ?></div>
          <div class="identity">
            <div class="avatar">👤</div>
            <div class="name"><?php echo esc_html($name); ?></div>
            <span class="verified"><?php echo get_field("singlepp_content_standard_reviews_verified_text","options"); ?></span>
          </div>
          <div class="content"><?php echo esc_html($text); ?></div>
        </article>
      <?php endforeach; endif; ?>
    </div>

    <!-- SHIPPING GRID (hidden initially) -->
    <div class="reviews-grid" id="reviews-grid-shipping" style="display:none;">
      <?php if (!empty($initial_ship)) : foreach ($initial_ship as $review) :
        $name  = $review['name'] ?? 'Anonymní';
        $text  = $review['text'] ?? '';
        $title = !empty($review['product_title']) ? $review['product_title'] : 'Jedna Siva Majica';
        $url   = !empty($review['product_url'])   ? $review['product_url']   : '#';
        $stars = '★★★★★';
        $date_display = $review['assigned_date'] ?? '';
      ?>
        <article class="review-card">
          <div class="card-top">
            <h3 class="product-title">
              <a href="<?php echo esc_url($url); ?>">
                <?php echo esc_html($title); ?>
              </a>
            </h3>
            <div class="date">
              <?php echo esc_html($date_display); ?>
            </div>
          </div>
          <div class="stars"><?php echo $stars; ?></div>
          <div class="identity">
            <div class="avatar">👤</div>
            <div class="name"><?php echo esc_html($name); ?></div>
            <span class="verified"><?php _e('Potvrđeno','your-textdomain'); ?></span>
          </div>
          <?php if (!empty($review['headline'])) : ?>
            <div class="headline"><?php echo esc_html($review['headline']); ?></div>
          <?php endif; ?>
          <div class="content"><?php echo esc_html($text); ?></div>
        </article>
      <?php endforeach; endif; ?>
    </div>

  </div>

  <!-- Controls: one CTA row, reused per tab -->
  <div class="container basic-reviews-section-container" style="width:100%;max-width:1100px;margin-top:30px!important;margin:0 auto;">
    <div class="cta-button" style="background:transparent;padding:0;justify-content:left;">
      <a class="cta-button2 button button--xl"
         style="margin:0 auto;text-align:left;background:black;font-family:'Roboto',sans-serif;color:#fff;text-transform:none;font-size:15px;padding:10px 25px;"
         href="#"><?php echo get_field("singlepp_content_standard_reviews_seemore_button","options"); ?></a>
    </div>
    <div id="reviews-loading" style="display:none;text-align:center;padding:15px;">
      <div class="loader"></div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    // Data from PHP (already include product_title/product_url/assigned_date)
    const chunksProduct = <?php echo json_encode($chunks_product); ?>;
    const chunksShip    = <?php echo json_encode($chunks_ship); ?>;

    let nextProduct = 0;
    let nextShip    = 0;

    const tabs    = document.querySelectorAll('.reviews-tab');
    const gridP   = document.getElementById('reviews-grid-product');
    const gridS   = document.getElementById('reviews-grid-shipping');
    const seeMore = document.querySelector('.cta-button2');
    const loader  = document.getElementById('reviews-loading');

    let activeTab = 'product';

    function setTab(tab){
      activeTab = tab;
      tabs.forEach(t=>{
        if(t.dataset.tab === tab){ t.classList.add('is-active'); t.style.background='#00000008'; t.style.borderColor='#e6e6e6'; }
        else{ t.classList.remove('is-active'); t.style.background='transparent'; t.style.borderColor='transparent'; }
      });
      if(tab === 'product'){ gridP.style.display='grid'; gridS.style.display='none'; }
      else{ gridP.style.display='none'; gridS.style.display='grid'; }

      const moreAvail = tab === 'product'
        ? (nextProduct < (chunksProduct?.length || 0))
        : (nextShip < (chunksShip?.length || 0));
      if (seeMore) seeMore.style.display = moreAvail ? 'inline-block' : 'none';
    }

    setTab('product');
    tabs.forEach(btn => btn.addEventListener('click', ()=> setTab(btn.dataset.tab)));

    // Escape helper
    const esc = (str) => String(str ?? '').replace(/[&<>"']/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
    }[s]));

    // Append a chunk of cards into a grid (uses product_title/product_url/assigned_date from PHP)
    function appendChunk(grid, chunk){
      chunk.forEach(function(review){
        const article = document.createElement('article');
        article.className = 'review-card is-new';

        const url      = review.product_url   || '#';
        const title    = review.product_title || 'Jedna Siva Majica';
        const name     = review.name          || 'Anonymní';
        const text     = review.text          || '';
        const headline = review.headline      || '';
        const date     = review.assigned_date || '';

        article.innerHTML = `
          <div class="card-top">
            <h3 class="product-title"><a href="${esc(url)}">${esc(title)}</a></h3>
            <div class="date">${esc(date)}</div>
          </div>
          <div class="stars">★★★★★</div>
          <div class="identity">
            <div class="avatar">👤</div>
            <div class="name">${esc(name)}</div>
            <span class="verified"><?php _e('Potvrđeno','your-textdomain'); ?></span>
          </div>
          ${headline ? `<div class="headline">${esc(headline)}</div>` : ''}
          <div class="content">${esc(text)}</div>
        `;
        grid.appendChild(article);
      });
    }

    seeMore && seeMore.addEventListener('click', function(e){
      e.preventDefault();
      seeMore.style.display='none';
      loader.style.display='block';

      setTimeout(function(){
        if(activeTab === 'product' && nextProduct < (chunksProduct?.length || 0)){
          appendChunk(gridP, chunksProduct[nextProduct]);
          nextProduct++;
        }else if(activeTab === 'shipping' && nextShip < (chunksShip?.length || 0)){
          appendChunk(gridS, chunksShip[nextShip]);
          nextShip++;
        }
        loader.style.display='none';
        const moreAvail = activeTab === 'product'
          ? (nextProduct < (chunksProduct?.length || 0))
          : (nextShip < (chunksShip?.length || 0));
        if(moreAvail) seeMore.style.display='inline-block';
      }, 400);
    });
  });
</script>





  
  
    
   
<!-- new review styling --> 
    
    
    <style>
    
/* ===== Reviews: Full corrected CSS ===== */

/* Section + container */
#reviews-section{
  font-family: "Roboto", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
  background:#f9f9f9;
}
.basic-reviews-section-container{
  max-width:1440px;       /* full-width desktop container */
  margin:0 auto;
  padding:0 0px;         /* comfy side padding */
}

/* Tabs (simple) */
.reviews-tabs{ display:flex; gap:18px; border-bottom:1px solid #eee; margin-bottom:18px; }
.reviews-tab{
  appearance:none; background:transparent; border:1px solid transparent; border-bottom:0;
  padding:8px 14px; font-weight:700; cursor:pointer;
}
.reviews-tab.is-active{ background:#00000008; border-color:#e6e6e6; }

/* Grid */
.reviews-grid{
  display:grid;
  grid-template-columns:repeat(3, 1fr);   /* 3 in a row on desktop */
  gap:10px;                                /* breathing room between cards */
  width:100%;
}
@media (max-width:1100px){
  .reviews-grid{ grid-template-columns:repeat(2, 1fr); }
}
@media (max-width:640px){
  .reviews-grid{ grid-template-columns:1fr; }  /* 1 per row on mobile */
}

/* Card */
.review-card{
     width: 100%;              /* ✅ stretch card to full column width */
  height: 100%;             /* optional: make all equal height */
    
    
  background:#fff;
  border:1px solid #efefef;
  border-radius:4px;
     box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
  padding:18px 20px;
  height:100%;                 /* equal height rows */
  display:flex; flex-direction:column;
}

/* Card top: product title + date */
.review-card .card-top{
  display:flex; align-items:flex-start; justify-content:space-between; gap:12px;
  margin:-2px 0 6px;
}
.review-card .product-title{
  margin:0; font-weight:800; font-size:16px; line-height:1.25;
}
.review-card .product-title a{
  color:#0e0e0e; text-decoration:underline; text-underline-offset:2px;
}
.review-card .date{
  color:#8c8c8c; font-size:13px; white-space:nowrap; margin-top:2px;
}

/* Stars */
.review-card .stars{
  letter-spacing:3px; font-size:18px; color:#0f0f0f; margin:2px 0 10px;
}

/* Identity row */
.review-card .identity{
  display:flex; align-items:center; gap:10px; margin:2px 0 12px;
}
.review-card .avatar{
  width:32px; height:32px; border:1px solid #dfdfdf; border-radius:0px;
  display:flex; align-items:center; justify-content:center; font-size:18px; color:#000; background:#fff;
}
.review-card .name{ font-weight:700; color:#111; font-size:15px; }
.review-card .verified{
  display:inline-block; background:#0f0f0f; color:#fff;
  font-size:12px; font-weight:700; line-height:1;
  padding:5px 8px 4px; border-radius:3px; margin-left:6px;
}

/* Headline + body */
.review-card .headline{ font-weight:800; font-size:16px; color:#111; margin:6px 0 6px; }
.review-card .content{ color:#2b2b2b; font-size:15px; line-height:1.7; }

/* Nice reveal for newly appended cards */
.review-card.is-new{ animation:rv-fade .28s ease-out both; }
@keyframes rv-fade{ from{opacity:0; transform:translateY(6px);} to{opacity:1; transform:none;} }

/* Load more CTA + loader */
.cta-button2.button{
  
  
}
#reviews-loading .loader{
  width:28px; height:28px; border:3px solid #e6e6e6; border-top-color:#111; border-radius:50%;
  margin:0 auto; animation:rv-spin .75s linear infinite;
}
@keyframes rv-spin{ to{ transform:rotate(360deg);} }

 
    </style>
  
  
  
  
  


<!-- new review styling --> 
  
  

<?php 
$faq_list = get_field('faq_list', 'option');
$faq_list2 = get_field('faq_list_2', 'option');
$faq_list3 = get_field('faq_list_3', 'option');
?>




<section class="faq-section">
  <h2><?php echo get_field("singlepp_content_part_faq_h1","options"); ?></h2>
  

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
		


