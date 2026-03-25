<?php 


// Append plain-text price to the single Add to Cart button (works for simple + variable).
add_filter( 'woocommerce_product_single_add_to_cart_text', 'my_add_price_to_button_text', 99, 2 );

function my_add_price_to_button_text( $text, $prod = null ) {
    // Fallback to global $product if the filter didn't pass it
    if ( ! $prod || ! is_a( $prod, 'WC_Product' ) ) {
        global $product;
        if ( $product instanceof WC_Product ) {
            $prod = $product;
        } else {
            return $text;
        }
    }

    $price_text = '';

    if ( $prod->is_type( 'simple' ) ) {
        // Display price (respects tax settings & sale price)
        $display_price = wc_get_price_to_display( $prod );
        if ( $display_price !== '' && $display_price !== null ) {
            $price_text = wp_strip_all_tags( wc_price( $display_price ) );
        }
    } elseif ( $prod->is_type( 'variable' ) ) {
        // Show the MIN variation price (clean text)
        $min_var_price = $prod->get_variation_price( 'min', true );
        $display_min   = wc_get_price_to_display( $prod, array( 'price' => $min_var_price ) );
        $price_text    = wp_strip_all_tags( wc_price( $display_min ) );

        // If you prefer a range, replace the 3 lines above with:
        // $min_var_price = $prod->get_variation_price( 'min', true );
        // $max_var_price = $prod->get_variation_price( 'max', true );
        // $price_text = wp_strip_all_tags( wc_price( wc_get_price_to_display( $prod, array( 'price' => $min_var_price ) ) ) )
        //             . ' - ' .
        //             wp_strip_all_tags( wc_price( wc_get_price_to_display( $prod, array( 'price' => $max_var_price ) ) ) );
    } else {
        // Leave other product types unchanged
        return $text;
    }

    if ( $price_text ) {
        // Remove any previous " - …" to avoid duplication from other filters
        $base_text = preg_replace( '/\s*-\s*.*$/', '', $text );
        return trim( $base_text ) . ' - ' . $price_text;
    }

    return $text;
}







add_action( 'woocommerce_before_variations_form', function() {
    get_template_part( 'template_parts/size-chart-modal' );
});



add_action( 'after_setup_theme', 'remove_storefront_product_image_zoom', 99 );
function remove_storefront_product_image_zoom() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}




// Ensure WooCommerce gallery features are on
add_action( 'after_setup_theme', function () {
   // add_theme_support( 'wc-product-gallery-zoom' );
   // add_theme_support( 'wc-product-gallery-lightbox' );
    //add_theme_support( 'wc-product-gallery-slider' );
} );


// Force direction arrows to show on single product gallery
add_filter( 'woocommerce_single_product_carousel_options', function ( $options ) {
    $options['directionNav'] = true;  // show prev/next arrows
    //$options['controlNav']   = true;  // show thumbnails/bullets (optional)
        $options['animationLoop'] = true;   // enable infinite loop

    return $options;
} );


