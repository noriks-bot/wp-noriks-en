<?php
/**
 * ============================================================
 * NORIKS — Front-end performance tweaks (safe, non-destructive)
 * ------------------------------------------------------------
 * Only removes WordPress head bloat that this store does not use,
 * kills the wp-embed script, and adds resource hints for the
 * third-party hosts that appear on every page. No functionality
 * is disabled. Everything here is easy to revert.
 * ============================================================
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1) Remove unused <head> meta links (no SEO / functional impact).
 *    (Emoji is already disabled in functions.php.)
 */
add_action( 'init', function () {
    remove_action( 'wp_head', 'wp_generator' );                        // <meta name="generator">
    remove_action( 'wp_head', 'wlwmanifest_link' );                    // Windows Live Writer
    remove_action( 'wp_head', 'rsd_link' );                            // Really Simple Discovery
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );                // <link rel="shortlink">
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 ); // prev/next rel links
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );       // oEmbed discovery <link>s
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );               // wp-embed.min.js loader
} );

/**
 * 2) Make sure the wp-embed script is not enqueued on the front-end.
 *    (Only used to embed *this* site elsewhere — not needed here.)
 */
add_action( 'wp_footer', function () {
    if ( ! is_admin() ) {
        wp_dequeue_script( 'wp-embed' );
    }
} );

/**
 * 3) Resource hints for third-party hosts used on (almost) every page,
 *    so the browser can open the connections earlier.
 */
add_filter( 'wp_resource_hints', function ( $hints, $relation ) {
    if ( 'preconnect' === $relation ) {
        // Country flags in the header + newsletter popup — present site-wide.
        $hints[] = 'https://static.devit.software';
        $hints[] = 'https://6096.squalomail.net';
        // Brand fonts load site-wide now.
        $hints[] = 'https://fonts.googleapis.com';
        $hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
    }
    return $hints;
}, 10, 2 );

/**
 * 4) Drop Gutenberg / WooCommerce *block* CSS where it is not used.
 *
 *    This theme renders the shop, product, home and archive pages with
 *    its own CSS (main/product/homepage.css), not the block editor, so
 *    the block stylesheets are dead weight there. Regular posts/pages,
 *    the cart and the checkout are left untouched so any block content
 *    or the WooCommerce cart/checkout blocks keep their styles.
 */
add_action( 'wp_enqueue_scripts', function () {
    if ( is_admin() ) {
        return;
    }

    $is_woo_listing = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) // shop / product / product taxonomy
        || ( function_exists( 'is_product' ) && is_product() )
        || is_front_page();

    // Never strip on cart / checkout (they may rely on WC block styles).
    $is_cart_checkout = ( function_exists( 'is_cart' ) && is_cart() )
        || ( function_exists( 'is_checkout' ) && is_checkout() );

    if ( $is_woo_listing && ! $is_cart_checkout ) {
        wp_dequeue_style( 'wp-block-library' );        // Gutenberg core blocks
        wp_dequeue_style( 'wp-block-library-theme' );  // Gutenberg theme blocks
        wp_dequeue_style( 'wc-blocks-style' );         // WooCommerce blocks
        wp_dequeue_style( 'global-styles' );           // theme.json global styles
        wp_dequeue_style( 'classic-theme-styles' );    // WP 6.1+ classic styles shim
    }
}, 100 );

/**
 * 5) (jQuery Migrate removal disabled — some checkout/gateway JS relies on it.)
 */

/**
 * 6) Add defer to the theme's own (dependency-free) scripts so they never
 *    block parsing. jQuery-dependent scripts are intentionally excluded.
 */
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    $defer = array( 'header-js', 'price-update-js' );
    if ( in_array( $handle, $defer, true ) && strpos( $tag, ' defer' ) === false ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }
    return $tag;
}, 10, 2 );
