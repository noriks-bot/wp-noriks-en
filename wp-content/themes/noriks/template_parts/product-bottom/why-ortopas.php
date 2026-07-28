<?php
/**
 * product-bottom: ORTHOPEDIC BACK BELT (ortopas)
 *
 * Dedicated bottom-nicer for the NORIKS orthopedic back belt.
 * Shown via single-product-bottom-nicer.php when noriks_is_type('ortopas').
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------
 * MEDIA per section.
 * Image 1 lives in WP media; videos 2 and 3 are in the theme (git) and are
 * referenced relatively via get_template_directory_uri() — /img/ortopas-videos/.
 *
 * NOTE: the two static images below still point at the HR upload. The collage
 * carries no text so it works as-is; the "indications" graphic has Croatian
 * text baked in and should be swapped for an English version once available.
 * ------------------------------------------------------------------ */
$opz_vid_dir      = get_template_directory_uri() . '/img/ortopas-videos/';
$opz_img_collage  = 'https://noriks.com/hr/wp-content/uploads/2026/07/ortopas-hr-9.png'; // 1) happy customers (image)
$opz_video_relief = $opz_vid_dir . 'relief.mp4';                                          // 2) natural pain relief (video)
$opz_video_cause  = $opz_vid_dir . 'cause.mp4';                                           // 3) the real cause (video)
$opz_img_indik    = 'https://noriks.com/hr/wp-content/uploads/2026/07/noriks_static_indikacije_HR_1x1.png'; // 5) how it works (image — TODO: EN version)
$opz_video_feat   = $opz_vid_dir . 'features.mp4';                                        // 6) innovative features (video)

/* Cards (circular videos) — section 4 with 3 cards */
$opz_cards = array(
    array(
        'video' => $opz_vid_dir . 'card-1.mp4',
        'title' => 'Eases the discomfort',
        'text'  => 'Can bring fast relief from sciatica and back pain',
    ),
    array(
        'video' => $opz_vid_dir . 'card-2.mp4',
        'title' => 'Relieves the lumbar spine',
        'text'  => 'Stabilises and aligns the lower back',
    ),
    array(
        'video' => $opz_vid_dir . 'card-3.mp4',
        'title' => 'A proven method',
        'text'  => 'Based on targeted compression technology',
    ),
);

/* Comparison table — section 6. array( label, NORIKS(bool), Physio(bool) ) */
$opz_cmp_rows = array(
    array( 'Pain relief',                    true,  true  ),
    array( 'Long-lasting effect',            true,  false ),
    array( 'Affordable',                     true,  false ),
    array( 'Instant relaxation',             true,  false ),
    array( 'No waiting',                     true,  false ),
    array( '60-day money-back guarantee',    true,  false ),
    array( 'Long-term costs',                false, true  ),
);
/* Reviews with photo — section 8 */
$opz_reviews = array(
    array(
        'img'   => get_template_directory_uri() . '/img/ortopas-reviews/review-1.webp',
        'title' => 'A huge help against lower back pain',
        'text'  => 'The NORIKS belt has genuinely made my life easier. It does exactly what it promises. I can bend down again without pain.',
        'name'  => 'Elizabeth M.',
    ),
    array(
        'img'   => get_template_directory_uri() . '/img/ortopas-reviews/review-2.jpg',
        'title' => 'Soft and comfortable',
        'text'  => 'My physiotherapist recommended a belt for back pain. I had tried other belts before, but this one is far more comfortable for sitting and bending. And it still gives excellent support!',
        'name'  => 'Julia U.',
    ),
    array(
        'img'   => get_template_directory_uri() . '/img/ortopas-reviews/review-3.webp',
        'title' => 'Top!',
        'text'  => 'It helps me sit upright and I feel like I walk taller too. The pain has come down a lot and I can finally stand up without pain even after sitting for a long time. I wear the belt about 2-3 hours a day — mostly at work.',
        'name'  => 'John D.',
    ),
);

$opz_yes = '<svg class="opz-yes" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M5 12.5l4 4 10-10" fill="none" stroke="#22a45d" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$opz_no  = '<svg class="opz-no" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M7 7l10 10M17 7L7 17" fill="none" stroke="#dc3545" stroke-width="2.4" stroke-linecap="round"/></svg>';
?>

<!-- ============ 1) Over 14,000 happy customers ============ -->
<section class="opz-why opz-customers">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <img loading="lazy" decoding="async" src="<?php echo esc_url( $opz_img_collage ); ?>" alt="Happy customers of the NORIKS orthopedic belt" />
    </div>
    <div class="opz-col opz-copy">
      <div class="opz-stars" aria-hidden="true">★★★★★</div>
      <h2 class="opz-title">Over 14,000 happy customers</h2>
      <p class="opz-sub">Thousands of people have already swapped daily back pain for stability and relief — at work, in the car and at home.</p>
    </div>
  </div>
</section>

