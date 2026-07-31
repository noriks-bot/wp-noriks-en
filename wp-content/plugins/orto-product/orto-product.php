
<?php
/*
Plugin Name: Orto Bundle Selector
Description: Custom bundle radio buttons for Orto products (multiple pairs, supports 2 or 4+ custom attributes like 2 colors + 2 sizes).
Version: 3.3
Author: Ante
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ============================================================
// OFFERS (ACF repeater)
// ============================================================

/**
 * Build bundle offers dynamically from ACF repeater _singlepp_orto_pairs.
 * Uses:
 *  - cena_1 = regular price
 *  - cena_2 = sale price (actual price)
 * Also reads:
 *  - p1, p2 (labels for 4-attribute UI headings + cart meta grouping)
 *  - tip (mixed|majica|bokserica|empty) -> controls which selectors appear in 4-attr products
 */
function gck_get_bundle_offers( $product_id = null ) : array {

    if ( ! $product_id ) {
        $product_id = get_the_ID();
    }
    if ( ! $product_id ) {
        return [];
    }

    $rows = get_field('_singlepp_orto_pairs', $product_id);
    if ( empty($rows) || ! is_array($rows) ) {
        return [];
    }

    $offers = [];
    $idx    = 0;

    foreach ( $rows as $row ) {
        $idx++;

        $qty = isset($row['qty']) ? (int) $row['qty'] : 0;
        if ( $qty <= 0 ) continue;

        $tekst1 = trim((string)($row['tekst_1'] ?? ''));
        $title  = trim($tekst1 . ' ');

        $regular = isset($row['cena_1']) ? (float) $row['cena_1'] : 0;
        $sale    = isset($row['cena_2']) ? (float) $row['cena_2'] : 0;
        if ( $sale <= 0 ) continue;

        $saving_amount = $regular - $sale;
        $saving_text   = "Ukupna ušteda " . number_format($saving_amount, 2, '.', '') . "€";

        $p1  = trim((string)($row['p1'] ?? ''));
        $p2  = trim((string)($row['p2'] ?? ''));
        $tip = trim((string)($row['tip'] ?? '')); // NEW

        // unique offer id per row
        $offer_id = $qty . '__' . $idx;

        // qty=1 exact
        if ( $qty === 1 ) {
            $offers[$offer_id] = [
                "id"     => $offer_id,
                "qty"    => $qty,
                "title"  => $title,
                "per"    => number_format($sale, 2, '.', ''),
                "total"  => number_format($sale, 2, '.', ''),
                "regular" => number_format($regular, 2, '.', ''), // cena_1
                "saving" => $saving_text,
                "p1"     => $p1,
                "p2"     => $p2,
                "tip"    => $tip,
            ];
            continue;
        }

        // multi-item: floor down per item to 2 decimals
        $per_item       = $sale / $qty;
        $per_item_fixed = floor($per_item * 100) / 100;

        $offers[$offer_id] = [
            "id"     => $offer_id,
            "qty"    => $qty,
            "title"  => $title,
            "per"    => number_format($per_item_fixed, 2, '.', ''),
            "total"  => number_format($sale, 2, '.', ''),
            "regular" => number_format($regular, 2, '.', ''), // cena_1
            "saving" => $saving_text,
            "p1"     => $p1,
            "p2"     => $p2,
            "tip"    => $tip,
        ];
    }

    return $offers;
}

function gck_is_orto_bundle_enabled( $product_id ) : bool {
    if ( ! $product_id ) return false;
    $flag = get_post_meta( $product_id, '_singlepp_orto_ison', true );
    return ! empty( $flag );
}

// ============================================================
// ATTRIBUTE HELPERS (supports 2 attrs OR 4 attrs)
// ============================================================

function gck_get_custom_attributes_in_order( $product ) : array {
    $attributes   = $product->get_attributes();
    $custom_attrs = [];

    foreach ( $attributes as $key => $attr ) {
        if ( $attr && ! $attr->is_taxonomy() ) {
            $custom_attrs[ $key ] = $attr;
        }
    }
    return $custom_attrs;
}

function gck_split_attrs_color_size( array $custom_attrs ) : array {
    $colors = [];
    $sizes  = [];
    $others = [];

    foreach ( $custom_attrs as $key => $attr ) {
        $label = method_exists($attr, 'get_name') ? (string) $attr->get_name() : (string) $key;

        $hay = strtolower( $key . ' ' . $label );

        $is_color = ( strpos($hay, 'boja') !== false || strpos($hay, 'barva') !== false || strpos($hay, 'farb') !== false || strpos($hay, 'culoare') !== false || strpos($hay, 'color') !== false || strpos($hay, 'colour') !== false || strpos($hay, 'colore') !== false || strpos($hay, 'kolor') !== false || strpos($hay, 'szín') !== false || strpos($hay, 'szin') !== false || strpos($hay, 'ρώμ') !== false || strpos($hay, 'цвят') !== false );
        $is_size = ( strpos($hay, 'vel') !== false || strpos($hay, 'size') !== false || strpos($hay, 'marime') !== false || strpos($hay, 'mărime') !== false || strpos($hay, 'rozmiar') !== false || strpos($hay, 'taglia') !== false || strpos($hay, 'größe') !== false || strpos($hay, 'grosse') !== false || strpos($hay, 'groesse') !== false || strpos($hay, 'meret') !== false || strpos($hay, 'méret') !== false || strpos($hay, 'εγεθ') !== false || strpos($hay, 'азмер') !== false || strpos($hay, 'размер') !== false );

        $values = $attr->get_options();
        if ( ! is_array($values) ) $values = [];

        $item = [
            'key'       => $key,
            'label'     => $label,
            'field_key' => 'attribute_' . $key,
            'values'    => $values,
        ];

        if ( $is_color && ! $is_size ) {
            $colors[] = $item;
        } elseif ( $is_size && ! $is_color ) {
            $sizes[] = $item;
        } else {
            $others[] = $item;
        }
    }

    // fallback to old behavior if not detected
    if ( empty($colors) && empty($sizes) && count($custom_attrs) >= 2 ) {
        $keys = array_keys($custom_attrs);

        $first  = $custom_attrs[$keys[0]];
        $second = $custom_attrs[$keys[1]];

        $colors[] = [
            'key'       => $keys[0],
            'label'     => (string) $first->get_name(),
            'field_key' => 'attribute_' . $keys[0],
            'values'    => (array) $first->get_options(),
        ];
        $sizes[] = [
            'key'       => $keys[1],
            'label'     => (string) $second->get_name(),
            'field_key' => 'attribute_' . $keys[1],
            'values'    => (array) $second->get_options(),
        ];

        for ( $i = 2; $i < count($keys); $i++ ) {
            $a = $custom_attrs[$keys[$i]];
            $others[] = [
                'key'       => $keys[$i],
                'label'     => (string) $a->get_name(),
                'field_key' => 'attribute_' . $keys[$i],
                'values'    => (array) $a->get_options(),
            ];
        }
    }

    return [
        'colors' => $colors,
        'sizes'  => $sizes,
        'others' => $others,
    ];
}

function gck_attr_group_token( string $label, string $type ) : string {
    $l = strtolower( trim( $label ) );

    if ( $type === 'color' ) {
        $l = str_replace(['boja', 'color', 'colour', ':'], ' ', $l);
    } else {
        $l = str_replace(['size', 'velicina', 'size', ':'], ' ', $l);
    }

    $l = trim( preg_replace('/\s+/', ' ', $l) );
    return sanitize_title( $l );
}

/**
 * Pair color + size into logical rows:
 * Prefer matching tokens, fallback by index.
 */
function gck_pair_color_size_groups( array $colors, array $sizes ) : array {
    $groups = [];

    $sizes_by_token = [];
    $sizes_used     = [];

    foreach ( $sizes as $i => $s ) {
        $tok = gck_attr_group_token( (string)($s['label'] ?? ''), 'size' );
        if ( $tok !== '' ) {
            $sizes_by_token[$tok][] = $i;
        }
    }

    foreach ( $colors as $ci => $c ) {
        $ctok = gck_attr_group_token( (string)($c['label'] ?? ''), 'color' );

        $matched_si = null;

        if ( $ctok !== '' && ! empty($sizes_by_token[$ctok]) ) {
            foreach ( $sizes_by_token[$ctok] as $candidate_si ) {
                if ( empty($sizes_used[$candidate_si]) ) {
                    $matched_si = $candidate_si;
                    break;
                }
            }
        }

        if ( $matched_si === null && isset($sizes[$ci]) && empty($sizes_used[$ci]) ) {
            $matched_si = $ci;
        }

        if ( $matched_si === null ) {
            foreach ( $sizes as $si => $_s ) {
                if ( empty($sizes_used[$si]) ) {
                    $matched_si = $si;
                    break;
                }
            }
        }

        $size_item = null;
        if ( $matched_si !== null && isset($sizes[$matched_si]) ) {
            $size_item = $sizes[$matched_si];
            $sizes_used[$matched_si] = true;
        }

        $groups[] = [
            'token' => $ctok,
            'color' => $c,
            'size'  => $size_item,
        ];
    }

    foreach ( $sizes as $si => $s ) {
        if ( empty($sizes_used[$si]) ) {
            $stok = gck_attr_group_token( (string)($s['label'] ?? ''), 'size' );
            $groups[] = [
                'token' => $stok,
                'color' => null,
                'size'  => $s,
            ];
        }
    }

    return $groups;
}

// ============================================================
// RENDER UI
// ============================================================

