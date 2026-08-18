<?php
/**
 * Prebacivanje paketa na stranicama paketa (majice, bokserice, …).
 *
 *   - red "Odaberi paket": sve velicine koje grupa ima (3/6/9/12/15 …), s cijenom po komadu
 *   - red "Boja": svi paketi ISTE velicine (druge kombinacije boja), trenutni oznacen
 *
 * PRENOSIVOST NA DRUGA TRZISTA — nista se ne cita iz naslova proizvoda ni iz
 * tvrdo upisanih slugova. Velicina i grupa se izvode iz STRUKTURE kategorija:
 *
 *   product_cat s roditeljem  +  broj u slugu   =>  "velicinska" kategorija
 *   broj iz sluga                               =>  broj komada u paketu
 *   ID roditeljske kategorije                   =>  grupa (majice / bokserice / …)
 *
 * Tako radi na svim jezicima bez ijedne izmjene:
 *   EN  pack-6-pcs      (parent: t-shirts)
 *   SI  6-paket-majic    (roditelj: majice)
 *   PL  pakiet-6-szt     (roditelj: koszulki)
 *
 * Ista kombinacija boja kroz velicine prepoznaje se po SKU-u (SKU je isti na svim
 * trzistima), npr. NORIKS-ALL-BLACK-6-PACK i NORIKS-ALL-BLACK-9-PACK => ALL-BLACK.
 * Ako te kombinacije nema u ciljanoj velicini, vodi na prvi paket te velicine.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const NORIKS_PACK_TRANSIENT = 'noriks_pack_index_v2';
const NORIKS_PACK_MIN_SIZE  = 1;    // ukljucuje i "1 komad" — i s jedne majice se nudi 3/6/9/12…
const NORIKS_PACK_MAX_SIZE  = 20;   // iznad toga to nije paket (npr. "komplet-25")

/**
 * Broj komada iz sluga kategorije — samo ako slug ima TOCNO JEDAN broj u razumnom
 * rasponu. Tako otpadaju kombinirani setovi ("komplet-5-5", "komplet-4-10",
 * "komplet-25") koji nisu paketi jedne vrste proizvoda.
 * @return int  0 ako slug nije velicinska kategorija
 */
function noriks_pack_size_from_slug( $slug ) {
    if ( ! preg_match_all( '/\d+/', (string) $slug, $m ) ) { return 0; }
    if ( count( $m[0] ) !== 1 ) { return 0; }
    $size = (int) $m[0][0];
    if ( $size < NORIKS_PACK_MIN_SIZE || $size > NORIKS_PACK_MAX_SIZE ) { return 0; }
    return $size;
}

/**
 * Velicinska kategorija proizvoda: dijete neke kategorije, sa brojem u slugu.
 * @return array|null  ['size' => int, 'group' => int(parent term id), 'term' => WP_Term]
 */
function noriks_pack_meta( $product_id ) {
    $terms = get_the_terms( $product_id, 'product_cat' );
    if ( empty( $terms ) || is_wp_error( $terms ) ) { return null; }

    $best = null;
    foreach ( $terms as $t ) {
        if ( (int) $t->parent === 0 ) { continue; }        // grupa mora imati roditelja
        $size = noriks_pack_size_from_slug( $t->slug );
        if ( $size === 0 ) { continue; }
        // ako ih je vise, uzmi onu s najvecim brojem djece istog roditelja (prava velicinska grana)
        if ( $best === null || $size > $best['size'] ) {
            $best = array( 'size' => $size, 'group' => (int) $t->parent, 'term' => $t );
        }
    }
    return $best;
}

/** "Obitelj" (kombinacija boja) iz SKU-a: NORIKS-ALL-BLACK-6-PACK => noriks-all-black */
function noriks_pack_family_key( $sku ) {
    $s = strtoupper( (string) $sku );
    if ( $s === '' ) { return ''; }
    $s = preg_replace( '/[-_]?\d+[-_]?(PACK|PAKET|PAKIET|SZT|KOM|KOS)S?\b/', '', $s ); // -6-PACK, -12-SZT …
    $s = preg_replace( '/[-_](DOZEN|PACK|PAKET)\b/', '', $s );                          // -DOZEN, -PACK
    return strtolower( trim( $s, '-_ ' ) );
}

/**
 * Indeks paketa po grupama: [group_term_id][size] => lista proizvoda.
 * Gradi se iz svih "velicinskih" kategorija (dijete + broj u slugu).
 */
