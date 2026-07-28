<?php
/**
 * Single product — bottom content dispatcher (EN).
 *
 * Per-type "why" content lives in template_parts/product-bottom/why-*.php,
 * the reviews / social-proof block is shared by every product.
 *
 * Product-type detection is centralised in functions/product-type.php
 * (noriks_is_type / noriks_product_type). Change categories there, not here.
 *
 * NOTE: the "why" blocks are independent (a product can match more
 * than one) to preserve the original behaviour.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$noriks_pb_dir = get_template_directory() . '/template_parts/product-bottom/';

// STARTER (starter-pack / orto-starter / orto-majica-bokserica)
if ( noriks_is_type( 'starter' ) ) {
    include $noriks_pb_dir . 'why-starter.php';
}

// T-SHIRTS (t-shirts / orto-majice) — also shown on black-friday
if ( noriks_is_type( 'majice' ) || noriks_is_black_friday() ) {
    include $noriks_pb_dir . 'why-majice.php';
}

// BOXERS (boxers / orto-bokserice) — not on black-friday
if ( noriks_is_type( 'bokserice' ) && ! noriks_is_black_friday() ) {
    include $noriks_pb_dir . 'why-bokserice.php';
}

// SLING CARRIER (orto-nosilka) — NORIKS BabyGo
if ( noriks_is_type( 'nosilka' ) ) {
    include $noriks_pb_dir . 'why-nosilka.php';
}

// KIDSNEST (orto-kidsnest) — kids pillow for healthy breathing
if ( noriks_is_type( 'kidsnest' ) ) {
    include $noriks_pb_dir . 'why-kidsnest.php';
}

// T-SHIRT GIFT BUNDLE (orto-majica-darila) — uses the same why-section as t-shirts
if ( noriks_is_type( 'majica-darila' ) && ! noriks_is_type( 'majice' ) ) {
    include $noriks_pb_dir . 'why-majice.php';
}

// SHARED reviews / social proof (all products)
include $noriks_pb_dir . 'reviews.php';
