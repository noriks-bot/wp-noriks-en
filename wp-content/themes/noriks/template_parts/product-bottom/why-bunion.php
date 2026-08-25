<?php
/**
 * product-bottom: BUNION CORRECTOR (bunion / hallux valgus)
 *
 * Dedicated bottom-nicer for the NORIKS bunion corrector.
 * Shown via single-product-bottom-nicer.php when noriks_is_type('bunion').
 *
 * Media live in the theme (git), referenced relatively via get_template_directory_uri():
 *   img/bunion-videos/section-1.mp4, section-2.mp4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$bun_vid_dir = get_template_directory_uri() . '/img/bunion-videos/';
$bun_video_1 = $bun_vid_dir . 'section-1.mp4'; // 1) One foot away
$bun_video_2 = $bun_vid_dir . 'funkcionira.mp4'; // 2) How it works

$bun_img_features = get_template_directory_uri() . '/img/bunion/why.png';

// Real results — percentages
$bun_results = array(
    array( 'pct' => 91, 'text' => 'of users reported less bunion pain from the 2nd session onwards' ),
    array( 'pct' => 90, 'text' => 'of users completely removed their bunion pain after only 14 days of consistent use (30 min/day)' ),
    array( 'pct' => 88, 'text' => 'of users saw visible improvements in toe alignment after only 30 days of consistent use (30 min/day)' ),
);

// Why choose us — comparison (same style as knc-table on the zip socks)
$bun_cmp = array(
    '30-day money-back guarantee',
    'Eases the discomfort',
    'Prevents the bunion from growing',
    'Improves the bunion over time',
    'Mobile design — you can walk in it',
    'Durable and long-lasting',
);

// How to use — 3 steps (video + description)
$bun_steps = array(
    array( 'video' => $bun_vid_dir . 'step-1.mp4', 'caption' => 'Fasten the NORIKS corrector to your big toe and foot' ),
    array( 'video' => $bun_vid_dir . 'step-2.mp4', 'caption' => 'Adjust the stretch intensity to your liking' ),
    array( 'video' => $bun_vid_dir . 'step-3.mp4', 'caption' => 'Relax and let the NORIKS corrector do its job' ),
);
?>

<!-- ============ 1) You are only one step away… ============ -->
<section class="bun-why bun-intro">
  <div class="bun-wrap bun-row">
    <div class="bun-col bun-media">
      <video src="<?php echo esc_url( $bun_video_1 ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="bun-col bun-copy">
      <h2 class="bun-title">You are only one step away from <span class="bun-hl">relief from bunion discomfort</span>, swollen toes and foot pain…</h2>
      <p>If you are reading this, there is a good chance you suffer from persistent <strong class="bun-red">bunion discomfort</strong>.</p>
      <p>The result? Pain and discomfort that get in the way of your daily life.</p>
      <p>Left untreated, they can get worse. Toes cross over, and hammer toes and bone growths can develop.</p>
      <p>Bunions are a <strong class="bun-red">progressive condition</strong> and they will not go away on their own.</p>
      <p>Over time this can lead to more serious problems such as <u>invasive surgery, hip, knee and lower back problems, and even immobility</u>.</p>
      <p>Using the benefits of clinically proven advanced alignment therapy and a patented hinge mechanism, the <strong>NORIKS bunion corrector</strong> effectively eases the discomfort in the affected part of the foot and restores your foot's health with just 30 minutes of use a day.</p>
      <p class="bun-stat"><span class="bun-check" aria-hidden="true">✔</span> <em>91% of users reported <strong>less foot pain</strong> from the very first week</em></p>
    </div>
  </div>
</section>

<!-- ============ 2) How does it work? ============ -->
<section class="bun-why">
  <div class="bun-wrap bun-row bun-reverse">
    <div class="bun-col bun-media">
      <video src="<?php echo esc_url( $bun_video_2 ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="bun-col bun-copy">
      <h2 class="bun-title">How does it work?</h2>
      <p>The <strong>NORIKS bunion corrector</strong> applies advanced alignment therapy. It is designed to <strong class="bun-red">support the realignment</strong> of the big toe and gradually ease inflammation using a strong patented hinge mechanism.</p>
      <p>It helps release muscle tension by gently returning the big toe to its natural position, which over time leads to painless natural alignment of the toe joint.</p>
      <p>This releases tension built up over years, the bulge is corrected and reduced, the pain eases and further growth is prevented — so you can get back on your feet, upright and confident.</p>
      <p>Some users may need a session or two to get used to it, because <strong class="bun-red">the sensation can be stronger</strong> than with other methods.</p>
      <p>It is a natural, non-invasive way to restore the natural position of the toe and foot and to correct the damage caused by unsuitable footwear or genetics.</p>
      <p>Whether it is a small child's foot or a large adult foot, <u>the corrector is built to fit all foot sizes comfortably</u>.</p>
      <p class="bun-stat"><span class="bun-check" aria-hidden="true">✔</span> <em>87% of users reported <strong>visible improvements</strong> within the first month</em></p>
    </div>
  </div>
</section>

<!-- ============ 3) How to use it (grey, 3 steps) ============ -->
<section class="bun-why bun-howto">
  <div class="bun-wrap">
    <h2 class="bun-howto-title">How to use it</h2>
    <div class="bun-howto-intro">
      <p>We recommend starting with 30 minutes a day and gradually building up to a session of 1 to 3 hours.</p>
      <p>Once it feels comfortable, you can start wearing it through the night as well.</p>
      <p>It is best while resting — lying on the sofa, watching TV, reading or sleeping.</p>
      <p>But unlike other products on the market, you can also move around without the NORIKS corrector restricting you, thanks to its mobile design.</p>
    </div>
    <div class="bun-steps-grid">
      <?php $bun_n = 0; foreach ( $bun_steps as $bun_step ) : $bun_n++; ?>
        <div class="bun-step">
          <div class="bun-step-media">
            <video src="<?php echo esc_url( $bun_step['video'] ); ?>" muted autoplay loop playsinline preload="metadata"></video>
          </div>
          <div class="bun-step-num"><?php echo (int) $bun_n; ?></div>
          <p class="bun-step-caption"><?php echo esc_html( $bun_step['caption'] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 4) 8 reasons you will love it ============ -->
<section class="bun-why">
  <div class="bun-wrap bun-row">
    <div class="bun-col bun-copy">
      <h2 class="bun-title">8 reasons you will love it</h2>
      <ul class="bun-reasons">
        <li><strong>Relief from discomfort</strong> while walking, exercising, standing and sleeping</li>
        <li><strong>Prevents</strong> the bunion from growing further</li>
        <li>A <strong>non-surgical option</strong> for relief</li>
        <li>Firm joint alignment that <strong>genuinely improves your condition</strong></li>
        <li><strong>Adjustable</strong> stretch intensity</li>
        <li>Designed and recommended by <strong>medical professionals</strong></li>
        <li><strong>Easy to use</strong> and portable</li>
        <li><strong>30-day money-back guarantee</strong> ("results or a full refund") because we are that confident in our product and know it will help you</li>
      </ul>
    </div>
    <div class="bun-col bun-media">
      <img loading="lazy" decoding="async" src="<?php echo esc_url( $bun_img_features ); ?>" alt="Why the NORIKS bunion corrector is different" />
    </div>
  </div>
</section>

<!-- ============ 5) Real results, real people ============ -->
<section class="bun-why bun-results-sec">
  <div class="bun-wrap bun-row">
    <div class="bun-col bun-copy">
      <h2 class="bun-title">Real <span class="bun-hl">results</span>, real people</h2>
      <p>We ran a consumer test in which we sent the NORIKS bunion corrector to more than <strong>37 podiatry practices</strong>. In total <strong>432 patients</strong> with bunions tested it. Here are the results.</p>
    </div>
    <div class="bun-col">
      <div class="bun-results">
        <?php foreach ( $bun_results as $bun_r ) : $bun_dash = round( $bun_r['pct'] * 1.6336, 1 ); ?>
          <div class="bun-result">
            <svg class="bun-ring" viewBox="0 0 60 60" aria-hidden="true">
              <circle cx="30" cy="30" r="26" fill="none" stroke="#dfe6ee" stroke-width="5"/>
              <circle cx="30" cy="30" r="26" fill="none" stroke="#1a86d0" stroke-width="5" stroke-linecap="round"
                      stroke-dasharray="<?php echo esc_attr( $bun_dash ); ?> 163.4" transform="rotate(-90 30 30)"/>
              <text x="30" y="34" text-anchor="middle" class="bun-ring-txt"><?php echo (int) $bun_r['pct']; ?>%</text>
            </svg>
            <p class="bun-result-text"><?php echo esc_html( $bun_r['text'] ); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ 6) Why choose us? (comparison table, knc style) ============ -->
<section class="bun-cmp-section">
  <div class="bun-cmp-wrap">
    <h2 class="bun-cmp-title">Why choose us?</h2>
    <p class="bun-cmp-lead">Don't fall for <span class="bun-hl">CHEAP imitations</span></p>
    <p class="bun-cmp-sub">How the <strong>NORIKS bunion corrector</strong> compares to the rest:</p>
    <div class="bun-cmp-scroll">
      <table class="bun-cmp-table">
        <thead>
          <tr>
            <th></th>
            <th class="bun-us">NORIKS</th>
            <th class="bun-comp">Other correctors</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $bun_cmp as $bun_row ) : ?>
            <tr>
              <td><?php echo esc_html( $bun_row ); ?></td>
              <td class="us ok">✓</td>
              <td class="no">✕</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<style>
  /* No "Size chart" link on the bunion corrector (neither plugin nor global). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Short description: hide the standard bullets (•), only the ✅ remains;
     space above "Benefits:" and more room below the list.
     (This template only loads on orto-bunion pages.) */
  .woocommerce-product-details__short-description ul {
      list-style: none;
      margin: 8px 0 26px;
      padding-left: 0;
  }
  .woocommerce-product-details__short-description ul li {
      list-style: none;
      padding-left: 0;
      margin-left: 0;
  }
  .woocommerce-product-details__short-description p:has(+ ul) {
      margin-top: 20px;
      margin-bottom: 4px;
  }

  .bun-why { padding: 44px 0; }
  .bun-why.bun-intro { background: #fbf9f4; }
  .bun-wrap { max-width: 1180px; margin: 0 auto; padding: 0 16px; }
  .bun-row { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
  .bun-media video { width: 100%; height: auto; border-radius: 12px; display: block; }
  .bun-title { font-size: clamp(24px,2.9vw,34px); font-weight: 800; color: #1c1c1c; line-height: 1.2; margin: 0 0 18px; }
  .bun-hl { color: #1a86d0; }
  .bun-red { color: #e0563f; }
  .bun-copy p { font-size: 16px; line-height: 1.7; color: #333; margin: 0 0 12px; }
  .bun-stat { display: flex; align-items: flex-start; gap: 8px; margin-top: 6px !important; }
  .bun-check { color: #1a86d0; font-weight: 800; }
  .bun-stat em { font-style: italic; color: #333; }

  /* section 2: media on the right */
  .bun-reverse .bun-media { order: 2; }
  .bun-reverse .bun-copy { order: 1; }

  /* 3) How to use it (grey background) */
  .bun-why.bun-howto { background: #f0f2f5; }
  .bun-howto-title { text-align: center; font-size: clamp(24px,2.9vw,34px); font-weight: 800; color: #1c1c1c; margin: 0 0 18px; }
  .bun-howto-intro { max-width: 820px; margin: 0 auto 34px; text-align: center; }
  .bun-howto-intro p { font-size: 16px; line-height: 1.6; color: #333; margin: 0 0 12px; }
  .bun-steps-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
  .bun-step { text-align: center; }
  .bun-step-media { width: 100%; aspect-ratio: 1 / 1; border-radius: 14px; overflow: hidden; background: #e6e9ee; }
  .bun-step-media video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .bun-step-num { font-size: 22px; font-weight: 800; color: #1c1c1c; margin: 14px 0 6px; }
  .bun-step-caption { font-size: 15px; line-height: 1.5; color: #333; margin: 0 8px; }

  /* 4) 8 reasons */
  .bun-media img { width: 100%; height: auto; border-radius: 12px; display: block; }
  .bun-reasons { list-style: none; margin: 0; padding: 0; }
  .bun-reasons li { position: relative; padding: 0 0 16px 34px; font-size: 15.5px; line-height: 1.5; color: #333; }
  .bun-reasons li:before {
      content: ""; position: absolute; left: 0; top: 1px; width: 22px; height: 22px; border-radius: 50%;
      background: #1a86d0 url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><path d='M6 12.5l4 4 8-8' fill='none' stroke='white' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round'/></svg>") center/15px no-repeat;
  }

  /* 5) Real results */
  .bun-results { display: flex; flex-direction: column; gap: 18px; }
  .bun-result { display: flex; align-items: center; gap: 16px; border-bottom: 1px solid #e6e6e6; padding-bottom: 16px; }
  .bun-result:last-child { border-bottom: 0; padding-bottom: 0; }
  .bun-ring { width: 70px; height: 70px; flex: 0 0 70px; }
  .bun-ring-txt { font-size: 16px; font-weight: 800; fill: #1a86d0; }
  .bun-result-text { font-size: 14.5px; line-height: 1.5; color: #333; margin: 0; }

  /* 6) Why choose us — comparison table (same style as knc-table) */
  .bun-cmp-section { background:#fff; padding:44px 0; }
  .bun-cmp-wrap { max-width:940px; margin:0 auto; padding:0 16px; }
  .bun-cmp-title { text-align:center; font-size:clamp(24px,3vw,34px); font-weight:800; color:#111; margin:0 0 8px; }
  .bun-cmp-lead { text-align:center; font-size:18px; font-weight:800; color:#111; margin:0 0 6px; }
  .bun-cmp-sub { text-align:center; font-size:14px; color:#444; margin:0 0 24px; }
  .bun-cmp-scroll { border-radius:16px; overflow:hidden; box-shadow:0 12px 34px rgba(18,48,90,.12); border:1px solid #edf0f4; }
  .bun-cmp-table { width:100%; border-collapse:collapse; table-layout:fixed; margin:0 !important; }
  .bun-cmp-table th, .bun-cmp-table td { padding:15px 12px; text-align:center; font-size:15px; }
  .bun-cmp-table thead th { color:#fff; font-weight:700; vertical-align:middle; font-size:14px; }
  .bun-cmp-table thead th:first-child { width:52%; background:#fff; }
  .bun-cmp-table .bun-comp { background:#767676; }
  .bun-cmp-table .bun-us { background:#111; }
  .bun-cmp-table tbody td:first-child { text-align:left; font-weight:600; color:#111; font-size:14px; line-height:1.3; padding-left:18px; }
  .bun-cmp-table tbody tr { border-bottom:1px solid #eef0f4; }
  .bun-cmp-table tbody tr:nth-child(even) { background:#fafbfc; }
  .bun-cmp-table td.ok { color:#1a9e5f; font-size:19px; font-weight:700; }
  .bun-cmp-table td.no { color:#d64545; font-size:18px; font-weight:700; }
  .bun-cmp-table td.us { background:#f3f3f3 !important; }
  .bun-cmp-table td.us.ok { color:#1a9e5f; }
  @media (max-width:600px) {
    .bun-cmp-table th, .bun-cmp-table td { padding:12px 6px; font-size:13px; }
    .bun-cmp-table thead th { font-size:12px; }
    .bun-cmp-table tbody td:first-child { font-size:12px; padding-left:10px; }
  }

  @media (max-width: 820px) {
    .bun-row { grid-template-columns: 1fr; gap: 22px; }
    .bun-reverse .bun-media { order: 0; }
    .bun-reverse .bun-copy { order: 0; }
    .bun-steps-grid { grid-template-columns: 1fr; gap: 18px; }
  }
</style>
