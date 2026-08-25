<?php
/**
 * product-bottom: NORIKS ErgoSit — ORTHOPEDIC SEAT CUSHION (orto-ortopedski-jastuk)
 * 1:1 copy of the original page (celinva.com/products/orthopedic-cushion):
 * same sections, same order, same left/right layout, English copy,
 * NORIKS ErgoSit rebrand. Pink accent #e5157e, navy #121030.
 *
 * NOTE: several static graphics still carry Croatian text baked into the image
 * (07_lifestyle_HR, 04_lijecnik_HR, 14_vsebina_HR, 15_znacka_60_dana_HR).
 * Swap them for EN versions when those are produced.
 *
 * Order (original):
 *   1. marquee  2. "#1 Orthopedic Seat Cushion" + UGC  3. End Tailbone (img L / txt R)
 *   4. Improve Posture (txt L / img R)  5. Relief That Adapts (grid L / txt R)
 *   6. UGC reviews strip  7. Engineered (img L / txt R + CTA)
 *   8. Effective Against (accordion, full width)  9. 20x Cheaper (img L / txt R + CTA)
 *   10. Won't Quit (txt L / table R)  11. 60 Days (dark, badge L / txt R)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$oj  = get_template_directory_uri() . '/img/ortopedski-jastuk/';
$ojv = get_template_directory_uri() . '/img/ortopedski-jastuk/videos/';
$oj_img = function( $file, $alt ) use ( $oj ) {
  return '<img src="'.esc_url($oj.$file).'" alt="'.esc_attr($alt).'" loading="lazy" onerror="this.style.display=\'none\'">';
};
?>

<!-- ============ 1) Marquee (dark bar, scrolling) ============ -->
<div class="oj-marquee" aria-hidden="true">
  <div class="oj-marquee-track">
    <?php $oj_ticker = array('BREATHABLE AND WASHABLE','PERFECT ALIGNMENT','STABILITYCORE™ FOAM','OEKO-TEX® CERTIFIED','HYPOALLERGENIC','SILKFLEX™ COVER');
    for ( $r = 0; $r < 2; $r++ ) { foreach ( $oj_ticker as $t ) { echo '<span class="oj-tick">'.esc_html($t).'</span><span class="oj-dot">•</span>'; } } ?>
  </div>
</div>

<!-- ============ 2) "World's #1" + UGC carousel ============ -->
<section class="oj-sec">
  <div class="oj-wrap">
    <h2 class="oj-hero-h">The world's <em>#1 orthopedic seat cushion</em> for everyday comfort</h2>
    <p class="oj-hero-sub">Trusted by thousands of happy customers — from <strong>drivers on the road to office workers and families at home.</strong></p>
    <div class="oj-ugc-grid oj-ugc-5">
      <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
        <div class="oj-ugc-item" data-src="<?php echo esc_url( $ojv.'nasi-'.$i.'.mp4' ); ?>">
          <video class="oj-ugc-video" preload="metadata" playsinline muted></video>
          <span class="oj-ugc-play" aria-label="Play"></span>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ============ 3) End tailbone pain — image LEFT, text RIGHT ============ -->
<section class="oj-sec oj-alt">
  <div class="oj-wrap oj-row2">
    <div class="oj-media"><?php echo $oj_img('07_lifestyle_HR.png','Before and after — tailbone pain while driving'); ?></div>
    <div class="oj-copy">
      <h2 class="oj-h2"><em class="oj-pink-i">End tailbone, sciatica and back pain</em> from sitting</h2>
      <p>Most chairs wreck your body within 30 minutes. <strong>Hips tilt, the spine curves and pressure builds on the tailbone and sciatic nerve.</strong> That is why long drives, office work or a dinner at the table leave your back sore, stiff or numb.</p>
      <p>The NORIKS <strong>ErgoSit orthopedic cushion</strong> is built differently. The tailbone cut-out removes direct pressure from the coccyx, while the contoured design supports the spine and restores healthy posture. High-density memory foam spreads the weight evenly across hips and thighs and keeps the blood flowing so your legs don't go numb.</p>
    </div>
  </div>
</section>

<!-- ============ 4) Improve posture — text LEFT, image RIGHT ============ -->
<section class="oj-sec">
  <div class="oj-wrap oj-row2">
    <div class="oj-copy">
      <h2 class="oj-h2">Improve your posture and boost circulation</h2>
      <p>Car seats and office chairs are built to last, not built for your body. Their shape makes hips sink and thighs press into the seat, circulation slows down — legs get restless and your back aches long after you stand up.</p>
      <p>The NORIKS <strong>ErgoSit</strong> is designed for long hours. The shaped base keeps your hips level, the contoured edges reduce pressure on the thighs, and the lift supports the spine mile after mile. The result? Upright posture, healthy circulation and hours of sitting without pain or stiffness.</p>
    </div>
    <div class="oj-media"><video class="oj-secvid" src="<?php echo esc_url( $ojv.'drzanje.mp4' ); ?>" autoplay muted loop playsinline preload="metadata"></video></div>
  </div>
</section>

<!-- ============ 5) Adapts wherever you sit — grid LEFT, text RIGHT ============ -->
<section class="oj-sec oj-alt">
  <div class="oj-wrap oj-row2">
    <div class="oj-media"><?php echo $oj_img('prilagodba.webp','NORIKS ErgoSit — adapts wherever you sit'); ?></div>
    <div class="oj-copy">
      <h2 class="oj-h2">Relief that adapts wherever you sit.</h2>
      <p>The NORIKS <strong>ErgoSit</strong> adapts to every place you sit. The stable non-slip base holds it in position on <strong>car seats, office chairs, dining chairs and wheelchairs</strong> — so comfort goes with you all day.</p>
      <p>High-density memory foam supports your body without flattening, while the removable washable cover stays fresh, clean and ready for daily use.</p>
    </div>
  </div>
</section>

<!-- ============ 6) Trust bar (like the original press-bar, with real NORIKS marks) + customer STILLS ============ -->
<section class="oj-sec oj-stills-sec">
  <div class="oj-trustbar" aria-hidden="true">
    <div class="oj-trustbar-track">
      <?php $oj_trust = array('120,000+ CUSTOMERS','RATED 4.8/5','OEKO-TEX®','DOCTOR RECOMMENDED','60-DAY GUARANTEE','ORTHOPEDIC DESIGN');
      for ( $r = 0; $r < 2; $r++ ) { foreach ( $oj_trust as $t ) { echo '<span class="oj-trust-item">'.esc_html($t).'</span><span class="oj-trust-dot">•</span>'; } } ?>
    </div>
  </div>
  <div class="oj-wrap">
    <div class="oj-stills">
      <?php for ( $i = 1; $i <= 6; $i++ ) : ?>
        <img src="<?php echo esc_url( $oj.'galerija/li'.$i.'.webp' ); ?>" alt="NORIKS ErgoSit — happy customers" loading="lazy" onerror="this.style.display='none'">
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ============ 7) Engineered with orthopedic insight — image LEFT, text RIGHT + CTA ============ -->
<section class="oj-sec oj-alt">
  <div class="oj-wrap oj-row2">
    <div class="oj-media"><?php echo $oj_img('04_lijecnik_HR.png','Doctor recommended — NORIKS ErgoSit'); ?></div>
    <div class="oj-copy">
      <h2 class="oj-h2">Engineered with orthopedic insight, built for everyday sitting.</h2>
      <p>With input from orthopedic specialists and months of ergonomic testing, the NORIKS <strong>ErgoSit</strong> was designed to ease the most common pains caused by long sitting — from tailbone pressure to lower back and hip discomfort.</p>
      <a class="oj-cta" href="#bundle-selector">👉 ORDER NOW</a>
    </div>
  </div>
</section>

<!-- ============ 8) Effective against common problems — accordion, FULL WIDTH ============ -->
<section class="oj-sec">
  <div class="oj-wrap">
    <h2 class="oj-h2 oj-center"><em class="oj-pink-i">Effective against</em> common sitting problems</h2>
    <div class="oj-acc">
      <?php
      $oj_probs = array(
        array('Tailbone (coccyx) pain','The tailbone cut-out lifts pressure off the coccyx and spreads the weight across the cushion, so you avoid that sharp, burning pain after just a few minutes of sitting.'),
        array('Sciatica and shooting leg pain','By keeping the hips level and the spine upright, the cushion relieves the sciatic nerve — so you can sit, drive or work without the shooting pain that runs down your legs.'),
        array('Lower back pain','Most chairs leave a gap behind your lower back. NORIKS fills it, restores the natural curve of the spine and reduces muscle tension through long hours of sitting.'),
        array('Numbness and swollen legs','Flat surfaces cut off circulation. The contoured edges of the cushion take pressure off the thighs and keep the blood flowing, so your legs feel light and energetic instead of heavy or numb.'),
        array('SI joint and hip pain','Uneven weight strains the hips and joints. NORIKS distributes the pressure evenly, helps maintain balanced posture and reduces tension in the hips.'),
        array('Relief for sensitive sitting','For sensitive areas the cushion combines firm support with gentle contouring — it relieves the pressure so you can sit comfortably even when your body is tender.'),
      );
      foreach ( $oj_probs as $p ) : ?>
        <div class="oj-acc-item">
          <button class="oj-acc-head" type="button" aria-expanded="false">
            <span class="oj-acc-tick">✔</span><span class="oj-acc-title"><?php echo esc_html($p[0]); ?></span><span class="oj-acc-chev">⌄</span>
          </button>
          <div class="oj-acc-body"><p><?php echo esc_html($p[1]); ?></p></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 9) 20x cheaper — image LEFT, text RIGHT + CTA ============ -->
<section class="oj-sec">
  <div class="oj-wrap oj-row2">
    <div class="oj-media"><?php echo $oj_img('14_vsebina_HR.png','NORIKS ErgoSit — sit better, live better'); ?></div>
    <div class="oj-copy">
      <h2 class="oj-h2"><em class="oj-pink-i">20× cheaper</em> than expensive solutions</h2>
      <p>Most people <strong>spend thousands</strong> trying to fix sitting pain:</p>
      <ul class="oj-x">
        <li><span>✕</span> Ergonomic chair: <strong>€750–1,100</strong></li>
        <li><span>✕</span> Weekly chiropractor visits: <strong>€70–140 per visit</strong> (€3,000+ a year)</li>
        <li><span>✕</span> Physical therapy: <strong>€190+ per visit</strong>, often weeks of appointments</li>
      </ul>
      <p><strong>NORIKS ErgoSit orthopedic cushion</strong></p>
      <p>A one-off purchase that eases tailbone, back and hip pain without emptying your wallet.</p>
      <a class="oj-cta" href="#bundle-selector">Order now</a>
    </div>
  </div>
</section>

<!-- ============ 10) A cushion that won't quit — text LEFT, table RIGHT ============ -->
<section class="oj-sec">
  <div class="oj-wrap oj-row2">
    <div class="oj-copy">
      <h2 class="oj-h2">A cushion that won't quit</h2>
      <p class="oj-lead">It stays firm, eases pain and holds support exactly where others fail.</p>
    </div>
    <div class="oj-cmp-wrap">
      <span class="oj-cmp-others">Others</span>
      <div class="oj-cmp-pill"><span>NORIKS</span></div>
      <div class="oj-cmp-card">
        <div class="oj-cmp-row"><div class="f">Relieves tailbone and back</div><div class="us">✓</div><div class="no">✕</div></div>
        <div class="oj-cmp-row"><div class="f">Supports upright, healthy posture</div><div class="us">✓</div><div class="no">✕</div></div>
        <div class="oj-cmp-row"><div class="f">Keeps its shape over time</div><div class="us">✓</div><div class="no">✕</div></div>
        <div class="oj-cmp-row"><div class="f">Non-slip base</div><div class="us">✓</div><div class="no">✕</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ 11) Try it for 60 days — DARK, badge LEFT, text RIGHT ============ -->
<section class="oj-sec oj-guar-sec">
  <div class="oj-wrap">
  <div class="oj-guarantee oj-row2">
    <div class="oj-guar-badge"><?php echo $oj_img('15_znacka_30_dana.png','30-day money-back guarantee'); ?></div>
    <div class="oj-guar-copy">
      <h2 class="oj-h2 oj-h2-light">Try it for <em class="oj-pink-i">60 days</em>, worry-free</h2>
      <p>Finding the right cushion is not easy — many flatten out or simply don't bring real relief. That is why every NORIKS <strong>ErgoSit</strong> comes with our <strong>60-day comfort guarantee</strong>.</p>
      <p>Take it to the office, into the car or for long hours at home. If you don't feel less pain and more comfort in your everyday sitting, our team will make it right.</p>
      <p>Because when it comes to your health and comfort, we believe you should <strong>feel</strong> the difference, not just hope for it.</p>
    </div>
  </div>
  </div>
</section>

<style>
  .oj-wrap { max-width: 1440px; margin: 0 auto; padding: 0 22px; } /* same width as the .product container above */
  .oj-wrap-narrow { max-width: 820px; margin: 0 auto; padding: 0 18px; }
  .oj-sec { padding: 60px 0; }
  .oj-alt { background: #faf6f9; }
  .oj-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
  .oj-h2 { font-size: clamp(24px,3vw,36px); font-weight: 800; color: #121030; line-height: 1.15; margin: 0 0 16px; }
  .oj-h2-light { color: #fff; }
  .oj-pink-i { color: #e5157e; font-style: italic; }
  .oj-center { text-align: center; }
  .oj-copy p, .oj-lead { font-size: 15.5px; line-height: 1.6; color: #3a3450; margin: 0 0 14px; }
  .oj-lead { font-size: 16px; color: #55506b; }
  .oj-media img, .oj-grid2 img, .oj-media video.oj-secvid { width: 100%; height: auto; display: block; border-radius: 18px; box-shadow: 0 14px 40px rgba(27,21,51,.10); }

  /* 1) Marquee */
  .oj-marquee { background: #121030; overflow: hidden; white-space: nowrap; }
  .oj-marquee-track { display: inline-block; padding: 13px 0; animation: ojScroll 26s linear infinite; }
  .oj-tick { color: #fff; font-weight: 800; font-style: italic; font-size: 15px; letter-spacing: .06em; text-transform: uppercase; }
  .oj-dot { color: #e5157e; margin: 0 22px; font-weight: 800; }
  @keyframes ojScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

  /* 2) hero */
  .oj-hero-h { text-align: center; font-size: clamp(26px,3.4vw,42px); font-weight: 800; color: #121030; line-height: 1.12; margin: 0 auto 12px; max-width: 900px; }
  .oj-hero-h em { color: #e5157e; font-style: italic; }
  .oj-hero-sub { text-align: center; font-size: 16px; color: #55506b; max-width: 660px; margin: 0 auto 28px; line-height: 1.55; }

  /* UGC */
  .oj-ugc-grid { display: grid; gap: 12px; }
  .oj-ugc-3 { grid-template-columns: repeat(3,1fr); max-width: 760px; margin: 0 auto; }
  .oj-ugc-5 { grid-template-columns: repeat(4,1fr); max-width: 1000px; margin: 0 auto; }
  .oj-ugc-item { position: relative; aspect-ratio: 9/16; border-radius: 12px; overflow: hidden; background: #121030; cursor: pointer; }
  .oj-ugc-item video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .oj-ugc-play { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,.92); }
  .oj-ugc-play::after { content: ""; position: absolute; top: 50%; left: 54%; transform: translate(-50%,-50%); border-style: solid; border-width: 10px 0 10px 16px; border-color: transparent transparent transparent #121030; }

  .oj-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

  /* CTA — dark navy button like the original */
  .oj-cta { display: inline-block; background: #121030; color: #fff; font-weight: 800; font-size: 15px; letter-spacing: .04em; padding: 15px 34px; border-radius: 8px; text-decoration: none; margin-top: 8px; }
  .oj-cta:hover { background: #e5157e; color: #fff; }

  /* 8) accordion */
  .oj-acc { max-width: 880px; margin: 18px auto 0; border-top: 1px solid #ecdfe8; }
  .oj-acc-item { border-bottom: 1px solid #ecdfe8; }
  .oj-acc-head { width: 100%; background: none; border: 0; display: flex; align-items: center; gap: 12px; padding: 16px 4px; cursor: pointer; font-size: 15.5px; font-weight: 700; color: #121030; text-align: left; }
  .oj-acc-tick { color: #22b573; font-weight: 800; }
  .oj-acc-title { flex: 1; }
  .oj-acc-chev { transition: transform .2s; color: #b39aab; }
  .oj-acc-item.open .oj-acc-chev { transform: rotate(180deg); }
  .oj-acc-body { max-height: 0; overflow: hidden; transition: max-height .25s ease; }
  .oj-acc-item.open .oj-acc-body { max-height: 260px; }
  .oj-acc-body p { font-size: 14.5px; line-height: 1.6; color: #4a4560; margin: 0 0 16px; padding-left: 28px; }

  /* 9) X list */
  .oj-x { list-style: none; margin: 0 0 14px; padding: 0; }
  .oj-x li { font-size: 15px; color: #3a3450; margin: 0 0 10px; }
  .oj-x li span { color: #d64545; font-weight: 800; margin-right: 8px; }

  /* 6) Trust bar — light, "logo" style like the original press-bar */
  .oj-trustbar { background: #f7f0f2; overflow: hidden; white-space: nowrap; width: 100vw; margin-left: calc(50% - 50vw); }
  .oj-trustbar-track { display: inline-block; padding: 14px 0; animation: ojScroll 34s linear infinite; }
  .oj-trust-item { color: #9b96a6; font-weight: 800; font-style: italic; font-size: 15px; letter-spacing: .06em; text-transform: uppercase; }
  .oj-trust-dot { color: #e5157e; margin: 0 22px; font-weight: 800; }

  /* 6) UGC stills (customer photos) — full-bleed like the original */
  .oj-stills-sec { padding: 20px 0 40px; }
  .oj-stills-sec .oj-wrap { margin-top: 0; }
  .oj-stills-sec .oj-wrap { max-width: none; padding: 0; }
  .oj-stills { display: grid; grid-template-columns: repeat(6,1fr); gap: 6px; width: 100vw; margin-left: calc(50% - 50vw); }
  .oj-stills img { width: 100%; aspect-ratio: 9/16; object-fit: cover; display: block; border-radius: 0; }

  /* 10) comparison — white card + floating pink pill (like the original) */
  .oj-cmp-wrap { position: relative; padding: 40px 0 30px; }
  .oj-cmp-others { position: absolute; top: 8px; right: 0; width: 88px; text-align: center; font-weight: 800; color: #121030; font-size: 14px; }
  .oj-cmp-pill { position: absolute; top: 0; bottom: 0; right: 96px; width: 100px; background: #e5157e; border-radius: 28px; box-shadow: 0 16px 36px rgba(229,21,126,.35); z-index: 1; display: flex; justify-content: center; align-items: flex-start; padding-top: 14px; }
  .oj-cmp-pill span { color: #fff; font-weight: 800; font-size: 10.5px; letter-spacing: .14em; }
  .oj-cmp-card { position: relative; background: #fff; border-radius: 16px; box-shadow: 0 12px 34px rgba(27,21,51,.10); border: 1px solid #f1edf3; }
  .oj-cmp-row { display: grid; grid-template-columns: 1fr 100px 88px; align-items: center; border-bottom: 1px solid #f2eff4; min-height: 62px; }
  .oj-cmp-row:last-child { border-bottom: 0; }
  .oj-cmp-row .f { padding: 14px 16px; text-align: center; font-weight: 800; color: #121030; font-size: 15px; line-height: 1.3; }
  .oj-cmp-row .us { position: relative; z-index: 2; text-align: center; color: #fff; font-weight: 800; font-size: 18px; }
  .oj-cmp-row .no { text-align: center; color: #e23a3a; font-weight: 800; font-size: 16px; }

  /* 11) guarantee — rounded dark card (like the original) */
  .oj-guar-sec { padding-top: 20px; }
  .oj-guarantee { background: #121030; border-radius: 18px; padding: 52px 48px; }
  .oj-guar-copy p { color: #cfc9e0; font-size: 15px; line-height: 1.6; margin: 0 0 12px; }
  .oj-guar-badge img { width: 280px; max-width: 100%; height: auto; margin: 0 auto; display: block; border-radius: 50%; }

  @media (max-width: 860px) {
    /* mobile: halved spacing between sections */
    .oj-sec { padding: 30px 0; }
    .oj-marquee + section.oj-sec { padding-top: 20px; }
    .oj-hero-h { font-size: 2rem !important; }
    .oj-stills-sec { padding: 10px 0 20px; }
    .oj-guar-sec { padding-top: 10px; }
    .oj-row2 { grid-template-columns: 1fr; gap: 18px; }
    .oj-ugc-3 { grid-template-columns: repeat(3,1fr); }
    /* hero videos: horizontal slider in one row (like the original) */
    .oj-ugc-5 { display: flex; overflow-x: auto; gap: 10px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding: 0 4px 6px; }
    .oj-ugc-5 .oj-ugc-item { flex: 0 0 46%; scroll-snap-align: center; }
    .oj-stills { grid-template-columns: repeat(3,1fr); }
    .oj-row2 .oj-media, .oj-row2 .oj-grid2 { order: -1; }
    .oj-guarantee { padding: 34px 22px; }
    .oj-guarantee .oj-guar-badge { order: -1; }
    .oj-cmp-others { width: 72px; }
    .oj-cmp-pill { right: 78px; width: 84px; }
    .oj-cmp-row { grid-template-columns: 1fr 84px 72px; }
  }

  /* No-attrs: hide "Size chart" if it shows up anywhere */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap, #open-size-chart, #open-size-chartCustom { display: none !important; }
</style>

<script>
(function(){
  /* Pink active bundle-option (survives LiteSpeed UCSS). */
  function paintOj(){
    var sel = document.getElementById('bundle-selector'); if(!sel) return;
    sel.querySelectorAll('.bundle-option').forEach(function(c){ c.style.removeProperty('border-color'); c.style.removeProperty('background'); c.style.removeProperty('border-width'); });
    var checked = sel.querySelector('input[name="bundle_option"]:checked');
    var card = checked ? checked.closest('.bundle-option') : (sel.querySelector('.bundle-option.active') || sel.querySelector('.bundle-option'));
    if(card){ card.style.setProperty('border-color','#ED5E95','important'); card.style.setProperty('background','rgba(237,94,149,0.1)','important'); card.style.setProperty('border-width','2px','important'); }
  }
  function bindOj(){ var sel=document.getElementById('bundle-selector'); if(!sel) return; paintOj(); sel.querySelectorAll('input[name="bundle_option"]').forEach(function(r){ r.addEventListener('change', paintOj); }); }
  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded', bindOj); } else { bindOj(); }

  /* Accordion */
  document.querySelectorAll('.oj-acc-head').forEach(function(btn){
    btn.addEventListener('click', function(){ var it=btn.closest('.oj-acc-item'); var open=it.classList.toggle('open'); btn.setAttribute('aria-expanded', open?'true':'false'); });
  });

  /* UGC video: show the first frame, click = play with sound */
  document.querySelectorAll('.oj-ugc-item').forEach(function(item){
    var v = item.querySelector('.oj-ugc-video'); if(!v) return; v.src = item.dataset.src;
    item.addEventListener('click', function(){
      if (item.dataset.loaded) return; item.dataset.loaded='1';
      var play=item.querySelector('.oj-ugc-play'); if(play) play.remove();
      v.muted=false; v.controls=true; v.playsInline=true; var p=v.play(); if(p&&p.catch) p.catch(function(){});
    });
  });

  /* Smooth scroll for the CTA */
  document.querySelectorAll('a.oj-cta[href="#bundle-selector"]').forEach(function(a){
    a.addEventListener('click', function(e){ e.preventDefault(); var t=document.getElementById('bundle-selector')||document.querySelector('.single_add_to_cart_button'); if(t) t.scrollIntoView({behavior:'smooth',block:'center'}); });
  });
})();
</script>
