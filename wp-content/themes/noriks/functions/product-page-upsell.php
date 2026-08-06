<?php
/**
 * NORIKS — product-page upsell ("Buy together and save").
 *
 * Okvir se prikazuje ODMAH ISPOD gumba "Dodaj u košaricu" i nudi 4x Green Boxers
 * po istoj cijeni kao post-purchase upsell na thank you stranici (14,97 € za 3 kom).
 *
 * - Uključuje se ACF prekidačem `noriks_pp_upsell` (polje registrirano u KODU, dolje).
 *   Prekidač je per-proizvod, pa se upsell može uključiti samo tamo gdje ga želimo.
 * - Kupac bira SAMO veličinu (jedan izbornik, sva 4 komada iste veličine).
 * - Kad je kvačica označena, uz glavni proizvod se u košaricu dodaje zasebna stavka
 *   (varijacija zelenih bokserica) s upsell cijenom.
 * - Stavka se u narudžbi označava meta poljem `_noriks_upsell` = 'product_page_upsell'
 *   (isti mehanizam kao sidecart i thank you upsell).
 *
 * Dizajn preslikan s referentne stranice (narančasta shema #ff5b01) + izbornik veličine
 * u stilu postojećih izbornika u bundle selectoru (#ff6d2e).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================
 * 1) ACF prekidač — registriran u kodu (kao orto countdown polja)
 * ============================================================ */
add_action( 'acf/init', 'noriks_pp_upsell_register_fields' );
function noriks_pp_upsell_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group( array(
		'key'    => 'group_noriks_pp_upsell',
		'title'  => 'Product page upsell',
		'fields' => array(
			array(
				'key'          => 'field_noriks_pp_upsell',
				'label'        => 'Show upsell below the button (4x Green Boxers)',
				'name'         => 'noriks_pp_upsell',
				'type'         => 'true_false',
				'instructions' => 'Adds the "Buy together and save" box right below the Add to cart button. The customer only picks a size.',
				'ui'           => 1,
			),
			array(
				'key'          => 'field_noriks_pp_upsell2',
				'label'        => 'Show upsell #2 below the button (2 t-shirts: black + gray)',
				'name'         => 'noriks_pp_upsell2',
				'type'         => 'true_false',
				'instructions' => 'Second upsell box: a bundle of 2 t-shirts (1 black + 1 gray), the customer picks one size for both. Independent of the first toggle — both can be on at once.',
				'ui'           => 1,
			),
			array(
				'key'          => 'field_noriks_pp_upsell3',
				'label'        => 'Show upsell #3 below the button (NORIKS FIT compression t-shirt)',
				'name'         => 'noriks_pp_upsell3',
				'type'         => 'true_false',
				'instructions' => 'Third upsell box: NORIKS FIT compression t-shirt, the customer picks a size. Independent of the first two toggles.',
				'ui'           => 1,
			),
		),
		'location'   => array(
			array(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ),
			),
		),
		'menu_order' => 5,
	) );
}

/* ============================================================
 * 2) Konfiguracija upsell ponude
 * ============================================================ */
function noriks_pp_upsell_config() {
	return apply_filters( 'noriks_pp_upsell_config', array(
		'product_id' => 2703,                    // Zelene Bokserice (varijabilni proizvod)
		'qty'        => 4,                       // uvijek 4 komada, iste veličine
		'total'      => 19.99,                   // ista cijena kao thank you upsell (4 komada)
		'title'      => '4x Green Boxers',
		'desc'       => 'Breathable and soft — add them to your order at %s%% off.', // %s = izracunati popust
		'size_attr'  => 'Size',
		// Interna oznaka paketa (SKU konvencija kao kod bundle proizvoda + UPSELL na kraju).
		'sku'        => 'NORIKS-BOX-GREEN-4-PACK-UPSELL',
		// Kompozitna slika 4 komada na svijetlo sivoj podlozi (kvadratna).
		'image'      => get_template_directory_uri() . '/img/upsell/upsell-4x-zelene.png',
	) );
}

/** Je li upsell uključen za dani proizvod (ACF prekidač). */
function noriks_pp_upsell_enabled( $product_id = 0 ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! function_exists( 'get_field' ) ) {
		return false;
	}
	$cfg = noriks_pp_upsell_config();
	if ( $product_id === (int) $cfg['product_id'] ) {
		return false; // nikad na samom upsell proizvodu
	}
	return (bool) get_field( 'noriks_pp_upsell', $product_id );
}

/** Dostupne veličine upsell proizvoda -> array( velicina => variation_id ). */
function noriks_pp_upsell_sizes() {
	$cfg     = noriks_pp_upsell_config();
	$product = wc_get_product( $cfg['product_id'] );
	$out     = array();

	if ( ! $product || ! $product->is_type( 'variable' ) ) {
		return $out;
	}
	foreach ( $product->get_children() as $vid ) {
		$var = wc_get_product( $vid );
		if ( ! $var || ! $var->is_in_stock() || ! $var->is_purchasable() ) {
			continue;
		}
		$size = $var->get_attribute( $cfg['size_attr'] );
		if ( $size === '' ) {
			$attrs = $var->get_variation_attributes();
			$size  = $attrs ? (string) reset( $attrs ) : '';
		}
		if ( $size !== '' && ! isset( $out[ $size ] ) ) {
			$out[ $size ] = (int) $vid;
		}
	}
	return $out;
}

/* ============================================================
 * 3) Prikaz okvira ispod gumba "Dodaj u košaricu"
 * ============================================================ */
