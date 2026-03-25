<?php


add_action( 'woocommerce_before_shop_loop_item_title', 'add_second_product_thumbnail', 11 );
function add_second_product_thumbnail() {
    global $product;
    $gallery = $product->get_gallery_image_ids();
    if ( ! empty( $gallery ) ) {
        $second = wp_get_attachment_image_src( $gallery[0], 'woocommerce_thumbnail' );
        if ( $second ) {
            echo '<img class="secondary-image" src="' . esc_url( $second[0] ) . '" alt="" />';
        }
    }
}

add_action('woocommerce_before_shop_loop', 'conditionally_remove_bottom_sorting', 1);

function conditionally_remove_bottom_sorting() {
    // Remove sorting dropdown before it's output the second time
    remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10);
}


// Remove default bottom sorting wrapper
remove_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9);
remove_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 11);

// Add custom bottom sorting wrapper with extra class
add_action('woocommerce_after_shop_loop', 'custom_bottom_sorting_wrapper_open', 9);
add_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 11);

function custom_bottom_sorting_wrapper_open() {
    echo '<div class="storefront-sorting storefront-sorting--bottom extra-class">';
}












// Change number of products per row to 4
add_filter('loop_shop_columns', 'custom_loop_columns', 999);
function custom_loop_columns() {
    return 4; // 4 products per row
}



// Show all products on shop/archive pages

add_filter('loop_shop_per_page', 'custom_products_per_page', 999);
function custom_products_per_page($cols) {
    return -1; // -1 means show all
}






