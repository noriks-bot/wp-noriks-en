<?php
/**
 * NORIKS — filtri kategorij brez vticnika YITH.
 *
 * Nadomesti [yith_wcan_filters slug="..."] v woocommerce/archive-product.php.
 * Filtri so v praksi le povezave na (pod)kategorije, zato jih izrisemo sami.
 *
 * ZAKAJ SEZNAM SPODAJ IN NE SAMODEJNO BRANJE KATEGORIJ:
 * napisi v vticniku NISO imena kategorij (kategorija "3-paket majic" se prikaze
 * kot "3-paket", "Barve" kot "Barvne"), vrstni red pa ni abecedni (1, 3, 6, 9,
 * 12, 15 — abecedno bi bilo 1, 12, 15, 3, 6, 9). Seznam je zato posnet z ZIVE
 * strani pred izklopom vticnika, da napisi in vrstni red ostanejo isti.
 *
 * Ce stran ni na seznamu, koda samodejno izrise podkategorije (rezerva).
 *
 * Povezave so DIREKTNE na kategorijo (get_term_link), brez ?yith_wcan= parametrov.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Posneto z zive strani. Kljuc je slug starsevske kategorije,
 * "__shop__" pa je stran trgovine. Vrstni red vpisov = vrstni red na strani.
 */
function noriks_shop_filter_map() {
	return apply_filters( 'noriks_shop_filter_map', array(
		'__shop__' => array(
			'bestsellers' => 'Best Sellers',
			'large-bundles' => 'Large Bundles',
			'starter-pack' => 'Starter Pack',
		),
		'boxers' => array(
			'1-pcs-boxers' => '1 pcs',
			'pack-3-pcs-boxers' => '3-pack',
			'pack-5-pcs-boxers' => '5-pack',
			'pack-7-pcs-boxers' => '7-pack',
			'pack-10-pcs-boxers' => '10-pack',
			'pack-15-pcs-boxers' => '15-pack',
			'black-boxers' => 'Black',
			'colorful-boxers' => 'Colorful',
		),
		'bundles' => array(
			'bestsellers' => 'Best Sellers',
			'large-bundles' => 'Large Bundles',
			'starter-pack' => 'Starter Pack',
		),
		'socks' => array(
			'black-socks' => 'Black',
			'white-socks' => 'White',
		),
		't-shirts' => array(
			'1-pcs' => '1 pcs',
			'pack-3-pcs' => '3-pack',
			'pack-6-pcs' => '6-pack',
			'pack-9-pcs' => '9-pack',
			'pack-12-pcs' => '12-pack',
			'pack-15-pcs' => '15-pack',
			'black' => 'Black',
			'colorful' => 'Colorful',
		),
	) );
}

/**
 * Vrne pare slug => napis za trenutno stran.
 */
function noriks_shop_filter_items() {

	$map = noriks_shop_filter_map();

	if ( is_shop() ) {
		return isset( $map['__shop__'] ) ? $map['__shop__'] : array();
	}

	if ( ! is_product_category() ) return array();

	$term = get_queried_object();
	if ( ! $term || ! isset( $term->slug ) ) return array();

	// Kategorija je na seznamu -> uporabi posnete napise in vrstni red.
	if ( isset( $map[ $term->slug ] ) ) return $map[ $term->slug ];

	// Kategorija je SAMA postavka nekega seznama (npr. Bestsellers, Starter paketi,
	// Veliki paketi) — te so vrhnje, brez starsa, zato jih zgornji pogoj ne ujame.
	// Pokazemo isti seznam kot na trgovini, s klikom oznacenim trenutnim.
	foreach ( $map as $group => $items ) {
		if ( isset( $items[ $term->slug ] ) ) return $items;
	}

	// Podkategorija (npr. /majice/3-paket-majic) -> pokazi seznam starsa,
	// tako kot je delal vticnik.
	if ( $term->parent ) {
		$parent = get_term( $term->parent, 'product_cat' );
		if ( $parent && ! is_wp_error( $parent ) && isset( $map[ $parent->slug ] ) ) {
			return $map[ $parent->slug ];
		}
	}

	// Rezerva: samodejno izrisi podkategorije z izdelki.
	$children = get_terms( array(
		'taxonomy'   => 'product_cat',
		'parent'     => $term->term_id,
		'hide_empty' => true,
		'orderby'    => 'name',
	) );
	$out = array();
	if ( ! is_wp_error( $children ) ) {
		foreach ( $children as $c ) $out[ $c->slug ] = $c->name;
	}
	return $out;
}

