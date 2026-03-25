
<?php

add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number' );
function change_woocommerce_order_number( $order_id ) {
    $prefix = 'NORIKS-GR-';
    $new_order_id = $prefix . $order_id;
    return $new_order_id;
}




// Change only the sticky bar button (text + href) on single product pages.
add_action( 'wp_footer', function () {
	if ( ! is_product() ) return; ?>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
	  var btn = document.querySelector('.storefront-sticky-add-to-cart__content-button');
	  if (!btn) return;
	  btn.textContent = 'Add to cart';
	  btn.setAttribute('href', '#title-buy-now'); // put your desired URL here
	});
	</script>
<?php
} );
