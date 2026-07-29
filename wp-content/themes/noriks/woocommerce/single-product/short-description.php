<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>

<!--
<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>
-->


<!-- my thre icons content -->

<!--
<div class="features">
    <div class="feature-card">
      <img src="<?php echo get_field("singlepp_icon_img1","option"); ?>" alt="Perfect Fit">
      <p><?php echo get_field("singlepp_icon_t1","option"); ?></p>
    </div>
    <div class="feature-card">
      <img src="<?php echo get_field("singlepp_icon_img2","option"); ?>" alt="Hides Dad Bod">
      <p><?php echo get_field("singlepp_icon_t2","option"); ?></p>
    </div>
    <div class="feature-card">
      <img src="<?php echo get_field("singlepp_icon_img3","option"); ?>" alt="Breathes">
       <p><?php echo get_field("singlepp_icon_t3","option"); ?></p>
    </div>
  </div>
-->

<style>


    .features {
      display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-top: 20px;
    }

    .feature-card {
    display: flex
;
    flex-direction: column;
    align-items: center;
    flex: 1;
    gap: 8px;
    border-radius: 5px;
    background: #F4F4F4;
    padding: 16px;
    font-size: 14px;
    font-weight: 400;
    color: #111213;
    line-height: 1.2;
    text-align: center;
    }

    .feature-card img {
      width: 32px;
      height: 32px;
      margin-bottom: 0px;
    }

    .feature-card p {
      margin: 0;
      font-weight: 500;
      font-size: 14px;
      color: #222;
       letter-spacing: -0.5px !important;
    }
  </style>

<?php if ( function_exists('noriks_is_type') && noriks_is_type('norikshers') ) : ?>
<div class="nhs-usp">
  <div class="nhs-usp-guarantee"><svg width="15" height="15" viewBox="0 0 24 24" style="flex:0 0 auto;"><circle cx="12" cy="12" r="12" fill="#7c3aed"/><path d="M7 12.5l3 3 7-7" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg> 30-day money-back guarantee</div>
  <div class="nhs-usp-grid">
    <div><span class="nhs-usp-ic">≈</span> Reduces wrinkles</div>
    <div><span class="nhs-usp-ic">↓</span> Reduces fine lines</div>
    <div><span class="nhs-usp-ic">♡</span> Fades scars overnight</div>
    <div><span class="nhs-usp-ic">✚</span> Extra adhesive</div>
    <div><span class="nhs-usp-ic">✓</span> Reusable and durable</div>
    <div><span class="nhs-usp-ic">↗</span> Boosts collagen production</div>
  </div>