/**
 * Izris. Klic v archive-product.php: noriks_shop_filter_links();
 */
function noriks_shop_filter_links() {

	$items = noriks_shop_filter_items();
	if ( empty( $items ) ) return;

	$current = '';
	if ( is_product_category() ) {
		$q = get_queried_object();
		if ( $q && isset( $q->slug ) ) $current = $q->slug;
	}

	echo '<div class="yith-wcan-filters no-title noriks-filters">';
	echo '<div class="filters-container">';
	echo '<div class="yith-wcan-filter filter-tax label-design" data-taxonomy="product_cat">';
	echo '<div class="filter-content">';
	echo '<ul class="filter-items filter-label level-0">';

	foreach ( $items as $slug => $label ) {

		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term || is_wp_error( $term ) ) continue;   // kategorije ni vec -> preskoci

		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) continue;

		printf(
			'<li class="filter-item label level-0 no-image label-right%1$s">'
			. '<a href="%2$s" role="button" data-term-id="%3$d" data-term-slug="%4$s">'
			. '<span class="term-label">%5$s</span></a></li>',
			( $slug === $current ) ? ' active' : '',
			esc_url( $link ),
			(int) $term->term_id,
			esc_attr( $slug ),
			esc_html( $label )
		);
	}

	echo '</ul></div></div></div></div>';
}

/**
 * Slog: prenesen iz vticnikovega shortcodes.css (samo pravila za te "cipe")
 * in iz njegovega vgrajenega sloga (barvne spremenljivke), da videz po izklopu
 * vticnika ostane nespremenjen.
 */
function noriks_shop_filter_links_css() {
	if ( ! is_shop() && ! is_product_category() ) return;
	?>
<style id="noriks-filter-links-css">
/* Barvne spremenljivke — prepisane iz vgrajenega sloga vticnika. */
:root{
	--yith-wcan-filters_colors_accent: rgb(222,222,222);
	--yith-wcan-labels_style_background: #FFFFFF;
	--yith-wcan-labels_style_background_hover: rgb(222,222,222);
	--yith-wcan-labels_style_background_active: rgb(222,222,222);
	--yith-wcan-labels_style_text: rgb(0,0,0);
	--yith-wcan-labels_style_text_hover: rgb(0,0,0);
	--yith-wcan-labels_style_text_active: rgb(0,0,0);
	--yith-wcan-anchors_style_text: #434343;
}
/* Dobesedno prepisano iz vticnikovega shortcodes.css — samo pravila, ki zadevajo
   nase oznake. POSTAVITVE (mreza stolpcev) se NE dotikamo, ker jo doloca tema. */
.yith-wcan-filters .yith-wcan-filter .filter-items { float: none; list-style: none; padding-left: 0; }
.yith-wcan-filters .yith-wcan-filter .filter-items.level-0 { margin: 0; padding: 0; }
.yith-wcan-filters .yith-wcan-filter .filter-items.filter-label { font-size: 0; margin: 0 -5px; }
.yith-wcan-filters .yith-wcan-filter.label-design .filter-items { font-size: 0; }
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item { line-height: 2; margin: 0; }
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item > a { color: var(--yith-wcan-anchors_style_text, #434343); text-decoration: none; }
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label {
	background-color: var(--yith-wcan-labels_style_background, #fff);
	box-shadow: 0 0 0 1px #D7D7D7;
	border-radius: 4px;
	display: inline-block;
	margin: 0 5px 10px;
	padding: 7px;
	text-align: center;
}
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label > a { color: var(--yith-wcan-labels_style_text, #434343); display: block; height: 100%; }
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label .term-label { display: block; font-size: 0.8rem; }
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label:not(.disabled):hover {
	background-color: var(--yith-wcan-labels_style_background_hover, #4e8ba2);
	box-shadow: 0 0 0 2px var(--yith-wcan-labels_style_background_hover, #4e8ba2);
	color: var(--yith-wcan-labels_style_text_hover, #fff);
}
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label:not(.disabled):hover .term-label { color: var(--yith-wcan-labels_style_text_hover, #fff); }
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label.active {
	background-color: var(--yith-wcan-labels_style_background_active, #4e8ba2);
	box-shadow: 0 0 0 2px var(--yith-wcan-labels_style_background_active, #4e8ba2);
	color: var(--yith-wcan-labels_style_text_active, #fff);
}
.yith-wcan-filters .yith-wcan-filter .filter-items .filter-item.label.active .term-label { color: var(--yith-wcan-labels_style_text_active, #fff); }
</style>
	<?php
}
add_action( 'wp_head', 'noriks_shop_filter_links_css', 99 );
