<?php
/**
 * product-bottom: FISIOREST (orto-fisiorest)
 *
 * Dedicated bottom-nicer for the NORIKS FisioRest product.
 * Shown via single-product-bottom-nicer.php when noriks_is_type('fisiorest').
 *
 * Media live in the theme: img/fisiorest*
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// 1) "Backed by science" — 4 cards.
$fis_science = array(
    array( 'title' => 'Tech neck hump',   'text' => 'Traction <strong>releases the pressure and restores proper posture</strong> to reduce the tech-neck hump.' ),
    array( 'title' => 'Neck pain',        'text' => '<strong>Eases muscle knots and stiffness</strong> and <strong>realigns the curve of the neck</strong> for fast pain relief.' ),
    array( 'title' => 'Quality sleep',    'text' => 'Soothing therapy <strong>relaxes the neck and spine</strong> for deep, restorative sleep.' ),
    array( 'title' => 'Stress relief',    'text' => 'Warm massage and stretching <strong>release built-up tension</strong> for greater comfort.' ),
);
$fis_v = get_template_directory_uri() . '/img/fisiorest-videos/';
$fis_hero_video = $fis_v . 'hero.mp4';

// 3) Recommended by experts
$fis_experts = array(
    array( 'vid' => $fis_v.'v01.mp4', 'name' => 'Marina Juric',    'role' => 'Certified massage therapist', 'org' => '' ),
    array( 'vid' => $fis_v.'v08.mp4', 'name' => 'Dr. Ana Kovacevic','role' => 'Doctor of physical therapy',  'org' => '' ),
    array( 'vid' => $fis_v.'v03.mp4', 'name' => 'Dr. Ivan Horvat', 'role' => 'Doctor of chiropractic',      'org' => '' ),
);
// 4) Customer experiences
$fis_ugc = array(
    array( 'vid' => $fis_v.'v09c.mp4', 'cap' => '"For the first time in ages I am finally pain-free…"' ),
    array( 'vid' => $fis_v.'v06.mp4', 'cap' => '"The NORIKS pillow is my new daily essential…"' ),
    array( 'vid' => $fis_v.'v11c.mp4', 'cap' => '"It really helps me release tension in my neck and shoulders."' ),
    array( 'vid' => $fis_v.'v02c.mp4', 'cap' => '"If you are a new mum, this might be exactly what you need…"' ),
);
// 5) ThermoTrac 3-in-1
$fis_thermo = array(
    array( 'vid' => $fis_v.'v10.mp4', 'title' => 'Traction therapy' ),
    array( 'vid' => $fis_v.'v07.mp4', 'title' => 'Vibration therapy' ),
    array( 'vid' => $fis_v.'v05.mp4', 'title' => 'Heat therapy' ),
);
// 6) Four upgrades
$fis_upgrades = array(
    array( 't' => 'Rechargeable battery',   'd' => 'Take it anywhere — the 2500 mAh battery lasts up to 2 hours.' , 'ico' => 'battery-v2.png' ),
    array( 't' => 'Mulberry silk cover',    'd' => 'Wrapped in cooling silk for a soft, luxurious feel.' , 'ico' => 'silk-v2.png' ),
    array( 't' => 'Auto shut-off at 30 min','d' => 'Four 30-minute sessions per charge, with nothing to worry about.' , 'ico' => 'timer-v2.png' ),
    array( 't' => 'Regulated heat',         'd' => 'Heating to 50 °C with advanced ThermoTrac technology.' , 'ico' => 'heat-v2.png' ),
);
?>

<!-- ============ 1) Backed by science ============ -->
<section class="fis-science">
  <div class="fis-wrap">
    <div class="fis-box">
      <h2 class="fis-title">Backed by science: proven relief for neck care</h2>
      <div class="fis-grid">
        <?php foreach ( $fis_science as $fis_c ) : ?>
          <div class="fis-card">
            <h3 class="fis-card-title"><?php echo esc_html( $fis_c['title'] ); ?></h3>
            <p class="fis-card-text"><?php echo wp_kses_post( $fis_c['text'] ); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ 2) Video hero + heading ============ -->
<section class="fis-hero">
  <video class="fis-hero-vid" src="<?php echo esc_url( $fis_hero_video ); ?>" muted autoplay loop playsinline preload="metadata"></video>
  <div class="fis-hero-overlay"></div>
  <div class="fis-hero-inner">
    <h2 class="fis-hero-title">Unlock a deep reset with the combined power of alignment and relaxation</h2>
  </div>
</section>

<!-- ============ 3) Recommended by experts ============ -->
<section class="fis-experts">
  <div class="fis-wrap fis-exp-grid">
    <div class="fis-exp-quote">
      <h2 class="fis-h2">Recommended by experts</h2>
      <p>"NORIKS is one of the best neck pillows on the market right now. Having been in the wellness community for more than 25 years, I have tried all sorts of neck pillows, and what sets NORIKS apart is the traction function…</p>
      <p>If you have forward head posture or a 'slouched' posture, traction can help the vertebrae realign and fully support the body. I use it myself and recommend it to my clients!"</p>
      <p class="fis-exp-author"><strong>Marina Juric</strong><br>Certified massage therapist</p>
    </div>
    <div class="fis-exp-cards">
      <?php foreach ( $fis_experts as $e ) : ?>
        <div class="fis-exp-card">
          <video src="<?php echo esc_url( $e['vid'] ); ?>" muted autoplay loop playsinline preload="metadata"></video>
          <div class="fis-exp-cap">
            <div class="fis-exp-name"><?php echo esc_html( $e['name'] ); ?></div>
            <div class="fis-exp-role"><?php echo esc_html( $e['role'] ); ?></div>
            <?php if ( $e['org'] ) : ?><div class="fis-exp-org"><?php echo esc_html( $e['org'] ); ?></div><?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 4) Customer experiences ============ -->
<section class="fis-ugc">
  <div class="fis-wrap">
    <div class="fis-ugc-grid">
      <?php foreach ( $fis_ugc as $u ) : ?>
        <div class="fis-ugc-card">
          <div class="fis-ugc-media"><video src="<?php echo esc_url( $u['vid'] ); ?>" muted autoplay loop playsinline preload="metadata"></video></div>
          <p class="fis-ugc-cap"><?php echo esc_html( $u['cap'] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 5) ThermoTrac 3-in-1 ============ -->
<section class="fis-thermo">
  <div class="fis-wrap">
    <p class="fis-eyebrow"><strong>ThermoTrac™</strong> technology</p>
    <h2 class="fis-h2 fis-center">Experience 3-in-1 neck therapy</h2>
    <div class="fis-thermo-grid">
      <?php foreach ( $fis_thermo as $t ) : ?>
        <div class="fis-thermo-card">
          <video src="<?php echo esc_url( $t['vid'] ); ?>" muted autoplay loop playsinline preload="metadata"></video>
          <div class="fis-thermo-label"><?php echo esc_html( $t['title'] ); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 6) Four upgrades ============ -->
<section class="fis-upg">
  <div class="fis-wrap">
    <h2 class="fis-h2 fis-center">Four big upgrades</h2>
    <div class="fis-upg-grid">
      <?php foreach ( $fis_upgrades as $g ) : ?>
        <div class="fis-upg-card">
          <img class="fis-upg-ico" src="<?php echo esc_url( get_template_directory_uri() . '/img/fisiorest-icons/' . $g['ico'] ); ?>" alt="" loading="lazy" width="120" height="120">
          <div class="fis-upg-title"><?php echo esc_html( $g['t'] ); ?></div>
          <p class="fis-upg-text"><?php echo esc_html( $g['d'] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 7) Designed by engineers ============ -->
<section class="fis-eng">
  <div class="fis-wrap fis-row2">
    <div class="fis-row2-copy">
      <h2 class="fis-h2">Designed by engineers. Built to PT standards</h2>
      <p>We invested more than €50,000 and 2 years of development to make sure NORIKS is not just a neck massager. It is a complete neck therapy device that treats the actual cause. Every order goes through detailed quality control so it arrives in perfect condition.</p>
    </div>
    <div class="fis-row2-media"><video src="<?php echo esc_url( $fis_v.'hero.mp4' ); ?>" muted autoplay loop playsinline preload="metadata"></video></div>
  </div>
</section>

<!-- ============ 8) 14x cheaper ============ -->
<section class="fis-cheaper">
  <div class="fis-wrap fis-row2">
    <div class="fis-row2-media"><video src="<?php echo esc_url( $fis_v.'v08.mp4' ); ?>" muted autoplay loop playsinline preload="metadata"></video></div>
    <div class="fis-row2-copy">
      <p class="fis-eyebrow">SAFE AND RELAXING THERAPY</p>
      <h2 class="fis-h2">14× cheaper than weekly appointments</h2>
      <p>NORIKS pays for itself in days, not months. Recommended by therapists, it delivers gentle relief without harsh pressure or risky procedures. Use it every evening. No booking. No travelling. No recurring costs — just safe, consistent neck care whenever you need it.</p>
    </div>
  </div>
</section>

<style>
  /* 2) Video hero */
  .fis-hero { position: relative; width: 100vw; left: 50%; margin-left: -50vw; min-height: 520px; display: flex; align-items: center; overflow: hidden; }
  .fis-hero-vid { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .fis-hero-overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(0,0,0,0.5), rgba(0,0,0,0.05) 55%); }
  .fis-hero-inner { position: relative; z-index: 2; max-width: 1180px; margin: 0 auto; padding: 0 40px; width: 100%; box-sizing: border-box; }
  .fis-hero-title { color: #fff; font-weight: 800; font-size: clamp(27px,4vw,46px); line-height: 1.15; max-width: 640px; margin: 0; text-shadow: 0 2px 14px rgba(0,0,0,0.4); }
  @media (max-width: 768px) { .fis-hero { min-height: 380px; } .fis-hero-inner { padding: 0 22px; } }

  /* shared */
  .fis-h2 { font-size: clamp(24px,3vw,34px); font-weight: 800; color: #1c1c1c; line-height: 1.2; margin: 0 0 18px; }
  .fis-center { text-align: center; }
  .fis-eyebrow { font-size: 13px; letter-spacing: 1px; text-transform: uppercase; color: #555; margin: 0 0 8px; }

  /* 3) Experts */
  .fis-experts { padding: 44px 0; }
  .fis-exp-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 40px; align-items: start; }
  .fis-exp-quote p { font-size: 15.5px; line-height: 1.6; color: #333; margin: 0 0 14px; }
  .fis-exp-author { color: #1c1c1c; }
  .fis-exp-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
  .fis-exp-card { position: relative; border-radius: 14px; overflow: hidden; aspect-ratio: 3/4; background: #222; }
  .fis-exp-card video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .fis-exp-cap { position: absolute; left: 0; right: 0; bottom: 0; padding: 14px; color: #fff; background: linear-gradient(0deg, rgba(0,0,0,.75), rgba(0,0,0,0)); }
  .fis-exp-name { font-weight: 800; font-size: 18px; }
  .fis-exp-role { font-size: 13px; }
  .fis-exp-org { font-size: 12px; font-style: italic; opacity: .9; }

  /* 4) UGC */
  .fis-ugc { background: #223047; padding: 40px 0; }
  .fis-ugc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
  .fis-ugc-media { border-radius: 12px; overflow: hidden; aspect-ratio: 3/4; background: #000; }
  .fis-ugc-media video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .fis-ugc-cap { color: #eee; font-size: 14px; line-height: 1.5; margin: 10px 0 0; }

  /* 5) ThermoTrac */
  .fis-thermo { background: #f0efe9; padding: 44px 0; }
  .fis-thermo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
  .fis-thermo-card { position: relative; border-radius: 16px; overflow: hidden; aspect-ratio: 4/3; background: #111; }
  .fis-thermo-card video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .fis-thermo-label { position: absolute; left: 16px; bottom: 14px; color: #fff; font-weight: 800; font-size: 18px; text-shadow: 0 1px 8px rgba(0,0,0,.6); }

  /* 6) Upgrades */
  .fis-upg { padding: 44px 0; }
  .fis-upg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center; }
  .fis-upg-ico { width: 56px; height: 56px; object-fit: contain; display: block; margin: 0 auto 14px; }
  .fis-upg-title { font-weight: 800; color: #1c1c1c; margin: 0 0 8px; font-size: 16px; }
  .fis-upg-text { font-size: 14px; line-height: 1.5; color: #444; margin: 0; }

  /* 7 + 8) row2 */
  .fis-eng, .fis-cheaper { padding: 40px 0; }
  .fis-cheaper { background: #f4f4f4; }
  .fis-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 44px; align-items: center; }
  .fis-row2-copy p { font-size: 15.5px; line-height: 1.65; color: #333; }
  .fis-row2-media { border-radius: 16px; overflow: hidden; }
  .fis-row2-media video { width: 100%; height: auto; display: block; }
  .fis-cheaper .fis-row2-media { aspect-ratio: 16 / 10; }
  .fis-cheaper .fis-row2-media video { height: 100%; object-fit: cover; }

  @media (max-width: 900px) {
    .fis-exp-grid { grid-template-columns: 1fr; gap: 24px; }
    .fis-ugc-grid { grid-template-columns: 1fr 1fr; }
    .fis-thermo-grid { grid-template-columns: 1fr; }
    .fis-upg-grid { grid-template-columns: 1fr 1fr; gap: 22px; }
    .fis-row2 { grid-template-columns: 1fr; gap: 22px; }
    .fis-cheaper .fis-row2-media { order: 2; }
  }
  @media (max-width: 560px) {
    .fis-exp-cards { grid-template-columns: 1fr 1fr; }
    .fis-ugc-grid { grid-template-columns: 1fr; }
  }

  /* 1) Backed by science — grey card */
  .fis-science { background: #f4f4f4; padding: 44px 0; }
  .fis-wrap { max-width: 1180px; margin: 0 auto; padding: 0 16px; }
  .fis-box { background: transparent; border-radius: 0; padding: 0; }
  .fis-title { font-size: clamp(23px,2.9vw,32px); font-weight: 800; color: #1c1c1c; line-height: 1.2; margin: 0 0 26px; }
  .fis-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; }
  .fis-card { padding: 0 22px; border-left: 1px solid #dcdcdc; }
  .fis-card:first-child { border-left: 0; padding-left: 0; }
  .fis-card-title { font-size: 17px; font-weight: 800; color: #1c1c1c; margin: 0 0 10px; }
  .fis-card-text { font-size: 15px; line-height: 1.55; color: #333; margin: 0; }
  @media (max-width: 820px) {
    .fis-grid { grid-template-columns: 1fr 1fr; gap: 22px 0; }
    .fis-card:nth-child(odd) { border-left: 0; padding-left: 0; }
  }
  @media (max-width: 480px) {
    .fis-grid { grid-template-columns: 1fr; }
    .fis-card { border-left: 0; padding-left: 0; }
  }

  /* No "Size chart" link on FisioRest (same as bunion). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Short description: hide the standard bullets (•), spacing. */
  .woocommerce-product-details__short-description ul { list-style: none; margin: 8px 0 26px; padding-left: 0; }
  .woocommerce-product-details__short-description ul li { list-style: none; padding-left: 0; margin-left: 0; }
  .woocommerce-product-details__short-description p:has(+ ul) { margin-top: 20px; margin-bottom: 4px; }
</style>

<script>
/* Orange active bundle-option via inline !important (survives LiteSpeed UCSS). */
(function(){
  function paintOrto(){
    var sel = document.getElementById('bundle-selector');
    if(!sel) return;
    sel.querySelectorAll('.bundle-option').forEach(function(c){ c.style.removeProperty('border-color'); c.style.removeProperty('background'); });
    var checked = sel.querySelector('input[name="bundle_option"]:checked');
    var card = checked ? checked.closest('.bundle-option')
             : (sel.querySelector('.bundle-option.active') || sel.querySelector('.bundle-option'));
    if(card){ card.style.setProperty('border-color','#f39c12','important'); card.style.setProperty('background','#f39c1217','important'); }
  }
  function bindOrto(){
    var sel = document.getElementById('bundle-selector');
    if(!sel) return;
    paintOrto();
    sel.querySelectorAll('input[name="bundle_option"]').forEach(function(r){ r.addEventListener('change', paintOrto); });
  }
  if(document.readyState==='loading'){ document.addEventListener('DOMContentLoaded', bindOrto); } else { bindOrto(); }
})();
</script>
