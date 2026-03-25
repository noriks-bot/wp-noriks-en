<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">


<?php wp_head(); ?>


<!-- SqualoMail Popup -->
<!--
<script type="text/javascript" src="https://6096.squalomail.net/forms/1/popup.js" async></script>
-->


<script>
document.addEventListener('DOMContentLoaded', function () {
  const qtyInput = document.querySelector('input.qty');
  const insPrice = document.querySelector('p.price ins .amount bdi');
  const delPrice = document.querySelector('p.price del .amount bdi');

  if (!qtyInput || !insPrice || !delPrice) return;

  const baseSale = parseFloat(insPrice.textContent.replace(',', '.').replace(/[^\d.]/g, ''));
  const baseRegular = parseFloat(delPrice.textContent.replace(',', '.').replace(/[^\d.]/g, ''));

  if (isNaN(baseSale) || isNaN(baseRegular)) return;

  function formatPrice(price) {
    return price.toFixed(2).replace('.', ',') + ' €';
  }

  function updatePrice() {
    const qty = parseInt(qtyInput.value);
    if (isNaN(qty) || qty < 1) return;

    // Regular price = no discount
    const totalRegular = baseRegular * qty;
    delPrice.textContent = formatPrice(totalRegular);

    // Sale price = apply discount if qty is 2 or more
    let discount = 0;
    
    if (qty === 2) {
      discount = 0.05;
    } else if (qty >= 3 && qty < 9 ) {
      discount = 0.1;
    }else if (qty >= 9 && qty < 12) {
      discount = 0.2;
    }
    else if (qty >= 12) {
      discount = 0.3;
    }
    
    console.log(qty);
    console.log(discount);

    const discountedUnitPrice = baseSale * (1 - discount);
    const totalSale = discountedUnitPrice * qty;
    insPrice.textContent = formatPrice(totalSale);
  }

  // Listen for quantity change
  qtyInput.addEventListener('input', updatePrice);

  // Fallback in case quantity is changed by other scripts
  let lastQty = qtyInput.value;
  setInterval(() => {
    if (qtyInput.value !== lastQty) {
      lastQty = qtyInput.value;
      updatePrice();
    }
  }, 100);

  // Initial run on load
  updatePrice();
});
</script>



<!-- Hotjar Tracking Code for https://noriks.com/hr -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:6473098,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>


</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>
	
	
	
	
	<div class="top-header" style="text-align:center;padding-top:10px;padding-bottom:2px;background-color:#d5d5d5;">
  <div class="top-rotator">
    <h3 class="rotator-item first" style="color:black;font-size:14px;font-weight:bold;margin:0;">
      <a href="/shop" style="color:black;font-size:14px;font-weight:bold;"><?php echo get_field("topheader_text","option"); ?></a>
    </h3>

    <h3 class="rotator-item" style="color:black;font-size:14px;font-weight:bold;margin:0;">
      <a href="/shop" style="color:black;font-size:14px;font-weight:bold;"><?php echo get_field("topheader_text2","option"); ?></a>
    </h3>

    <h3 class="rotator-item" style="color:black;font-size:14px;font-weight:bold;margin:0;">
      <a href="/shop" style="color:black;font-size:14px;font-weight:bold;"><?php echo get_field("topheader_text3","option"); ?></a>
    </h3>
  </div>
</div>

<style>
  .top-rotator {
    position: relative;
    display: inline-block;
    min-height: 22px;
    overflow: hidden;
    width: 100%;
  }

  .rotator-item {
    position: absolute;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    opacity: 0;
    transform: translateX(100%);
    pointer-events: none;
  }
  .rotator-item a { pointer-events: auto; }

  /* First cycle: first one visible immediately */
  .rotator-item.first {
    opacity: 1;
    transform: translateX(0);
    animation: firstSlideOnce 12s 1;
  }

  /* Others rotate normally */
  .rotator-item:nth-child(2) { animation: slideRotate 12s infinite; animation-delay: 4s; }
  .rotator-item:nth-child(3) { animation: slideRotate 12s infinite; animation-delay: 8s; }

  /* First one only on the first cycle */
  @keyframes firstSlideOnce {
    0%   { opacity: 1; transform: translateX(0); }
    30%  { opacity: 1; transform: translateX(0); }
    35%  { opacity: 0; transform: translateX(-100%); }
    100% { opacity: 0; transform: translateX(-100%); }
  }

  /* Normal rotating slides */
  @keyframes slideRotate {
    0%   { opacity: 0; transform: translateX(100%); }
    5%   { opacity: 1; transform: translateX(0); }
    30%  { opacity: 1; transform: translateX(0); }
    35%  { opacity: 0; transform: translateX(-100%); }
    100% { opacity: 0; transform: translateX(-100%); }
  }
