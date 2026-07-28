<?php
/**
 * product-bottom: SLING CARRIER (orto-nosilka) — NORIKS BabyGo sling carrier.
 * Sections mirror the en-bambelle reference, English copy, sales-oriented.
 * Order:
 *   1. Gallery strip (g1–g9, full-bleed, sliding)      — FIRST section
 *   2. No more tired arms   (text L / image R)
 *   3. Made for real life   (image L / text R)
 *   4. Join the community   (text L / collage R) + CTA
 *   5. Gallery strip (g10–g17, full-bleed, sliding)    — LAST section
 * Images: img/nosilka/ (+ /galerija/)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$ns = get_template_directory_uri() . '/img/nosilka/';
$ns_img = function( $file, $alt ) use ( $ns ) {
  return '<img src="'.esc_url($ns.$file).'" alt="'.esc_attr($alt).'" loading="lazy" onerror="this.style.display=\'none\'">';
};
?>

<!-- ============ 1) Gallery — FIRST strip (g1–g9) ============ -->
<section class="nsl-gal-sec nsl-gal-top">
  <div class="nsl-gal">
    <div class="nsl-gal-track">
      <?php for ( $r = 0; $r < 2; $r++ ) : for ( $i = 1; $i <= 9; $i++ ) : ?>
        <img src="<?php echo esc_url( $ns.'galerija/g'.$i.'.jpg' ); ?>" alt="NORIKS BabyGo — parents and babies" loading="lazy" onerror="this.style.display='none'">
      <?php endfor; endfor; ?>
    </div>
  </div>
</section>

<!-- ============ 2) No more tired arms — text LEFT, image RIGHT ============ -->
<section class="nsl-sec">
  <div class="nsl-wrap nsl-row2">
    <div class="nsl-copy">
      <h2 class="nsl-h2">No more tired arms and aching back!</h2>
      <p>Carrying a baby who wants to be held all day wears you out fast… <strong>Sore arms, tired shoulders, back pain</strong> — and never a free hand.</p>
      <p>The NORIKS <strong>BabyGo carrier</strong> <strong>spreads the weight evenly</strong> across your shoulders and back, so you can move freely without strain. It is the easiest way to stay close — and <strong>pain-free</strong>!</p>
      <p>Imagine a day where your baby gets all the closeness they need, while you have <strong>free hands and a back that doesn't hurt</strong>. That is exactly what BabyGo gives thousands of parents — every single day.</p>
    </div>
    <div class="nsl-media"><?php echo $ns_img('01-ruke-leda.png','Sling carrier — no tired arms or back pain'); ?></div>
  </div>
</section>

<!-- ============ 3) Made for real life — image LEFT, text RIGHT ============ -->
<section class="nsl-sec nsl-alt">
  <div class="nsl-wrap nsl-row2">
    <div class="nsl-media"><?php echo $ns_img('02-prakticnost.png','Sling carrier in everyday life — shopping with a baby'); ?></div>
    <div class="nsl-copy">
      <h2 class="nsl-h2">Made for real life</h2>
      <p>When your baby wants to be held but life doesn't pause — that is where BabyGo steps in.</p>
      <p>It keeps your little one snug against you while you can <strong>shop, cook, tidy up or simply finish your coffee.</strong></p>
      <p>It is light, fits in any bag and goes on in <strong>seconds</strong> — no complicated buckles or adjusting. From the morning coffee to the evening walk, BabyGo is ready whenever your baby asks for it.</p>
    </div>
  </div>
</section>

<!-- ============ 4) Join the community — text LEFT, collage RIGHT + CTA ============ -->
<section class="nsl-sec">
  <div class="nsl-wrap nsl-row2">
    <div class="nsl-copy">
      <h2 class="nsl-h2">Join our community!</h2>
      <p><strong>Thousands of mums and dads</strong> no longer leave the house without their BabyGo carrier — and the reason is simple: the baby is <strong>calm and close to you</strong>, and you finally get your day back.</p>
      <p>Less crying, more cuddles. Less back pain, more freedom. Join the parents who turned juggling a baby in their arms into <strong>calm walks, finished errands and hot coffee</strong>.</p>
      <p>Your arms will thank you. Your baby even more.</p>
      <a class="nsl-cta" href="#bundle-selector">Order your BabyGo carrier</a>
    </div>
    <div class="nsl-media"><?php echo $ns_img('03-zajednica.png','Thousands of happy parents — carrier community'); ?></div>
  </div>
</section>

<!-- ============ 5) Gallery — LAST strip (g10–g17) ============ -->
<section class="nsl-gal-sec">
  <div class="nsl-gal">
    <div class="nsl-gal-track">
      <?php for ( $r = 0; $r < 2; $r++ ) : for ( $i = 10; $i <= 17; $i++ ) : ?>
        <img src="<?php echo esc_url( $ns.'galerija/g'.$i.'.jpg' ); ?>" alt="NORIKS BabyGo — happy parents" loading="lazy" onerror="this.style.display='none'">
      <?php endfor; endfor; ?>
    </div>
  </div>
</section>

<style>
  .nsl-wrap { max-width: 1440px; margin: 0 auto; padding: 0 22px; } /* same container as the .product block above */
  .nsl-sec { padding: 60px 0; }
  .nsl-alt { background: #f5f8fb; }
  .nsl-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
  .nsl-h2 { font-size: clamp(26px,3.2vw,40px); font-weight: 800; color: #20283a; line-height: 1.12; margin: 0 0 16px; }
  .nsl-copy p { font-size: 15.5px; line-height: 1.65; color: #3c4354; margin: 0 0 14px; }
  .nsl-media img { width: 100%; height: auto; display: block; border-radius: 18px; box-shadow: 0 14px 40px rgba(32,40,58,.10); }

  /* CTA — navy button, blue hover */
  .nsl-cta { display: inline-block; background: #20283a; color: #fff; font-weight: 800; font-size: 15px; padding: 15px 32px; border-radius: 10px; text-decoration: none; margin-top: 8px; }
  .nsl-cta:hover { background: #3d76b4; color: #fff; }

  /* gallery — full-bleed, slow slide, pause on hover */
  .nsl-gal-sec { padding: 10px 0 40px; }
  .nsl-gal-top { padding: 26px 0 10px; }
  .nsl-gal { overflow: hidden; width: 100vw; margin-left: calc(50% - 50vw); }
  .nsl-gal-track { display: flex; gap: 8px; width: max-content; animation: nslGal 70s linear infinite; }
  .nsl-gal:hover .nsl-gal-track { animation-play-state: paused; }
  .nsl-gal-track img { width: 280px; aspect-ratio: 3/4; object-fit: cover; border-radius: 10px; display: block; flex: 0 0 auto; }
  @keyframes nslGal { from { transform: translateX(0); } to { transform: translateX(-50%); } }

  @media (max-width: 860px) {
    .nsl-sec { padding: 30px 0; }
    .nsl-row2 { grid-template-columns: 1fr; gap: 18px; }
    .nsl-row2 .nsl-media { order: -1; }
    .nsl-h2 { font-size: 2rem; }
    .nsl-gal-track img { width: 190px; }
    .nsl-gal-sec { padding: 6px 0 24px; }
    .nsl-gal-top { padding: 14px 0 6px; }
  }
</style>

<script>
(function(){
  /* Smooth scroll for the CTA to the offers */
  document.querySelectorAll('a.nsl-cta[href="#bundle-selector"]').forEach(function(a){
    a.addEventListener('click', function(e){ e.preventDefault(); var t=document.getElementById('bundle-selector')||document.querySelector('.single_add_to_cart_button'); if(t) t.scrollIntoView({behavior:'smooth',block:'center'}); });
  });
})();
</script>
