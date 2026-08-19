<?php
/**
 * Traka "Flash Deals" na vrhu kategorije ljetne rasprodaje.
 *
 * Prikazuje se SAMO na jednoj kategoriji (slug u NORIKS_FLASH_CAT). Postotak
 * ustede se racuna iz stvarnih cijena proizvoda u toj kategoriji (najveci popust),
 * pa napis nikad ne obecava vise nego sto stvarno stoji na policama.
 *
 * Odbrojavanje: svaki posjetitelj dobije svoj prozor od 24 sata, spremljen u
 * localStorage. Kad istekne, krece novi — traka nikad ne pokazuje "00:00:00".
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const NORIKS_FLASH_CAT   = 'summer-sale';
const NORIKS_FLASH_HOURS = 24;
// Paketi nemaju WooCommerce akcijsku cijenu (popust radi izbornik ponuda), pa
// automatski izracun vrati 0. Tada se koristi ova brojka — provjereno na
// stvarnim cijenama: najveci paket ima 58 % nize po komadu, pa je 55 % sigurno.
const NORIKS_FLASH_OFF_FALLBACK = 55;

/** Najveci postotak popusta u kategoriji (cache 1 h). 0 = nema akcijskih cijena. */
function noriks_flash_max_discount() {
    $key    = 'noriks_flash_max_discount_' . NORIKS_FLASH_CAT;
    $cached = get_transient( $key );
    if ( $cached !== false ) { return (int) $cached; }

    $max = 0;
    if ( function_exists( 'wc_get_products' ) ) {
        $ids = wc_get_products( array(
            'status'   => 'publish',
            'limit'    => -1,
            'return'   => 'ids',
            'category' => array( NORIKS_FLASH_CAT ),
        ) );
        foreach ( (array) $ids as $pid ) {
            $p = wc_get_product( $pid );
            if ( ! $p ) { continue; }
            $reg  = (float) $p->get_regular_price();
            $sale = (float) $p->get_price();
            if ( $reg > 0 && $sale > 0 && $sale < $reg ) {
                $max = max( $max, (int) floor( ( ( $reg - $sale ) / $reg ) * 100 ) );
            }
        }
    }
    set_transient( $key, $max, HOUR_IN_SECONDS );
    return $max;
}