function noriks_pack_index() {
    $cached = get_transient( NORIKS_PACK_TRANSIENT );
    if ( is_array( $cached ) ) { return $cached; }

    $index = array();
    $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) );
    if ( is_wp_error( $terms ) ) { return $index; }

    foreach ( $terms as $t ) {
        if ( (int) $t->parent === 0 ) { continue; }
        $size = noriks_pack_size_from_slug( $t->slug );
        if ( $size === 0 ) { continue; }

        $ids = get_posts( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 60,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'tax_query'      => array( array(
                'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => (int) $t->term_id,
            ) ),
        ) );
        foreach ( $ids as $id ) {
            $product = wc_get_product( $id );
            if ( ! $product || ! $product->is_visible() ) { continue; }
            $price = (float) $product->get_price();
            $index[ (int) $t->parent ][ $size ][] = array(
                'id'     => $id,
                'sku'    => $product->get_sku(),
                'family' => noriks_pack_family_key( $product->get_sku() ),
                'label'  => get_the_title( $id ),
                'url'    => get_permalink( $id ),
                'img'    => get_the_post_thumbnail_url( $id, 'woocommerce_thumbnail' ),
                'price'  => $price,
                // cijena po komadu se ODREZE na 2 decimale (kao u pluginu) — 134,99/9 = 14,99, ne 15,00
                'ppu'    => $size > 0 ? floor( ( $price / $size ) * 100 ) / 100 : 0,
            );
        }
    }
    foreach ( $index as $g => $sizes ) {
        ksort( $index[ $g ], SORT_NUMERIC );
        foreach ( $sizes as $s => $list ) {
            usort( $index[ $g ][ $s ], function ( $a, $b ) { return strcmp( (string) $a['sku'], (string) $b['sku'] ); } );
        }
    }
    set_transient( NORIKS_PACK_TRANSIENT, $index, 10 * MINUTE_IN_SECONDS );
    return $index;
}

/**
 * Cilj kad se mijenja velicina: ista kombinacija (SKU obitelj); ako je nema,
 * ona s najduzim zajednickim pocetkom SKU-a; tek na kraju prvi paket te velicine.
 */
function noriks_pack_target( $list, $family ) {
    if ( empty( $list ) ) { return null; }
    if ( $family !== '' ) {
        foreach ( $list as $p ) {
            if ( $p['family'] === $family ) { return $p; }
        }
        $best = null; $best_score = 0;
        foreach ( $list as $p ) {
            $n = 0; $max = min( strlen( $p['family'] ), strlen( $family ) );
            while ( $n < $max && $p['family'][ $n ] === $family[ $n ] ) { $n++; }
            if ( $n > $best_score ) { $best_score = $n; $best = $p; }
        }
        if ( $best && $best_score >= 8 ) { return $best; }   // npr. "noriks-a…" ni dovolj
    }
    return $list[0];
}

