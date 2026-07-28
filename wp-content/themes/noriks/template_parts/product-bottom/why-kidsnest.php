<?php
/**
 * product-bottom: NORIKS KidsNest — kids pillow for healthy breathing (orto-kidsnest).
 * Section copy mirrors tryneedo.com/products/kids-pillow, English copy (medical claims softened).
 * Order:
 *   1. Trust marquee (blue)  2. "Start tonight..." (image L / text R, blue heading)
 *   3. "Proper head and neck support" (text L / image R)  4. Stats 94/60/98 (light blue, 3 ring cards)
 *   5. "#1 kids pillow 2026" + stars + sliding photo strip
 * Blue: #2b3fb0, light: #eef1fb, navy: #1b2450. Images: img/kidsnest/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$kn = get_template_directory_uri() . '/img/kidsnest/';
?>

<!-- ============ 1) Trust marquee (blue bar, scrolling) ============ -->
<div class="kn-marquee" aria-hidden="true">
  <div class="kn-marquee-track">
    <?php $kn_ticker = array('PAEDIATRICIAN RECOMMENDED','OEKO-TEX® MEMORY FOAM','3-ZONE STRUCTURE','90-NIGHT TRIAL','HYPOALLERGENIC','WASHABLE COVER');
    for ( $r = 0; $r < 2; $r++ ) { foreach ( $kn_ticker as $t ) { echo '<span class="kn-tick">'.esc_html($t).'</span><span class="kn-dot">•</span>'; } } ?>
  </div>
</div>

<!-- ============ 2) Start tonight — image LEFT, text RIGHT ============ -->
<section class="kn-sec">
  <div class="kn-wrap kn-row2">
    <div class="kn-media"><img src="<?php echo esc_url( $kn.'01-poravnan.webp' ); ?>" alt="Perfectly aligned — head, neck and spine during sleep" loading="lazy" onerror="this.style.display='none'"></div>
    <div class="kn-copy">
      <p class="kn-eyebrow">Developed with airway dentists for children's breathing</p>
      <h2 class="kn-h2 kn-h2-blue">Start correcting the hidden damage tonight.</h2>
      <p>Paediatric airway dentists keep warning parents about the same quiet problem: children who snore and breathe through their mouth are not simply "sleeping worse". Their jaw, palate and facial structure can slowly develop in the wrong direction.</p>
      <p><strong>And the window to correct it does not stay open forever.</strong></p>
      <p>The NORIKS <strong>KidsNest pillow</strong> is designed to <strong>support the head, jaw and airway in the right position during sleep</strong> — encouraging nasal breathing and healthier facial development while it still matters.</p>
      <p><strong>This is not just a pillow.<br>It is nightly airway support during the years that shape your child's face.</strong></p>
    </div>
  </div>
</section>

<!-- ============ 3) Proper support — text LEFT, image RIGHT ============ -->
<section class="kn-sec">
  <div class="kn-wrap kn-row2">
    <div class="kn-copy">
      <h2 class="kn-h2 kn-h2-blue">Proper head and neck support is key to healthy sleep.</h2>
      <p>An ergonomic kids pillow keeps the <strong>head and neck in natural alignment and helps prevent the head from tilting</strong> through the night. That keeps the spine properly aligned — even if your child moves around a lot in their sleep.</p>
      <p><strong>The result is calmer sleep and better recovery.</strong></p>
    </div>
    <div class="kn-media"><img src="<?php echo esc_url( $kn.'02-san.jpg' ); ?>" alt="Child sleeping peacefully on the KidsNest pillow" loading="lazy" onerror="this.style.display='none'"></div>
  </div>
</section>

<!-- ============ 4) Stats — light blue, 3 ring cards ============ -->
<section class="kn-sec kn-stats-sec">
  <div class="kn-wrap">
    <h2 class="kn-h2 kn-h2-blue kn-center">Built to protect your child's developing face</h2>
    <p class="kn-sub kn-center"><strong>Mouth breathing in childhood can reshape a growing face. KidsNest keeps your child's head aligned so they breathe through the nose.</strong></p>
    <div class="kn-stats">
      <?php
      $kn_stats = array(
        array('94','165.3','of parents notice their child sleeping with a <strong>closed mouth</strong> within 2 weeks'),
        array('60','105.5','of your <strong>child\'s</strong> facial development is shaped by age 6 — that window does not reopen'),
        array('98','172.3','of parents would recommend <strong>KidsNest</strong> to protect another child\'s smile'),
      );
      foreach ( $kn_stats as $st ) : ?>
      <div class="kn-stat-card">
        <svg class="kn-ring" viewBox="0 0 64 64" aria-hidden="true">
          <circle cx="32" cy="32" r="28" fill="none" stroke="#dfe5f5" stroke-width="5"/>
          <circle cx="32" cy="32" r="28" fill="none" stroke="#2b3fb0" stroke-width="5" stroke-linecap="round" stroke-dasharray="<?php echo esc_attr($st[1]); ?> 175.9" transform="rotate(-90 32 32)"/>
          <text x="32" y="38" text-anchor="middle" class="kn-ring-t"><?php echo esc_html($st[0]); ?>%</text>
        </svg>
        <p><?php echo wp_kses_post($st[2]); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 5) #1 kids pillow + stars + sliding photo strip ============ -->
<section class="kn-sec kn-rated-sec">
  <div class="kn-wrap">
    <h2 class="kn-h2 kn-h2-blue kn-center">Rated the #1 kids sleep pillow of 2026.</h2>
    <p class="kn-sub kn-center">Support their sleep — support the growing years.</p>
    <p class="kn-stars kn-center"><span aria-hidden="true">★★★★★</span> Rated 4.8/5 based on 140+ reviews</p>
  </div>
  <div class="kn-strip">
    <div class="kn-strip-track">
      <?php for ( $r = 0; $r < 2; $r++ ) : for ( $i = 1; $i <= 5; $i++ ) : ?>
        <img src="<?php echo esc_url( $kn.'traka/t'.$i.'.webp' ); ?>" alt="NORIKS KidsNest — children and parents" loading="lazy" onerror="this.style.display='none'">
      <?php endfor; endfor; ?>
    </div>
  </div>
</section>

<!-- ============ 6) Material quality — image LEFT, text RIGHT ============ -->
<section class="kn-sec">
  <div class="kn-wrap kn-row2">
    <div class="kn-media"><img src="<?php echo esc_url( $kn.'03-detalj.webp' ); ?>" alt="KidsNest — 3-zone structure and breathable fabric close up" loading="lazy" onerror="this.style.display='none'"></div>
    <div class="kn-copy">
      <h2 class="kn-h2 kn-h2-blue">Quality you can feel — night after night.</h2>
      <p>The dense, breathable knit and carefully shaped surface are not there for looks — <strong>every zone has a job</strong>. The centre cradles the head gently, the edges support the neck, and the structure holds its shape even after months of daily use.</p>
      <p>The cover comes off and goes in the washing machine, and the foam is <strong>hypoallergenic and dust-mite resistant</strong> — so the pillow stays fresh, clean and ready for every night. No dents, no flattening, no compromises.</p>
      <p><strong>A pillow that still looks — and supports — like day one, a year later.</strong></p>
    </div>
  </div>
</section>

<style>
  .kn-wrap { max-width: 1440px; margin: 0 auto; padding: 0 22px; } /* same container as the .product block above */
  .kn-sec { padding: 60px 0; }
  .kn-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
  .kn-h2 { font-size: clamp(26px,3.2vw,40px); font-weight: 800; color: #1b2450; line-height: 1.14; margin: 0 0 16px; }
  .kn-h2-blue { color: #2b3fb0; }
  .kn-center { text-align: center; }
  .kn-eyebrow { font-size: 13px; font-weight: 800; letter-spacing: .02em; color: #1b2450; margin: 0 0 6px; }
  .kn-copy p { font-size: 15.5px; line-height: 1.65; color: #33394f; margin: 0 0 14px; }
  .kn-sub { font-size: 16px; line-height: 1.55; color: #33394f; max-width: 680px; margin: 0 auto 10px; }
  .kn-media img { width: 100%; height: auto; display: block; border-radius: 18px; box-shadow: 0 14px 40px rgba(27,36,80,.10); }

  /* 1) marquee */
  .kn-marquee { background: #2b3fb0; overflow: hidden; white-space: nowrap; margin-top: 26px; }
  @media (min-width: 861px) { .kn-marquee { margin-top: -20px; } } /* desktop: halved gap to the content above */
  .kn-marquee + .kn-sec { padding-top: 26px; }
  .kn-marquee-track { display: inline-block; padding: 13px 0; animation: knScroll 28s linear infinite; }
  .kn-tick { color: #fff; font-weight: 800; font-style: italic; font-size: 15px; letter-spacing: .06em; text-transform: uppercase; }
  .kn-dot { color: #aebafe; margin: 0 22px; font-weight: 800; }
  @keyframes knScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

  /* 4) stats */
  .kn-stats-sec { background: #eef1fb; }
  .kn-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; max-width: 1180px; margin: 30px auto 0; }
  .kn-stat-card { background: #fff; border-radius: 16px; padding: 34px 26px; text-align: center; box-shadow: 0 10px 28px rgba(27,36,80,.07); }
  .kn-ring { width: 150px; height: 150px; margin: 0 auto 18px; display: block; }
  .kn-ring-t { font-size: 15px; font-weight: 800; fill: #2b3fb0; }
  .kn-stat-card p { font-size: 15px; line-height: 1.5; color: #33394f; margin: 0; }
  .kn-stat-card p strong { color: #2b3fb0; }

  /* 5) rated + strip */
  .kn-rated-sec { background: #eef1fb; padding-bottom: 0; }
  .kn-stars { font-size: 16px; color: #1b2450; font-weight: 600; margin: 6px 0 26px; }
  .kn-stars span { color: #f5a623; letter-spacing: 2px; margin-right: 8px; }
  .kn-strip { overflow: hidden; width: 100vw; margin-left: calc(50% - 50vw); padding-bottom: 34px; }
  .kn-strip-track { display: flex; gap: 8px; width: max-content; animation: knScroll 60s linear infinite; }
  .kn-strip:hover .kn-strip-track { animation-play-state: paused; }
  .kn-strip-track img { width: 350px; aspect-ratio: 1/1; object-fit: cover; border-radius: 10px; display: block; flex: 0 0 auto; }

  @media (max-width: 860px) {
    .kn-sec { padding: 30px 0; }
    .kn-row2 { grid-template-columns: 1fr; gap: 18px; }
    .kn-row2 .kn-media { order: -1; }
    .kn-h2 { font-size: 2rem; }
    .kn-stats { grid-template-columns: 1fr; gap: 14px; margin-top: 18px; }
    .kn-ring { width: 120px; height: 120px; }
    .kn-strip-track img { width: 240px; }
  }
</style>