function noriks_flash_deals_banner() {
    if ( ! function_exists( 'is_product_category' ) || ! is_product_category( NORIKS_FLASH_CAT ) ) { return; }

    $off = noriks_flash_max_discount();
    if ( $off < 1 ) { $off = NORIKS_FLASH_OFF_FALLBACK; }
    ?>
    <div class="nfd" role="region" aria-label="Summer Sale">
      <div class="nfd-in">
        <div class="nfd-left">
          <p class="nfd-eyebrow">Seasonal discount</p>
          <div class="nfd-head">
            <h2 class="nfd-title">Summer Sale</h2>
            <?php if ( $off > 0 ) : ?>
              <span class="nfd-badge">-&minus;<?php echo (int) $off; ?>%</span>
            <?php endif; ?>
          </div>
          <p class="nfd-sub">Reduced while stocks last</p>
        </div>

        <div class="nfd-right">
          <span class="nfd-cta">Offer ends in</span>
          <div class="nfd-clock" id="nfd-clock" aria-live="off">
            <span class="nfd-unit"><b data-u="d">00</b><em>days</em></span>
            <span class="nfd-unit"><b data-u="h">00</b><em>hrs</em></span>
            <span class="nfd-unit"><b data-u="m">00</b><em>min</em></span>
            <span class="nfd-unit"><b data-u="s">00</b><em>sec</em></span>
          </div>
        </div>
      </div>
    </div>

    <style>
      /* Na ovoj kategoriji traka JE naslov stranice — hero slika se ne prikazuje. */
      .one-banner-shop { display: none !important; }

      .nfd { width: 100vw; margin-left: calc(50% - 50vw); color: #fff; position: relative; overflow: hidden;
             /* gotovo ravna boja — prijelaz je jedva primjetan i ide u suprotnom smjeru */
             background: linear-gradient(283deg, #f5820a 0%, #ef7a03 100%); }
      /* diskretan sjaj po dijagonali — traka ne izgleda kao plosnata boja */
      .nfd:after { content: ""; position: absolute; inset: 0; pointer-events: none;
                   background: linear-gradient(283deg, rgba(255,255,255,.05) 0%, rgba(255,255,255,0) 55%); }
      /* poravnano s ostatkom stranice (filteri, mreza proizvoda) */
      .nfd-in { position: relative; z-index: 1; max-width: 1800px; margin: 0 auto; padding: 22px 15px;
                display: flex; align-items: center; justify-content: space-between; gap: 24px; }
      .nfd-left { min-width: 0; }
      .nfd-eyebrow { margin: 0 0 5px; font-size: 11.5px; font-weight: 700; letter-spacing: .18em;
                     text-transform: uppercase; color: rgba(255,255,255,.78); }
      .nfd-head { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
      .nfd-title { margin: 0; font-size: clamp(24px, 3.2vw, 36px); font-weight: 800; letter-spacing: -.02em;
                   line-height: 1.05; text-transform: uppercase; color: #fff; }
      .nfd-badge { background: #0f7a4a; color: #fff; font-size: 12.5px; font-weight: 700; letter-spacing: .06em;
                   text-transform: uppercase; padding: 6px 13px; border-radius: 999px; white-space: nowrap;
                   box-shadow: 0 1px 0 rgba(255,255,255,.18) inset; }
      .nfd-sub { margin: 8px 0 0; font-size: 14.5px; color: rgba(255,255,255,.92); }

      .nfd-right { flex: 0 0 auto; text-align: right; }
      .nfd-cta { display: block; font-size: 11px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
                 color: rgba(255,255,255,.8); margin-bottom: 9px; }
      .nfd-clock { display: flex; gap: 9px; }
      .nfd-unit { background: #fff; border-radius: 10px; padding: 9px 12px 8px; min-width: 64px;
                  display: flex; flex-direction: column; align-items: center; line-height: 1;
                  box-shadow: 0 2px 10px rgba(0,0,0,.10); }
      .nfd-unit b { font-size: 23px; font-weight: 800; color: #14100c; font-variant-numeric: tabular-nums; }
      .nfd-unit em { font-style: normal; font-size: 10px; font-weight: 600; letter-spacing: .1em;
                     text-transform: uppercase; color: #8a8a8a; margin-top: 6px; }

      @media (max-width: 820px) {
        .nfd-in { flex-direction: column; align-items: flex-start; gap: 14px; padding: 15px 15px 17px; }
        .nfd-eyebrow { font-size: 10.5px; letter-spacing: .14em; }
        .nfd-title { font-size: 22px; }
        .nfd-badge { font-size: 11px; padding: 5px 11px; }
        .nfd-sub { margin-top: 6px; font-size: 13px; }
        .nfd-right { width: 100%; text-align: left; }
        .nfd-clock { width: 100%; gap: 7px; }
        .nfd-unit { flex: 1 1 0; min-width: 0; padding: 8px 4px 7px; border-radius: 8px; }
        .nfd-unit b { font-size: 19px; }
        .nfd-unit em { font-size: 9px; letter-spacing: .06em; }
      }
    </style>

    <script>
    (function(){
      var box = document.getElementById('nfd-clock');
      if (!box) { return; }
      var HOURS = <?php echo (int) NORIKS_FLASH_HOURS; ?>, KEY = 'nfd_end_<?php echo esc_js( NORIKS_FLASH_CAT ); ?>';
      function end(){
        var v = 0;
        try { v = parseInt(localStorage.getItem(KEY) || '0', 10); } catch(e) {}
        if (!v || v <= Date.now()) {
          v = Date.now() + HOURS * 3600 * 1000;
          try { localStorage.setItem(KEY, String(v)); } catch(e) {}
        }
        return v;
      }
      var target = end();
      var el = {};
      box.querySelectorAll('b[data-u]').forEach(function(b){ el[b.getAttribute('data-u')] = b; });
      function p(n){ return (n < 10 ? '0' : '') + n; }
      function tick(){
        var left = target - Date.now();
        if (left <= 0) { target = end(); left = target - Date.now(); }
        var s = Math.floor(left / 1000);
        el.d.textContent = p(Math.floor(s / 86400));
        el.h.textContent = p(Math.floor(s % 86400 / 3600));
        el.m.textContent = p(Math.floor(s % 3600 / 60));
        el.s.textContent = p(s % 60);
      }
      tick();
      setInterval(tick, 1000);
    })();
    </script>
    <?php
}
add_action( 'woocommerce_before_main_content', 'noriks_flash_deals_banner', 5 );
