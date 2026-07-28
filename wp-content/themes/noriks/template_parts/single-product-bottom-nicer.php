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

// COMPRESSION T-SHIRTS (orto-kompresijske-majice) — NORIKS FIT
if ( noriks_is_type( 'kompresijske-majice' ) ) {
    include $noriks_pb_dir . 'why-kompresijske-majice.php';
}

// LEAK BOXERS (orto-leak-boxers) — men's incontinence underwear
if ( noriks_is_type( 'leakboxers' ) ) {
    include $noriks_pb_dir . 'why-leakboxers.php';
}

// COMPRESSION SOCKS — not the back belt / bunion corrector / FisioRest
// (those may still carry the socks category from being duplicated)
if ( noriks_is_type( 'kompresijske-nogavice' ) && ! noriks_is_type( 'ortopas' ) && ! noriks_is_type( 'bunion' ) && ! noriks_is_type( 'fisiorest' ) ) {
    include $noriks_pb_dir . 'why-kompresijske.php';
}

// NORIKS HERS (orto-norikshers) — silicone collagen patches
if ( noriks_is_type( 'norikshers' ) ) {
    include $noriks_pb_dir . 'why-norikshers.php';
}

// ORTHOPEDIC BACK BELT (orto-ortopas)
if ( noriks_is_type( 'ortopas' ) ) {
    include $noriks_pb_dir . 'why-ortopas.php';
}

// BUNION CORRECTOR (orto-bunion)
if ( noriks_is_type( 'bunion' ) ) {
    include $noriks_pb_dir . 'why-bunion.php';
}

// FISIOREST (orto-fisiorest) — neck therapy device
if ( noriks_is_type( 'fisiorest' ) ) {
    include $noriks_pb_dir . 'why-fisiorest.php';
}

// ERGOSIT ORTHOPEDIC CUSHION (orto-ortopedski-jastuk)
if ( noriks_is_type( 'ortopedski-jastuk' ) ) {
    include $noriks_pb_dir . 'why-ortopedski-jastuk.php';
}

// SHARED reviews / social proof (all products)
include $noriks_pb_dir . 'reviews.php';