<!-- ============ 2) Natural pain relief ============ -->
<section class="opz-why">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <video src="<?php echo esc_url( $opz_video_relief ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Natural pain relief</h2>
      <p>When you put on the NORIKS belt, advanced technology with <strong>two compression zones</strong> works to align your hips and lower back properly. That can stabilise your spine and take pressure off the sciatic nerve.</p>
      <p>Normally you would need extensive physiotherapy to reach this kind of relief. The NORIKS belt lets you <strong>feel the relief in real time</strong> — while you work or move around with the people you love.</p>
      <p>As soon as your lower back and hips are properly supported, the pressure on the sciatic nerve can drop. That can mean <strong>less pain and more mobility</strong>.</p>
    </div>
  </div>
</section>

<!-- ============ 3) The real cause of back pain and sciatica ============ -->
<section class="opz-why opz-cause">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <video src="<?php echo esc_url( $opz_video_cause ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">The real cause of back pain and sciatica</h2>
      <p>Hours at a desk, repetitive movements or heavy physical work can create <strong>uneven pressure on the spinal discs</strong>. Combined with poor posture, that can cause significant damage to the spine over the years.</p>
      <p>As a result, discs can slip out of position and press on the sciatic nerve, leading to <strong>burning, shooting pain and even weakness</strong> spreading from the lower back down the legs.</p>
    </div>
  </div>
</section>

<!-- ============ 4) Natural relief (3 cards) ============ -->
<section class="opz-why opz-cards">
  <div class="opz-wrap">
    <h2 class="opz-cards-title">Natural relief from sciatica and back pain</h2>
    <div class="opz-cards-grid">
      <?php foreach ( $opz_cards as $opz_card ) : ?>
        <div class="opz-card">
          <div class="opz-card-media">
            <video src="<?php echo esc_url( $opz_card['video'] ); ?>" muted autoplay loop playsinline preload="metadata"></video>
          </div>
          <div class="opz-card-head">
            <span class="opz-check" aria-hidden="true">
              <svg viewBox="0 0 24 24" width="22" height="22"><circle cx="12" cy="12" r="12" fill="#28a745"/><path d="M7 12.5l3 3 7-7" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="opz-card-title"><?php echo esc_html( $opz_card['title'] ); ?></h3>
          </div>
          <p class="opz-card-text"><?php echo esc_html( $opz_card['text'] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 5) How does the NORIKS belt work? ============ -->
<section class="opz-why">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <img loading="lazy" decoding="async" src="<?php echo esc_url( $opz_img_indik ); ?>" alt="Indications — what the NORIKS orthopedic belt helps with" />
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">How does the NORIKS belt work?</h2>
      <p>The NORIKS belt <strong>stabilises the L5 area</strong> of the spine with <strong>targeted compression</strong>, aligns the pelvis correctly and returns the SI joint to its natural range of motion.</p>
      <p><strong>It supports the problem area</strong>, can relieve the spinal discs and thereby reduce pressure on the sciatic nerve.</p>
      <p>Targeted compression encourages circulation, which can support the body's own healing process.</p>
      <p>This combination can bring fast relief from sciatica, back pain and SI problems, as well as <strong>long-lasting pain relief</strong> with regular use.</p>
    </div>
  </div>
</section>

<!-- ============ 6) Innovative features ============ -->
<section class="opz-why">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <video src="<?php echo esc_url( $opz_video_feat ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Innovative features</h2>
      <p><strong>Slim and practical:</strong> Designed for everyday use and it sits comfortably under most clothes, so nobody notices you are wearing it!</p>
      <p><strong>Adjustable compression:</strong> Lets you tune the level of support to your needs and gives you maximum comfort.</p>
      <p>Access to physiotherapists and pain specialists is often limited, expensive and time-consuming. <strong>The NORIKS belt offers a professional-grade solution</strong> and an effective, affordable alternative.</p>
    </div>
  </div>
</section>

<!-- ============ 7) The NORIKS belt compared (table) ============ -->
<section class="opz-why opz-compare">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-copy">
      <h2 class="opz-title">The NORIKS belt compared</h2>
      <p class="opz-sub">It works specifically on the lower back to reduce the load.</p>
    </div>
    <div class="opz-col">
      <table class="opz-table">
        <thead>
          <tr>
            <th class="opz-th-feat"></th>
            <th class="opz-th-brand">NORIKS</th>
            <th class="opz-th-alt">Physio</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $opz_cmp_rows as $opz_r ) : ?>
            <tr>
              <th class="opz-feat"><?php echo esc_html( $opz_r[0] ); ?></th>
              <td class="opz-brand"><?php echo $opz_r[1] ? $opz_yes : $opz_no; ?></td>
              <td class="opz-alt"><?php echo $opz_r[2] ? $opz_yes : $opz_no; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ============ 8) Customer reviews (with photo) ============ -->
