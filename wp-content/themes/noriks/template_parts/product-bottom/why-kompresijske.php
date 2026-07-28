<!-- product-bottom: COMPRESSION SOCKS (kompresijske-nogavice) -->
<?php
// Images live in the theme's /img/ folder. Drop these 3 files there:
//   wp-content/themes/noriks/img/kompresijske-1.jpg
//   wp-content/themes/noriks/img/kompresijske-2.jpg
//   wp-content/themes/noriks/img/kompresijske-3.jpg
// Until a file exists, a neutral placeholder is shown.
// Drop the images into wp-content/themes/noriks/img/ named so they start
// with "kompresijske" (e.g. kompresijske-1.jpg, kompresijske-2.jpg ...).
// Any of jpg/jpeg/png/webp works; they are used in sorted order.
$kn_dir_path = get_template_directory() . '/img/';
$kn_dir_uri  = get_template_directory_uri() . '/img/';

$kn_matches = glob( $kn_dir_path . 'kompresijske*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE );
$kn_matches = is_array( $kn_matches ) ? $kn_matches : array();
sort( $kn_matches );

$kn_url = function( $i ) use ( $kn_matches, $kn_dir_uri ) {
    return isset( $kn_matches[ $i ] ) ? ( $kn_dir_uri . rawurlencode( basename( $kn_matches[ $i ] ) ) ) : '';
};
$kn_img_1 = $kn_url( 0 );
$kn_img_2 = $kn_url( 1 );
$kn_img_3 = $kn_url( 2 );

$kn_placeholder = '<div style="width:100%;aspect-ratio:1/1;background:#f1f1f1;"></div>';
$knv = get_template_directory_uri() . '/img/kompresijske-videos/';
?>

<!-- How it works in practice (product demo videos, autoplay on view) — FIRST -->
<section class="why-section knc-demo">
  <div class="knc-demo-wrap">
    <h2 class="knc-demo-title">How it works in practice</h2>
    <div class="knc-demo-grid">
      <video class="knc-lazyvid" data-src="<?php echo esc_url( $knv ); ?>demo-1.mp4" poster="<?php echo esc_url( $knv ); ?>demo-1-poster.jpg" muted loop playsinline preload="none"></video>
      <video class="knc-lazyvid" data-src="<?php echo esc_url( $knv ); ?>demo-2.mp4" poster="<?php echo esc_url( $knv ); ?>demo-2-poster.jpg" muted loop playsinline preload="none"></video>
      <video class="knc-lazyvid" data-src="<?php echo esc_url( $knv ); ?>demo-3.mp4" poster="<?php echo esc_url( $knv ); ?>demo-3-poster.jpg" muted loop playsinline preload="none"></video>
      <video class="knc-lazyvid" data-src="<?php echo esc_url( $knv ); ?>demo-4.mp4" poster="<?php echo esc_url( $knv ); ?>demo-4-poster.jpg" muted loop playsinline preload="none"></video>
    </div>
  </div>
</section>

<!-- Comparison: NORIKS vs the rest -->
<section class="why-section knc-compare-section">
  <div class="knc-compare-wrap">
    <h2 class="knc-compare-title">NORIKS vs the rest</h2>
    <div class="knc-table-scroll">
      <table class="knc-table">
        <thead>
          <tr>
            <th class="knc-feat"></th>
            <th class="knc-comp">Classic stockings<span>(Bauerfeind, medi…)</span></th>
            <th class="knc-comp">TV socks<span>(Zip Sox &amp; Co.)</span></th>
            <th class="knc-us">NORIKS<em class="knc-badge">No. 1</em></th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Medical compression</td><td class="ok">✓</td><td class="no">✕</td><td class="us ok">✓</td></tr>
          <tr><td>Zip for easy putting on</td><td class="no">✕</td><td class="ok">✓</td><td class="us ok">✓</td></tr>
          <tr><td>Put on by yourself, no help needed</td><td class="no">✕</td><td class="mid">~</td><td class="us ok">✓</td></tr>
          <tr><td>Reinforced zip that never jams</td><td class="mid">—</td><td class="no">✕</td><td class="us ok">✓</td></tr>
          <tr><td>Breathable fabric</td><td class="mid">~</td><td class="no">✕</td><td class="us ok">✓</td></tr>
          <tr><td>All-day comfort (12+ hours)</td><td class="mid">~</td><td class="no">✕</td><td class="us ok">✓</td></tr>
          <tr><td>60-day money-back guarantee</td><td class="no">✕</td><td class="no">✕</td><td class="us ok">✓</td></tr>
          <tr class="knc-price"><td>Price per pair</td><td>from €85</td><td>~€15</td><td class="us">from €23.33</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="why-section">
  <div style="max-width: 1440px;" class="container why-container">

    <!-- Left media -->
    <div class="why-col">
      <div class="video-wrapper">
        <?php if ( $kn_img_1 ) : ?>
          <img loading="lazy" decoding="async" style="width:100%; aspect-ratio:1/1; object-fit:cover;" src="<?php echo esc_url( $kn_img_1 ); ?>" alt="Compression socks">
        <?php else : echo $kn_placeholder; endif; ?>
      </div>
    </div>

    <!-- Right content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
        WHY COMPRESSION SOCKS?
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Better circulation, less fatigue</strong></p>
        <p class="description">Graduated compression gently encourages blood flow back towards the heart, reduces that heavy, tired feeling in the legs and helps them stay light even through the longest day.</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Less swelling</strong></p>
        <p class="description">Ideal for long journeys and for standing or sitting for hours. Even pressure reduces fluid retention and swelling around the ankles and calves.</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Support you can feel</strong></p>
        <p class="description">The anatomical cut keeps the sock in place without digging in at the top. Firm but comfortable support that lasts all day.</p>
      </div>
    </div>

  </div>
</section>


<style>
  .knc-compare-section { background:#fff; padding:30px 0 40px; }
  .knc-compare-wrap { max-width:940px; margin:0 auto; padding:0 16px; }
  .knc-compare-title { text-align:center; font-size:clamp(24px,3vw,34px); font-weight:700; color:#111; margin:0 0 24px; }
  .knc-table-scroll { border-radius:16px; overflow:hidden; box-shadow:0 12px 34px rgba(18,48,90,.12); border:1px solid #edf0f4; }
  .knc-table { width:100%; border-collapse:collapse; table-layout:fixed; margin:0 !important; }
  .knc-table th, .knc-table td { padding:15px 12px; text-align:center; font-size:15px; }
  .knc-table thead th { color:#fff; font-weight:700; vertical-align:middle; font-size:14px; }
  .knc-table thead th:first-child { width:34%; background:#fff; }
  .knc-table .knc-comp { background:#767676; }
  .knc-table .knc-comp span { display:block; font-weight:400; font-size:11.5px; opacity:.8; margin-top:3px; }
  .knc-table .knc-us { background:#111; }
  .knc-badge { display:inline-block; margin-left:6px; background:#fff; color:#111; font-style:normal; font-weight:700; font-size:10.5px; padding:2px 8px; border-radius:999px; vertical-align:middle; }
  .knc-table tbody td:first-child { text-align:left; font-weight:600; color:#111; font-size:14px; line-height:1.3; padding-left:18px; }
  .knc-table tbody tr { border-bottom:1px solid #eef0f4; }
  .knc-table tbody tr:nth-child(even) { background:#fafbfc; }
  .knc-table td.ok { color:#1a9e5f; font-size:19px; font-weight:700; }
  .knc-table td.no { color:#cdd2da; font-size:18px; }
  .knc-table td.mid { color:#e0a52e; font-size:18px; font-weight:700; }
  .knc-table td.us { background:#f3f3f3 !important; }
  .knc-table td.us.ok { color:#1a9e5f; }
  .knc-table .knc-price td { font-weight:700; color:#4a5568; }
  .knc-table .knc-price td:first-child { color:#1e2a3a; }
  .knc-table .knc-price td.us { color:#111; font-size:16px; }
  @media (max-width:640px){
    .knc-table th, .knc-table td { padding:12px 6px; font-size:13px; }
    .knc-table thead th { font-size:12px; }
    .knc-table thead th:first-child { width:40%; }
    .knc-table tbody td:first-child { font-size:12px; padding-left:10px; }
    .knc-badge { display:block; margin:4px auto 0; width:-moz-max-content; width:max-content; }
  }
</style>

<!-- What our customers say (UGC testimonial videos, load on click) -->
<section class="why-section knc-ugc">
  <div class="knc-ugc-wrap">
    <h2 class="knc-ugc-title">What our customers say</h2>
    <div class="knc-ugc-grid">
      <div class="knc-ugc-item" data-src="<?php echo esc_url( $knv ); ?>review-1.mp4"><video class="knc-ugc-video" poster="<?php echo esc_url( $knv ); ?>review-1-poster.jpg" preload="none" playsinline></video><span class="knc-ugc-play" aria-label="Play"></span></div>
      <div class="knc-ugc-item" data-src="<?php echo esc_url( $knv ); ?>review-2.mp4"><video class="knc-ugc-video" poster="<?php echo esc_url( $knv ); ?>review-2-poster.jpg" preload="none" playsinline></video><span class="knc-ugc-play" aria-label="Play"></span></div>
      <div class="knc-ugc-item" data-src="<?php echo esc_url( $knv ); ?>review-3.mp4"><video class="knc-ugc-video" poster="<?php echo esc_url( $knv ); ?>review-3-poster.jpg" preload="none" playsinline></video><span class="knc-ugc-play" aria-label="Play"></span></div>
    </div>
  </div>
</section>

<style>
  .knc-demo { background:#f4f4f4; padding:30px 0 22px; }
  .knc-demo-wrap, .knc-ugc-wrap { max-width:1100px; margin:0 auto; padding:0 16px; }
  .knc-demo-title { text-align:center; font-size:clamp(22px,3vw,30px); font-weight:700; color:#222; margin:0 0 22px; }
  .knc-demo-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
  .knc-lazyvid { width:100%; aspect-ratio:9/16; object-fit:cover; border-radius:8px; background:#eceae4; display:block; }
  .knc-ugc { background:#fff; padding:34px 0 42px; }
  .knc-ugc-title { text-align:center; font-size:clamp(22px,3vw,30px); font-weight:700; color:#222; margin:0 0 24px; }
  .knc-ugc-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; max-width:820px; margin:0 auto; }
  .knc-ugc-item { position:relative; aspect-ratio:9/16; border-radius:8px; overflow:hidden; background:#0d2444; cursor:pointer; }
  .knc-ugc-item video { width:100%; height:100%; object-fit:cover; display:block; }
  .knc-ugc-play { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:56px; height:56px; border-radius:50%; background:rgba(255,255,255,.92); }
  .knc-ugc-play::after { content:""; position:absolute; top:50%; left:54%; transform:translate(-50%,-50%); border-style:solid; border-width:11px 0 11px 18px; border-color:transparent transparent transparent #111; }
  @media (max-width:768px){
    .knc-demo-grid { grid-template-columns:repeat(2,1fr); gap:10px; }
  }
  @media (max-width:560px){
    .knc-ugc-grid { grid-template-columns:1fr; max-width:320px; }
  }
</style>

<script>
(function(){
  var lazy = document.querySelectorAll('.knc-lazyvid');
  if ('IntersectionObserver' in window && lazy.length){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(en){
        var v = en.target;
        if (en.isIntersecting){
          if (!v.src){ v.src = v.dataset.src; }
          var p = v.play(); if (p && p.catch) p.catch(function(){});
        } else { v.pause(); }
      });
    }, { threshold:0.25 });
    lazy.forEach(function(v){ io.observe(v); });
  } else {
    lazy.forEach(function(v){ if(!v.src){ v.src = v.dataset.src; v.play&&v.play(); } });
  }
  document.querySelectorAll('.knc-ugc-item').forEach(function(item){
    item.addEventListener('click', function(){
      if (item.dataset.loaded) return;
      item.dataset.loaded = '1';
      var play = item.querySelector('.knc-ugc-play'); if (play) play.remove();
      var v = item.querySelector('.knc-ugc-video');
      if (!v){ v = document.createElement('video'); v.className = 'knc-ugc-video'; item.appendChild(v); }
      v.src = item.dataset.src; v.controls = true; v.autoplay = true; v.playsInline = true; v.preload = 'auto';
      var p = v.play(); if (p && p.catch) p.catch(function(){});
    });
  });
})();
</script>