</style>

<script>
  // After the first 12s cycle, convert first item to normal animation
  setTimeout(() => {
    const first = document.querySelector('.rotator-item.first');
    if (first) {
      first.classList.remove('first');
      first.style.animation = "slideRotate 12s infinite";
      first.style.animationDelay = "0s";
    }
  }, 12000);
</script>


	
	
	
	<header class="navbar header">
	    
	    	<?php
	    	
	    //	die();
		/**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_header_container                 - 0
		 * @hooked storefront_skip_links                       - 5
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_header_container_close           - 41
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */
		//do_action( 'storefront_header' );

?>


 <div class="container container-header">
     
      <!-- Hamburger Icon (Visible on mobile only) -->
    <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
      ☰
    </div>
     
     
    <div class="navbar-left">
      <a href="<?php echo get_home_url(); ?>">
        <span style="color: white; font-family: 'Roboto', sans-serif; font-size: 33px; font-weight: bold; letter-spacing: 1.75px;">NORIKS</span>
        <span style="color: white; display: block; font-size: 10px; font-family: 'Roboto', sans-serif; margin-top: -14px; letter-spacing: 0.38px; margin-left: 1px;">Simple Shirts, Done Better</span>
      </a>
    </div>

 

    <!-- Mobile + Desktop Navigation -->
    <?php $header_nav = get_field("mainheader_menu", "option"); ?>
    <nav class="navbar-center mobile-hidden" id="mobileMenu">
        <button class="mobile-menu-close mobile-only " onclick="toggleMobileMenu()">×</button>
      <?php if ($header_nav): ?>
        <?php foreach ($header_nav as $item): ?>
          <?php $link = $item['link']; $text = $item['text']; ?>
          <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($text); ?></a>
        <?php endforeach; ?>
      <?php endif; ?>
      <!--
            <a class="mobile-only-menu-item" href="tel:+38518801114" style="color: white;">
  <i class="fas fa-phone" style="margin-right: 8px;"></i>+385 188 011 14
</a>
-->
<a class="mobile-only-menu-item" href="mailto:info@noriks.com" style="color: white;">
  <i class="fas fa-envelope" style="margin-right: 8px;"></i>info@noriks.com
</a>
      
        <div class="language-selector mobile-only" onclick="openLanguageModal()">
          <img src="https://static.devit.software/countries/flags/rectangle/<?php echo get_field("webshop_icon", "options"); ?>" alt="" class="flag">
          <span><?php echo get_field("webshop_language", "options"); ?></span>
        </div>

    </nav>

    <div class="navbar-right">
      <div class="language-selector hidden-mobile" onclick="openLanguageModal()">
        <img src="https://static.devit.software/countries/flags/rectangle/<?php echo get_field("webshop_icon", "options"); ?>" alt="" class="flag">
        <span><?php echo get_field("webshop_language", "options"); ?></span>
      </div>

      <div class="cart-auto-icon">
        <?php echo do_shortcode('[xoo_wsc_cart]'); ?>
        <style>
          .xoo-wsc-sc-bki {
            color: white !important;
          }
          .xoo-wsc-sc-count {
            background-color: #971b1b !important;
            color: white !important;
          }
        </style>
      </div>
    </div>
  </div>
</header>


<!-- mobile nav styling -->
<style>

.xoo-wsc-sc-subt  {
        display: none !important;
  }

.language-selector {
  display: none; /* Hide by default */
}


@media (min-width: 769px) {
      .mobile-only  {
        display: none !important;
  }
    
}