<section class="opz-why opz-reviews">
  <div class="opz-wrap">
    <div class="opz-reviews-grid">
      <?php foreach ( $opz_reviews as $opz_rev ) : ?>
        <div class="opz-review">
          <div class="opz-review-media">
            <img loading="lazy" decoding="async" src="<?php echo esc_url( $opz_rev['img'] ); ?>" alt="NORIKS belt — customer review by <?php echo esc_attr( $opz_rev['name'] ); ?>" />
          </div>
          <div class="opz-review-stars" aria-hidden="true">★★★★★</div>
          <h3 class="opz-review-title"><?php echo esc_html( $opz_rev['title'] ); ?></h3>
          <p class="opz-review-text"><?php echo esc_html( $opz_rev['text'] ); ?></p>
          <div class="opz-review-name"><?php echo esc_html( $opz_rev['name'] ); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
  /* No "Size chart" link on the belt (neither plugin nor global). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Short description of the belt: hide the standard bullets (•),
     only the ✅ from the text remains; a little spacing between
     "Benefits:" and the list.
     (This template only loads on orto-ortopas pages.) */
  .woocommerce-product-details__short-description ul {
      list-style: none;
      margin: 8px 0 26px;   /* more space below the list */
      padding-left: 0;
  }
  .woocommerce-product-details__short-description ul li {
      list-style: none;
      padding-left: 0;
      margin-left: 0;
  }
  /* space above "Benefits:" (the paragraph right before the list) */
  .woocommerce-product-details__short-description p:has(+ ul) {
      margin-top: 20px;
      margin-bottom: 4px;
  }

  .opz-why { padding: 44px 0; }
  .opz-why.opz-customers { background: #f7f7f7; }
  .opz-wrap { max-width: 1180px; margin: 0 auto; padding: 0 16px; }
  .opz-row { display: grid; grid-template-columns: 1fr 1fr; gap: 44px; align-items: center; }
  .opz-media img,
  .opz-media video { width: 100%; height: auto; border-radius: 12px; display: block; }
  .opz-stars { color: #f5a623; font-size: 24px; letter-spacing: 2px; margin-bottom: 10px; }
  .opz-title { font-size: clamp(26px,3.2vw,40px); font-weight: 800; color: #1c1c1c; line-height: 1.15; margin: 0 0 16px; }
  .opz-copy p { font-size: 16px; line-height: 1.7; color: #333; margin: 0 0 14px; }
  .opz-sub { font-size: 17px; line-height: 1.6; color: #333; margin: 0; }

  /* --- 4) card section (grey background / noriks style) --- */
  .opz-why.opz-cards { background: #f7f7f7; }
  .opz-cards-title { text-align: center; font-size: clamp(22px,2.6vw,30px); font-weight: 800; color: #1c1c1c; margin: 0 0 32px; line-height: 1.2; }
  .opz-cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
  .opz-card { background: #fff; border-radius: 14px; padding: 26px 22px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
  .opz-card-media { width: 108px; height: 108px; margin: 0 auto 18px; border-radius: 50%; overflow: hidden; }
  .opz-card-media video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .opz-card-head { display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0 0 10px; }
  .opz-check { flex: 0 0 auto; line-height: 0; }
  .opz-card-title { font-size: 18px; font-weight: 800; color: #1c1c1c; margin: 0; line-height: 1.2; }
  .opz-card-text { font-size: 14px; line-height: 1.55; color: #555; margin: 0; }

  /* --- comparison table (noriks green style) --- */
  .opz-why.opz-compare { background: #f7f7f7; }
  .opz-table { width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
  .opz-table th, .opz-table td { padding: 13px 14px; text-align: center; vertical-align: middle; }
  .opz-table thead th { background: #22a45d; color: #fff; font-size: 15px; font-weight: 800; }
  .opz-table thead .opz-th-feat { background: #22a45d; }
  .opz-table .opz-feat { background: #22a45d; color: #fff; font-weight: 700; text-align: left; font-size: 14px; line-height: 1.25; width: 55%; }
  .opz-table tbody tr td { border-bottom: 1px solid #eee; background: #fff; }
  .opz-table tbody tr:last-child td,
  .opz-table tbody tr:last-child .opz-feat { border-bottom: 0; }
  .opz-table .opz-brand { background: #f2fbf6; }
  .opz-yes, .opz-no { display: inline-block; vertical-align: middle; }

  /* --- 8) customer reviews (with photo) --- */
  .opz-reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
  .opz-review { background: #fafafa; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: center; }
  .opz-review-media { width: 100%; aspect-ratio: 1 / 1; background: #eee; }
  .opz-review-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .opz-review-stars { color: #f5b301; font-size: 20px; letter-spacing: 2px; margin: 16px 0 8px; }
  .opz-review-title { font-size: 17px; font-weight: 800; color: #1c1c1c; margin: 0 14px 10px; line-height: 1.25; }
  .opz-review-text { font-size: 14px; line-height: 1.6; color: #444; margin: 0 16px 14px; }
  .opz-review-name { font-size: 13px; font-style: italic; font-weight: 700; color: #333; border-top: 1px solid #e6e6e6; margin: 0 16px; padding: 12px 0 18px; }

  @media (max-width: 820px) {
    .opz-row { grid-template-columns: 1fr; gap: 22px; }
    .opz-title { text-align: left; }
    .opz-cards-grid { grid-template-columns: 1fr; gap: 16px; }
    .opz-reviews-grid { grid-template-columns: 1fr; gap: 18px; }
    .opz-table th, .opz-table td { padding: 11px 10px; }
    .opz-table .opz-feat { font-size: 13px; }
    .opz-table thead th { font-size: 14px; }
  }
</style>