// ============================================================
// COUNTDOWN / SCARCITY FIELDS (registered in code)
// ============================================================
add_action( 'acf/init', 'gck_register_orto_countdown_fields' );
function gck_register_orto_countdown_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }
    acf_add_local_field_group( array(
        'key'    => 'group_orto_countdown_options',
        'title'  => 'Orto Bundle – countdown',
        'fields' => array(
            array(
                'key'          => 'field_orto_precheck_second',
                'label'        => 'Preselect the 2nd offer',
                'name'         => 'orto_precheck_second',
                'type'         => 'true_false',
                'instructions' => 'When enabled, the SECOND offer is preselected on page load instead of the first. Applies to this product only.',
                'ui'           => 1,
            ),
            array(
                'key'          => 'field_orto_show_gratis_labels',
                'label'        => 'Show "Free t-shirt" labels',
                'name'         => 'orto_show_gratis_labels',
                'type'         => 'true_false',
                'instructions' => 'Adds labels (Choose N t-shirts, Choose another N free t-shirts ...) above the colour/size selectors. The number of free pieces is read from the offer title, e.g. "2+2".',
                'ui'           => 1,
            ),
            array(
                'key'          => 'field_orto_show_price_highlights',
                'label'        => 'Show price highlight (price per piece + discount)',
                'name'         => 'orto_show_price_highlights',
                'type'         => 'true_false',
                'instructions' => 'Dodaje okvir s cijenom po komadu (precrtana redovna cijena/kom) i zelenu oznaku popusta (−XX%) uz svaku ponudu. Vrijedi samo za ovaj proizvod.',
                'ui'           => 1,
            ),
            array(
                'key'          => 'field_orto_show_countdown',
                'label'        => 'Show countdown (scarcity bar)',
                'name'         => 'orto_show_countdown',
                'type'         => 'true_false',
                'instructions' => 'Shows a scarcity/urgency bar with an evergreen per-visitor countdown above the offers. Applies only to this product.',
                'ui'           => 1,
            ),
            array(
                'key'               => 'field_orto_countdown_minutes',
                'label'             => 'Countdown duration (minutes)',
                'name'              => 'orto_countdown_minutes',
                'type'              => 'number',
                'instructions'      => 'How many minutes the countdown runs per visitor. Default 10. Resets discreetly on the next visit once it expires.',
                'default_value'     => 10,
                'min'               => 1,
                'max'               => 1440,
                'append'            => 'min',
                'conditional_logic' => array(
                    array(
                        array( 'field' => 'field_orto_show_countdown', 'operator' => '==', 'value' => '1' ),
                    ),
                ),
            ),
            array(
                'key'               => 'field_orto_countdown_stock',
                'label'             => 'Remaining stock (pieces)',
                'name'              => 'orto_countdown_stock',
                'type'              => 'number',
                'instructions'      => 'Number shown in the bar ("Only X left"). Set 0 or leave empty to hide it.',
                'default_value'     => 27,
                'min'               => 0,
                'max'               => 9999,
                'append'            => 'pcs',
                'conditional_logic' => array(
                    array(
                        array( 'field' => 'field_orto_show_countdown', 'operator' => '==', 'value' => '1' ),
                    ),
                ),
            ),
        ),
        'location'   => array(
            array(
                array( 'param' => 'post_type', 'operator' => '==', 'value' => 'product' ),
            ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );
}

/**
 * Croatian declension for garment nouns used in gratis labels.
 * Both "majica" (t-shirt) and "bokserica" (boxers) are -ica feminine nouns that
 * decline identically; only the stem differs (majic- vs bokseric-).
 * 1 -icu, 2-4 -ice, 5+ -ica. Free adjective: besplatnu/besplatne/besplatnih.
 *
 * @param int    $n    Count.
 * @param bool   $free Prefix the "free" adjective when true.
 * @param string $type Garment type: 'majica' (default) or 'bokserica'.
 */
function gck_hr_majice_phrase( int $n, bool $free = false, string $type = 'majica' ) : string {
    $m100 = $n % 100;
    $m10  = $n % 10;

    $is1   = ( $m10 === 1 && $m100 !== 11 );
    $is234 = ( in_array( $m10, array( 2, 3, 4 ), true ) && ! in_array( $m100, array( 12, 13, 14 ), true ) );

    if ( $type === 'bokserica' ) {
        $stem = 'bokseric';
    } elseif ( $type === 'carapa' ) {
        $stem = 'čarap'; // čarapu / čarape / čarapa
    } else {
        $stem = 'majic';
    }

    if ( $is1 ) {
        $adj = 'besplatnu'; $noun = $stem . 'u';
    } elseif ( $is234 ) {
        $adj = 'besplatne'; $noun = $stem . 'e';
    } else {
        $adj = 'besplatnih'; $noun = $stem . 'a';
    }

    return $free ? ( $adj . ' ' . $noun ) : $noun;
}

add_action( 'woocommerce_before_add_to_cart_button', 'gck_render_bundle_selector', 5 );

function gck_render_bundle_selector() {

    global $product;

    if ( ! is_product() || ! $product || ! gck_is_orto_bundle_enabled( $product->get_id() ) ) return;

    $product_id = $product->get_id();

    $offers = gck_get_bundle_offers( $product_id );
    if ( empty( $offers ) ) return;

    // Per-product display toggles (registered in code).
    $precheck_second       = (bool) get_field( 'orto_precheck_second', $product_id );
    $show_gratis           = (bool) get_field( 'orto_show_gratis_labels', $product_id );
    $show_price_highlights = (bool) get_field( 'orto_show_price_highlights', $product_id );

    // Garment type for gratis labels: "bokserica" (boxers), "carapa"
    // (compression socks) vs default "majica" (t-shirt). Detect via product
    // category or slug/name so each product gets the correct noun.
    $gck_garment = 'majica';
    if (
        has_term( array( 'orto-bokserice', 'orto-bokserice2' ), 'product_cat', $product_id )
        || ( stripos( (string) $product->get_slug(), 'bokseric' ) !== false )
        || ( stripos( (string) $product->get_name(), 'bokseric' ) !== false )
    ) {
        $gck_garment = 'bokserica';
    } elseif (
        has_term( array( 'orto-kompresijske-carape' ), 'product_cat', $product_id )
        || ( stripos( (string) $product->get_slug(), 'nogavic' ) !== false )
        || ( stripos( (string) $product->get_slug(), 'carap' ) !== false )
        || ( stripos( (string) $product->get_name(), 'čarap' ) !== false )
        || ( stripos( (string) $product->get_name(), 'nogavic' ) !== false )
    ) {
        // Compression SOCKS only. Note: the compression MAJICA (orto-kompresijske-majice)
        // must NOT match here — it keeps the default "majica" noun.
        $gck_garment = 'carapa';
    }

    // Countdown / scarcity element (registered in code, per-product toggle).
    $show_countdown    = (bool) get_field( 'orto_show_countdown', $product_id );
    $countdown_minutes = (int) get_field( 'orto_countdown_minutes', $product_id );
    if ( $countdown_minutes < 1 || $countdown_minutes > 1440 ) {
        $countdown_minutes = 10;
    }
    $countdown_stock = (int) get_field( 'orto_countdown_stock', $product_id );
    if ( $countdown_stock < 0 ) {
        $countdown_stock = 0;
    }

    $custom_attrs = gck_get_custom_attributes_in_order( $product );

    // Special orto products that don't use the standard colour + size selectors:
    //  - orto-bunion  : quantity-only bundle, NO colour and NO size selectors.
    //  - orto-ortopas : single "Veličina" attribute, no colour (size selector only).
    // Every other product keeps the original 2-attribute (colour + size) requirement.
    $gck_no_attrs    = has_term( array( 'orto-bunion', 'orto-fisiorest', 'orto-norikshers', 'orto-noriks-hers', 'orto-ortopedski-jastuk' ), 'product_cat', $product_id );
    $gck_single_size = has_term( array( 'orto-ortopas', 'orto-kidsnest' ), 'product_cat', $product_id );

    // SHGIFTS (orto-majica-darila): the SAME split-garment selector as SHBOX,
    // extended to 3 garment groups (4 majica + 1 bokserica + 1 čarapa). Gated
    // strictly to this category. This site's save/sync collapses product
    // attributes to a single Boja/Veličina, so the 3 groups' colour/size options
    // are sourced from hardcoded arrays fed into the EXACT SHBOX swatch/select
    // markup + CSS (see the render branch below). Detected early and bypasses the
    // attribute guards so it always renders regardless of product attributes.
    $gck_shgifts = has_term( 'orto-majica-darila', 'product_cat', $product_id );

    if ( ! $gck_no_attrs && ! $gck_single_size && ! $gck_shgifts && count( $custom_attrs ) < 2 ) return;

    $split  = gck_split_attrs_color_size( $custom_attrs );
    $colors = $split['colors'];
    $sizes  = $split['sizes'];

    if ( $gck_no_attrs ) {
        // Bunion: quantity-only, render offers with no colour/size pickers at all.
        $colors = array();
        $sizes  = array();
    } elseif ( $gck_single_size ) {
        // Belt only needs a selectable size.
        if ( empty($sizes) ) return;
    } elseif ( ! $gck_shgifts ) {
        if ( empty($colors) || empty($sizes) ) return;
    }

    $attr_groups = gck_pair_color_size_groups( $colors, $sizes );

    // "4-attribute case" in your implementation means we have 2 selector groups (majica + bokserica)
    $show_group_titles = ( count($attr_groups) > 1 );

    // Special product (SKU NORIKS-ORTO-SHBOX): show the two garment groups as separate
    // gratis-style sections ("Izaberi N majice" + "Izaberi još N bokserice gratis")
    // instead of interleaved pairs. Saved data structure stays identical (same field keys/indices).
    $gck_split_garments = ( strtoupper( (string) $product->get_sku() ) === 'NORIKS-ORTO-SHBOX' && count( $attr_groups ) >= 2 );

    ?>
    <style>
      .single_add_to_cart_button {
          font-family: 'Roboto', sans-serif;
          height: 60px;
          width: 100%;
          background: #c00 !important;
          letter-spacing: -0.5px !important;
          border-radius: 8px;
          font-size: 17px !important;
          letter-spacing: 0.2px !important;
          color: white;
          text-transform: uppercase  !important;
      }
      body.single-product .quantity { display: none; }

      .bundle-box { margin: 0 0 15px 0 }
      .bundle-option {
         display: block;
         border: 2px solid #939393;
         background: #f4f4f4b0;
         border-radius: 8px;
         padding: 10px 5px 10px 15px;
         margin-bottom: 12px;
         cursor: pointer;
         position: relative;
         box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
      }
      .bundle-option-title {
          font-weight: 600;
          color: black;
          letter-spacing: 0.3px;
      }
      .bundle-option.active { border-color: #f39c12; background: #f39c1217; }
      .bundle-pairs { margin-top: 10px; padding-top: 10px; border-top: 1px solid #969696; }

      .bundle-pair { display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px; }
      .bundle-attr-row{ display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }

      .gck-group-title{
          width: 100%;
          font-weight: 800;
          font-size: 14px;
          color: #111;
          margin: 2px 0 0 0;
          letter-spacing: .2px;
      }

      .gck-pair-label{
          width: 100%;
          font-weight: 800;
          font-size: 15px;
          color: #111;
          margin: 2px 0 5px 0;
          letter-spacing: .2px;
          line-height: 1.2;
      }
      .gck-pair-label.is-gratis{ color: #c00; margin-top: 12px; }

      .gck-per-chip{
          display: inline-flex;
          align-items: center;
          gap: 6px;
          background: #c00;
          border-radius: 4px;
          padding: 6px 9px;
          margin-left: 4px;
          font-size: 14px;
          font-weight: 600;
          line-height: 1;
          vertical-align: middle;
      }
      .gck-per-old{ color: rgba(255,255,255,0.85); text-decoration: line-through; font-weight: 600; }
      .gck-per-new{ color: #fff; font-weight: 800; }
      .gck-discount-badge{
          display: inline-flex;
          align-items: center;
          margin-left: 6px;
          background: #2e7d32;
          color: #fff;
          font-size: 13px;
          font-weight: 800;
          padding: 6px 9px;
          border-radius: 4px;
          line-height: 1;
          vertical-align: middle;
      }
      .gck-hl-break{ display: none; }

      .bundle-total-line { margin-top: 0px; text-align: right; font-weight: 600; color: black; }
      small { color: black; }

      .hidden { visibility: hidden !important; height: 0 !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important; display: none; }

      .color-swatches { display: flex; gap: 2px; align-items: center; }
      .color-swatches .swatch {
          width: 33px; height: 34px;
          border-radius: 4px;
          border: 2px solid #ddd;
          cursor: pointer;
          display: flex;
          justify-content: center;
          align-items: center;
          transition: all .15s;
      }
      .color-swatches .swatch.active { border-color: #ff6d2e; transform: scale(1.08); }
      .swatch-circle { width: 27px; height: 27px; border-radius: 50%; }

      .color-black { background: #000; } .color-crna { background: #000; }
      .color-blue  { background: #203240; } .color-modra { background: #203240; } .color-plava { background: #203240; }
      .color-green  { background: #294d3b; } .color-zelena { background: #294d3b; }
      .color-gray { background: #706d78; } .color-siva { background: #706d78; }
      .color-crvena { background: #ba212f; }
      .color-white { background: #fff; border: 1px solid #ccc; }
      .color-bela  { background: #fff; border: 1px solid #ccc; }
      .color-bijela{ background: #fff; border: 1px solid #ccc; }
      .color-bez { background: #e4e0cf; }
      .color-smeda { background: #9f6f4e; }
      .color-zelena { background: #65633c; }
      .color-tamnoplava { background: #2a3262; }

      .bundle-option input[type="radio"] {
          -webkit-appearance: none;
          appearance: none;
          margin: -2px 2px 0 2px;
          width: 16px; height: 16px;
          border-radius: 50%;
          border: 2px solid #ff6d2e;
          background: #fff;
          position: relative;
          cursor: pointer;
          vertical-align: middle;
          outline: none;
          box-shadow: none;
          accent-color: initial;
      }
      .bundle-option input[type="radio"]::before {
          content: '';
          position: absolute;
          inset: 3px;
          border-radius: 50%;
          background: #ff6d2e;
          transform: scale(0);
          transition: transform 0.15s ease;
      }
      .bundle-option input[type="radio"]:checked::before { transform: scale(1); }

      .bundle-box select {
          flex: 0 0 auto;
          max-width: 150px;
          min-width: 69px;
          padding: 1px 10px;
          border-radius: 4px;
          border: 2px solid #ff6d2e;
          background: #ffffff;
          font-size: 18px;
          font-weight: 600;
          color: #333;
          appearance: none;
          background-image: linear-gradient(45deg, transparent 50%, #444 50%),
                            linear-gradient(135deg, #444 50%, transparent 50%);
          background-position: calc(100% - 13px) 50%, calc(100% - 8px) 50%;
          background-size: 6px 6px, 6px 6px;
          background-repeat: no-repeat;
          margin-left: -2px;
      }

      .gck-popular-badge,
      .gck-popular-badge-2 {
          
          display: none !important;
          
          transform: rotate(3deg);
          position: absolute;
          top: -18px;
          right: -15px;
          background: #000;
          color: #fff;
          font-size: 13px;
          padding: 3px 17px;
          border-radius: 8px;
          font-weight: 600;
          z-index: 10;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
          white-space: nowrap;
      }

      .gck-top-banner-wrap { margin-bottom: 0px; }
      .gck-divider { display:flex; align-items:center; justify-content:center; margin: 8px 0 4px; }
      .gck-divider span { font-size: 15px; font-weight: 600; color: black; padding: 0 12px; text-transform: uppercase; }
      .gck-divider:before, .gck-divider:after { content:""; flex:1; height:2px; background:#f39c12; max-width:400px; }

      .gck-timer-bar { background: #f39c12; color: #fff; padding: 4px 14px; border-radius: 4px; text-align: center; font-weight: 600; font-size: 15px; }
      .gck-benefits-box { margin-top:-15px; color: #000; }
      .gck-benefits-list { list-style:none; margin:0 0 8px; padding:0; margin-top:-25px !important; }
      .gck-benefits-list li { font-size:15px; line-height:1.6; margin:0 0 4px; display:flex; align-items:flex-start; }
      .gck-check { margin-right:6px; font-size:14px; }

      .gck-size-link {
          display: inline-flex;
          align-items: center;
          font-size: 14px;
          text-decoration: underline;
          color: #000;
          margin-left: -5px;
          font-weight: bold;
          text-transform: uppercase;
          margin-top: 10px;
      }
      .features { display: none !important; }
      
      
      
      @media (max-width: 490px) {
         .gck-divider span {
             }

      }

      @media (max-width: 767px) {
          .gck-pair-label { font-size: 14px; margin: 1px 0 4px 0; }
          .gck-pair-label.is-gratis { margin-top: 8px; }
          .gck-hl-break { display: block; }
          .gck-per-chip { margin-left: 0; margin-top: 4px; }
          .gck-discount-badge { margin-top: 4px; margin-left: 6px; }
      }


      .gck-regular-price{
  color:#c00;
  text-decoration: line-through;
  font-weight:700;
  margin-right:6px;
}
    </style>
    
    
    
    

    <?php
    // Your extra conditional style block (kept)
    if (  !has_term( array( 'orto-starter', 'orto-majice', 'orto-bokserice', 'orto-kompresijske-carape', 'orto-ortopas', 'orto-bunion', 'orto-fisiorest', 'orto-norikshers', 'orto-noriks-hers', 'orto-majica-darila', 'orto-leak-boxers', 'orto-kompresijske-majice', 'orto-ortopedski-jastuk', 'orto-nosilka', 'orto-kidsnest' ), 'product_cat', $product_id )  )   :
    ?>
        <style>
          .bundle-option { border: 2px solid #ededed; background: #f4f4f4b0  !important; border-radius: 4px; }
          .bundle-option.active { border-color: #969696 !important;  background: #62626217  !important; border: none !important; }
          .color-swatches .swatch.active { border-color: black  !important; }
          .bundle-box select { border: 2px solid black !important; }
        </style>
    <?php endif; ?>

    <?php
    // Wider size select ONLY for compression socks (longer labels like "S/M 36-40").
    if ( has_term( array( 'orto-kompresijske-carape' ), 'product_cat', $product_id ) ) :
    ?>
        <style>
          .bundle-box select { max-width: 195px !important; min-width: 92px !important; padding-right: 26px !important; }
        </style>
    <?php endif; ?>

    <?php
    // SHGIFTS: the čarapa size labels ("35-38" …) are wider than shirt/boxer sizes,
    // so widen just that one size-select so the value isn't clipped/overlapped.
    if ( $gck_shgifts ) :
    ?>
        <style>
          #bundle-selector .gck-size-select[data-size-key="carapa_size"] {
              max-width: 130px !important;
              min-width: 96px !important;
              padding-right: 26px !important;
          }
        </style>
    <?php endif; ?>
    
    
    
        <?php
    // Your extra conditional style block (kept)
    if (  has_term( array( 'orto-starter'), 'product_cat', $product_id )  )   :
    ?>
        <style>
          
          
            .price ins {
                   
                   font-size: 25px !important;
                   font-weight: bold;
               
               }
               
                 .price del {
                   
                   font-size: 25px !important;
               
               }
               
               .price-badge {
                   
                   font-size: 17px !important;
                   margin-top: -7px !important;
               }
               
               
                  .gck-divider span {
        font-size: 17px;
        font-weight: 600;
        color: black;
        padding: 0 12px;
        text-transform: uppercase;
        letter-spacing: -0.2px;
        text-align: center !important;
        line-height: 1.3;
    }
          
           @media (max-width: 991px) {
               
               
                   .gck-divider span {
        font-size: 17px;
        font-weight: 600;
        color: black;
        padding: 0 12px;
        text-transform: uppercase;
        letter-spacing: -0.2px;
        text-align: center !important;
        line-height: 1.3;
    }
               
               #title-buy-now  {
                       font-size: 35px !important;
                       letter-spacing: 0.1px !important;
               
               }
               
               
               
             
               
           }
          
           @media (min-width: 992px) {
               .why-section img {
               
               float: right;
               max-width: 70%;
               }
               
               .why-content{
               
               padding-right: 30px;
               
               }
           }
          
        </style>
    <?php endif; ?>
    
    

    <div class="gck-benefits-box">
        <?php if ( ! has_term( array( 'orto-kompresijske-carape', 'orto-ortopas', 'orto-bunion', 'orto-fisiorest', 'orto-norikshers', 'orto-noriks-hers', 'orto-leak-boxers', 'orto-kompresijske-majice', 'orto-ortopedski-jastuk', 'orto-nosilka', 'orto-kidsnest' ), 'product_cat', $product_id ) ) : // hide benefits list for compression socks + back belt + bunion + fisiorest + leak boxers + kompresijske majice + orthopedic pillow ?>
        <ul class="gck-benefits-list">
            <?php if ( !has_term( array( 'orto-bokserice', 'orto-bokserice2', 'starter-paketi', 'starter-pack' ), 'product_cat', $product_id ) ) : ?>
                <li><span class="gck-check">✔</span> <strong>A perfect fit</strong></li>
            <?php endif; ?>


     <?php if ( has_term( array( 'bokserice-savrsene-za-ispod-kupacih' ), 'product_cat', get_the_ID() )  ): ?>
     
     
  <li><strong>✔ Quick-drying fabric — perfect under swim shorts</strong></li>
            <li><strong>✔ Light as air, comfortable all day at the beach</strong></li>
       
            
            
            <?php else:?>
            
           
            
            
                 <li><strong>✔ Vrhunski materiali precizan kroj</strong></li>
            <li><strong>✔ Udobnost bez kompromisa</strong></li>
            
            
            <?php endif; ?>
            
            

            <?php if ( has_term( array( 'orto-starter' ), 'product_cat', $product_id ) ) : ?>
                <li style="color: #c00;"><strong>✔ Starter paket dostupan samo jednom po osobi</strong></li>
                <li style="color: #c00;"><strong>✔ Limitirano na 1.000 paketa </strong></li>
            <?php endif; ?>
        </ul>
        <?php endif; // /hide benefits list for compression socks ?>

        <?php if ( ! $show_countdown && ! $gck_no_attrs && ! $gck_single_size && ! has_term( array( 'orto-kompresijske-carape', 'orto-leak-boxers', 'orto-kompresijske-majice' ), 'product_cat', $product_id ) ) : ?>
        <a id="open-size-chartCustom" href="#size-chart" class="gck-size-link">
            <svg style="margin-right: 5px; width: 23px; height: 23px; display: inline-block; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                <path d="M11.4124 2.58464L2.08525 11.9118C1.86558 12.1315 1.86558 12.4876 2.08525 12.7073L5.78977 16.4118C6.00944 16.6315 6.3656 16.6315 6.58527 16.4118L15.9124 7.08466C16.1321 6.86499 16.1321 6.50883 15.9124 6.28916L12.2079 2.58464C11.9883 2.36497 11.6321 2.36497 11.4124 2.58464Z" stroke="#111213" stroke-width="0.84375"></path>
                <path d="M9.28125 4.71875L11.5312 6.96875M6.75 7.25L9 9.5M4.21875 9.78125L6.46875 12.0312" stroke="#111213" stroke-width="0.84375"></path>
            </svg>
            Tablica veličina
        </a>
        <?php endif; ?>
    </div>

    <div class="gck-top-banner-wrap">
        <?php if ( has_term( array( 'orto-starter' ), 'product_cat', $product_id ) ) : ?>
            <!-- divider/timer removed in your current code -->
            
            <div class="gck-divider">
           <!-- <span>More pieces, bigger savings!</span>-->
           <span>Uzmite svoj ekskluzivni<br/> starter paket</span>
        </div>
        
        
        <?php endif; ?>
    </div>

    <?php if ( has_term( array( 'orto-starter' ), 'product_cat', $product_id ) ) : ?>
        <div class="dev-banner" data-total="1000" data-sold="354">
            <div class="dev-banner__text">
                <span class="dev-banner__icon">📦</span>
                <span>Limited stock <b class="sold">0</b> / <b class="total">0</b> (Only <b class="remain">0</b> komada)</span>
            </div>
            <div class="dev-banner__bar" aria-label="Sales progress">
                <div class="dev-banner__fill"></div>
            </div>
        </div>
    <?php elseif ( ! $show_countdown ) : ?>
        <div style="height: 10px;"></div>
    <?php endif; ?>

    <style>
      .dev-banner{   font-size: 14px; color: black; max-width: 100%; margin-bottom: 12px; margin-top: 7px; }
      .dev-banner__text{ display: flex; align-items: center; gap: 8px; margin-bottom: 0px; padding-top: 10px; background: #e9f7f0; padding-bottom: 10px; justify-content: center; border-radius: 3px 0 0 3px; }
      .dev-banner__text b{ color:#333; font-weight:700; }
      .dev-banner__icon{ font-size: 14px; }
      .dev-banner__bar{ height: 12px; border-radius: 3px; overflow: hidden; background: repeating-linear-gradient(135deg,#efefef 0px,#efefef 10px,#e6e6e6 10px,#e6e6e6 20px); }
      .dev-banner__fill{ height: 100%; width: 0%; background: #2e7d32; border-radius: 3px 0 0 3px; }
    </style>

    <script>
      (function () {
        const el = document.querySelector(".dev-banner");
        if (!el) return;
        const total = Number(el.dataset.total) || 1;
        const sold = Math.min(Math.max(Number(el.dataset.sold) || 0, 0), total);
        const remaining = total - sold;
        const pct = (sold / total) * 100;

        el.querySelector(".sold").textContent = sold;
        el.querySelector(".total").textContent = total;
        el.querySelector(".remain").textContent = remaining;
        el.querySelector(".dev-banner__fill").style.width = pct + "%";
      })();
    </script>

    <?php if ( $show_countdown ) : ?>
        <style>
          .gck-countdown{
              background:#fdeeee;
              border:1px solid #f3c9c9;
              border-left:5px solid #c00;
              border-radius:4px; padding:14px 16px; margin:14px 0;
              font-family:'Roboto', sans-serif; color:#333; text-align:left;
          }
          .gck-countdown__head{
              display:flex; align-items:center; gap:8px;
              color:#c00; font-weight:800; font-size:16px;
              margin-bottom:6px; line-height:1.2;
          }
          .gck-countdown__icon{ font-size:18px; line-height:1; animation:gckCdPulse 1.3s ease-in-out infinite; }
          .gck-countdown__body{ font-size:14px; line-height:1.45; color:#333; }
          .gck-countdown__body strong{ color:#c00; font-weight:800; }
          .gck-countdown__timer{ font-weight:800; color:#c00; font-variant-numeric:tabular-nums; white-space:nowrap; }
          @keyframes gckCdPulse{ 0%,100%{ transform:scale(1); } 50%{ transform:scale(1.18); } }
          @media (prefers-reduced-motion: reduce){ .gck-countdown__icon{ animation:none; } }
          @media (max-width: 767px){
              .gck-countdown{ padding:12px 13px; margin:11px 0; }
              .gck-countdown__head{ font-size:15px; }
              .gck-countdown__icon{ font-size:16px; }
              .gck-countdown__body{ font-size:13px; }
          }
        </style>
        <div class="gck-countdown" id="gck-countdown"
             data-minutes="<?php echo esc_attr( $countdown_minutes ); ?>"
             data-key="gck_cd_<?php echo esc_attr( $product_id ); ?>">
            <div class="gck-countdown__head">
                <span class="gck-countdown__icon">🔥</span>
                Ograničena zaliha
            </div>
            <div class="gck-countdown__body">
                Akcija vrijedi samo danas — još <span class="gck-countdown__timer" aria-live="polite">--:--</span>.<?php
                if ( $countdown_stock > 0 ) :
                    ?> Ostalo samo <strong><?php echo esc_html( $countdown_stock ); ?> kom</strong>.<?php
                endif; ?> <strong>Hurry before it runs out!</strong>
            </div>
        </div>
        <script>
          (function(){
              var el = document.getElementById('gck-countdown');
              if (!el) return;
              var minutes = parseInt(el.getAttribute('data-minutes'), 10) || 10;
              var key = el.getAttribute('data-key') || 'gck_cd_default';
              var timerEl = el.querySelector('.gck-countdown__timer');
              var durationMs = minutes * 60 * 1000;
              var now = Date.now();
              var end;
              try {
                  var stored = window.localStorage.getItem(key);
                  end = stored ? parseInt(stored, 10) : 0;
                  if (!end || isNaN(end) || end <= now) {
                      end = now + durationMs;
                      window.localStorage.setItem(key, String(end));
                  }
              } catch (e) {
                  end = now + durationMs;
              }
              function pad(n){ return (n < 10 ? '0' : '') + n; }
              function tick(){
                  var diff = end - Date.now();
                  if (diff <= 0){
                      end = Date.now() + durationMs;
                      try { window.localStorage.setItem(key, String(end)); } catch (e) {}
                      diff = durationMs;
                  }
                  var totalSec = Math.floor(diff / 1000);
                  var h = Math.floor(totalSec / 3600);
                  var m = Math.floor((totalSec % 3600) / 60);
                  var s = totalSec % 60;
                  timerEl.textContent = h > 0
                      ? (pad(h) + ':' + pad(m) + ':' + pad(s))
                      : (pad(m) + ':' + pad(s));
              }
              tick();
              setInterval(tick, 1000);
          })();
        </script>
    <?php endif; ?>

    <?php if ( $show_countdown && ! $gck_no_attrs && ! $gck_single_size && ! has_term( array( 'orto-leak-boxers', 'orto-kompresijske-majice' ), 'product_cat', $product_id ) ) : ?>
    <div class="gck-size-link-wrap" style="text-align:right; margin:0 0 8px 0;">
        <a id="open-size-chartCustom" href="#size-chart" class="gck-size-link">
            <svg style="margin-right: 5px; width: 23px; height: 23px; display: inline-block; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                <path d="M11.4124 2.58464L2.08525 11.9118C1.86558 12.1315 1.86558 12.4876 2.08525 12.7073L5.78977 16.4118C6.00944 16.6315 6.3656 16.6315 6.58527 16.4118L15.9124 7.08466C16.1321 6.86499 16.1321 6.50883 15.9124 6.28916L12.2079 2.58464C11.9883 2.36497 11.6321 2.36497 11.4124 2.58464Z" stroke="#111213" stroke-width="0.84375"></path>
                <path d="M9.28125 4.71875L11.5312 6.96875M6.75 7.25L9 9.5M4.21875 9.78125L6.46875 12.0312" stroke="#111213" stroke-width="0.84375"></path>
            </svg>
            Tablica veličina
        </a>
    </div>
    <?php endif; ?>

    <?php if ( $gck_single_size ) : ?>
    <style>
      /* Ortopas: duga imena veličina ("S/M (Opseg bokova 75–110 cm)") — puni širine select,
         manji font, jedan red (bez lomljenja). */
      #bundle-selector.is-single-size .bundle-pairs,
      #bundle-selector.is-single-size .bundle-pair,
      #bundle-selector.is-single-size .bundle-attr-row { width: 100% !important; display: block !important; }
      #bundle-selector.is-single-size .gck-size-select {
          display: block !important;
          width: 50% !important;        /* desktop: 50% */
          max-width: 50% !important;    /* pobijedi bazni .bundle-box select { max-width:150px } */
          min-width: 0 !important;
          flex: 0 0 auto !important;
          box-sizing: border-box;
          font-size: 12px;
          padding: 11px 30px 11px 12px;
          white-space: nowrap;
          text-overflow: ellipsis;
          overflow: hidden;
      }
      @media (max-width: 767px) {
          #bundle-selector.is-single-size .gck-size-select {
              width: 80% !important;    /* mobitel: 80% */
              max-width: 80% !important;
          }
      }
    </style>
    <?php endif; ?>
    <?php if ( $gck_no_attrs ) : ?>
    <style>
      /* Bunion (no-attrs): nema selektora — makni divider iznad prazne .bundle-pairs. */
      #bundle-selector.is-no-attrs .bundle-pairs { border-top: 0 !important; padding-top: 0 !important; margin-top: 0 !important; }
    </style>
    <?php endif; ?>
    <?php // Leak boxers + kompresijske majice: size-chart link top-right, directly above the bundle buttons.
    if ( has_term( array( 'orto-leak-boxers', 'orto-kompresijske-majice' ), 'product_cat', $product_id ) ) : ?>
    <div class="gck-size-link-wrap" style="text-align:right; margin:0 0 10px 0;">
        <a id="open-size-chartCustom" href="#size-chart" class="gck-size-link">
            <svg style="margin-right:5px;width:20px;height:20px;display:inline-block;vertical-align:middle;" xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none"><path d="M11.4124 2.58464L2.08525 11.9118C1.86558 12.1315 1.86558 12.4876 2.08525 12.7073L5.78977 16.4118C6.00944 16.6315 6.3656 16.6315 6.58527 16.4118L15.9124 7.08466C16.1321 6.86499 16.1321 6.50883 15.9124 6.28916L12.2079 2.58464C11.9883 2.36497 11.6321 2.36497 11.4124 2.58464Z" stroke="#111213" stroke-width="0.84375"></path></svg>
            Tablica veličina
        </a>
    </div>
    <?php endif; ?>
    <div id="bundle-selector" class="bundle-box<?php echo $gck_single_size ? ' is-single-size' : ''; ?><?php echo $gck_no_attrs ? ' is-no-attrs' : ''; ?>" data-split-garments="<?php echo $gck_split_garments ? '1' : '0'; ?>">
        <?php
        $default_index = ( $precheck_second && count( $offers ) > 2 ) ? 2 : 0;
        $loop_index    = 0;

        foreach ( $offers as $offer_id => $data ) :
            // SHGIFTS is a single fixed set (4+1+1); render only the first offer row
            // even if the bundle meta happens to carry more than one.
            if ( $gck_shgifts && $loop_index > 0 ) break;

            $pairs = (int) ( $data['qty'] ?? 0 );
            if ( $pairs <= 0 ) continue;

            $is_default = ( $loop_index === $default_index );

            $offer_p1 = trim((string)($data['p1'] ?? ''));
            $offer_p2 = trim((string)($data['p2'] ?? ''));
            $offer_tip = strtolower(trim((string)($data['tip'] ?? ''))); // NEW

            // Decide which selector OPTIONS to show for each group in 4-attr case.
            // IMPORTANT: we keep group field keys as-is so values do NOT overwrite.
            // - mixed (or empty) => group0 uses group0 options, group1 uses group1 options
            // - majica => BOTH groups show majica options (source group 0), but group1 keeps its own field keys
            // - bokserica => BOTH groups show bokserica options (source group 1), but group0 keeps its own field keys
            $force_source_group = null; // null = normal
            if ( $show_group_titles ) {
                if ( $offer_tip === 'majica' ) {
                    $force_source_group = 0;
                } elseif ( $offer_tip === 'bokserica' || $offer_tip === 'bokserice' ) {
                    $force_source_group = 1;
                } // mixed or empty => null
            }

            // Price highlights: per-piece regular price + discount %.
            // SHBOX split: real piece count is pairs * 2 (majice + bokserice), so per-piece
            // must use total pieces, not just the paid pairs.
            // SHGIFTS: real piece count is 6 (4 majica + 1 bokserica + 1 čarapa),
            // so per-piece pricing reads sensibly, same idea as the SHBOX split.
            $gck_pieces   = $gck_split_garments ? ( $pairs * 2 ) : ( $gck_shgifts ? 6 : $pairs );
            $per_regular  = ( $gck_pieces > 0 ) ? ( (float) $data['regular'] / $gck_pieces ) : 0;
            $gck_per_new  = ( $gck_split_garments || $gck_shgifts )
                ? ( ( $gck_pieces > 0 ) ? ( floor( ( (float) $data['total'] / $gck_pieces ) * 100 ) / 100 ) : 0 )
                : (float) $data['per'];
            $discount_pct = ( (float) $data['regular'] > 0 )
                ? (int) round( ( ( (float) $data['regular'] - (float) $data['total'] ) / (float) $data['regular'] ) * 100 )
                : 0;
        ?>
            <label style="position: relative; <?php if ( ($loop_index == 1 ||  $loop_index == 3) && ! $show_group_titles) : ?> margin-top: 25px;  <?php endif; ?>"
                   class="bundle-option<?php echo $is_default ? ' active' : ''; ?>">

                <?php if ( ! $show_group_titles ) : ?>
                    <?php if ( $loop_index == 1 ) : ?>
                        <div class="gck-popular-badge">Najpopularnije 🔥</div>
                    <?php endif; ?>

                    <?php if ( $loop_index == 3 ) : ?>
                        <div class="gck-popular-badge-2">Najbolja cena 🔥</div>
                    <?php endif; ?>
                <?php endif; ?>

                <input type="radio"
                    required
                    name="bundle_option"
                    value="<?php echo esc_attr( $offer_id ); ?>"
                    data-total="<?php echo esc_attr( $data['total'] ); ?>"
                    data-qty="<?php echo esc_attr( $pairs ); ?>"
                    <?php checked( $is_default ); ?>>
                    
                    
                    
  

                <span class="bundle-option-title"><?php echo esc_html( $data['title'] ); ?></span>
                
                  <?php

    if (  !has_term( array( 'orto-starter' ), 'product_cat', $product_id ) 
    
    && !has_term( array( 'starter-paketi', 'starter-pack' ), 'product_cat', $product_id ) 
    
    )  :  ?>
                <?php if ( $show_price_highlights ) : ?>
                    <br class="gck-hl-break">
                    <span class="gck-per-chip">
                        <?php if ( $per_regular > $gck_per_new ) : ?>
                            <span class="gck-per-old"><?php echo number_format( $per_regular, 2 ); ?>€</span>
                        <?php endif; ?>
                        <span class="gck-per-new"><?php echo number_format( $gck_per_new, 2 ); ?>€ / kom</span>
                    </span>
                    <?php if ( $discount_pct > 0 ) : ?>
                        <span class="gck-discount-badge">−<?php echo (int) $discount_pct; ?>%</span>
                    <?php endif; ?>
                <?php else : ?>
                    — <span class="bundle-option-title"><?php echo number_format( $gck_per_new, 2 ); ?>€ / kom</span>
                <?php endif; ?>

                
                <?php endif; ?>
                

                <br/>

                <!--
                <div class="bundle-total-line">
                    <span style="font-weight:normal;">Ukupno:</span>
                    <span class="line-total"><?php echo number_format( (float) $data['total'], 2 ); ?>€</span>
                </div>

-->

<div class="bundle-total-line">
    <span style="font-weight:normal;">Ukupno:</span>

    <?php if ( ! empty($data['regular']) && (float)$data['regular'] > (float)$data['total'] ) : ?>
        <span class="gck-regular-price">
            <?php echo number_format( (float) $data['regular'], 2 ); ?>€
        </span>
    <?php endif; ?>

    <span class="line-total"><?php echo number_format( (float) $data['total'], 2 ); ?>€</span>
</div>



                <div class="bundle-pairs <?php echo $is_default ? '' : 'hidden'; ?>"
                     data-offer-id="<?php echo esc_attr( $offer_id ); ?>"
                     data-qty="<?php echo esc_attr( $pairs ); ?>">

                    <?php
                    // Gratis labels: parse "X+Y" from offer title (X = paid, Y = gratis).
                    $gck_paid = 0;
                    $gck_free = 0;
                    if ( $show_gratis && preg_match( '/(\d+)\s*\+\s*(\d+)/u', (string) ( $data['title'] ?? '' ), $gck_m ) ) {
                        $gck_paid = (int) $gck_m[1];
                        $gck_free = (int) $gck_m[2];
                    }
                    $gck_show_sections = ( $show_gratis && ! $show_group_titles && ( $gck_paid + $gck_free ) > 0 && ! $gck_no_attrs );
                    ?>
                    <?php if ( $gck_shgifts ) : ?>
                    <?php
                    // ---- SHGIFTS: SHBOX-style split-garment selector, 3 groups (4+1+1) ----
                    // SAME markup/CSS as the SHBOX split path (.gck-pair-label section
                    // headers + .bundle-pair rows with .color-swatches + .gck-size-select).
                    // Colour/size OPTIONS come from the product's REAL (admin-managed)
                    // attributes — "Boja majice"/"Veličina majice", "Boja bokserice"/
                    // "Veličina bokserice", "Boja čarape"/"Veličina čarape" — grouped by
                    // the garment word via the same helper SHBOX uses ($attr_groups).
                    // Form field names stay stable (majica_color …) so cart/label capture
                    // and the socks-width CSS are unaffected. Falls back to hardcoded
                    // options if a garment group can't be resolved, so it never breaks.
                    // Each item gets its own pair index so all 6 selections reach the cart.
                    $gck_sections = array(
                        array(
                            'label'   => 'Izaberi 4 ' . gck_hr_majice_phrase( 4, false, 'majica' ),
                            'gratis'  => false, 'count' => 4, 'gg' => 0, 'garment' => 'majica',
                            'ckey'    => 'majica_color', 'skey' => 'majica_size',
                            'colors'  => array( 'Crna', 'Bijela', 'Siva', 'Bez', 'Tamnoplava', 'Smeđa', 'Zelena' ),
                            'sizes'   => array( 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL' ),
                        ),
                        array(
                            'label'   => 'Izaberi 1 ' . gck_hr_majice_phrase( 1, false, 'bokserica' ) . ' gratis',
                            'gratis'  => true, 'count' => 1, 'gg' => 1, 'garment' => 'bokserica',
                            'ckey'    => 'bokserica_color', 'skey' => 'bokserica_size',
                            'colors'  => array( 'Crna', 'Plava', 'Siva', 'Zelena', 'Crvena' ),
                            'sizes'   => array( 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL' ),
                        ),
                        array(
                            'label'   => 'Choose your sock colour (5 pairs)',
                            'gratis'  => true, 'count' => 1, 'gg' => 2, 'garment' => 'carapa',
                            'ckey'    => 'carapa_color', 'skey' => 'carapa_size',
                            'colors'  => array( 'Crna', 'Bijela' ),
                            'sizes'   => array( '39-43', '43-46' ),
                        ),
                    );

                    // Real, admin-managed option lists per garment from the grouped
                    // attributes ($attr_groups was built earlier with the SHBOX helper).
                    $gck_real = array();
                    foreach ( (array) $attr_groups as $grp ) {
                        $c   = $grp['color'] ?? null;
                        $s   = $grp['size'] ?? null;
                        $hay = strtolower(
                            (string) ( $c['label'] ?? '' ) . ' ' . (string) ( $c['key'] ?? '' ) . ' ' .
                            (string) ( $s['label'] ?? '' ) . ' ' . (string) ( $s['key'] ?? '' ) . ' ' .
                            (string) ( $grp['token'] ?? '' )
                        );
                        $cv = $c ? array_values( array_filter( array_map( 'strval', (array) ( $c['values'] ?? array() ) ), 'strlen' ) ) : array();
                        $sv = $s ? array_values( array_filter( array_map( 'strval', (array) ( $s['values'] ?? array() ) ), 'strlen' ) ) : array();
                        if ( strpos( $hay, 'majic' ) !== false )        { $gck_real['majica']    = array( 'colors' => $cv, 'sizes' => $sv ); }
                        elseif ( strpos( $hay, 'bokseric' ) !== false ) { $gck_real['bokserica'] = array( 'colors' => $cv, 'sizes' => $sv ); }
                        elseif ( strpos( $hay, 'carap' ) !== false )    { $gck_real['carapa']    = array( 'colors' => $cv, 'sizes' => $sv ); }
                    }

                    $gck_run = 0;
                    ?>
                    <?php foreach ( $gck_sections as $sec ) :
                        $garment    = (string) $sec['garment'];
                        // Prefer real attribute options; fall back to hardcoded per group.
                        $colors_use = ( ! empty( $gck_real[ $garment ]['colors'] ) ) ? $gck_real[ $garment ]['colors'] : $sec['colors'];
                        $sizes_use  = ( ! empty( $gck_real[ $garment ]['sizes'] ) )  ? $gck_real[ $garment ]['sizes']  : $sec['sizes'];
                    ?>
                        <div class="gck-pair-label<?php echo $sec['gratis'] ? ' is-gratis' : ''; ?>"><?php echo esc_html( $sec['label'] ); ?></div>
                        <?php for ( $k = 1; $k <= (int) $sec['count']; $k++ ) : $gck_run++; ?>
                        <div class="bundle-pair">
                            <div class="bundle-attr-row">
                                <?php if ( ! empty( $colors_use ) && $sec['ckey'] !== '' ) : ?>
                                    <div class="color-swatches"
                                         data-attr-key="<?php echo esc_attr( $sec['ckey'] ); ?>"
                                         data-name="pairs[<?php echo esc_attr( $offer_id ); ?>][<?php echo (int) $gck_run; ?>][<?php echo esc_attr( $sec['ckey'] ); ?>]">
                                        <?php foreach ( $colors_use as $val ) : $slug = sanitize_title( $val ); ?>
                                            <div class="swatch" data-value="<?php echo esc_attr( $val ); ?>" title="<?php echo esc_attr( $val ); ?>">
                                                <span class="swatch-circle color-<?php echo esc_attr( $slug ); ?>"></span>
                                            </div>
                                        <?php endforeach; ?>
                                        <input type="hidden" class="swatch-input"
                                               data-attr-key="<?php echo esc_attr( $sec['ckey'] ); ?>"
                                               name="pairs[<?php echo esc_attr( $offer_id ); ?>][<?php echo (int) $gck_run; ?>][<?php echo esc_attr( $sec['ckey'] ); ?>]"
                                               value="">
                                    </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $sizes_use ) && $sec['skey'] !== '' ) : ?>
                                    <select class="gck-size-select"
                                            data-size-key="<?php echo esc_attr( $sec['skey'] ); ?>"
                                            data-garment-group="<?php echo (int) $sec['gg']; ?>"
                                            name="pairs[<?php echo esc_attr( $offer_id ); ?>][<?php echo (int) $gck_run; ?>][<?php echo esc_attr( $sec['skey'] ); ?>]">
                                        <?php foreach ( $sizes_use as $val ) : ?>
                                            <option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $val ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <?php
                    // Render passes. Normal: single pass, all groups interleaved per pair.
                    // SHBOX split (SKU NORIKS-ORTO-SHBOX): two sections — paid majice, then gratis bokserice.
                    if ( $gck_split_garments ) {
                        $gck_passes = array(
                            array( 'group' => 0, 'label' => 'Izaberi ' . (int) $pairs . ' ' . gck_hr_majice_phrase( $pairs, false, 'majica' ), 'gratis' => false ),
                            array( 'group' => 1, 'label' => 'Choose another ' . (int) $pairs . ' ' . gck_hr_majice_phrase( $pairs, true, 'bokserica' ), 'gratis' => true ),
                        );
                    } else {
                        $gck_passes = array( array( 'group' => null, 'label' => null, 'gratis' => false ) );
                    }
                    ?>
                    <?php foreach ( $gck_passes as $gck_pass ) : ?>
                        <?php if ( $gck_pass['label'] !== null ) : ?>
                            <div class="gck-pair-label<?php echo $gck_pass['gratis'] ? ' is-gratis' : ''; ?>"><?php echo esc_html( $gck_pass['label'] ); ?></div>
                        <?php endif; ?>
                    <?php for ( $i = 1; $i <= $pairs; $i++ ) : ?>
                        <?php if ( $gck_pass['group'] === null && $gck_show_sections && $gck_paid > 0 && $i === 1 ) : ?>
                            <div class="gck-pair-label">Izaberi <?php echo (int) $gck_paid; ?> <?php echo esc_html( gck_hr_majice_phrase( $gck_paid, false, $gck_garment ) ); ?></div>
                        <?php endif; ?>
                        <?php if ( $gck_pass['group'] === null && $gck_show_sections && $gck_free > 0 && $i === ( $gck_paid + 1 ) ) : ?>
                            <div class="gck-pair-label is-gratis">Choose another <?php echo (int) $gck_free; ?> <?php echo esc_html( gck_hr_majice_phrase( $gck_free, true, $gck_garment ) ); ?></div>
                        <?php endif; ?>
                        <div class="bundle-pair">
                            <?php foreach ( $attr_groups as $g_index => $group ) :
                                if ( $gck_pass['group'] !== null && $g_index !== $gck_pass['group'] ) continue;

                                // Target group (field keys used for saving)
                                $target_c = $group['color'] ?? null;
                                $target_s = $group['size'] ?? null;

                                $target_color_field_key = $target_c ? (string)($target_c['field_key'] ?? '') : '';
                                $target_color_attr_key  = $target_c ? (string)($target_c['key'] ?? '') : '';
                                $target_size_field_key  = $target_s ? (string)($target_s['field_key'] ?? '') : '';
                                $target_size_attr_key   = $target_s ? (string)($target_s['key'] ?? '') : '';

                                // Source group (options shown to user)
                                $source_index = $g_index;
                                if ( $force_source_group !== null && isset($attr_groups[$force_source_group]) ) {
                                    $source_index = (int)$force_source_group;
                                }

                                $source_group = $attr_groups[$source_index] ?? $group;
                                $source_c = $source_group['color'] ?? null;
                                $source_s = $source_group['size'] ?? null;

                                $color_values = $source_c ? (array)($source_c['values'] ?? []) : [];
                                $size_values  = $source_s ? (array)($source_s['values'] ?? []) : [];

                                if ( empty($color_values) && empty($size_values) ) continue;

                                // Headings (p1/p2) in 4-attr case:
                                $group_title = '';
                                if ( $show_group_titles ) {
                                    if ( $g_index === 0 && $offer_p1 !== '' ) $group_title = $offer_p1;
                                    if ( $g_index === 1 && $offer_p2 !== '' ) $group_title = $offer_p2;
                                }
                            ?>
                                <?php if ( $show_group_titles && ! $gck_split_garments && $group_title !== '' ) : ?>
                                    <div class="gck-group-title"><?php echo esc_html($group_title); ?></div>
                                <?php endif; ?>

                                <div class="bundle-attr-row">

                                    <?php if ( ! empty($color_values) && $target_color_field_key !== '' ) : ?>
                                        <div class="color-swatches"
                                             data-attr-key="<?php echo esc_attr($target_color_attr_key); ?>"
                                             data-name="pairs[<?php echo esc_attr( $offer_id ); ?>][<?php echo $i; ?>][<?php echo esc_attr( $target_color_field_key ); ?>]">

                                            <?php foreach ( $color_values as $val ) :
                                                $slug = sanitize_title( $val ); ?>
                                                <div class="swatch" data-value="<?php echo esc_attr( $val ); ?>" title="<?php echo esc_attr( $val ); ?>">
                                                    <span class="swatch-circle color-<?php echo esc_attr( $slug ); ?>"></span>
                                                </div>
                                            <?php endforeach; ?>

                                            <input type="hidden"
                                                   class="swatch-input"
                                                   data-attr-key="<?php echo esc_attr($target_color_attr_key); ?>"
                                                   name="pairs[<?php echo esc_attr( $offer_id ); ?>][<?php echo $i; ?>][<?php echo esc_attr( $target_color_field_key ); ?>]"
                                                   value="">
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( ! empty($size_values) && $target_size_field_key !== '' ) : ?>
                                        <select
                                            class="gck-size-select"
                                            data-size-key="<?php echo esc_attr($target_size_attr_key); ?>"
                                            data-garment-group="<?php echo (int) $g_index; ?>"
                                            name="pairs[<?php echo esc_attr( $offer_id ); ?>][<?php echo $i; ?>][<?php echo esc_attr( $target_size_field_key ); ?>]">
                                            <?php foreach ( $size_values as $val ) : ?>
                                                <option value="<?php echo esc_attr( $val ); ?>">
                                                    <?php echo esc_html( $val ); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endfor; ?>
                    <?php endforeach; ?>
                    <?php endif; /* $gck_shgifts vs SHBOX/normal render */ ?>

                    <?php if ( ! $gck_no_attrs ) : ?>
                    <small style="display: block; line-height: 1;"><?php esc_html_e( 'Nudimo 30 dana za povrat novca ili besplatnu zamjenu proizvoda – bezbrižna kupovina!
', 'gift-card-kompetentnost' ); ?></small>
                    <?php endif; ?>
                </div>
            </label>
        <?php
        $loop_index++;
        endforeach;
        ?>

        <input type="hidden" id="bundle_quantity" name="quantity" value="1">
    </div>

    <?php
    get_template_part( 'template_parts/size-chart-modal' );
}

// ============================================================
// FRONTEND JS
// ============================================================

add_action( 'wp_footer', 'gck_bundle_js' );
function gck_bundle_js() {
    if ( ! is_product() ) return;

    global $post;
    if ( ! $post || $post->post_type !== 'product' ) return;
    if ( ! gck_is_orto_bundle_enabled( $post->ID ) ) return;
    ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal   = document.getElementById("custom-size-chart-modal");
    const openBtn = document.getElementById("open-size-chartCustom");
    const closeX  = document.getElementById("close-size-chart-x");
    if (!modal) return;

    openBtn?.addEventListener("click", function (e) { e.preventDefault(); modal.classList.add("show"); });
    closeX?.addEventListener("click", function () { modal.classList.remove("show"); });

    modal.addEventListener("click", function (e) {
        if (e.target === modal) modal.classList.remove("show");
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") modal.classList.remove("show");
    });
});

document.addEventListener('DOMContentLoaded', function () {

    // default color + size
    document.querySelectorAll('.bundle-pairs').forEach(wrap => {
        wrap.querySelectorAll('.bundle-pair').forEach(pair => {
            pair.querySelectorAll('.color-swatches').forEach(swGroup => {
                const firstSwatch = swGroup.querySelector('.swatch');
                const hidden      = swGroup.querySelector('.swatch-input');
                if (firstSwatch && hidden) {
                    swGroup.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
                    firstSwatch.classList.add('active');
                    hidden.value = firstSwatch.dataset.value || '';
                }
            });
            pair.querySelectorAll('select').forEach(sel => {
                const firstOpt = sel.querySelector('option:nth-child(1)');
                if (firstOpt) sel.value = firstOpt.value;
            });
        });
    });

    const selector = document.getElementById('bundle-selector');
    if (!selector) return;

    const radios   = selector.querySelectorAll('input[name="bundle_option"]');
    const qtyField = document.getElementById('bundle_quantity');

    function updateBundleUI(selectedRadio) {
        const selectedOfferId = selectedRadio.value;

        selector.querySelectorAll('.bundle-option').forEach(card => card.classList.remove('active'));
        selectedRadio.closest('.bundle-option')?.classList.add('active');

        selector.querySelectorAll('.bundle-pairs').forEach(wrap => {
            const oid = wrap.getAttribute('data-offer-id');
            wrap.classList.toggle('hidden', oid !== selectedOfferId);
        });

        if (qtyField) qtyField.value = 1;

        const total = selectedRadio.dataset.total;
        const priceElem = document.querySelector('.cart .summary .price .woocommerce-Price-amount');
        if (priceElem && total) {
            priceElem.innerHTML = total.replace('.', ',') + ' €';
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function () { updateBundleUI(this); });
        if (radio.checked) updateBundleUI(radio);
    });

    // validation
    const form = document.querySelector('form.cart');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const selectedRadio = selector.querySelector('input[name="bundle_option"]:checked');
        if (!selectedRadio) return;

        const offerId = selectedRadio.value;
        const wrap  = selector.querySelector('.bundle-pairs[data-offer-id="' + offerId + '"]');
        if (!wrap) return;

        let valid = true;

        wrap.querySelectorAll('.bundle-pair').forEach(pair => {
            pair.querySelectorAll('select').forEach(sel => {
                if (!sel.value) { valid = false; sel.classList.add('woocommerce-invalid'); }
                else sel.classList.remove('woocommerce-invalid');
            });

            pair.querySelectorAll('.swatch-input').forEach(hidden => {
                if (!hidden.value) { valid = false; hidden.classList.add('woocommerce-invalid'); }
                else hidden.classList.remove('woocommerce-invalid');
            });
        });

        if (!valid) {
            e.preventDefault();
            alert('Please choose a colour and a size for every set.');
        }
    });

    // swatches
    document.querySelectorAll('.color-swatches').forEach(swWrap => {
        const hidden = swWrap.querySelector('.swatch-input');
        if (!hidden) return;

        swWrap.querySelectorAll('.swatch').forEach(swatch => {
            swatch.addEventListener('click', () => {
                swWrap.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
                swatch.classList.add('active');
                hidden.value = swatch.dataset.value || '';
                hidden.classList.remove('woocommerce-invalid');
            });
        });
    });

});

/* Size sync per size attribute */
document.addEventListener("DOMContentLoaded", function () {
    function activateSizeSync() {
        const selectorEl = document.getElementById('bundle-selector');
        const splitMode  = !!(selectorEl && selectorEl.dataset.splitGarments === '1');

        document.querySelectorAll('.bundle-pairs').forEach(pairBlock => {
            // Which selects act as "drivers" (their change syncs the others):
            // Normal: the first pair's size selects (one per size attribute).
            // Split (SHBOX): only the FIRST size select of each garment group
            // (first majica, first bokserica). The 2nd/3rd stay independent.
            let drivers;
            if (splitMode) {
                const seenGroup = {};
                drivers = [];
                pairBlock.querySelectorAll('select.gck-size-select').forEach(s => {
                    const grp = s.dataset.garmentGroup || '';
                    if (seenGroup[grp]) return;
                    seenGroup[grp] = true;
                    drivers.push(s);
                });
            } else {
                const firstPair = pairBlock.querySelector('.bundle-pair');
                drivers = firstPair ? Array.from(firstPair.querySelectorAll('select.gck-size-select')) : [];
            }

            drivers.forEach(firstSelect => {
                const sizeKey = firstSelect.dataset.sizeKey || '';
                if (!sizeKey) return;

                if (firstSelect.dataset.gckBound === '1') return;
                firstSelect.dataset.gckBound = '1';

                firstSelect.addEventListener('change', function () {
                    const newSize  = this.value;
                    const selector = document.getElementById('bundle-selector');
                    if (!selector) return;

                    // Normal: sync this size across same-attribute selects in ALL pairs/offers.
                    // Split (SHBOX): sync only within the same garment group, so majice and
                    // bokserice are independent and the customer can pick different sizes.
                    let sel;
                    if (splitMode) {
                        const grp = this.dataset.garmentGroup || '';
                        sel = 'select.gck-size-select[data-garment-group="' + CSS.escape(grp) + '"]';
                    } else {
                        sel = 'select.gck-size-select[data-size-key="' + CSS.escape(sizeKey) + '"]';
                    }

                    selector
                        .querySelectorAll(sel)
                        .forEach(s => { if (s !== this) s.value = newSize; });
                });
            });
        });
    }

    activateSizeSync();

    document.querySelectorAll('#bundle-selector input[name="bundle_option"]').forEach(radio => {
        radio.addEventListener('change', function () {
            setTimeout(activateSizeSync, 50);
        });
    });
});
</script>
<?php
}

// ============================================================
// CART / ORDER LOGIC
// ============================================================

/**
 * Build cart/order lines.
 *
 * ✅ 2-attribute case: returns "Crna - L"
 * ✅ 4-attribute case: uses offer p1/p2 and returns TWO lines per pair:
 *   "P1: X - Y"
 *   "P2: X - Y"
 *
 * NOTE: It assumes post order is:
 *   color1, size1, color2, size2
 * (this remains TRUE even when tip=majica/bokserica because we kept distinct field keys)
 */
function gck_build_pair_lines( array $pairs_data, string $p1 = '', string $p2 = '' ) : array {
    $lines = [];

    foreach ( $pairs_data as $index => $attrs ) {
        if ( ! is_array( $attrs ) ) continue;

        $values = array_values( $attrs );
        $clean  = [];

        foreach ( $values as $v ) {
            $v = trim((string)$v);
            if ( $v !== '' ) $clean[] = $v;
        }

        if ( empty($clean) ) continue;

        if ( $p1 !== '' && $p2 !== '' && count($clean) >= 4 ) {
            $g1 = array_slice($clean, 0, 2);
            $g2 = array_slice($clean, 2, 2);

            $lines[] = $p1 . ': ' . implode(' - ', $g1);
            $lines[] = $p2 . ': ' . implode(' - ', $g2);
            continue;
        }

        $lines[] = implode(' - ', $clean);
    }

    return $lines;
}

/**
 * SHGIFTS (orto-majica-darila) line builder.
 *
 * The SHBOX-style 4+1+1 selector submits ONE pair index per chosen item — six in
 * total: majica 1-4 (colour+size), bokserica (colour+size), čarapa (colour+size).
 * Each line is labelled by inspecting its field keys so the cart / order clearly
 * shows which garment was picked, e.g. "Majica: Crna - L", "Bokserica: Plava - M",
 * "Čarapa: Crna - 39-42".
 */
function gck_build_shgifts_lines( array $pairs_data ) : array {
    $lines = [];

    foreach ( $pairs_data as $attrs ) {
        if ( ! is_array( $attrs ) ) continue;

        $label = '';
        foreach ( array_keys( $attrs ) as $k ) {
            $kl = strtolower( (string) $k );
            if ( strpos( $kl, 'majic' ) !== false )    { $label = 'Majica';    break; }
            if ( strpos( $kl, 'bokseric' ) !== false ) { $label = 'Bokserica'; break; }
            if ( strpos( $kl, 'carap' ) !== false )    { $label = 'Čarapa';     break; }
        }

        $clean = [];
        foreach ( $attrs as $v ) {
            $v = trim( (string) $v );
            if ( $v !== '' ) $clean[] = $v;
        }
        if ( empty( $clean ) ) continue;

        $line    = implode( ' - ', $clean );
        $lines[] = ( $label !== '' ) ? ( $label . ': ' . $line ) : $line;
    }

    return $lines;
}

add_filter( 'woocommerce_add_cart_item_data', 'gck_add_cart_item_data', 10, 3 );
function gck_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

    $offer_id = isset( $_POST['bundle_option'] ) ? sanitize_text_field( wp_unslash( $_POST['bundle_option'] ) ) : '';

    if ( ! gck_is_orto_bundle_enabled( $product_id ) || $offer_id === '' ) {
        return $cart_item_data;
    }

    $offers = gck_get_bundle_offers( $product_id );
    if ( empty( $offers ) || ! isset( $offers[ $offer_id ] ) ) {
        return $cart_item_data;
    }

    $offer          = $offers[ $offer_id ];
    $pairs_selected = (int) ( $offer['qty'] ?? 0 );
    if ( $pairs_selected <= 0 ) {
        return $cart_item_data;
    }

    $p1 = trim((string)($offer['p1'] ?? ''));
    $p2 = trim((string)($offer['p2'] ?? ''));

    $pairs_all  = isset( $_POST['pairs'] ) ? (array) $_POST['pairs'] : [];
    $pairs_data = isset( $pairs_all[ $offer_id ] ) ? (array) $pairs_all[ $offer_id ] : [];

    // SHGIFTS (orto-majica-darila): 6 items (4 majica + 1 bokserica + 1 čarapa),
    // each under its own pair index, labelled by garment. Strictly gated to that
    // category; SHBOX and every other product keep the original p1/p2 builder.
    if ( has_term( 'orto-majica-darila', 'product_cat', $product_id ) ) {
        $lines = gck_build_shgifts_lines( $pairs_data );
    } else {
        $lines = gck_build_pair_lines( $pairs_data, $p1, $p2 );
    }

    $total      = (float) $offer['total'];
    $unit_price = $total;

    $cart_item_data['_orto_offer_id']          = $offer_id;
    $cart_item_data['_orto_offer_title']       = (string) ( $offer['title'] ?? '' );
    $cart_item_data['_orto_bundle_pairs']      = $pairs_selected;
    $cart_item_data['_orto_bundle_total']      = $total;
    $cart_item_data['_orto_bundle_unit_price'] = $unit_price;
    $cart_item_data['_orto_pairs_json']        = wp_json_encode( $pairs_data );
    $cart_item_data['_orto_lines']             = $lines;

    $cart_item_data['_orto_p1'] = $p1;
    $cart_item_data['_orto_p2'] = $p2;

    $cart_item_data['orto_unique_key'] = md5( microtime( true ) . wp_rand() . serialize( $cart_item_data ) );

    return $cart_item_data;
}

function gck_apply_price_to_cart_item( $cart_item ) {
    if ( isset( $cart_item['_orto_bundle_unit_price'] ) && $cart_item['data'] instanceof WC_Product ) {
        $price = (float) $cart_item['_orto_bundle_unit_price'];
        if ( $price > 0 ) {
            $cart_item['data']->set_price( $price );
        }
    }
    return $cart_item;
}

add_filter( 'woocommerce_add_cart_item', 'gck_add_cart_item_apply_price', 20, 2 );
function gck_add_cart_item_apply_price( $cart_item, $cart_item_key ) {
    return gck_apply_price_to_cart_item( $cart_item );
}

add_filter( 'woocommerce_get_cart_item_from_session', 'gck_session_cart_item_apply_price', 20, 2 );
function gck_session_cart_item_apply_price( $cart_item, $values ) {
    if ( isset( $values['_orto_bundle_unit_price'] ) ) $cart_item['_orto_bundle_unit_price'] = $values['_orto_bundle_unit_price'];
    if ( isset( $values['_orto_lines'] ) ) $cart_item['_orto_lines'] = $values['_orto_lines'];
    if ( isset( $values['_orto_offer_id'] ) ) $cart_item['_orto_offer_id'] = $values['_orto_offer_id'];
    if ( isset( $values['_orto_offer_title'] ) ) $cart_item['_orto_offer_title'] = $values['_orto_offer_title'];
    if ( isset( $values['_orto_p1'] ) ) $cart_item['_orto_p1'] = $values['_orto_p1'];
    if ( isset( $values['_orto_p2'] ) ) $cart_item['_orto_p2'] = $values['_orto_p2'];
    return gck_apply_price_to_cart_item( $cart_item );
}

add_action( 'woocommerce_before_calculate_totals', 'gck_adjust_bundle_price', 9999 );
function gck_adjust_bundle_price( $cart ) {
    if ( ! $cart instanceof WC_Cart ) return;
    foreach ( $cart->get_cart() as $key => &$item ) {
        $item = gck_apply_price_to_cart_item( $item );
    }
}

add_filter( 'woocommerce_get_item_data', 'gck_display_item_data', 10, 2 );
function gck_display_item_data( $item_data, $cart_item ) {

    if ( empty( $cart_item['_orto_lines'] ) || ! is_array( $cart_item['_orto_lines'] ) ) {
        return $item_data;
    }

    $lines     = array_values( $cart_item['_orto_lines'] );
    $numbered  = [];
    foreach ( $lines as $i => $line ) {
        $numbered[] = ( $i + 1 ) . ': ' . $line;
    }

    $value = implode( '<br>', array_map( 'esc_html', $numbered ) );

    $item_data[] = array(
        'name'    => false,
        'display' => $value,
    );

    return $item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'gck_order_item_meta', 10, 4 );
function gck_order_item_meta( $item, $cart_item_key, $values, $order ) {

    // Proizvodi S atributima: jedan redak meta po komadu (velicina/boja).
    if ( ! empty( $values['_orto_lines'] ) && is_array( $values['_orto_lines'] ) ) {
        $lines = array_values( $values['_orto_lines'] );
        foreach ( $lines as $i => $line ) {
            $item->add_meta_data( (string) ( $i + 1 ), sanitize_text_field( $line ), true );
        }
    } elseif ( ! empty( $values['_orto_bundle_pairs'] ) ) {
        // Proizvodi BEZ atributa (bunion, fisiorest, ControlPro ...): nema redaka po
        // komadu, pa se odabrana ponuda ne bi nigdje vidjela. Zapisi vidljivu meta
        // liniju s brojem komada da se u narudzbi zna je li kupljen 1x, 2x, 2+2 ...
        $qty_pairs   = (int) $values['_orto_bundle_pairs'];
        $offer_title = isset( $values['_orto_offer_title'] ) ? trim( (string) $values['_orto_offer_title'] ) : '';
        $meta_value  = $qty_pairs . ' x';
        if ( $offer_title !== '' ) {
            $meta_value = $offer_title . ' (' . $qty_pairs . ' x)';
        }
        $item->add_meta_data( 'Selected package', sanitize_text_field( $meta_value ), true );
    }

    // Skrivena meta (izvjestaji) — uvijek, neovisno o atributima.
    if ( ! empty( $values['_orto_bundle_pairs'] ) ) {
        $item->add_meta_data( '_bundle_pairs', (int) $values['_orto_bundle_pairs'], true );
    }

    if ( ! empty( $values['_orto_offer_id'] ) ) {
        $item->add_meta_data( '_offer_id', sanitize_text_field( $values['_orto_offer_id'] ), true );
    }
}

// ============================================================
// SHGIFTS FREE-GIFTS INFO STRIP (display only, orto-majica-darila)
// ============================================================
/**
 * Purely informational "free gifts" strip shown UNDER the add-to-cart button on
 * the SHGIFTS product (category orto-majica-darila). It only DISPLAYS what comes
 * free with the purchase — no inputs, no selects, no swatches, no JS, no cart
 * interaction. Completely self-contained and does not touch the bundle selector
 * or cart logic. Strictly gated to the orto-majica-darila category.
 */
add_action( 'woocommerce_after_add_to_cart_button', 'gck_shgifts_free_gifts_notice', 20 );
function gck_shgifts_free_gifts_notice() {

    if ( ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }

    global $product;
    if ( ! $product instanceof WC_Product ) {
        return;
    }
    if ( ! has_term( 'orto-majica-darila', 'product_cat', $product->get_id() ) ) {
        return;
    }

    // Gifts shown: 1x bokserica + 5x par carapa. Images + crossed-out reference
    // prices are editable placeholders — adjust to real photos/prices.
    $boxer_img = 'https://noriks.com/hr/wp-content/uploads/2026/01/StarterPack_2xcrnaboksarica.png';
    $socks_img = 'https://noriks.com/hr/wp-content/uploads/2025/11/stumfi_crni3942-683x1024.jpg.webp';

    $gifts = array(
        array( 'label' => '1x bokserica', 'img' => $boxer_img, 'price' => '19,99 €' ),
    );
    for ( $i = 0; $i < 5; $i++ ) {
        $gifts[] = array( 'label' => '1x pair of socks', 'img' => $socks_img, 'price' => '12,99 €' );
    }
    ?>
    <style>
      .gck-fg{
          margin:16px 0 6px;
          background:#f4f4f4;
          border:1px solid #e2e2e2;
          border-radius:9px;
          overflow:hidden;
      }
      .gck-fg__head{
          background:linear-gradient(90deg,#111 0%,#2b2b2b 55%,#3d3d3d 100%);
          color:#fff;
          font-weight:700;
          font-size:15px;
          text-align:center;
          letter-spacing:.3px;
          padding:12px 14px;
      }
      .gck-fg__grid{
          display:flex;
          flex-wrap:nowrap;
          gap:8px;
          justify-content:center;
          padding:18px 12px 14px;
      }
      .gck-fg__card{
          flex:1 1 0;
          min-width:0;
          box-sizing:border-box;
          background:#fff;
          border:1.5px dashed #c9c9c9;
          border-radius:4px;
          padding:16px 6px 9px;
          text-align:center;
          position:relative;
      }
      .gck-fg__badge{
          position:absolute;
          top:-10px; left:50%; transform:translateX(-50%);
          background:#111;
          color:#fff;
          font-size:10px;
          font-weight:800;
          letter-spacing:1px;
          padding:3px 10px;
          border-radius:4px;
          text-transform:uppercase;
          white-space:nowrap;
          box-shadow:0 1px 2px rgba(0,0,0,.18);
      }
      .gck-fg__img{
          width:100%;
          max-width:100%;
          height:74px;
          object-fit:contain;
          margin:6px auto 8px;
          display:block;
      }
      .gck-fg__label{ font-weight:600; font-size:13px; color:#222; }
      .gck-fg__price{ font-size:12px; margin-top:2px; }
      .gck-fg__price s{ color:#8c8c8c; }
      @media (max-width:600px){
          .gck-fg__grid{ flex-wrap:wrap; }
          .gck-fg__card{ flex:1 1 28%; min-width:88px; }
          .gck-fg__img{ height:60px; }
      }
    </style>
    <div class="gck-fg">
        <div class="gck-fg__head">6 BESPLATNIH komada uz svaku kupnju</div>
        <div class="gck-fg__grid">
            <?php foreach ( $gifts as $g ) : ?>
                <div class="gck-fg__card">
                    <span class="gck-fg__badge">Gratis</span>
                    <img class="gck-fg__img" src="<?php echo esc_url( $g['img'] ); ?>" alt="<?php echo esc_attr( $g['label'] ); ?>" loading="lazy">
                    <div class="gck-fg__label"><?php echo esc_html( $g['label'] ); ?></div>
                    <div class="gck-fg__price"><s><?php echo esc_html( $g['price'] ); ?></s></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
