<?php
/**
 * Obvestilo "ni na zalogi" na strani izdelka.
 *
 * WooCommerce pri variabilnem izdelku obvestilo izpise samo, ce sploh ni variacij.
 * Ce so variacije, a je izdelek oznacen kot razprodan, kupec ni videl nicesar --
 * velikosti so bile le sivo obarvane, gumb pa je ostal viden. Tu izpisemo
 * standardno WooCommerce obvestilo in skrijemo obrazec za nakup.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'woocommerce_before_add_to_cart_form', function () {
    global $product;
    if ( ! $product || ! is_a( $product, 'WC_Product' ) || $product->is_in_stock() ) {
        return;
    }
    ?>
    <p class="stock out-of-stock noriks-oos"><?php echo esc_html( 'Currently out of stock.' ); ?></p>
    <style>
    .single-product .product.outofstock form.cart,
    .single-product .product.outofstock .gck-offer,
    .single-product .product.outofstock .single_add_to_cart_button { display:none !important; }
    .noriks-oos {
        display:block; margin:0 0 18px; padding:14px 16px;
        background:#fdf0f0; border:1px solid #e4b7b7; border-radius:8px;
        color:#a12a2a; font-weight:600; font-size:15px; line-height:1.4;
    }
    </style>
    <?php
}, 5 );