</div>
<style>
  .nhs-usp { margin: 6px 0 2px; }
  .nhs-usp-guarantee { display: inline-flex; align-items: center; gap: 9px; background: #f6f2ff; border: 1px dashed #c3aef0; border-radius: 999px; padding: 8px 18px; font-size: 13.5px; font-weight: 600; color: #180D33; margin-bottom: 18px; }
  .nhs-usp-guarantee span { color: #7c3aed; }
  .nhs-usp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px 7px; }
  .nhs-usp-grid > div { display: flex; align-items: center; gap: 10px; font-size: 14.5px; color: #2a2340; }
  .nhs-usp-ic { flex: 0 0 auto; width: 20px; text-align: center; color: #180D33; font-size: 15px; }
  @media (max-width: 560px) { .nhs-usp-grid { gap: 5px 8px; } .nhs-usp-grid > div { font-size: 13px; } .nhs-usp-ic { width: 18px; font-size: 14px; } }
  /* Countdown na ovom proizvodu: neutralno sivo (bez crvenog). */
  .gck-countdown { background: #ededed !important; border: 1px solid #f7f7f7 !important; border-radius: 0 !important; color: #333 !important; text-align: left !important; }
  .gck-countdown * { color: #333 !important; }
  /* Discount badge (−33% …): vijolicno na ovom proizvodu. */
  .gck-discount-badge { background: #7c3aed !important; color: #fff !important; }
  /* Bundle radio gumbi: vijolicno na ovom proizvodu. */
  .bundle-option input[type="radio"] { border-color: #7c3aed !important; }
  .bundle-option input[type="radio"]::before { background: #7c3aed !important; }
  .bundle-option input[type="radio"]:checked::before { background: #7c3aed !important; }
</style>
<?php endif; ?>

<?php if ( function_exists('noriks_is_type') && noriks_is_type('ortopedski-jastuk') ) : ?>
<!-- ErgoSit: zelena "preporuka" pilula (JS je premjesti odmah ispod naslova) + original-style checklista -->
<div class="oj-recpill">
  <img src="<?php echo esc_url( get_template_directory_uri().'/img/ortopedski-jastuk/04_lijecnik_HR.png' ); ?>" alt="Doctor" class="oj-recpill-av" onerror="this.style.display='none'">
  <span><strong>Recommended by Dr. Maric</strong>&nbsp;|&nbsp;<em>Spine specialist</em></span>
  <svg class="oj-recpill-check" width="14" height="14" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#22b573"/><path d="M7 12.5l3 3 7-7" fill="none" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
</div>
<!-- ErgoSit: mala "zaliha" pilula kao original (umjesto velikog crvenog countdown boxa) -->
<div class="oj-stockpill">
  <svg width="15" height="15" viewBox="0 0 24 24" style="flex:0 0 auto;"><circle cx="12" cy="12" r="12" fill="#e5344a"/><path d="M12 6.5v7" stroke="#fff" stroke-width="2.6" stroke-linecap="round"/><circle cx="12" cy="17" r="1.5" fill="#fff"/></svg>
  <em>Only a few left in stock!</em>
</div>
<!-- ErgoSit: "narudžbe u 24h" red kao original (zeleni pulsirajući krug), JS ga stavi iznad ADD TO CART -->
<div class="oj-orders">
  <span class="oj-orders-dot"></span>
  <em><strong>128 orders</strong> in the last <strong>24 hours!</strong></em>
</div>
<style>
  .oj-recpill { display: inline-flex; align-items: center; gap: 9px; background: #e9f8f0; border-radius: 999px; padding: 6px 16px 6px 7px; font-size: 13.5px; color: #1b1533; margin: 8px 0 12px; }
  .oj-recpill strong { font-weight: 800; }
  .oj-recpill em { font-style: italic; color: #4a6155; }
  .oj-recpill-av { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; object-position: 50% 12%; flex: 0 0 auto; }
  .oj-recpill-check { flex: 0 0 auto; }
  /* Mala zaliha-pilula (original: "Only a few are left in stock!") */
  .oj-stockpill { display: flex; width: max-content; max-width: 100%; align-items: center; gap: 6px; background: #fff; border: 1px solid #f3b8c6; border-radius: 11px; padding: 4px 9px; }
  .oj-stockpill svg { width: 12px; height: 12px; }
  .oj-stockpill em { font-style: italic; font-size: 11.5px; font-weight: 600; color: #e5344a; }
  /* Sakrij veliki crveni countdown box na ovom proizvodu */
  .gck-countdown { display: none !important; }
  /* Discount badge (−33% …): outline chip kao original */
  .gck-discount-badge { background: #fff !important; color: #ED5E95 !important; border: 1px solid #ED5E95 !important; border-radius: 6px !important; padding: 5px 9px !important; font-size: 11px !important; font-weight: 700 !important; line-height: 1 !important; display: inline-flex !important; align-items: center !important; align-self: center !important; vertical-align: middle !important; }
  /* Sakrij SAMO riječ "Ukupno:" u ponudama (cijene ostaju) */
  .bundle-total-line > span[style*="font-weight:normal"] { display: none !important; }
  /* Sakrij crveni "€/kom" chip u ponudama */
  .gck-per-chip { display: none !important; }
  /* Ujednačen vertikalni ritam u summary (cijena → pilula → ponude → gumb): 12px svugdje */
  .summary .price { margin: 0 0 18px !important; }
  .oj-recpill { margin: 6px 0 12px !important; }
  .oj-stockpill { margin: 0 0 12px !important; }
  #bundle-selector { margin-top: 0 !important; }
  #bundle-selector .bundle-option { margin: 0 0 12px !important; }
  .oj-orders { margin: 0 0 12px !important; }
  /* ADD TO CART gumb: rose-pink kao original */
  .single-product .single_add_to_cart_button,
  .single-product button.single_add_to_cart_button.alt {
    background: #ef4266 !important; border-color: #ef4266 !important; color: #fff !important;
  }
  .single-product .single_add_to_cart_button:hover,
  .single-product button.single_add_to_cart_button.alt:hover {
    background: #d92f55 !important; border-color: #d92f55 !important;
  }
  /* Akcijska (sale) cijena: pink kao original */
  .summary .price ins,
  .summary .price ins .woocommerce-Price-amount,
  .summary .price > .woocommerce-Price-amount:last-child {
    color: #fd4f93 !important;
  }
  /* Ponude — TOCNO po originalu (izmjereno): #ED5E95, border 2px .3, radius 6px */
  .bundle-option { background: rgba(237,94,149,0.02) !important; border: 2px solid rgba(237,94,149,0.3) !important; border-radius: 6px !important; }
  .bundle-option.active { border-color: #ED5E95 !important; background: rgba(237,94,149,0.1) !important; }
  /* Jedan red: naziv+chip lijevo, cijene desno u stupcu, vertikalno centrirano */
  .bundle-option { display: flex !important; flex-wrap: wrap; align-items: center !important; min-height: 72px; padding: 14px 18px !important; cursor: pointer; transition: border-color .15s ease, background .15s ease; }
  .bundle-option .bundle-option-title { display: inline-flex; align-items: center; font-weight: 700; color: rgba(18,16,48,0.9); font-size: 16px; }
  .bundle-option .bundle-total-line { margin: 0 0 0 auto !important; display: inline-flex; flex-direction: row; align-items: center; gap: 4px; font-size: 16px; font-weight: 700; color: #ED5E95; }
  .bundle-option .gck-regular-price { font-weight: 400 !important; font-size: 14px !important; color: rgba(18,16,48,0.6) !important; text-decoration: line-through; }
  .bundle-option input[type="radio"] { margin-right: 7px !important; border-color: #ED5E95 !important; }
  .bundle-option .bundle-option-title { margin-left: 2px; }
  .bundle-option input[type="radio"]::before, .bundle-option input[type="radio"]:checked::before { background: #ED5E95 !important; }
  .bundle-option .gck-discount-badge { margin-left: 8px; }
  /* No-attrs proizvod: prazan bundle-pairs gura sadržaj prema gore — sakrij ga */
  .bundle-option .bundle-pairs { display: none !important; }
  /* Mobilni: tjesnji razmaci i kompaktnije kartice */
  @media (max-width: 600px) {
    .summary .price { margin: 0 0 12px !important; }
    .oj-recpill { margin: 4px 0 8px !important; }
    .oj-stockpill { margin: 0 0 8px !important; }
    #bundle-selector .bundle-option { margin: 0 0 8px !important; }
    .bundle-option { min-height: 64px; padding: 14px 12px !important; }
    .bundle-option input[type="radio"] { margin-right: 6px !important; }
    .bundle-option .bundle-option-title { font-size: 14.5px; }
    .bundle-option .gck-discount-badge { margin-left: 6px; padding: 4px 7px !important; font-size: 10px !important; }
    .bundle-option .bundle-total-line { gap: 3px; font-size: 15px; }
    .bundle-option .gck-regular-price { font-size: 12.5px !important; }
    .oj-orders { margin: 0 0 8px !important; }
    .oj-orders em { font-size: 13px; }
  }
  /* "narudžbe u 24h" red (zeleni pulsirajući krug) */
  .oj-orders { display: flex; align-items: center; gap: 9px; margin: 12px 0 10px; }
  .oj-orders em { font-style: italic; font-size: 14px; color: #121030; }
  .oj-orders-dot { flex: 0 0 auto; width: 11px; height: 11px; border-radius: 50%; background: #17c964; box-shadow: 0 0 0 3px rgba(23,201,100,.25); animation: ojPulse 1.6s ease-in-out infinite; }
  @keyframes ojPulse { 0%,100% { box-shadow: 0 0 0 3px rgba(23,201,100,.25); } 50% { box-shadow: 0 0 0 6px rgba(23,201,100,.12); } }
  /* Checklista u kratkom opisu — čisto kao original (bez bullet točkica) */
  .woocommerce-product-details__short-description ul { list-style: none; margin: 10px 0 14px; padding-left: 0; }
  .woocommerce-product-details__short-description ul li { list-style: none; padding-left: 18px; text-indent: -18px; margin: 0 0 7px; font-size: 15px; line-height: 1.45; color: #1b1533; }
</style>
<script>
(function(){
  /* Premjesti pilule: preporuka ispod naslova, zaliha ispod cijene */
  function movePills(){
    var pill = document.querySelector('.oj-recpill');
    var title = document.querySelector('.product_title');
    if ( pill && title && title.nextSibling !== pill ) { title.parentNode.insertBefore(pill, title.nextSibling); }
    var stock = document.querySelector('.oj-stockpill');
    var badge = document.querySelector('.summary .price-badge');
    var price = document.querySelector('.summary .price');
    var anchor = badge || price; /* iza "Lowest price" badgea, da cijena+badge ostanu u istom redu */
    if ( stock && anchor && anchor.nextSibling !== stock ) { anchor.parentNode.insertBefore(stock, anchor.nextSibling); }
    /* "narudžbe u 24h" iznad ADD TO CART gumba */
    var orders = document.querySelector('.oj-orders');
    var cartBtn = document.querySelector('.single_add_to_cart_button');
    if ( orders && cartBtn && cartBtn.previousElementSibling !== orders ) { cartBtn.parentNode.insertBefore(orders, cartBtn); }
  }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', movePills); } else { movePills(); }
})();
</script>
<?php endif; ?>

<?php if ( function_exists('noriks_is_type') && noriks_is_type('nosilka') ) : ?>
<!-- BabyGo nosiljka: klasicni paket (kao ErgoSit), PLAVA shema -->
<div class="nsb-stockpill">
  <svg width="12" height="12" viewBox="0 0 24 24" style="flex:0 0 auto;"><circle cx="12" cy="12" r="12" fill="#e5344a"/><path d="M12 6.5v7" stroke="#fff" stroke-width="2.6" stroke-linecap="round"/><circle cx="12" cy="17" r="1.5" fill="#fff"/></svg>
  <em>Only a few left in stock!</em>
</div>
<style>
  /* Mala zaliha-pilula umjesto velikog crvenog countdown boxa */
  .nsb-stockpill { display: flex; width: max-content; max-width: 100%; align-items: center; gap: 6px; background: #fff; border: 1px solid #b9d2ea; border-radius: 11px; padding: 4px 9px; }
  .nsb-stockpill em { font-style: italic; font-size: 11.5px; font-weight: 600; color: #e5344a; }
  /* Nosiljka nema velicina: sakrij SVE size-chart triggere */
  .gck-size-link, .gck-size-link-wrap, #open-size-chartCustom, #open-size-chart, .noriks-global-sizechart { display: none !important; }
  .gck-countdown { display: none !important; }
  /* Sakrij crveni per-kom chip i rijec "Ukupno:" (cijene ostaju) */
  .gck-per-chip { display: none !important; }
  .bundle-total-line > span[style*="font-weight:normal"] { display: none !important; }
  /* Discount chip: outline plavi */
  .gck-discount-badge { background: #fff !important; color: #3d76b4 !important; border: 1px solid #3d76b4 !important; border-radius: 6px !important; padding: 5px 9px !important; font-size: 11px !important; font-weight: 700 !important; line-height: 1 !important; display: inline-flex !important; align-items: center !important; align-self: center !important; }
  /* Ponude: bijele s plavim borderom, jedan red, iste visine, centrirano */
  .bundle-option { background: #fff !important; border: 2px solid rgba(61,118,180,0.30) !important; border-radius: 6px !important; display: flex !important; flex-wrap: wrap; align-items: center !important; min-height: 72px; padding: 14px 18px !important; cursor: pointer; transition: border-color .15s ease, background .15s ease; }
  .bundle-option.active { border-color: #3d76b4 !important; background: rgba(61,118,180,0.08) !important; }
  .bundle-option .bundle-option-title { display: inline-flex; align-items: center; font-weight: 700; color: rgba(18,16,48,0.9); font-size: 16px; }
  .bundle-option .bundle-total-line { margin: 0 0 0 auto !important; display: inline-flex; flex-direction: row; align-items: center; gap: 4px; font-size: 16px; font-weight: 700; color: #20283a; }
  .bundle-option .gck-regular-price { font-weight: 400 !important; font-size: 14px !important; color: rgba(18,16,48,0.6) !important; text-decoration: line-through; }
  .bundle-option input[type="radio"] { margin-right: 7px !important; border-color: #3d76b4 !important; }
  .bundle-option input[type="radio"]::before, .bundle-option input[type="radio"]:checked::before { background: #3d76b4 !important; }
  .bundle-option .gck-discount-badge { margin-left: 8px; }
  /* Vertikalni ritam */
  .summary .price { margin: 0 0 18px !important; }
  .nsb-stockpill { margin: 0 0 12px !important; }
  #bundle-selector { margin-top: 0 !important; }
  #bundle-selector .bundle-option { margin: 0 0 12px !important; }
  /* Checklista: hanging indent */
  .woocommerce-product-details__short-description ul { list-style: none; margin: 10px 0 14px; padding-left: 0; }
  .woocommerce-product-details__short-description ul li { list-style: none; padding-left: 18px; text-indent: -18px; margin: 0 0 7px; font-size: 15px; line-height: 1.45; color: #20283a; }
  @media (max-width: 600px) {
    .summary .price { margin: 0 0 12px !important; }
    .nsb-stockpill { margin: 0 0 8px !important; }
    #bundle-selector .bundle-option { margin: 0 0 8px !important; }
    .bundle-option { min-height: 64px; padding: 14px 12px !important; }
    .bundle-option input[type="radio"] { margin-right: 6px !important; }
    .bundle-option .bundle-option-title { font-size: 14.5px; }
    .bundle-option .gck-discount-badge { margin-left: 6px; padding: 4px 7px !important; font-size: 10px !important; }
    .bundle-option .bundle-total-line { gap: 3px; font-size: 15px; }
    .bundle-option .gck-regular-price { font-size: 12.5px !important; }
  }
</style>
<script>
(function(){
  /* Plava aktivna ponuda (prezivi LiteSpeed UCSS) */
  function paintNsb(){
    var sel = document.getElementById('bundle-selector'); if(!sel) return;
    sel.querySelectorAll('.bundle-option').forEach(function(c){ c.style.removeProperty('border-color'); c.style.removeProperty('background'); c.style.removeProperty('border-width'); });
    var checked = sel.querySelector('input[name="bundle_option"]:checked');
    var card = checked ? checked.closest('.bundle-option') : (sel.querySelector('.bundle-option.active') || sel.querySelector('.bundle-option'));
    if(card){ card.style.setProperty('border-color','#3d76b4','important'); card.style.setProperty('background','rgba(61,118,180,0.08)','important'); card.style.setProperty('border-width','2px','important'); }
  }
  function bindNsb(){ var sel=document.getElementById('bundle-selector'); if(!sel) return; paintNsb(); sel.querySelectorAll('input[name="bundle_option"]').forEach(function(r){ r.addEventListener('change', paintNsb); }); }
  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded', bindNsb); } else { bindNsb(); }
  /* Zaliha-pilula ispod cijene (iza "Najniza cena" badgea) */
  function movePill(){
    var stock=document.querySelector('.nsb-stockpill');
    var badge=document.querySelector('.summary .price-badge');
    var price=document.querySelector('.summary .price');
    var anchor=badge||price;
    if(stock&&anchor&&anchor.nextSibling!==stock){ anchor.parentNode.insertBefore(stock, anchor.nextSibling); }
  }
  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded', movePill); } else { movePill(); }
})();
</script>
<?php endif; ?>

<?php if ( function_exists('noriks_is_type') && noriks_is_type('kidsnest') ) : ?>
<!-- KidsNest: summary paket po tryneedo originalu (plava #2b3fb0, rdeca #c0452f) -->
<div class="kn2-badge">
  <svg width="15" height="15" viewBox="0 0 24 24" style="flex:0 0 auto;"><circle cx="12" cy="12" r="12" fill="#2b7de0"/><path d="M7 12.5l3 3 7-7" fill="none" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
  <span>Paediatrician recommended</span>
</div>
<div class="kn2-below">
  <div class="kn2-trust">
    <span><svg width="16" height="16" viewBox="0 0 24 24"><path d="M12 2l8 3v6c0 5-3.5 9.5-8 11-4.5-1.5-8-6-8-11V5l8-3z" fill="#2b3fb0"/><path d="M8.5 12l2.5 2.5 4.5-4.5" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> 90-night trial</span>
    <span><svg width="18" height="14" viewBox="0 0 24 18"><rect x="1" y="3" width="13" height="10" rx="1.5" fill="#2b3fb0"/><path d="M14 6h4l4 4v3h-8z" fill="#2b3fb0"/><circle cx="6" cy="15" r="2.4" fill="#1b2450"/><circle cx="18" cy="15" r="2.4" fill="#1b2450"/></svg> Fast delivery</span>
    <span><svg width="17" height="15" viewBox="0 0 24 20"><rect x="3" y="2" width="18" height="11" rx="2" fill="none" stroke="#2b3fb0" stroke-width="2"/><circle cx="12" cy="7.5" r="2.6" fill="#2b3fb0"/><path d="M2 17c3 2 7 3 10 3s7-1 10-3" fill="none" stroke="#2b3fb0" stroke-width="2" stroke-linecap="round"/></svg> Cash on delivery</span>
  </div>
  <div class="kn2-redbox">
    <p class="kn2-red-title"><svg width="14" height="13" viewBox="0 0 24 22" style="vertical-align:-2px;margin-right:6px;"><path d="M12 1L23 21H1L12 1z" fill="none" stroke="#c0452f" stroke-width="2.4" stroke-linejoin="round"/><path d="M12 8v6" stroke="#c0452f" stroke-width="2.4" stroke-linecap="round"/><circle cx="12" cy="17.4" r="1.3" fill="#c0452f"/></svg>FOR PARENTS OF CHILDREN UNDER 9</p>
    <p class="kn2-red-body">Every night on the wrong pillow <strong class="kn2-red">your child's face is being shaped — in the wrong direction.</strong> Drooling. Snoring. Mouth open. Do <strong class="kn2-red">9. godine</strong> the changes set in. Do <strong class="kn2-red">14.</strong> — expensive braces. Do <strong class="kn2-red">18.</strong> — a conversation about more serious procedures. Paediatric airway dentists see this every day — they have stopped calling it rare. <strong class="kn2-red">Change the pillow tonight.</strong></p>
  </div>
</div>
<style>
  /* Badge iznad naslova */
  .kn2-badge { display: inline-flex; align-items: center; gap: 7px; background: #e8ecfb; border-radius: 6px; padding: 5px 12px; font-size: 13px; font-weight: 600; color: #121212; margin: 0 0 10px; }
  /* Skrij staro vrstico ikon (features2) — vsebina je v trust vrstici */
  .features2 { display: none !important; }
  /* Razmik med naslovom in kratkim opisom */
  .single-product .product_title { margin-bottom: 14px !important; }
  .summary .woocommerce-product-details__short-description { margin-top: 10px; }
  /* Checklista (kratki opis): BREZ bulletov, emoji iz vsebine je ikona, hanging indent poravnava */
  .summary .woocommerce-product-details__short-description ul,
  .woocommerce-product-details__short-description ul { list-style: none !important; margin: 10px 0 14px !important; padding-left: 0 !important; }
  .summary .woocommerce-product-details__short-description ul li,
  .woocommerce-product-details__short-description ul li { list-style: none !important; padding-left: 26px !important; text-indent: -26px !important; margin: 0 0 8px !important; font-size: 15px; line-height: 1.5; color: #121212; }
  .summary .woocommerce-product-details__short-description ul li::marker,
  .woocommerce-product-details__short-description ul li::marker { content: '' !important; }
  /* Divider "LJETNA AKCIJA" nad ponudama */
  .kn2-divider { display: flex; align-items: center; gap: 14px; margin: 14px 0 12px; color: #1b2450; font-size: 13px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
  .kn2-divider::before, .kn2-divider::after { content: ""; flex: 1; height: 1px; background: #c9d2f0; }
  /* Ponude: plava shema */
  .bundle-option { position: relative; background: #fff !important; border: 1.5px solid #d9dff5 !important; border-radius: 10px !important; display: flex !important; flex-wrap: wrap; align-items: center !important; min-height: 58px; padding: 10px 18px !important; cursor: pointer; transition: border-color .15s ease, background .15s ease; overflow: visible !important; }
  .bundle-option.active { border: 2px solid #2b3fb0 !important; background: #e8ecfb !important; }
  .bundle-option .bundle-option-title { display: inline-flex; align-items: center; font-weight: 700; color: #121212; font-size: 16px; }
  .bundle-option .bundle-total-line { margin: 0 0 0 auto !important; display: flex; flex-direction: column-reverse; align-items: flex-end; gap: 1px; font-size: 16px; font-weight: 700; color: #121212; }
  .bundle-option .gck-regular-price { font-weight: 400 !important; font-size: 13.5px !important; color: #8a90a3 !important; text-decoration: line-through; }
  .bundle-option input[type="radio"] { margin-right: 10px !important; border-color: #2b3fb0 !important; }
  .bundle-option input[type="radio"]::before, .bundle-option input[type="radio"]:checked::before { background: #2b3fb0 !important; }
  /* per-cijena chip: skrit (uporabnik ne zeli cene v gumbih) */
  .bundle-option .gck-per-chip { display: none !important; }
  /* Velikost dropdown: kot original (bel, siv rob, brez oranzne) */
  .bundle-option hr, .bundle-option .gck-divider { margin: 8px 0 !important; }
  #bundle-selector select, .bundle-box select, .bundle-option select {
    border: 1px solid #c9cfdd !important; border-radius: 8px !important; background-color: #fff !important;
    color: #121212 !important; font-size: 14px !important; font-weight: 500 !important;
    padding: 9px 34px 9px 12px !important; box-shadow: none !important;
  }
  #bundle-selector select:focus, .bundle-box select:focus { border-color: #2b3fb0 !important; outline: none !important; }
  .kn2-selnum { display: inline-block; min-width: 24px; font-weight: 700; font-size: 14px; color: #121212; }
  .kn2-selrow { display: flex; align-items: center; gap: 10px; margin: 0 0 10px; }
  .kn2-selrow select { flex: 1 1 auto; margin: 0 !important; }
  /* skrij % chip, "Ukupno:", countdown in "Nudimo 30 dana..." noto */
  .gck-discount-badge { display: none !important; }
  .bundle-total-line > span[style*="font-weight:normal"] { display: none !important; }
  .gck-countdown { display: none !important; }
  #bundle-selector small { display: none !important; }
  /* skrij samostojni "Tablica velicina" link (tablica je v akordeonu) */
  .noriks-global-sizechart, #open-size-chart { display: none !important; }
  /* privzete plugin znacke -> plavi ribboni gor-desno */
  .gck-popular-badge, .gck-popular-badge-2, .kn2-ribbon { position: absolute; top: -11px; right: 12px; left: auto !important; background: #2b3fb0 !important; color: #fff !important; font-size: 11px !important; font-weight: 700 !important; border-radius: 4px !important; padding: 3px 10px !important; line-height: 1.4 !important; transform: none !important; }
  /* ADD TO CART: plavi pill (izmjereno: radius 42px, 19px/700, uppercase, ls 1px) */
  .single-product .single_add_to_cart_button,
  .single-product button.single_add_to_cart_button.alt {
    background: #2b3fb0 !important; border-color: #2b3fb0 !important; color: #fff !important;
    font-size: 19px !important; font-weight: 700 !important;
    text-transform: uppercase !important; letter-spacing: 1px !important; padding-top: 16px !important; padding-bottom: 16px !important;
  }
  .single-product .single_add_to_cart_button:hover { background: #1b2450 !important; border-color: #1b2450 !important; }
  /* Trust vrstica pod gumbom */
  .kn2-trust { display: flex; justify-content: center; gap: 28px; margin: 12px 0 4px; }
  .kn2-trust span { display: inline-flex; align-items: center; gap: 8px; font-size: 14.5px; font-weight: 600; color: #121212; }
  /* Rdeci box */
  .kn2-redbox { background: #fdf1ee; border: 1px solid #f5cfc5; border-left: 3px solid #c0452f; border-radius: 8px; padding: 15px 18px; margin: 16px 0 10px; }
  .kn2-red-title { margin: 0 0 8px; font-size: 12px; font-weight: 800; letter-spacing: .12em; color: #c0452f; }
  .kn2-red-body { margin: 0; font-size: 14.5px; line-height: 1.6; color: #3b3b3b; }
  .kn2-red { color: #c0452f; }
  /* Akordeona pod boxom */
  .kn2-accs { border-top: 1px solid #e5e5e5; margin-top: 6px; }
  .kn2-acc { border-bottom: 1px solid #e5e5e5; }
  .kn2-acc summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 2px; cursor: pointer; font-size: 15px; font-weight: 700; color: #121212; list-style: none; }
  .kn2-acc summary::-webkit-details-marker { display: none; }
  .kn2-chev { color: #9aa0b4; transition: transform .2s; }
  .kn2-acc[open] .kn2-chev { transform: rotate(180deg); }
  .kn2-acc-body { padding: 0 2px 14px; }
  .kn2-acc-body p { font-size: 14.5px; line-height: 1.65; color: #3b4052; margin: 0 0 11px; }
  .kn2-acc-body ul { margin: 0 0 12px; padding-left: 20px; }
  .kn2-acc-body li { font-size: 14.5px; line-height: 1.6; color: #3b4052; margin: 0 0 7px; }
  /* Vertikalni ritam */
  .summary .price { margin: 0 0 14px !important; }
  #bundle-selector { margin-top: 0 !important; }
  #bundle-selector .bundle-option { margin: 0 0 14px !important; }
  @media (max-width: 600px) {
    .single-product .product_title { font-size: 1.8rem !important; line-height: 1.18 !important; }
    #bundle-selector .bundle-option { margin: 0 0 10px !important; }
    .bundle-option { min-height: 64px; padding: 13px 12px !important; }
    .bundle-option .bundle-option-title { font-size: 14.5px; }
    .bundle-option .bundle-total-line { font-size: 15px; }
    .kn2-trust { gap: 16px; flex-wrap: wrap; }
  }
</style>
<script>
(function(){
  function init(){
    /* Badge iznad naslova */
    var badge=document.querySelector('.kn2-badge'), title=document.querySelector('.product_title');
    if(badge&&title&&title.previousElementSibling!==badge){ title.parentNode.insertBefore(badge,title); }
    var sel=document.getElementById('bundle-selector');
    /* Ribboni: strip ogenj + dodaj "Najbolja vrijednost" na 3. karto */
    document.querySelectorAll('.gck-popular-badge, .gck-popular-badge-2').forEach(function(b){ b.textContent=b.textContent.replace('🔥','').trim(); if(/Najpopularnije/i.test(b.textContent)) b.textContent='Najpopularnije'; });
    var cards=sel?sel.querySelectorAll('.bundle-option'):[];
    if(cards.length>=3 && !cards[2].querySelector('.kn2-ribbon') && !cards[2].querySelector('.gck-popular-badge, .gck-popular-badge-2')){
      var rb=document.createElement('div'); rb.className='kn2-ribbon'; rb.textContent='Najbolja vrijednost'; cards[2].appendChild(rb);
    }
    /* Ikone (pouzece/30dni/dostava) + dostava-box prestavi POD accordion */
    var acc=document.querySelector('.summary .accordion')||document.querySelector('.accordion');
    var ship=document.querySelector('.shipping-box');
    if(acc && ship){ acc.parentNode.insertBefore(ship, acc.nextSibling); }
    /* Blok (trust + redbox + akordeoni) pod ADD TO CART */
    var below=document.querySelector('.kn2-below'), btn=document.querySelector('.single_add_to_cart_button');
    if(below&&btn&&btn.nextElementSibling!==below){ btn.parentNode.insertBefore(below, btn.nextSibling); }
    /* Plava aktivna karta (prezivi cache) */
    function paint(){
      if(!sel) return;
      sel.querySelectorAll('.bundle-option').forEach(function(c){ c.style.removeProperty('border'); c.style.removeProperty('background'); });
      var checked=sel.querySelector('input[name="bundle_option"]:checked');
      var card=checked?checked.closest('.bundle-option'):(sel.querySelector('.bundle-option.active')||sel.querySelector('.bundle-option'));
      if(card){ card.style.setProperty('border','2px solid #2b3fb0','important'); card.style.setProperty('background','#e8ecfb','important'); }
    }
    function numberSelects(){
      if(!sel) return;
      sel.querySelectorAll('.bundle-pairs').forEach(function(wrap){
        var sels=wrap.querySelectorAll('select');
        if(sels.length<2){ wrap.querySelectorAll('.kn2-selnum').forEach(function(l){ l.remove(); }); return; }
        var i=0;
        sels.forEach(function(sl){
          i++;
          if(sl.parentNode.classList && sl.parentNode.classList.contains('kn2-selrow')){ sl.parentNode.querySelector('.kn2-selnum').textContent='#'+i; return; }
          var row=document.createElement('div'); row.className='kn2-selrow';
          var lab=document.createElement('span'); lab.className='kn2-selnum'; lab.textContent='#'+i;
          sl.parentNode.insertBefore(row, sl);
          row.appendChild(lab); row.appendChild(sl);
        });
      });
    }
    if(sel){ paint(); numberSelects(); sel.querySelectorAll('input[name="bundle_option"]').forEach(function(r){ r.addEventListener('change', function(){ paint(); numberSelects(); }); }); }
  }
  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded', init); } else { init(); }
})();
</script>
<?php endif; ?>