@media (max-width: 768px) {
    
   .hidden-mobile  {
        display: none !important;
  }
    
    
  .language-selector {
    display: flex;
    align-items: center;
    margin-top: 30px;
    gap: 10px;
    color: white;
    cursor: pointer;
  }

  .language-selector img {
    width: 24px;
    height: auto;
  }

  .language-selector span {
    font-size: 14px;
    font-family: 'Roboto', sans-serif;
  }
}


@media (max-width: 768px) {
  .mobile-menu-close {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 14.8px;
    background: white;
    border: none;
    color: black;
    cursor: pointer;
    z-index: 10000;
  }

  .mobile-menu-close:hover {
    color: #ccc;
  }
}


/* Hide hamburger by default */
.mobile-menu-toggle {
  display: none;
  font-size: 33px;
  color: white;
  cursor: pointer;
}

/* Base styles */
.navbar-center {
  display: flex;
  gap: 20px;
}

@media (max-width: 768px) {
  .mobile-menu-toggle {
    display: block;
  }

  .navbar-center {
    flex-direction: column;
    position: fixed;
    top: 0;
    left: -350px; /* Hidden by default */
    width: 350px;
    height: 100vh;
    background-color: #111;
    padding: 40px 20px;
    transition: left 0.3s ease-in-out;
    z-index: 999999999;
    box-shadow: 2px 0 8px rgba(0,0,0,0.4);
  }

  .navbar-center a {
    color: white;
    font-size: 18px;
    margin: 10px 0;
    text-decoration: none;
  }

  .navbar-center.active {
    left: 0; /* Slide in */
  }

  .navbar-right {
    display: none; /* Optional: hide cart/lang */
  }
}

</style>


<script>
  function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('active');
  }
</script>

<!-- mobile nov styling -->





<!-- 🌐 Language Modal -->
<div id="languageModal" class="language-modal">
  <div class="language-modal-content">
    <span class="language-close" onclick="closeLanguageModal()">&times;</span>
    <h3><?php  echo get_field("country_shop_list_POPUP_t1","options"); ?></h3>
   <div class="language-options">
       
       
       
  <a href="/" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/eu.svg"><span>English (Europe)</span>
  </a>
  
<a href="/hr" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/hr.svg"><span>Croatia (HR)</span>
  </a>
  
  <!--
   <a disabled href="/hu" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/hu.svg"><span>Hungary (HU)</span>
  </a>
    <a href="/de" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/de.svg"><span>Germany (DE)</span>
  </a>
  -->

  <a href="/pl" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/pl.svg"><span>Poland (PL)</span>
  </a>
  <a href="/sk" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/sk.svg"><span>Slovakia (SK)</span>
  </a>
  <a href="/cz" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/cz.svg"><span>Czech Republic (CZ)</span>
  </a>
  <!--
  <a href="/ro" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/ro.svg"><span>Romania (RO)</span>
  </a>
  -->
  <a href="/gr" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/gr.svg"><span>Greece (GR)</span>
  </a>
  <!--
  <a href="/si" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/si.svg"><span>Slovenia (SI)</span>
  </a>
  -->
  
  
   <a href="https://www.noriksofficial.com/" class="language-option">
    <img src="https://static.devit.software/countries/flags/rectangle/us.svg"><span>English (USA)</span>
  </a>
  
</div>
  </div>
</div>

<style>

a:focus, input:focus, textarea:focus, button:focus {
    outline: none !important;
}


  .language-modal {
  display: none;
  position: fixed;
  z-index: 9999999998;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  justify-content: center;
  align-items: center;
  font-family: 'Inter', sans-serif;
}

.language-modal-content {
  background: #fff;
  border-radius: 4px;
  padding: 40px 30px;
  max-width: 900px;
  width: 90%;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  text-align: center;
  position: relative;
}

.language-close {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 28px;
  cursor: pointer;
  color: #444;
}

.language-modal-content h3 {
  font-size: 22px;
  font-weight: 800;
  margin-bottom: 30px;
  color: #111;
}

.language-options {
  display: grid;
  grid-template-columns: 1fr 1fr; /* Two columns */
  gap: 16px 20px; /* row-gap | column-gap */
  align-items: start;
}

.language-option {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 14px;
  border: 1px solid #e2e2e2;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.25s ease;
  background: #fff;
  text-align: left;
}