add_action( 'woocommerce_after_add_to_cart_button', 'noriks_pp_upsell_render', 15 );
function noriks_pp_upsell_render() {
	if ( ! noriks_pp_upsell_enabled() ) {
		return;
	}

	$cfg     = noriks_pp_upsell_config();
	$product = wc_get_product( $cfg['product_id'] );
	if ( ! $product ) {
		return;
	}

	$sizes = noriks_pp_upsell_sizes();
	if ( empty( $sizes ) ) {
		return; // nema dostupnih veličina -> ne prikazuj ništa
	}

	// Redovna cijena = zbroj redovnih cijena pojedinačnih komada.
	$unit_regular = 0.0;
	foreach ( $sizes as $vid ) {
		$var = wc_get_product( $vid );
		if ( $var ) {
			$unit_regular = (float) $var->get_regular_price();
			break;
		}
	}
	$regular_total = $unit_regular * (int) $cfg['qty'];
	// Popust se racuna iz stvarnih cijena (postotak se razlikuje po trzistu).
	$discount = ( $regular_total > 0 ) ? (int) round( ( 1 - ( (float) $cfg['total'] / $regular_total ) ) * 100 ) : 0;
	$desc     = ( strpos( $cfg['desc'], '%s' ) !== false ) ? sprintf( $cfg['desc'], $discount ) : $cfg['desc'];

	$image = ! empty( $cfg['image'] ) ? $cfg['image'] : wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
	if ( ! $image ) {
		$image = wc_placeholder_img_src( 'medium' );
	}
	noriks_pp_upsell_styles();
	?>
	<div class="npu-wrap">
		<span class="npu-label">Buy together and save:</span>

		<div class="npu-box" id="npu-box">
			<div class="npu-grid">
				<span class="npu-img-wrap">
					<img class="npu-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cfg['title'] ); ?>" loading="lazy">
				</span>

				<div class="npu-info">
					<p class="npu-title"><?php echo esc_html( $cfg['title'] ); ?></p>
					<div class="npu-desc"><?php echo esc_html( $desc ); ?></div>
					<div class="npu-prices">
						<span class="npu-price"><?php echo wc_price( (float) $cfg['total'] ); ?></span>
						<?php if ( $regular_total > 0 ) : ?>
							<span class="npu-price-old"><?php echo wc_price( $regular_total ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="npu-actions">
					<label class="npu-check">
						<input type="checkbox" id="npu-toggle" name="noriks_pp_upsell" value="1">
						<span class="npu-box-mark" aria-hidden="true"></span>
						<span class="npu-check-text">Add to purchase</span>
					</label>

					<select class="npu-size" name="noriks_pp_upsell_size" aria-label="Veličina bokserica">
						<?php foreach ( array_keys( $sizes ) as $size ) : ?>
							<option value="<?php echo esc_attr( $size ); ?>"><?php echo esc_html( $size ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>


	<script>
	(function () {
		var box = document.getElementById('npu-box');
		var cb  = document.getElementById('npu-toggle');
		if (!box || !cb) { return; }
		function paint() { box.classList.toggle('npu-checked', cb.checked); }
		cb.addEventListener('change', paint);
		paint();
		/* klik bilo gdje po okviru (osim po izborniku veličine) prebacuje kvačicu */
		box.addEventListener('click', function (e) {
			if (e.target.closest('.npu-size') || e.target.closest('.npu-check')) { return; }
			cb.checked = !cb.checked;
			cb.dispatchEvent(new Event('change', { bubbles: true }));
		});
	})();
	</script>

	<script>
	/* Kad kupac promijeni PRVI izbornik velicine u paketu, upsell izbornik se
	   automatski postavi na istu velicinu (dok je kupac sam ne promijeni). */
	(function () {
		var sel = document.querySelector('.npu-size');
		if (!sel) { return; }
		var touched = false;
		sel.addEventListener('change', function () { touched = true; });

		/* Izvor velicine: <select> odabrane ponude. NE koristimo offsetParent jer
		   je kod alternativnih izbornika (ACF orto_alt_dropdowns) pravi <select>
		   skriven, pa bi provjera vidljivosti uvijek pala. */
		function npuSizeUsable(s) {
			if (!s || !s.value) { return false; }
			var w = s.closest ? s.closest('.bundle-pairs') : null;
			if (w && w.classList.contains('hidden')) { return false; }
			if (s.offsetParent !== null) { return true; }
			return !!(s.closest && s.closest('#bundle-selector'));
		}
		function firstBundleSize() {
			var all = document.querySelectorAll('.gck-size-select');
			for (var i = 0; i < all.length; i++) {
				if (npuSizeUsable(all[i])) { return all[i]; }
			}
			return null;
		}
		function sync() {
			if (touched) { return; }
			var src = firstBundleSize();
			if (!src) { return; }
			var v = String(src.value).trim().toLowerCase();
			for (var i = 0; i < sel.options.length; i++) {
				if (String(sel.options[i].value).trim().toLowerCase() === v) {
					sel.value = sel.options[i].value;
					return;
				}
			}
		}
		document.addEventListener('change', function (e) {
			var t = e.target;
			if (!t) { return; }
			if (t.classList && t.classList.contains('gck-size-select')) { sync(); }
			if (t.name === 'bundle_option') { setTimeout(sync, 60); }
		});
		setTimeout(sync, 250);
		setTimeout(sync, 900);
	})();
	</script>
	<?php
}

/* ============================================================
 * 4) Dodavanje u košaricu uz glavni proizvod
 * ============================================================ */
add_action( 'woocommerce_add_to_cart', 'noriks_pp_upsell_maybe_add', 20, 6 );
function noriks_pp_upsell_maybe_add( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	static $busy = false;

	if ( $busy || ! empty( $cart_item_data['_noriks_pp_upsell'] ) ) {
		return; // sprječava rekurziju kod dodavanja same upsell stavke
	}
	if ( empty( $_POST['noriks_pp_upsell'] ) ) {
		return;
	}
	if ( ! noriks_pp_upsell_enabled( $product_id ) ) {
		return;
	}

	$cfg   = noriks_pp_upsell_config();
	$sizes = noriks_pp_upsell_sizes();
	if ( empty( $sizes ) ) {
		return;
	}

	$size = isset( $_POST['noriks_pp_upsell_size'] )
		? sanitize_text_field( wp_unslash( $_POST['noriks_pp_upsell_size'] ) )
		: '';
	if ( $size === '' || ! isset( $sizes[ $size ] ) ) {
		$size = (string) key( $sizes ); // sigurnosna mreža: prva dostupna veličina
	}

	$variation_id_upsell = (int) $sizes[ $size ];
	$var                 = wc_get_product( $variation_id_upsell );
	if ( ! $var ) {
		return;
	}

	$qty = max( 1, (int) $cfg['qty'] );

	// Sadrzaj paketa: jedan red po komadu (naziv proizvoda + odabrana velicina).
	$parent    = wc_get_product( (int) $cfg['product_id'] );
	$line_name = $parent ? $parent->get_name() : (string) $cfg['title'];
	$lines     = array();
	for ( $i = 0; $i < $qty; $i++ ) {
		$lines[] = $line_name . ' - ' . $size;
	}

	// PAKET: u kosaricu ide JEDNA stavka (kolicina 1) s cijenom cijelog paketa.
	// Tako kupac ne moze mijenjati kolicinu niti razbiti ponudu na komade.
	$busy = true;
	WC()->cart->add_to_cart(
		(int) $cfg['product_id'],
		1,
		$variation_id_upsell,
		$var->get_variation_attributes(),
		array(
			'_noriks_pp_upsell'       => 1,
			'_noriks_pp_upsell_unit'  => (float) $cfg['total'], // cijena cijelog paketa (kolicina 1)
			'_noriks_pp_upsell_qty'   => $qty,                  // koliko komada paket sadrzi
			'_noriks_pp_upsell_title' => (string) $cfg['title'],
			'_noriks_pp_upsell_lines' => $lines,                // sadrzaj paketa, red po red
			'_noriks_pp_upsell_key'   => md5( 'npu' . $variation_id_upsell . microtime( true ) ),
		)
	);
	$busy = false;
}

/* ---- Paket u kosarici: ime, sadrzaj, slika, zakljucana kolicina ---- */

/* Sadrzaj paketa red po red (isti prikaz kao orto bundle: "1: ... / 2: ..."). */
add_filter( 'woocommerce_get_item_data', 'noriks_pp_upsell_item_data', 20, 2 );
function noriks_pp_upsell_item_data( $item_data, $cart_item ) {
	if ( empty( $cart_item['_noriks_pp_upsell_lines'] ) || ! is_array( $cart_item['_noriks_pp_upsell_lines'] ) ) {
		return $item_data;
	}
	$numbered = array();
	foreach ( array_values( $cart_item['_noriks_pp_upsell_lines'] ) as $i => $line ) {
		$numbered[] = esc_html( ( $i + 1 ) . ': ' . $line );
	}
	$item_data[] = array(
		'name'    => false,
		'display' => implode( '<br>', $numbered ),
	);
	return $item_data;
}

/* U kosarici se prikazuje slika paketa (kompozit), a ne slika jedne varijacije. */
add_filter( 'woocommerce_cart_item_thumbnail', 'noriks_pp_upsell_cart_thumb', 20, 3 );
function noriks_pp_upsell_cart_thumb( $thumbnail, $cart_item, $cart_item_key ) {
	if ( empty( $cart_item['_noriks_pp_upsell'] ) ) {
		return $thumbnail;
	}
	$cfg = noriks_pp_upsell_config();
	if ( empty( $cfg['image'] ) ) {
		return $thumbnail;
	}
	return '<img src="' . esc_url( $cfg['image'] ) . '" alt="' . esc_attr( $cfg['title'] ) . '" width="300" height="300" class="npu-cart-thumb attachment-woocommerce_thumbnail" />';
}

/* ---- Paket u kosarici: ime stavke, zakljucana kolicina ---- */

add_filter( 'woocommerce_cart_item_name', 'noriks_pp_upsell_cart_item_name', 20, 3 );
function noriks_pp_upsell_cart_item_name( $name, $cart_item, $cart_item_key ) {
	if ( ! empty( $cart_item['_noriks_pp_upsell'] ) && ! empty( $cart_item['_noriks_pp_upsell_title'] ) ) {
		return esc_html( $cart_item['_noriks_pp_upsell_title'] );
	}
	return $name;
}

/* Kolicina se ne moze mijenjati — prikazuje se samo broj komada u paketu. */
add_filter( 'woocommerce_cart_item_quantity', 'noriks_pp_upsell_cart_item_quantity', 20, 3 );
function noriks_pp_upsell_cart_item_quantity( $html, $cart_item_key, $cart_item ) {
	if ( ! empty( $cart_item['_noriks_pp_upsell'] ) ) {
		$qty = (int) ( $cart_item['_noriks_pp_upsell_qty'] ?? 1 );
		return '<span class="npu-cart-qty">' . esc_html( $qty ) . '</span>';
	}
	return $html;
}

/* Vrati custom podatke stavke iz sesije. */
add_filter( 'woocommerce_get_cart_item_from_session', 'noriks_pp_upsell_from_session', 20, 2 );
function noriks_pp_upsell_from_session( $cart_item, $values ) {
	if ( ! empty( $values['_noriks_pp_upsell'] ) ) {
		$cart_item['_noriks_pp_upsell']       = $values['_noriks_pp_upsell'];
		$cart_item['_noriks_pp_upsell_unit']  = $values['_noriks_pp_upsell_unit'] ?? null;
		$cart_item['_noriks_pp_upsell_qty']   = $values['_noriks_pp_upsell_qty'] ?? 1;
		$cart_item['_noriks_pp_upsell_title'] = $values['_noriks_pp_upsell_title'] ?? '';
		$cart_item['_noriks_pp_upsell_lines'] = $values['_noriks_pp_upsell_lines'] ?? array();
		$cart_item['_noriks_pp_upsell_sku']   = $values['_noriks_pp_upsell_sku'] ?? '';
	}
	return $cart_item;
}

/* Upsell cijena u košarici. */
add_action( 'woocommerce_before_calculate_totals', 'noriks_pp_upsell_apply_price', 25 );
function noriks_pp_upsell_apply_price( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	if ( ! $cart instanceof WC_Cart ) {
		return;
	}
	foreach ( $cart->get_cart() as $item ) {
		if ( ! empty( $item['_noriks_pp_upsell'] ) && isset( $item['_noriks_pp_upsell_unit'] ) && $item['data'] instanceof WC_Product ) {
			$item['data']->set_price( (float) $item['_noriks_pp_upsell_unit'] );
			// Upsell paket ima svojo sifro; sifra variacije izdelka je zavajajoca.
			if ( ! empty( $item['_noriks_pp_upsell_sku'] ) ) {
				$item['data']->set_sku( (string) $item['_noriks_pp_upsell_sku'] );
			}
		}
	}
}

/* ============================================================
 * 5) Oznaka u narudžbi (isto kao sidecart / thank you upsell)
 * ============================================================ */
add_action( 'woocommerce_checkout_create_order_line_item', 'noriks_pp_upsell_order_item_meta', 20, 4 );
function noriks_pp_upsell_order_item_meta( $item, $cart_item_key, $values, $order ) {
	if ( ! empty( $values['_noriks_pp_upsell'] ) ) {
		$item->add_meta_data( '_noriks_upsell', 'product_page_upsell', true );
		// Paket: kolicina stavke je 1, pa broj komada mora biti jasno zapisan u narudzbi.
		if ( ! empty( $values['_noriks_pp_upsell_title'] ) ) {
			$item->set_name( (string) $values['_noriks_pp_upsell_title'] );
		}
		$item->add_meta_data( '_noriks_upsell_pieces', (int) ( $values['_noriks_pp_upsell_qty'] ?? 1 ), true );
		$cfg_sku = noriks_pp_upsell_config();
		if ( ! empty( $cfg_sku['sku'] ) ) {
			// Interna oznaka paketa — po istoj SKU konvenciji kao bundle proizvodi.
			$item->add_meta_data( '_noriks_upsell_sku', sanitize_text_field( $cfg_sku['sku'] ), true );
		}
		// Sadrzaj paketa u narudzbi, isto numerirano kao kod orto bundlea.
		if ( ! empty( $values['_noriks_pp_upsell_lines'] ) && is_array( $values['_noriks_pp_upsell_lines'] ) ) {
			foreach ( array_values( $values['_noriks_pp_upsell_lines'] ) as $i => $line ) {
				$item->add_meta_data( (string) ( $i + 1 ), sanitize_text_field( $line ), true );
			}
		}
	}
}

/* ============================================================
 * UPSELL #2 — paket 2 majice (1 crna + 1 siva), jedna veličina za obje.
 * Potpuno neovisan o prvom upsellu: vlastiti ACF prekidač (noriks_pp_upsell2),
 * vlastita konfiguracija i vlastita logika košarice. Dijeli samo CSS.
 * ============================================================ */

function noriks_pp_upsell2_config() {
	return apply_filters( 'noriks_pp_upsell2_config', array(
		'product_a'  => 250,   // Crna majica (varijabilni proizvod) — nosi stavku u košarici
		'product_b'  => 471,   // Siva majica (varijabilni proizvod)
		'total'      => 16.99, // cijena cijelog paketa (2 komada)
		'title'      => '1x Black + 1x Gray t-shirt',
		'desc'       => 'Two essential t-shirts in a bundle — add them to your order at %s%% off.',
		'size_attr'  => 'Size',
		'sku'        => 'NORIKS-SHIRTS-BLACK-GRAY-2-PACK-UPSELL',
		'image'      => get_template_directory_uri() . '/img/upsell/upsell-2x-majici.png',
		'label'      => 'Buy together and save:',
		'add_text'   => 'Add to purchase',
		'aria'       => 'T-shirt size',
	) );
}

/** Je li upsell #2 uključen za dani proizvod. */
function noriks_pp_upsell2_enabled( $product_id = 0 ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! function_exists( 'get_field' ) ) {
		return false;
	}
	$cfg = noriks_pp_upsell2_config();
	if ( in_array( $product_id, array( (int) $cfg['product_a'], (int) $cfg['product_b'] ), true ) ) {
		return false; // nikad na samim upsell proizvodima
	}
	return (bool) get_field( 'noriks_pp_upsell2', $product_id );
}

/** Veličine dostupne kod OBA proizvoda -> array( velicina => array(vid_a, vid_b) ). */
function noriks_pp_upsell2_sizes() {
	$cfg = noriks_pp_upsell2_config();
	$map = array();
	foreach ( array( 'a' => $cfg['product_a'], 'b' => $cfg['product_b'] ) as $slot => $pid ) {
		$prod = wc_get_product( (int) $pid );
		if ( ! $prod || ! $prod->is_type( 'variable' ) ) {
			return array();
		}
		foreach ( $prod->get_children() as $vid ) {
			$var = wc_get_product( $vid );
			if ( ! $var || ! $var->is_in_stock() || ! $var->is_purchasable() ) {
				continue;
			}
			$size = $var->get_attribute( $cfg['size_attr'] );
			if ( $size === '' ) {
				$attrs = $var->get_variation_attributes();
				$size  = $attrs ? (string) reset( $attrs ) : '';
			}
			if ( $size !== '' && ! isset( $map[ $size ][ $slot ] ) ) {
				$map[ $size ][ $slot ] = (int) $vid;
			}
		}
	}
	// zadrži samo veličine koje postoje kod OBA proizvoda
	return array_filter( $map, function( $v ) { return isset( $v['a'], $v['b'] ); } );
}

add_action( 'woocommerce_after_add_to_cart_button', 'noriks_pp_upsell2_render', 16 );
function noriks_pp_upsell2_render() {
	if ( ! noriks_pp_upsell2_enabled() ) {
		return;
	}
	$cfg   = noriks_pp_upsell2_config();
	$sizes = noriks_pp_upsell2_sizes();
	if ( empty( $sizes ) ) {
		return;
	}

	// Redovna cijena = redovna cijena obje majice zajedno.
	$regular_total = 0.0;
	foreach ( array( 'a', 'b' ) as $slot ) {
		foreach ( $sizes as $pair ) {
			$var = wc_get_product( $pair[ $slot ] );
			if ( $var ) { $regular_total += (float) $var->get_regular_price(); }
			break;
		}
	}
	$discount = ( $regular_total > 0 ) ? (int) round( ( 1 - ( (float) $cfg['total'] / $regular_total ) ) * 100 ) : 0;
	$desc     = ( strpos( $cfg['desc'], '%s' ) !== false ) ? sprintf( $cfg['desc'], $discount ) : $cfg['desc'];
	$image    = $cfg['image'] ? $cfg['image'] : wc_placeholder_img_src( 'medium' );
	noriks_pp_upsell_styles();
	?>
	<div class="npu-wrap">
		<span class="npu-label"><?php echo esc_html( $cfg['label'] ); ?></span>

		<div class="npu-box" id="npu2-box">
			<div class="npu-grid">
				<span class="npu-img-wrap">
					<img class="npu-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cfg['title'] ); ?>" loading="lazy">
				</span>

				<div class="npu-info">
					<p class="npu-title"><?php echo esc_html( $cfg['title'] ); ?></p>
					<div class="npu-desc"><?php echo esc_html( $desc ); ?></div>
					<div class="npu-prices">
						<span class="npu-price"><?php echo wc_price( (float) $cfg['total'] ); ?></span>
						<?php if ( $regular_total > 0 ) : ?>
							<span class="npu-price-old"><?php echo wc_price( $regular_total ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="npu-actions">
					<label class="npu-check">
						<input type="checkbox" id="npu2-toggle" name="noriks_pp_upsell2" value="1">
						<span class="npu-box-mark" aria-hidden="true"></span>
						<span class="npu-check-text"><?php echo esc_html( $cfg['add_text'] ); ?></span>
					</label>

					<select class="npu-size" name="noriks_pp_upsell2_size" aria-label="<?php echo esc_attr( $cfg['aria'] ); ?>">
						<?php foreach ( array_keys( $sizes ) as $size ) : ?>
							<option value="<?php echo esc_attr( $size ); ?>"><?php echo esc_html( $size ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<script>
	(function () {
		var box = document.getElementById('npu2-box');
		var cb  = document.getElementById('npu2-toggle');
		if (!box || !cb) { return; }
		function paint() { box.classList.toggle('npu-checked', cb.checked); }
		cb.addEventListener('change', paint);
		paint();
		box.addEventListener('click', function (e) {
			if (e.target.closest('.npu-size') || e.target.closest('.npu-check')) { return; }
			cb.checked = !cb.checked;
			cb.dispatchEvent(new Event('change', { bubbles: true }));
		});
		/* velicina se uskladi s prvim izbornikom u paketu, dok je kupac sam ne promijeni */
		var sel = box.querySelector('.npu-size'), touched = false;
		if (sel) {
			sel.addEventListener('change', function () { touched = true; });
			var sync = function () {
				if (touched) { return; }
				var all = document.querySelectorAll('.gck-size-select'), src = null;
				for (var i = 0; i < all.length; i++) { if ((function (s) { if (!s || !s.value) { return false; } var w = s.closest ? s.closest('.bundle-pairs') : null; if (w && w.classList.contains('hidden')) { return false; } if (s.offsetParent !== null) { return true; } return !!(s.closest && s.closest('#bundle-selector')); })(all[i])) { src = all[i]; break; } }
				if (!src) { return; }
				var v = String(src.value).trim().toLowerCase();
				for (var j = 0; j < sel.options.length; j++) {
					if (String(sel.options[j].value).trim().toLowerCase() === v) { sel.value = sel.options[j].value; return; }
				}
			};
			document.addEventListener('change', function (e) {
				var t = e.target; if (!t) { return; }
				if (t.classList && t.classList.contains('gck-size-select')) { sync(); }
				if (t.name === 'bundle_option') { setTimeout(sync, 60); }
			});
			setTimeout(sync, 250); setTimeout(sync, 900);
		}
	})();
	</script>
	<?php
}

/* ---- Dodavanje paketa u košaricu ---- */
add_action( 'woocommerce_add_to_cart', 'noriks_pp_upsell2_maybe_add', 21, 6 );
function noriks_pp_upsell2_maybe_add( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	static $busy2 = false;
	if ( $busy2 || ! empty( $cart_item_data['_noriks_pp_upsell2'] ) || ! empty( $cart_item_data['_noriks_pp_upsell'] ) ) {
		return;
	}
	if ( empty( $_POST['noriks_pp_upsell2'] ) || ! noriks_pp_upsell2_enabled( $product_id ) ) {
		return;
	}
	$cfg   = noriks_pp_upsell2_config();
	$sizes = noriks_pp_upsell2_sizes();
	if ( empty( $sizes ) ) {
		return;
	}
	$size = isset( $_POST['noriks_pp_upsell2_size'] ) ? sanitize_text_field( wp_unslash( $_POST['noriks_pp_upsell2_size'] ) ) : '';
	if ( $size === '' || ! isset( $sizes[ $size ] ) ) {
		$size = (string) key( $sizes );
	}
	$vid_a = (int) $sizes[ $size ]['a'];
	$var_a = wc_get_product( $vid_a );
	$var_b = wc_get_product( (int) $sizes[ $size ]['b'] );
	if ( ! $var_a || ! $var_b ) {
		return;
	}

	$prod_a = wc_get_product( (int) $cfg['product_a'] );
	$prod_b = wc_get_product( (int) $cfg['product_b'] );
	$lines  = array(
		( $prod_a ? $prod_a->get_name() : 'Black t-shirt' ) . ' - ' . $size,
		( $prod_b ? $prod_b->get_name() : 'Gray t-shirt' ) . ' - ' . $size,
	);

	// PAKET: jedna stavka (količina 1) s cijenom cijelog paketa.
	$busy2 = true;
	WC()->cart->add_to_cart(
		(int) $cfg['product_a'],
		1,
		$vid_a,
		$var_a->get_variation_attributes(),
		array(
			'_noriks_pp_upsell2'      => 1,
			'_noriks_pp_upsell'       => 1,   // dijeli prikaz/cijenu s prvim upsellom
			'_noriks_pp_upsell_unit'  => (float) $cfg['total'],
			'_noriks_pp_upsell_qty'   => 2,
			'_noriks_pp_upsell_title' => (string) $cfg['title'],
			'_noriks_pp_upsell_lines' => $lines,
			'_noriks_pp_upsell_sku'   => (string) $cfg['sku'],
			'_noriks_pp_upsell_key'   => md5( 'npu2' . $vid_a . microtime( true ) ),
		)
	);
	$busy2 = false;
}

/* Slika paketa u košarici (upsell #2). */
add_filter( 'woocommerce_cart_item_thumbnail', 'noriks_pp_upsell2_cart_thumb', 21, 3 );
function noriks_pp_upsell2_cart_thumb( $thumbnail, $cart_item, $cart_item_key ) {
	if ( empty( $cart_item['_noriks_pp_upsell2'] ) ) {
		return $thumbnail;
	}
	$cfg = noriks_pp_upsell2_config();
	if ( empty( $cfg['image'] ) ) {
		return $thumbnail;
	}
	return '<img src="' . esc_url( $cfg['image'] ) . '" alt="' . esc_attr( $cfg['title'] ) . '" width="300" height="300" class="npu-cart-thumb attachment-woocommerce_thumbnail" />';
}

/* Prenos podataka iz sesije + oznaka u narudžbi. */
add_filter( 'woocommerce_get_cart_item_from_session', 'noriks_pp_upsell2_from_session', 21, 2 );
function noriks_pp_upsell2_from_session( $cart_item, $values ) {
	if ( ! empty( $values['_noriks_pp_upsell2'] ) ) {
		$cart_item['_noriks_pp_upsell2']    = 1;
		$cart_item['_noriks_pp_upsell_sku'] = $values['_noriks_pp_upsell_sku'] ?? '';
	}
	return $cart_item;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'noriks_pp_upsell2_order_item_meta', 21, 4 );
function noriks_pp_upsell2_order_item_meta( $item, $cart_item_key, $values, $order ) {
	if ( ! empty( $values['_noriks_pp_upsell2'] ) ) {
		$item->update_meta_data( '_noriks_upsell', 'product_page_upsell_2' );
		$cfg = noriks_pp_upsell2_config();
		$item->update_meta_data( '_noriks_upsell_sku', sanitize_text_field( $cfg['sku'] ) );
	}
}

/* CSS izpisemo enkrat, ne glede na to kateri upsell je vklopljen. */
function noriks_pp_upsell_styles() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	?>	<style>
	/* Sve vrijednosti eksplicitne — tema inače nameće svoju boju (#6d6d6d),
	   liniju (1.618) i težinu (400) pa je izgled odstupao od reference. */
	.npu-wrap {
		--npu-accent: #ff5b01;
		--npu-accent-light: #ffeee8;
		--npu-ink: #1a1a1a;
		margin: 18px 0 6px;
	}
	.npu-wrap .npu-label {
		display: block; font-size: 16px !important; font-weight: 400 !important;
		line-height: 1.4 !important; color: var(--npu-ink) !important; margin: 0 0 8px;
	}
	.npu-wrap .npu-box {
		border: 2px solid var(--npu-accent);
		border-radius: 6px;
		box-shadow: 0 2px 3px 0 #00000029;
		padding: 8px;
		background-color: #fafafb;   /* svjetlije od podloge slike (#f2f2f4) */
		color: var(--npu-ink);
		font-size: 16px;
		line-height: 1.4;
		transition: background-color .15s ease;
	}
	.npu-wrap .npu-box.npu-checked { background-color: var(--npu-accent-light); }
	.npu-wrap .npu-grid { display: grid; grid-template-columns: auto minmax(0,1fr); column-gap: 10px; row-gap: 10px; }
	/* slika je unutar okvira s razmakom (kao na referenci), kvadratna */
	.npu-wrap .npu-img-wrap {
		grid-column: 1 / 2; grid-row: 1 / 3; align-self: center;
		width: clamp(104px, 30vw, 150px);
		background-color: #f2f2f4;
		border-radius: 6px;
		overflow: hidden;
		display: block;
	}
	.npu-wrap .npu-img {
		display: block; width: 100%; height: auto; aspect-ratio: 1 / 1;
		object-fit: cover; border-radius: 0;
	}
	.npu-wrap .npu-info { grid-column: 2 / -1; }   /* auto-placement -> 1. red */
	.npu-wrap .npu-title {
		font-size: 17px !important; font-weight: 700 !important; line-height: 1.3 !important;
		color: var(--npu-ink) !important; margin: 0 0 6px !important; padding: 0;
	}
	.npu-wrap .npu-desc {
		font-size: 15px !important; line-height: 1.45 !important; color: #3d3d3d !important; margin: 0;
	}
	.npu-wrap .npu-prices { margin-top: 10px; line-height: 1; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
	.npu-wrap .npu-price {
		display: inline-block; font-size: 17px !important; font-weight: 700 !important; line-height: 1.15 !important;
		color: #fff !important; background-color: var(--npu-accent);
		border-radius: 6px; padding: 6px 11px 5px; white-space: nowrap;
	}
	.npu-wrap .npu-price .woocommerce-Price-amount,
	.npu-wrap .npu-price bdi { color: #fff !important; font-weight: 700 !important; }
	.npu-wrap .npu-price-old {
		font-size: 16px !important; font-weight: 600 !important; line-height: 1.15 !important;
		color: var(--npu-ink) !important; text-decoration: line-through;
	}
	.npu-wrap .npu-price-old .woocommerce-Price-amount,
	.npu-wrap .npu-price-old bdi { color: var(--npu-ink) !important; text-decoration: line-through; }
	.npu-wrap .npu-box p:last-child { margin: 0; }

	.npu-wrap .npu-actions {
		grid-column: 2 / -1;                    /* bez grid-row: auto-placement -> 2. red */
		display: flex; align-items: center; justify-content: flex-start;
		gap: 14px; flex-wrap: wrap;
	}
	.npu-wrap .npu-check { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; margin: 0; padding: 0; }
	.npu-wrap .npu-check input[type="checkbox"] { position: absolute; opacity: 0; width: 0; height: 0; }
	.npu-wrap .npu-box-mark {
		width: 30px; height: 30px; flex: 0 0 30px; display: inline-block; position: relative;
		background-color: #fff; border: 2px solid #d9d9d9; border-radius: 7px; box-sizing: border-box;
	}
	/* oznaceno stanje: puni narancasti kvadrat s bijelom kvacicom, tocno centrirano */
	.npu-wrap .npu-box-mark::before {
		content: ""; position: absolute; left: 50%; top: 46%;
		width: 7px; height: 13px; box-sizing: border-box;
		border: solid #fff; border-width: 0 3px 3px 0; border-radius: 1px;
		transform: translate(-50%, -50%) rotate(45deg);
		opacity: 0; transition: opacity .12s ease;
		-webkit-backface-visibility: hidden;
	}
	.npu-wrap .npu-check input[type="checkbox"]:checked + .npu-box-mark {
		background-color: var(--npu-accent); border-color: var(--npu-accent);
	}
	.npu-wrap .npu-check input[type="checkbox"]:checked + .npu-box-mark::before { opacity: 1; }
	.npu-wrap .npu-check input[type="checkbox"]:focus-visible + .npu-box-mark { outline: 2px solid var(--npu-accent); outline-offset: 2px; }
	.npu-wrap .npu-check-text {
		font-size: 16px !important; font-weight: 700 !important; line-height: 1.2 !important;
		color: var(--npu-ink) !important;
	}

	/* izbornik veličine — stil postojećih izbornika u bundle selectoru,
	   visina usklađena s kvačicom (30px) da red bude poravnat */
	.npu-wrap .npu-size {
		flex: 0 0 auto; max-width: 150px; min-width: 84px;
		/* visina in tipografija enaki kot pri izbirnikih velikosti v paketu (izmerjeno: 35.1px / 18px) */
		height: 35px; line-height: 1.2; box-sizing: border-box;
		margin: 0; padding: 1px 28px 1px 11px; border-radius: 6px; border: 2px solid #ff6d2e;
		background-color: #ffffff; font-size: 18px !important; font-weight: 700 !important; color: #333 !important;
		appearance: none; -webkit-appearance: none; -moz-appearance: none;
		background-image: linear-gradient(45deg, transparent 50%, #444 50%),
		                  linear-gradient(135deg, #444 50%, transparent 50%);
		background-position: calc(100% - 15px) 50%, calc(100% - 10px) 50%;
		background-size: 6px 6px, 6px 6px; background-repeat: no-repeat;
	}
	.npu-wrap .npu-size:focus { outline: 2px solid var(--npu-accent); outline-offset: 1px; }

	@media (max-width: 560px) {
		/* mobitel: slika poravnata s vrhom naslova, kvacica u tekstualnom stupcu,
		   a kvacica i izbornik zadrzavaju ISTE dimenzije kao na desktopu */
		.npu-wrap .npu-box { padding: 8px; }
		.npu-wrap .npu-grid { column-gap: 10px; row-gap: 8px; }
		.npu-wrap .npu-img-wrap { grid-row: 1 / 3; align-self: start; width: clamp(112px, 34vw, 148px); }
		.npu-wrap .npu-info { grid-column: 2 / -1; }
		.npu-wrap .npu-actions { grid-column: 2 / -1; gap: 10px; flex-wrap: wrap; }
		.npu-wrap .npu-title { font-size: 15px !important; line-height: 1.25 !important; margin: 0 0 5px !important; }
		.npu-wrap .npu-desc { font-size: 13.5px !important; line-height: 1.35 !important; }
		.npu-wrap .npu-prices { margin-top: 8px; gap: 8px; }
		.npu-wrap .npu-price { font-size: 15px !important; padding: 5px 9px 4px; }
		.npu-wrap .npu-price-old { font-size: 14px !important; }
	}
	</style>
	<?php
}
/* ============================================================
 * UPSELL #3 — NORIKS FIT kompresijska majica (KOMPSFIT).
 * Neovisan o prva dva: vlastiti ACF prekidač (noriks_pp_upsell3),
 * vlastita konfiguracija i logika košarice. Dijeli samo CSS.
 *
 * KOMPSFIT je JEDNOSTAVAN proizvod (bez varijacija) — veličine dolaze iz
 * atributa proizvoda, pa se odabrana veličina sprema kao meta stavke.
 * ============================================================ */

function noriks_pp_upsell3_config() {
	return apply_filters( 'noriks_pp_upsell3_config', array(
		'product_id' => 0,   // KOMPSFIT na EN se ne obstaja — vpisi ID, ko bo izdelek dodan   // NORIKS FIT kompresijska majica (jednostavan proizvod)
		'total'      => 17.99,  // cijena za 1 komad
		'title'      => 'NORIKS FIT compression t-shirt',
		'desc'       => 'Shapes your silhouette and supports posture — add it to your order at %s%% off.',
		'size_attr'  => 'Size',
		'sizes'      => array( 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL' ), // upsell nudi S–4XL
		'sku'        => 'NORIKS-KOMPSFIT-1-UPSELL',
		'image'      => '', // prazno => koristi se glavna slika proizvoda
		'label'      => 'Buy together and save:',
		'add_text'   => 'Add to purchase',
		'aria'       => 'T-shirt size',
	) );
}

/** Je li upsell #3 uključen za dani proizvod. */
function noriks_pp_upsell3_enabled( $product_id = 0 ) {
	$product_id = $product_id ? (int) $product_id : (int) get_the_ID();
	if ( ! $product_id || ! function_exists( 'get_field' ) ) {
		return false;
	}
	$cfg = noriks_pp_upsell3_config();
	if ( $product_id === (int) $cfg['product_id'] ) {
		return false; // nikad na samom upsell proizvodu
	}
	return (bool) get_field( 'noriks_pp_upsell3', $product_id );
}

/** Veličine iz atributa proizvoda (jednostavan proizvod nema varijacije). */
function noriks_pp_upsell3_sizes() {
	$cfg = noriks_pp_upsell3_config();
	// Ponuda je izricito S–4XL (5XL se ne nudi u upsellu).
	if ( ! empty( $cfg['sizes'] ) && is_array( $cfg['sizes'] ) ) {
		return array_values( $cfg['sizes'] );
	}
	$prod = wc_get_product( (int) $cfg['product_id'] );
	if ( ! $prod ) {
		return array();
	}
	$raw = $prod->get_attribute( $cfg['size_attr'] );
	if ( $raw === '' ) {
		return array();
	}
	$sizes = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
	return array_values( $sizes );
}

add_action( 'woocommerce_after_add_to_cart_button', 'noriks_pp_upsell3_render', 17 );
function noriks_pp_upsell3_render() {
	if ( ! noriks_pp_upsell3_enabled() ) {
		return;
	}
	$cfg   = noriks_pp_upsell3_config();
	$sizes = noriks_pp_upsell3_sizes();
	$prod  = wc_get_product( (int) $cfg['product_id'] );
	if ( empty( $sizes ) || ! $prod ) {
		return;
	}

	$regular  = (float) $prod->get_regular_price();
	$discount = ( $regular > 0 ) ? (int) round( ( 1 - ( (float) $cfg['total'] / $regular ) ) * 100 ) : 0;
	$desc     = ( strpos( $cfg['desc'], '%s' ) !== false ) ? sprintf( $cfg['desc'], $discount ) : $cfg['desc'];
	$image    = ! empty( $cfg['image'] ) ? $cfg['image'] : wp_get_attachment_image_url( $prod->get_image_id(), 'medium' );
	if ( ! $image ) {
		$image = wc_placeholder_img_src( 'medium' );
	}
	noriks_pp_upsell_styles();
	?>
	<div class="npu-wrap">
		<span class="npu-label"><?php echo esc_html( $cfg['label'] ); ?></span>

		<div class="npu-box" id="npu3-box">
			<div class="npu-grid">
				<span class="npu-img-wrap">
					<img class="npu-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cfg['title'] ); ?>" loading="lazy">
				</span>

				<div class="npu-info">
					<p class="npu-title"><?php echo esc_html( $cfg['title'] ); ?></p>
					<div class="npu-desc"><?php echo esc_html( $desc ); ?></div>
					<div class="npu-prices">
						<span class="npu-price"><?php echo wc_price( (float) $cfg['total'] ); ?></span>
						<?php if ( $regular > 0 ) : ?>
							<span class="npu-price-old"><?php echo wc_price( $regular ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="npu-actions">
					<label class="npu-check">
						<input type="checkbox" id="npu3-toggle" name="noriks_pp_upsell3" value="1">
						<span class="npu-box-mark" aria-hidden="true"></span>
						<span class="npu-check-text"><?php echo esc_html( $cfg['add_text'] ); ?></span>
					</label>

					<select class="npu-size" name="noriks_pp_upsell3_size" aria-label="<?php echo esc_attr( $cfg['aria'] ); ?>">
						<?php foreach ( $sizes as $size ) : ?>
							<option value="<?php echo esc_attr( $size ); ?>"><?php echo esc_html( $size ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<script>
	(function () {
		var box = document.getElementById('npu3-box');
		var cb  = document.getElementById('npu3-toggle');
		if (!box || !cb) { return; }
		function paint() { box.classList.toggle('npu-checked', cb.checked); }
		cb.addEventListener('change', paint);
		paint();
		box.addEventListener('click', function (e) {
			if (e.target.closest('.npu-size') || e.target.closest('.npu-check')) { return; }
			cb.checked = !cb.checked;
			cb.dispatchEvent(new Event('change', { bubbles: true }));
		});
		/* velicina se uskladi s prvim izbornikom u paketu, dok je kupac sam ne promijeni */
		var sel = box.querySelector('.npu-size'), touched = false;
		if (sel) {
			sel.addEventListener('change', function () { touched = true; });
			var sync = function () {
				if (touched) { return; }
				var all = document.querySelectorAll('.gck-size-select'), src = null;
				for (var i = 0; i < all.length; i++) { if ((function (s) { if (!s || !s.value) { return false; } var w = s.closest ? s.closest('.bundle-pairs') : null; if (w && w.classList.contains('hidden')) { return false; } if (s.offsetParent !== null) { return true; } return !!(s.closest && s.closest('#bundle-selector')); })(all[i])) { src = all[i]; break; } }
				if (!src) { return; }
				var v = String(src.value).trim().toLowerCase();
				for (var j = 0; j < sel.options.length; j++) {
					if (String(sel.options[j].value).trim().toLowerCase() === v) { sel.value = sel.options[j].value; return; }
				}
			};
			document.addEventListener('change', function (e) {
				var t = e.target; if (!t) { return; }
				if (t.classList && t.classList.contains('gck-size-select')) { sync(); }
				if (t.name === 'bundle_option') { setTimeout(sync, 60); }
			});
			setTimeout(sync, 250); setTimeout(sync, 900);
		}
	})();
	</script>
	<?php
}

/* ---- Dodavanje u košaricu ---- */
add_action( 'woocommerce_add_to_cart', 'noriks_pp_upsell3_maybe_add', 22, 6 );
function noriks_pp_upsell3_maybe_add( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	static $busy3 = false;
	if ( $busy3 || ! empty( $cart_item_data['_noriks_pp_upsell'] ) ) {
		return;
	}
	if ( empty( $_POST['noriks_pp_upsell3'] ) || ! noriks_pp_upsell3_enabled( $product_id ) ) {
		return;
	}
	$cfg   = noriks_pp_upsell3_config();
	$sizes = noriks_pp_upsell3_sizes();
	if ( empty( $sizes ) ) {
		return;
	}
	$size = isset( $_POST['noriks_pp_upsell3_size'] ) ? sanitize_text_field( wp_unslash( $_POST['noriks_pp_upsell3_size'] ) ) : '';
	if ( $size === '' || ! in_array( $size, $sizes, true ) ) {
		$size = (string) $sizes[0];
	}
	$prod = wc_get_product( (int) $cfg['product_id'] );
	if ( ! $prod ) {
		return;
	}

	$busy3 = true;
	WC()->cart->add_to_cart(
		(int) $cfg['product_id'],
		1,
		0,
		array(),
		array(
			'_noriks_pp_upsell3'      => 1,
			'_noriks_pp_upsell'       => 1,   // dijeli prikaz/cijenu s prvim upsellom
			'_noriks_pp_upsell_unit'  => (float) $cfg['total'],
			'_noriks_pp_upsell_qty'   => 1,
			'_noriks_pp_upsell_title' => (string) $cfg['title'],
			'_noriks_pp_upsell_lines' => array( $prod->get_name() . ' - ' . $size ),
			'_noriks_pp_upsell_size'  => $size,
			'_noriks_pp_upsell_sku'   => (string) $cfg['sku'],
			'_noriks_pp_upsell_key'   => md5( 'npu3' . $size . microtime( true ) ),
		)
	);
	$busy3 = false;
}

/* Prenos podataka iz sesije + oznaka u narudžbi. */
add_filter( 'woocommerce_get_cart_item_from_session', 'noriks_pp_upsell3_from_session', 22, 2 );
function noriks_pp_upsell3_from_session( $cart_item, $values ) {
	if ( ! empty( $values['_noriks_pp_upsell3'] ) ) {
		$cart_item['_noriks_pp_upsell3']     = 1;
		$cart_item['_noriks_pp_upsell_size'] = $values['_noriks_pp_upsell_size'] ?? '';
		$cart_item['_noriks_pp_upsell_sku']  = $values['_noriks_pp_upsell_sku'] ?? '';
	}
	return $cart_item;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'noriks_pp_upsell3_order_item_meta', 22, 4 );
function noriks_pp_upsell3_order_item_meta( $item, $cart_item_key, $values, $order ) {
	if ( ! empty( $values['_noriks_pp_upsell3'] ) ) {
		$item->update_meta_data( '_noriks_upsell', 'product_page_upsell_3' );
		$cfg = noriks_pp_upsell3_config();
		$item->update_meta_data( '_noriks_upsell_sku', sanitize_text_field( $cfg['sku'] ) );
		if ( ! empty( $values['_noriks_pp_upsell_size'] ) ) {
			$item->update_meta_data( 'Size', sanitize_text_field( $values['_noriks_pp_upsell_size'] ) );
		}
	}
}
