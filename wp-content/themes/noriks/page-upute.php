<?php
/**
 * Template Name: User manuals
 *
 * Popis proizvoda s PDF uputama u dva stupca, sa slikom proizvoda i pretragom.
 */
get_header();
$dir_url  = get_template_directory_uri() . '/manuals/';
$dir_path = get_template_directory() . '/manuals/';
?>

<div class="nmn">
  <div class="nmn-wrap">
    <h1 class="nmn-title"><?php echo esc_html( get_the_title() ); ?></h1>
    <p class="nmn-sub">Download the manual for your NORIKS product as a PDF.</p>

    <div class="nmn-search">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      <input type="search" id="nmn-q" placeholder="Search by product name…" autocomplete="off">
    </div>

    <div class="nmn-grid" id="nmn-grid">
      <?php foreach ( noriks_manuals_list() as $m ) :
          if ( ! file_exists( $dir_path . $m['file'] ) ) { continue; }
          $size = size_format( filesize( $dir_path . $m['file'] ) );
          $p    = noriks_manual_product( $m['sku'] );
          $key  = mb_strtolower( $m['title'] . ' ' . $m['sub'] );
          ?>
        <div class="nmn-card" data-key="<?php echo esc_attr( $key ); ?>">
          <div class="nmn-thumb">
            <?php if ( $p['img'] ) : ?>
              <img src="<?php echo esc_url( $p['img'] ); ?>" alt="<?php echo esc_attr( $m['title'] ); ?>" loading="lazy">
            <?php else : ?>
              <span class="nmn-ic" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
              </span>
            <?php endif; ?>
          </div>

          <div class="nmn-body">
            <h2><?php echo esc_html( $m['title'] ); ?></h2>
            <p class="nmn-kind"><?php echo esc_html( $m['sub'] ); ?></p>
            <p class="nmn-links">
              <a class="nmn-btn" href="<?php echo esc_url( $dir_url . $m['file'] ); ?>" target="_blank" rel="noopener">
                Download PDF <span>(<?php echo esc_html( $size ); ?>)</span>
              </a>
              <?php if ( $p['url'] ) : ?>
                <a class="nmn-link" href="<?php echo esc_url( $p['url'] ); ?>">Product page</a>
              <?php endif; ?>
            </p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p class="nmn-empty" id="nmn-empty" hidden>No manual for that term. Write to us at <a href="mailto:info@noriks.com">info@noriks.com</a>.</p>
    <p class="nmn-help">Need help with a product? Write to us at <a href="mailto:info@noriks.com">info@noriks.com</a>.</p>
  </div>
</div>

<style>
  .nmn { background: #f5f7f9; padding: 34px 0 54px; }
  .nmn-wrap { max-width: 1000px; margin: 0 auto; padding: 0 18px; }
  .nmn-title { font-size: 30px; line-height: 1.2; margin: 0 0 6px; }
  .nmn-sub { color: #55606e; margin: 0 0 18px; }
  .nmn-search { position: relative; display: flex; align-items: center; gap: 9px; background: #fff;
                border: 1px solid #e3e7ec; border-radius: 9px; padding: 0 13px; margin: 0 0 18px; max-width: 420px; }
  .nmn-search svg { color: #8a94a2; flex: 0 0 auto; }
  .nmn-search input { border: 0; outline: 0; padding: 12px 0; width: 100%; font-size: 15px; background: transparent; }
  .nmn-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
  .nmn-card { display: flex; gap: 14px; height: 100%; background: #fff; border-radius: 9px; padding: 18px;
              box-shadow: 0 1px 2px rgba(0,0,0,.06); box-sizing: border-box; }
  .nmn-thumb { flex: 0 0 auto; width: 84px; height: 84px; border-radius: 8px; overflow: hidden; background: #f1f4f8;
               display: flex; align-items: center; justify-content: center; color: #8a94a2; }
  .nmn-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .nmn-body { min-width: 0; display: flex; flex-direction: column; flex: 1 1 auto; }
  .nmn-card h2 { font-size: 17px; margin: 0 0 3px; line-height: 1.25; }
  .nmn-kind { margin: 0 0 8px; color: #55606e; font-size: 13.5px; }
  .nmn-links { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin: auto 0 0; }
  .nmn-btn { display: inline-block; background: #0d1b2a; color: #fff !important; text-decoration: none;
             padding: 9px 15px; border-radius: 7px; font-size: 14px; font-weight: 600; }
  .nmn-btn span { font-weight: 400; opacity: .7; }
  .nmn-link { font-size: 13.5px; color: #0d1b2a; text-decoration: underline; }
  .nmn-empty, .nmn-help { color: #55606e; font-size: 14px; margin: 18px 0 0; }
  @media (max-width: 760px) {
    .nmn-grid { grid-template-columns: 1fr; }
    .nmn-title { font-size: 24px; }
    .nmn-thumb { width: 64px; height: 64px; }
  }
</style>

<script>
(function () {
  var q = document.getElementById('nmn-q'), grid = document.getElementById('nmn-grid'),
      empty = document.getElementById('nmn-empty');
  if (!q || !grid) return;
  var cards = Array.prototype.slice.call(grid.querySelectorAll('.nmn-card'));
  function norm(s) { return (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''); }
  q.addEventListener('input', function () {
    var t = norm(q.value.trim()), shown = 0;
    cards.forEach(function (c) {
      var hit = !t || norm(c.getAttribute('data-key')).indexOf(t) !== -1;
      c.style.display = hit ? '' : 'none';
      if (hit) shown++;
    });
    empty.hidden = shown > 0;
  });
})();
</script>

<?php get_footer(); ?>