.language-option:hover {
  background-color: #f8f8f8;
  border-color: #ccc;
}

.language-options a {
  text-decoration: none;
}

.language-option {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 14px;
  border: 1px solid #e2e2e2;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.25s ease;
  background: #fff;
  text-align: left;
}

.language-option:hover {
  background-color: #f8f8f8;
  border-color: #ccc;
}

.language-option img {
  width: 24px;
  height: auto;
  border-radius: 3px;
}

.language-option span {
  font-size: 15px;
  font-weight: 500;
  color: #222;
}

@media (max-width: 768px) {
  .language-options {
    grid-template-columns: 1fr;
    gap: 6px 20px; /* row-gap | column-gap */
  }
  .language-option span {

    font-size: 13px;

}
}
</style>


<script>
function openLanguageModal() {
  document.getElementById("languageModal").style.display = "flex";
}

function closeLanguageModal() {
  document.getElementById("languageModal").style.display = "none";
}

window.addEventListener("click", function(e) {
  const modal = document.getElementById("languageModal");
  if (e.target === modal) {
    closeLanguageModal();
  }
});
</script>


<?php

$webshop_language = get_field("webshop_language", "options");

if( $webshop_language == null  || $webshop_language == false  || $webshop_language == "" ) {
  $webshop_language = "EN";
}


if(  $webshop_language == "EN" ):
?>



<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById("languageModal");

  // Show only once per session
  if (!sessionStorage.getItem("languageModalShown")) {
    modal.style.display = "flex";
    sessionStorage.setItem("languageModalShown", "true");
  }

  // Optional: clicking outside modal closes it
  window.addEventListener("click", function(e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});
</script>




<!-- 🌐 Language Modal -->

<?php endif; ?>


<style>
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
}




.navbar {
  background-color: black;
  padding: 20px 20px;
}

.header .container {
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
      padding-left: 0px;
    padding-right: 0px;
}

.header .navbar-left .logo {
  height: auto;
}

.header .navbar-center a {
  color: white;
  text-decoration: none;
  margin: 0 15px;
  font-size: 16px;
  font-weight: 500;
}

.header .navbar-center a:hover {
  text-decoration: underline;
}

.header .navbar-right {
  display: flex;
  align-items: center;
}

.header .language-selector {
  display: flex;
  align-items: center;
  background: rgba(255,255,255,0.05);
  padding: 5px 10px;
  border-radius: 20px;
  margin-right: 20px;
}

.header .language-selector:hover {
    cursor:pointer;
}

.language-selector .flag {
  width: 20px;
  height: 15px;
  margin-right: 5px;
}

.language-selector span {
  color: white;
  font-size: 14px;
  font-weight: 400;
}

.icon {
  margin: 0 10px;
}

.icon img {
  width: 24px;
  height: 24px;
}

.cart-icon {
  position: relative;
}

.cart-count {
  position: absolute;
  top: -6px;
  right: -6px;
  background: white;
  color: #00203D;
  font-size: 12px;
  font-weight: 700;
  width: 18px;
  height: 18px;
  text-align: center;
  line-height: 18px;
  border-radius: 50%;
}
</style>

		<style>
		

		
		  .site-search {
                display: none;
            }
        .site-header-cart {
                display: none;
            }
        .site-branding {
            width: 100% !important;
            float: none;
            margin: 0 auto;
            text-align: center;
        }
        .woocommerce-active .site-header .main-navigation {
        width: 100% !important;
        /* float: left; */
        margin-right: 0;
        clear: both;
        text-align: center;
    }


	@media (min-width: 768px) {
    .col-full {
        max-width: 1400px;
		}
	}



	@media (min-width: 768px) {
    .storefront-full-width-content.single-product div.product .woocommerce-product-gallery {
			width: 48%;
			margin-right: 30px;
		}
	}

	.storefront-full-width-content.single-product div.product .summary {
       width: 48%;
    }

	.woocommerce div.product div.images img {
    width: 100%;
    height: auto;
    object-fit: cover;
    max-width: none;
}
		</style>
	
	

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full2">

		<?php
		do_action( 'storefront_content_top' );
