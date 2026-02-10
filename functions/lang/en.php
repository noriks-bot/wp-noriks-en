<?php
/**
 * English (EN) Language Configuration
 * Noriks International Store
 */

// Translate WooCommerce attribute labels
add_filter( 'gettext', 'translate_attribute_labels_en', 20, 3 );
function translate_attribute_labels_en( $translated_text, $text, $domain ) {
    $translations = array(
        'Choose your size' => 'Choose your size',
        'Choose an option' => 'Choose an option',
        'Add to cart' => 'Add to cart',
        'Select options' => 'Select',
        'View cart' => 'View cart',
        'Checkout' => 'Checkout',
        'Proceed to checkout' => 'Proceed to checkout',
        'Update cart' => 'Update cart',
        'Apply coupon' => 'Apply coupon',
        'Coupon code' => 'Coupon code',
        'Cart totals' => 'Cart totals',
        'Subtotal' => 'Subtotal',
        'Total' => 'Total',
        'Shipping' => 'Shipping',
        'Free shipping' => 'Free shipping',
    );
    
    if ( isset( $translations[$text] ) ) {
        return $translations[$text];
    }
    return $translated_text;
}

// Checkout phone placeholder
add_filter( 'woocommerce_checkout_fields', 'custom_billing_phone_placeholder_en' );
function custom_billing_phone_placeholder_en( $fields ) {
    $fields['billing']['billing_phone']['placeholder'] = 'Mobile (e.g.: +1 234 567 8900)';
    return $fields;
}

// Order number prefix
add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number_en' );
function change_woocommerce_order_number_en( $order_id ) {
    $prefix = 'NORIKS-EN-';
    return $prefix . $order_id;
}

// Force country to US (can be changed based on IP)
add_filter( 'default_checkout_billing_country', '__return_us' );
add_filter( 'default_checkout_shipping_country', '__return_us' );
function __return_us() {
    return 'US';
}

// Show country fields for international
add_filter( 'woocommerce_checkout_fields', 'show_country_fields_en' );
function show_country_fields_en( $fields ) {
    // Keep country fields visible for international store
    return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'hide_checkout_fields_en' );
function hide_checkout_fields_en( $fields ) {
    unset( $fields['shipping']['shipping_address_2'] );
    return $fields;
}

/**
 * English translations for hardcoded strings
 */
function noriks_en_translations() {
    return array(
        // Hero section
        'Tričko, které řeší všechny problémy.' => 'The t-shirt that solves all problems.',
        'KOUPIT TEĎ' => 'BUY NOW',
        
        // Collections
        'Nakupujte podle kolekce' => 'Shop by collection',
        'Všechny produkty' => 'All products',
        
        // Category names
        'Trička' => 'T-Shirts',
        'Boxerky' => 'Boxers',
        'Sady' => 'Sets',
        'Startovací balíček' => 'Starter Pack',
        
        // Category descriptions
        'Pohodlí po celý den. Bez vytahování.' => 'All-day comfort. No riding up.',
        'Měkké. Prodyšné. Spolehlivé.' => 'Soft. Breathable. Reliable.',
        'Nejlepší poměr ceny a kvality v setu.' => 'Best value for money in a set.',
        'Vyzkoušej NORIKS výhodněji.' => 'Try NORIKS at a better price.',
        
        // Header marquee
        'Doprava zdarma pro objednávky nad 1700 Kč' => 'Free shipping on orders over $70',
        'Doprava zdarma při objednávkách nad 1700 Kč' => 'Free shipping on orders over $70',
        '30 dní bez rizika – vyzkoušej bez obav' => '30 days risk-free – try without worry',
        
        // Product page features
        'Platba na dobírku' => 'Cash on delivery',
        'Vyzkoušejte 30 dní, bez rizika' => 'Try for 30 days, risk-free',
        
        // Shipping/delivery
        'Objednejte během následujících' => 'Order within',
        'Doručení od' => 'Delivery from',
        'do' => 'to',
        
        // Cart
        'Prosím, pospěš si! Někdo si právě objednal jeden z produktů ve tvém košíku. Rezervace platí už jen' => 'Please hurry! Someone just ordered one of the products in your cart. Reservation valid for only',
        'minut' => 'minutes',
    );
}

/**
 * English weekday names
 */
function noriks_en_weekdays() {
    return array(
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    );
}