function noriks_render_pack_switcher() {
    global $product;
    if ( ! $product instanceof WC_Product ) { return; }

    $id = $product->get_id();

    // Samo webshop asortiman (majice/bokserice paketi) — nikad orto proizvodi.
    if ( function_exists( 'noriks_is_type' ) && noriks_is_type( 'orto', $id ) ) { return; }

    $meta = noriks_pack_meta( $id );
    if ( ! $meta ) { return; }

    $index = noriks_pack_index();
    if ( empty( $index[ $meta['group'] ] ) ) { return; }

    $sizes  = $index[ $meta['group'] ];
    $size   = $meta['size'];
    $family = noriks_pack_family_key( $product->get_sku() );
    $same   = isset( $sizes[ $size ] ) ? $sizes[ $size ] : array();

    // Proizvod mora i sam biti dio te ponude (skriveni upsell/mystery artikli
    // nose istu kategoriju, ali nisu dio izbora paketa).
    $in_list = false;
    foreach ( $same as $p ) {
        if ( (int) $p['id'] === (int) $id ) { $in_list = true; break; }
    }
    if ( ! $in_list ) { return; }

    if ( count( $sizes ) < 2 && count( $same ) < 2 ) { return; }

    // Odabrana boja uvijek na PRVOM mjestu u redu boja.
    foreach ( $same as $k => $p ) {
        if ( (int) $p['id'] === (int) $id ) { unset( $same[ $k ] ); array_unshift( $same, $p ); break; }
    }

    // "1 komad" se prikazuje samo kad smo na proizvodu od jednog komada — inace nikad.
    $min_size  = ( (int) $size === 1 ) ? 1 : 2;
    $keys      = array_values( array_filter( array_keys( $sizes ), function ( $k ) use ( $min_size ) { return (int) $k >= $min_size; } ) );
    $shown     = count( $keys );
    ?>
    <div class="npk">

        <?php if ( $shown > 1 ) : ?>
        <div class="npk-block">
            <div class="npk-label"><?php esc_html_e( 'Choose your pack', 'noriks' ); ?></div>
            <div class="npk-scroll">
            <div class="npk-sizes">
                <?php foreach ( $sizes as $s => $list ) :
                    if ( (int) $s < $min_size ) { continue; }
                    $t = noriks_pack_target( $list, $family );
                    if ( ! $t ) { continue; }
                    $is_cur = ( (int) $s === (int) $size );
                    $ppu    = $is_cur
                        ? floor( ( (float) $product->get_price() / max( 1, $size ) ) * 100 ) / 100
                        : $t['ppu'];
                    ?>
                    <a class="npk-size<?php echo $is_cur ? ' is-active' : ''; ?>"
                       href="<?php echo esc_url( $is_cur ? get_permalink( $id ) : $t['url'] ); ?>"
                       <?php echo $is_cur ? 'aria-current="true"' : ''; ?>>
                        <span class="npk-size-n"><?php
                            if ( (int) $s === 1 ) {
                                esc_html_e( '1 piece', 'noriks' );
                            } else {
                                echo (int) $s; ?><?php esc_html_e( '-pack', 'noriks' );
                            } ?></span>
                        <span class="npk-size-p"><?php echo wp_kses_post( wc_price( $ppu ) ); ?><span class="npk-size-u"><?php esc_html_e( '/ pc', 'noriks' ); ?></span></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="npk-bar" aria-hidden="true"><span></span></div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ( count( $same ) > 1 ) : ?>
        <div class="npk-block">
            <div class="npk-label"><?php esc_html_e( 'Color', 'noriks' ); ?></div>
            <div class="npk-scroll">
            <div class="npk-colors">
                <?php foreach ( $same as $p ) :
                    $is_cur = ( (int) $p['id'] === (int) $id ); ?>
                    <a class="npk-color<?php echo $is_cur ? ' is-active' : ''; ?>"
                       href="<?php echo esc_url( $p['url'] ); ?>"
                       title="<?php echo esc_attr( $p['label'] ); ?>">
                        <?php if ( $p['img'] ) : ?>
                            <img src="<?php echo esc_url( $p['img'] ); ?>" alt="<?php echo esc_attr( $p['label'] ); ?>" loading="lazy">
                        <?php else : ?>
                            <span class="npk-color-txt"><?php echo esc_html( mb_substr( $p['label'], 0, 14 ) ); ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="npk-bar" aria-hidden="true"><span></span></div>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <style>
      .npk { margin: 8px 0 14px; }
      <?php if ( count( $same ) > 1 ) : ?>
      /* temin red "druge boje" iznad nudi iste linkove -> ne prikazujemo ga dvaput */
      .color-selections { display: none !important; }
      <?php endif; ?>
      .npk-block { margin-bottom: 10px; }
      .npk-label { font-size: 15px; font-weight: 800; color: #141414; margin: 0 0 7px; }

      /* velicine paketa — ravni robovi, kao i gumbi za velicinu ispod */
      .npk-sizes { display: grid; grid-auto-flow: column; grid-auto-columns: 1fr; gap: 10px; }
      .npk-size { position: relative; display: flex; flex-direction: column; justify-content: center;
                  min-height: 62px; min-width: 0; text-align: center; text-decoration: none;
                  border: 1px solid #d7d7d7; border-radius: 0; padding: 12px 6px; background: #fff;
                  transition: border-color .15s, background .15s; }
      .npk-size:hover { border-color: #141414; }
      .npk-size.is-active { background: #12233b; border-color: #12233b; }
      /* pisava se skrci s sirino stolpca, da tekst nikoli ne izpade iz kartice */
      .npk-size-n { display: block; font-size: clamp(14px, 1.35vw, 18px); font-weight: 800; color: #141414; line-height: 1.15; }
      .npk-size-p { display: block; font-size: clamp(10px, .95vw, 12px); color: #6b6b6b; margin-top: 4px;
                    line-height: 1.25; white-space: nowrap; }
      /* mjerna jedinica uvijek u DRUGI red i sitnije */
      .npk-size-u { display: block; font-size: .9em; }
      .npk-size.is-active .npk-size-n, .npk-size.is-active .npk-size-p,
      .npk-size.is-active .npk-size-p .amount, .npk-size.is-active .npk-size-p bdi { color: #fff !important; }
      .npk-size.is-active .npk-size-p { opacity: .92; }

      /* boje — kvadratne plocice */
      .npk-block:last-child { margin-bottom: 0; }
      /* kvadratne plocice, najvise 110px — pri 3 boje ostaju male, pri mnogima se skrce */
      .npk-colors { display: grid; grid-auto-flow: column; grid-auto-columns: minmax(0, 110px);
                    justify-content: start; gap: 10px; }
      .npk-color { display: block; width: 100%; aspect-ratio: 1 / 1; min-width: 0; border: 1px solid #e2e2e2; border-radius: 0;
                   overflow: hidden; background: #f4f4f4; transition: border-color .15s; }
      .npk-color img { width: 100%; height: 100%; object-fit: cover; display: block; }
      .npk-color:hover { border-color: #9a9a9a; }
      .npk-color.is-active { border: 2px solid #141414; }
      .npk-color-txt { display: flex; width: 100%; height: 100%; align-items: center; justify-content: center;
                       font-size: 10.5px; line-height: 1.2; text-align: center; color: #6b6b6b; padding: 4px; box-sizing: border-box; }

      @media (min-width: 1024px) {
        .npk-label { font-size: 16px; }
        .npk-size { min-height: 70px; padding: 12px 8px; }
      }

      /* uvijek vidljiva crta drsnika ispod reda (nativna se na mobitelu skriva) */
      .npk-scroll { position: relative; }
      .npk-bar { display: none; height: 3px; border-radius: 3px; background: #e4e4e4; margin: 8px 0 0; overflow: hidden; }
      .npk-bar span { display: block; height: 100%; width: 30%; border-radius: 3px; background: #12233b; transform: translateX(0); }
      .npk-scroll.is-scrollable .npk-bar { display: block; }

      @media (max-width: 700px) {
        /* vodoravni drsnik — vidi se ~4 kartice, ostale podrsas */
        .npk-sizes { display: flex; grid-auto-flow: unset; gap: 8px; overflow-x: auto;
                     scroll-snap-type: x proximity; -webkit-overflow-scrolling: touch;
                     scrollbar-width: none; padding-bottom: 2px; }
        .npk-sizes::-webkit-scrollbar { display: none; }
        .npk-size { flex: 0 0 calc((100% - 24px) / 3.6); scroll-snap-align: center; }

        .npk-colors { display: flex; grid-auto-flow: unset; flex-wrap: nowrap; gap: 8px; overflow-x: auto;
                      -webkit-overflow-scrolling: touch; scrollbar-width: none; padding-bottom: 2px; }
        .npk-colors::-webkit-scrollbar { display: none; }
        .npk-color { flex: 0 0 calc((100% - 24px) / 3.6); width: auto; max-width: 110px; }
      }
      @media (max-width: 560px) {
        .npk-sizes { gap: 8px; }
        .npk-size { min-height: 56px; padding: 10px 4px; }
        .npk-size-n { font-size: 14.5px; }
        .npk-size-p { font-size: 11px; }
      }
    </style>
    <script>
    (function(){
      /* Na mobitelu odabrani paket i odabranu boju dovedemo u sredinu drsnika. */
      function center(sel){
        var box = document.querySelector(sel);
        if (!box) { return; }
        var act = box.querySelector('.is-active');
        if (!act || box.scrollWidth <= box.clientWidth + 4) { return; }
        box.scrollLeft = act.offsetLeft - (box.clientWidth - act.offsetWidth) / 2;
      }
      /* Crta drsnika: sirina palca = udio vidljivog dijela, pomak = polozaj drsnika. */
      function bar(box){
        var wrap = box.closest ? box.closest('.npk-scroll') : null;
        if (!wrap) { return; }
        var thumb = wrap.querySelector('.npk-bar span');
        var can   = box.scrollWidth > box.clientWidth + 4;
        wrap.classList.toggle('is-scrollable', can);
        if (!can || !thumb) { return; }
        var ratio = box.clientWidth / box.scrollWidth;
        var track = wrap.querySelector('.npk-bar').clientWidth;
        var w     = Math.max(28, track * ratio);
        var max   = box.scrollWidth - box.clientWidth;
        thumb.style.width = w + 'px';
        thumb.style.transform = 'translateX(' + ((box.scrollLeft / max) * (track - w)) + 'px)';
      }
      function each(fn){ ['.npk-sizes', '.npk-colors'].forEach(function(sel){
        document.querySelectorAll(sel).forEach(fn);
      }); }
      function run(){ center('.npk-sizes'); center('.npk-colors'); each(bar); }
      each(function(box){ box.addEventListener('scroll', function(){ bar(box); }, { passive: true }); });
      if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', run); } else { run(); }
      window.addEventListener('load', run);
      window.addEventListener('resize', run);
    })();
    </script>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'noriks_render_pack_switcher', 24 );

/** Indeks se osvjezi kad se proizvod ili kategorija promijeni. */
function noriks_pack_index_flush() { delete_transient( NORIKS_PACK_TRANSIENT ); }
add_action( 'save_post_product', 'noriks_pack_index_flush' );
add_action( 'woocommerce_update_product', 'noriks_pack_index_flush' );
add_action( 'edited_product_cat', 'noriks_pack_index_flush' );
