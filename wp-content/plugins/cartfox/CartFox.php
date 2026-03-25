<?php
/*
Plugin Name: Abandoned cart SMS reminders and SMS campaigns - CartFox
Description: Dynamic SMS abandoned cart reminders with coupons, post-purchase campaigns and various options for SMS campaigns. Available for 25 languages worldwide.
Author: CartFox
Version: 4.0.8
*/

define('CARTFOX_INTEGRATION_PLATFORM', "WooCommerce");
define('CARTFOX_INTEGRATION_VERSION', "4.0.8");

function cartfox_add_integration_to_payload($payload){
	$payload["integration"] = [
		"platform" => CARTFOX_INTEGRATION_PLATFORM,
		"version" => CARTFOX_INTEGRATION_VERSION,
	];
	return $payload;
}

add_action('wp_footer', 'cartfox_register_input_change'); 
function cartfox_register_input_change() {
	$countries_obj   = new WC_Countries();
	$default_country = $countries_obj->get_base_country();

	$cm_options = get_option( 'cartfox_settings' );

    ?>
		<script type="text/javascript">


			var script_timeout = 0;
			if(document.getElementById("fc-wrapper")) script_timeout = 2000;
			setTimeout(function(){
				function setCookie(key, value, expiry) {
			        var expires = new Date();
			        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
			        document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
			    }
			    
			    function getCookie(key) {
	                var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
	                return keyValue ? keyValue[2] : null;
	            }

				var cm_ajax_running = false;
				var cm_ajax_queue = false;

				var plugins_additional_selectors = ", .woocommerce-checkout input, .woocommerce-checkout select, .woocommerce-checkout textarea";

				<?php if(isset($cm_options["cartfox_special_checkout_fields"]) && $cm_options["cartfox_special_checkout_fields"] != ""): ?>
					plugins_additional_selectors += ", input, select, textarea";
    			<?php endif; ?>

				jQuery(function($){
					$('.woocommerce-billing-fields input, .woocommerce-billing-fields select, .woocommerce-billing-fields textarea' + plugins_additional_selectors).on('change', function () {
						var billing_inputs = $("input[name^=billing_], select[name^=billing_], input[name^=shipping_], select[name^=shipping_]");
						var cm_data = [];
						$.each(billing_inputs, function(i, e){

							if(e.name == "billing_cf_optin"){
								e.value = 0;
								if($(e).is(':checked')){
									e.value = 1;
								}
							}

							cm_data[i] = {"key": e.name, "value": e.value};
						});

					    var data = {
					        action: 'cartfox_send_data',
					        cm_data: cm_data,
					    };

					    ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';

					    if($("input[name=billing_phone]").val() != ""){
						    $.ajax({
								type: "POST",
								url: ajaxurl,
								data: data,
								beforeSend: function() {
									if(cm_ajax_running == true){
										console.log("CartFox: Request already running");
										cm_ajax_queue = true;
										return false;
									}

									cm_ajax_running = true;
								},
								success: function(response) {
						        	var cm_response = JSON.parse(response);
						        	if(cm_response.msg) console.log(cm_response.msg);
											else console.log(cm_response);
						        	if(cm_response.sid){
						        	    if(!getCookie('cf_sid')){
						        		    setCookie('cf_sid',cm_response.sid,'3');
						        	    }
						        		localStorage.setItem("cf_sid", cm_response.sid);
						        	}
						        	cm_ajax_running = false;
						        	if(cm_ajax_queue == true){
						        		console.log("CartFox: Queue running");
						        		cm_ajax_queue = false;
						        		$("input[name=billing_phone]").trigger("change");
						        	}
								}
							});

						    //jQuery.post(ajaxurl, data, function(response) {
						    //    console.log(JSON.parse(response));
						    //});
						}
					});

					if(document.getElementById("fc-wrapper")){
						$("input[name=billing_phone]").trigger("change");
					}
				});
			}, script_timeout);
		</script>
	<?php 

}




add_action('wp_ajax_nopriv_cartfox_send_data', 'cartfox_send_data');
add_action('wp_ajax_cartfox_send_data', 'cartfox_send_data');
function cartfox_send_data() {

    global $wp, $wpdb, $woocommerce, $post, $post_id;
    $cm_options = get_option( 'cartfox_settings' );
    $cm_api_key = sanitize_text_field($cm_options["cartfox_api_key"]);

    if(isset($cm_options["cartfox_optin"]) && $cm_options["cartfox_optin"] != ""){
    	$cm_opt_in = sanitize_text_field($cm_options["cartfox_optin"]);
	} else {
		$cm_opt_in = false;
	}

    

    $contact_info = [];

    if(!isset($_REQUEST['cm_data'])){
    	echo json_encode(["msg" => "CF: Form not present."]);
		wp_die();
    }

    foreach($_REQUEST['cm_data'] as $cd){
    	$cd = (object)$cd;

    	if($cd->key == "billing_email" || $cd->key == "shipping_email"){
			$contact_info[$cd->key] = sanitize_email($cd->value);
		} else {
			$contact_info[$cd->key] = sanitize_text_field($cd->value);
		}
    }

    $contact_info["name"] = $contact_info["billing_first_name"] . " " . $contact_info["billing_last_name"];
    $contact_info["phone"] = $contact_info["billing_phone"];
    $contact_info["email"] = $contact_info["billing_email"];
    $contact_info["address"] = $contact_info["billing_address_1"];
    $contact_info["house_number"] = $contact_info["billing_houseno"];
    $contact_info["city"] = $contact_info["billing_city"];
    $contact_info["post_number"] = $contact_info["billing_postcode"];
    $contact_info["country"] = $contact_info["billing_country"];

    if($contact_info["phone"] == "" || !isset($contact_info["phone"])){
    	echo json_encode(["msg" => "CartFox warning: data not sent - phone is empty"]);
		wp_die();
    }

    if($cm_opt_in == "only_checkbox" && isset($contact_info["billing_cf_optin"]) && $contact_info["billing_cf_optin"] != 1){
    	echo json_encode(["msg" => "CartFox warning: Opt-In not checked"]);
		wp_die();
    }

    $cart_products = [];
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product = $cart_item['data'];

        $line_price = $cart_item["new_price"];
        if(!$line_price || $line_price == 0){
        	$line_price = $cart_item["line_total"];
        }

        $product_categories = [];
        $category_ids = wp_get_post_terms($cart_item["product_id"], 'product_cat', array('fields' => 'ids'));
        $category_names = wp_get_post_terms($cart_item["product_id"], 'product_cat', array('fields' => 'names'));

        if($category_ids){
        	for($i = 0; $i < count($category_ids); $i++){
        		$product_categories[$category_ids[$i]] = $category_names[$i];
        	}
        }

        if(!empty($product)){
            $cart_products[] = [
            	"id" => $product->get_sku(),
            	"name" => $product->get_title(),
            	"currency" => get_option('woocommerce_currency'),
            	"quantity" => $cart_item["quantity"],
            	"price" => $line_price,
            	"wp_product_id" => $cart_item["product_id"],
            	"wp_variation_id" => $cart_item["variation_id"],
            	"image" => wp_get_attachment_image_url($product->get_image_id(), 'full' ),
            	"categories" => $product_categories,
            	"complete_cart_item" => $cart_item
            ];
        }
    }

    $cm_default_country =  sanitize_text_field($cm_options["cartfox_default_country"]);


	if(isset($contact_info["country"])) $cm_default_country = $contact_info["country"];


	$cf_checkout_url = str_replace(get_site_url(), "", wc_get_checkout_url()) . '?cartfox_return_to_checkout=1';
	if(isset($cm_options["cartfox_dynamic_cart_url"]) && $cm_options["cartfox_dynamic_cart_url"] == 1){
		$cf_checkout_url = add_query_arg( 'cartfox_return_to_checkout', '1', $_SERVER['HTTP_REFERER']);
		$cf_checkout_url = str_replace(get_site_url(), "", $cf_checkout_url);
	}

    $config = [
    	"api_key" => $cm_api_key,
    	"country" => $cm_default_country,
    	"autofill" => true,
    	"order_id" => "",
    	"checkout_url" => $cf_checkout_url,
    ]; 

    $cf_sid = "";
    $cf_ref = "";
    $cf_msg_id = "";

    if(isset($_COOKIE['cf_sid'])) $cf_sid = sanitize_text_field($_COOKIE['cf_sid']);
    if(isset($_COOKIE['cf_ref'])) $cf_ref = sanitize_text_field($_COOKIE['cf_ref']);
    if(isset($_COOKIE['cf_msg_id'])) $cf_msg_id = sanitize_text_field($_COOKIE['cf_msg_id']);

    $payload = [
    	"domain_host" => get_site_url(),
    	"config" => $config,
    	"data" => $contact_info,
    	"session_id" => $cf_sid,
    	"sent_timestamp" => time(),
    	"shopping_cart" => $cart_products
    ];

    $payload = cartfox_add_integration_to_payload($payload);


    $cf_api_endpoint = "https://api.cartfox.io/event_receiver.php";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);


    if($cf_response->status == "success"){
        if(!isset($_COOKIE['cf_sid'])){
		    setcookie('cf_sid', sanitize_text_field($cf_response->session_id), time()+86400);
        }
		echo json_encode(["msg" => "CartFox success: " . sanitize_text_field($cf_response->message), "sid" => sanitize_text_field($cf_response->session_id)]);
		wp_die();
	} else {
		echo json_encode(["msg" => "CartFox error: " . sanitize_text_field($cf_response->message)]);
		wp_die();
	}

}






add_filter( 'woocommerce_checkout_fields' , 'cartfox_load_data', 9999 ); 
function cartfox_load_data( $attr ) { 
	if(!isset($_COOKIE["cf_sid"]) || !isset($_COOKIE["cf_msg_id"]) || $_COOKIE["cf_ref"] == "sms_campaign"){
		return $attr; 
	}

	if(isset($_POST["billing_phone"]) && $_POST["billing_phone"] != ""){
		return $attr;
	}

	$cm_options = get_option( 'cartfox_settings' );
    $cm_api_key = $cm_options["cartfox_api_key"];

    $cm_default_country = $cm_options["cartfox_default_country"];

    $config = [
    	"api_key" => $cm_api_key,
    	"country" => $cm_default_country,
    	"autofill" => true,
    	"order_id" => "",
    	"checkout_url" => str_replace(get_site_url(), "", wc_get_checkout_url()) . '?cartfox_return_to_checkout=1',
    ]; 


    $cf_sid = "";
    $cf_ref = "";
    $cf_msg_id = "";

    if(isset($_COOKIE['cf_sid'])) $cf_sid = sanitize_text_field($_COOKIE['cf_sid']);
    if(isset($_COOKIE['cf_ref'])) $cf_ref = sanitize_text_field($_COOKIE['cf_ref']);
    if(isset($_COOKIE['cf_msg_id'])) $cf_msg_id = sanitize_text_field($_COOKIE['cf_msg_id']);

    $payload = [
    	"domain_host" => get_site_url(),
    	"config" => $config,
    	"session_id" => $cf_sid,
		"msg_id" => $cf_msg_id,
    ];


    $payload = cartfox_add_integration_to_payload($payload);

    $cf_api_endpoint = "https://api.cartfox.io/event_loader.php";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);

	if($cf_response->status == "success"){
		foreach($cf_response->data as $input_name => $input_value){
			if($input_name == "billing_email" || $input_name == "shipping_email"){
				$_POST[$input_name] = sanitize_email($input_value);
			} else {
				$_POST[$input_name] = sanitize_text_field($input_value);
			}
			
		}
	}	


	if(isset($cm_options["cartfox_custom_thank_you"]) && $cm_options["cartfox_custom_thank_you"] != ""){
		?>
			<script type="text/javascript">
				jQuery(function($){
					localStorage.setItem("cf_sid", '<?php echo esc_js($_COOKIE['cf_sid']); ?>');
					localStorage.setItem("cf_ref", '<?php echo esc_js($_COOKIE['cf_ref']); ?>');
					localStorage.setItem("cf_msg_id", '<?php echo esc_js($_COOKIE['cf_msg_id']); ?>');
				});
			</script>
		<?php
	} 

	return $attr; 
}


add_action( 'wp', 'cartfox_return_to_checkout' );
function cartfox_return_to_checkout() {
    if ( ! is_admin() && isset( $_GET['cartfox_return_to_checkout'] ) && isset( $_GET['cf_sid'] ) && isset( $_GET['cf_ref'] ) && isset( $_GET['cf_msg_id'] ) ) {

			$cf_sid = sanitize_text_field($_GET['cf_sid']);
		setcookie('cf_sid', $cf_sid, time()+86400, "/");


		$cf_ref = sanitize_text_field($_GET['cf_ref']);
		setcookie('cf_ref', $cf_ref, time()+86400, "/");

		$cf_msg_id = sanitize_text_field($_GET['cf_msg_id']);
		setcookie('cf_msg_id', $cf_msg_id, time()+86400, "/");

		
		$cm_options = get_option( 'cartfox_settings' );
	    $cm_api_key = sanitize_text_field($cm_options["cartfox_api_key"]);

	    $cm_default_country = sanitize_text_field($cm_options["cartfox_default_country"]);

	    $config = [
	    	"api_key" => $cm_api_key,
	    	"country" => $cm_default_country,
	    	"autofill" => true,
	    	"order_id" => "",
	    	"checkout_url" => '?cartfox_return_to_checkout=1',
	    ]; 


	    $payload = [
	    	"domain_host" => get_site_url(),
	    	"config" => $config,
	    	"session_id" => $cf_sid,
				"msg_id" => $cf_msg_id,
	    ];

	    $payload = cartfox_add_integration_to_payload($payload);


		// YOURLS is visiting URL after curl request, so we prevent action to trigger SMS opened
		if(isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
			//YOURLS v1.7.4 +http://yourls.org/ (running on https://sman.si)
			$ue = strpos($_SERVER['HTTP_USER_AGENT'], 'http://yourls.org');
			if($ue !== false)
				$payload['trigger_ctr'] = false;
			else
				$payload['trigger_ctr'] = true;
		}

	    $cf_api_endpoint = "https://api.cartfox.io/event_loader.php";
	    $cf_api_data = json_encode($payload);

	    $cf_api_args = [
	    	'body'        => $cf_api_data,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
	    ];


	    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
	    $cf_body = wp_remote_retrieve_body($cf_response);
	    $cf_response = json_decode($cf_body, false);

		if($cf_response->status == "success"){
			WC()->cart->empty_cart();
			foreach($cf_response->shopping_cart as $cart_item){
				WC()->cart->add_to_cart( $cart_item->wp_product_id, $cart_item->quantity, $cart_item->wp_variation_id);
			}

			$discount_data = json_decode($cf_response->message_settings->discount);
			if($discount_data->enabled == "on"){
				if($discount_data->type == "Percentage"){
					$coupon_code = 'cf_p_' . uniqid();
					$amount = $discount_data->amount;
					$discount_type = 'percent'; 
					          
					$coupon = array(
					    'post_title' => $coupon_code,
					    'post_content' => '',
					    'post_status' => 'publish',
					    'post_author' => 1,
					    'post_type'     => 'shop_coupon'
					);
					            
					if(!wc_get_coupon_id_by_code( $coupon_code ) ) {
						$new_coupon_id = wp_insert_post( $coupon );
					            
						// Add meta
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
						update_post_meta( $new_coupon_id, 'product_ids', '' );
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '1' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
					}

					WC()->cart->add_discount($coupon_code);
				}

				if($discount_data->type == "Free shipping"){
					$coupon_code = 'cf_fs';
					$amount = 0;
					$discount_type = 'percent'; 
					          
					$coupon = array(
					    'post_title' => $coupon_code,
					    'post_content' => '',
					    'post_status' => 'publish',
					    'post_author' => 1,
					    'post_type'     => 'shop_coupon'
					);
					


					if(!wc_get_coupon_id_by_code( $coupon_code ) ) {

						$new_coupon_id = wp_insert_post( $coupon );
						            
						// Add meta
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
						update_post_meta( $new_coupon_id, 'product_ids', '' );
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'yes' );
					}


					WC()->cart->add_discount($coupon_code);
				}


				if($discount_data->type == "Custom coupon"){
					WC()->cart->add_discount($discount_data->amount);
				}
			}
		}

		$redirect_to = wc_get_checkout_url();
		$cf_query_params = cf_get_query_string_parameters();
		if($cf_query_params) $redirect_to .= '?' . $cf_query_params;

		if(headers_sent()){
			echo "<script>window.location='". $redirect_to ."'</script>";
			exit();
		} else {
			wp_safe_redirect($redirect_to);
		}
		exit();

    }
}


function cf_get_query_string_parameters() {
	$request = ltrim($_SERVER['REQUEST_URI'], "/");
	if(strpos($request, '?') === false) return '';
	$request = explode("?", $request);
	$request = end($request);
	$params = rtrim($request, '/');
	parse_str($params, $query_params);
	$unset_vars = ['cartfox_return_to_checkout', 'cartfox_campaign', 'cartfox_post_purchase', 'cf_sid', 'cf_msg_id', 'cf_ref'];
	foreach($unset_vars as $utm) unset($query_params[$utm]);
	return http_build_query($query_params);
}


add_action( 'wp', 'cartfox_campaign' );
function cartfox_campaign() {
    if ( ! is_admin() && isset( $_GET['cartfox_campaign'] ) && isset( $_GET['cf_sid'] ) && isset( $_GET['cf_ref'] ) && isset( $_GET['cf_msg_id'] ) ) {

			$cf_sid = sanitize_text_field($_GET['cf_sid']);
		setcookie('cf_sid', $cf_sid, time()+86400, "/");


		$cf_ref = sanitize_text_field($_GET['cf_ref']);
		setcookie('cf_ref', $cf_ref, time()+86400, "/");

		$cf_msg_id = sanitize_text_field($_GET['cf_msg_id']);
		setcookie('cf_msg_id', $cf_msg_id, time()+86400, "/");

		
		$cm_options = get_option( 'cartfox_settings' );
	    $cm_api_key = sanitize_text_field($cm_options["cartfox_api_key"]);

	    $cm_default_country = sanitize_text_field($cm_options["cartfox_default_country"]);

	    $config = [
	    	"api_key" => $cm_api_key,
	    	"country" => $cm_default_country,
	    	"autofill" => true,
	    	"order_id" => "",
	    	"checkout_url" => '?cartfox_return_to_checkout=1',
	    ]; 


	    $payload = [
	    	"domain_host" => get_site_url(),
	    	"config" => $config,
	    	"session_id" => $cf_sid,
			"msg_id" => $cf_msg_id,
			"order_reference" => $cf_ref
	    ];

	    $payload = cartfox_add_integration_to_payload($payload);

		// YOURLS is visiting URL after curl request, so we prevent action to trigger SMS opened
		if(isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
			//YOURLS v1.7.4 +http://yourls.org/ (running on https://sman.si)
			$ue = strpos($_SERVER['HTTP_USER_AGENT'], 'http://yourls.org');
			if($ue !== false)
				$payload['trigger_ctr'] = false;
			else
				$payload['trigger_ctr'] = true;
		}

	    $cf_api_endpoint = "https://api.cartfox.io/event_loader.php";
	    $cf_api_data = json_encode($payload);

	    $cf_api_args = [
	    	'body'        => $cf_api_data,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
	    ];


	    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
	    $cf_body = wp_remote_retrieve_body($cf_response);
	    $cf_response = json_decode($cf_body, false);


		if($cf_response->status == "success"){
			$discount_data = json_decode($cf_response->message_settings->discount);
			if($discount_data->enabled == "on"){
				if($discount_data->type == "Percentage"){
					$coupon_code = 'cf_p_' . uniqid();
					$amount = $discount_data->amount;
					$discount_type = 'percent'; 
					          
					$coupon = array(
					    'post_title' => $coupon_code,
					    'post_content' => '',
					    'post_status' => 'publish',
					    'post_author' => 1,
					    'post_type'     => 'shop_coupon'
					);
					            
					if(!wc_get_coupon_id_by_code( $coupon_code ) ) {
						$new_coupon_id = wp_insert_post( $coupon );
					            
						// Add meta
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
						update_post_meta( $new_coupon_id, 'product_ids', '' );
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '1' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
					}

					setcookie('cf_campaign_discount_code', $coupon_code, time()+86400, "/");
				}

				if($discount_data->type == "Free shipping"){
					$coupon_code = 'cf_fs';
					$amount = 0;
					$discount_type = 'percent'; 
					          
					$coupon = array(
					    'post_title' => $coupon_code,
					    'post_content' => '',
					    'post_status' => 'publish',
					    'post_author' => 1,
					    'post_type'     => 'shop_coupon'
					);
					


					if(!wc_get_coupon_id_by_code( $coupon_code ) ) {

						$new_coupon_id = wp_insert_post( $coupon );
						            
						// Add meta
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
						update_post_meta( $new_coupon_id, 'product_ids', '' );
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'yes' );
					}


					setcookie('cf_campaign_discount_code', $coupon_code, time()+86400, "/");
				}


				if($discount_data->type == "Custom coupon"){
					setcookie('cf_campaign_discount_code', $discount_data->amount, time()+86400, "/");
				}
			}

		}


		$redirect_to = get_site_url();
		$cf_query_params = cf_get_query_string_parameters();
		if($cf_query_params) $redirect_to .= '?' . $cf_query_params;

		if(isset($cf_response->redirect_url) && $cf_response->redirect_url != ""){
			$redirect_to = sanitize_url($cf_response->redirect_url);
		} 
		
		if(headers_sent()){
			echo "<script>window.location='". $redirect_to ."'</script>";
			exit();
		} else {
			wp_safe_redirect($redirect_to);
		}
		exit();

    } 
}


add_action( 'wp', 'cartfox_post_purchase' );
function cartfox_post_purchase() {


    if ( ! is_admin() && isset( $_GET['cartfox_post_purchase'] ) && isset( $_GET['cf_sid'] ) && isset( $_GET['cf_ref'] ) && isset( $_GET['cf_msg_id'] ) ) {

			$cf_sid = sanitize_text_field($_GET['cf_sid']);
		setcookie('cf_sid', $cf_sid, time()+86400, "/");


		$cf_ref = sanitize_text_field($_GET['cf_ref']);
		setcookie('cf_ref', $cf_ref, time()+86400, "/");

		$cf_msg_id = sanitize_text_field($_GET['cf_msg_id']);
		setcookie('cf_msg_id', $cf_msg_id, time()+86400, "/");

		
		$cm_options = get_option( 'cartfox_settings' );
	    $cm_api_key = sanitize_text_field($cm_options["cartfox_api_key"]);

	    $cm_default_country = sanitize_text_field($cm_options["cartfox_default_country"]);

	    $config = [
	    	"api_key" => $cm_api_key,
	    	"country" => $cm_default_country,
	    	"autofill" => true,
	    	"order_id" => "",
	    	"checkout_url" => '?cartfox_return_to_checkout=1',
	    ]; 


	    $payload = [
	    	"domain_host" => get_site_url(),
	    	"config" => $config,
	    	"session_id" => $cf_sid,
			"msg_id" => $cf_msg_id,
			"order_reference" => $cf_ref
	    ];

	    $payload = cartfox_add_integration_to_payload($payload);

		if(isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
			$ue = strpos($_SERVER['HTTP_USER_AGENT'], 'http://yourls.org');
			if($ue !== false)
				$payload['trigger_ctr'] = false;
			else
				$payload['trigger_ctr'] = true;
		}

	    $cf_api_endpoint = "https://api.cartfox.io/event_loader.php";
	    $cf_api_data = json_encode($payload);

	    $cf_api_args = [
	    	'body'        => $cf_api_data,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
	    ];


	    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
	    $cf_body = wp_remote_retrieve_body($cf_response);
	    $cf_response = json_decode($cf_body, false);

		if($cf_response->status == "success"){
			$discount_data = json_decode($cf_response->message_settings->discount);

			$hide_product_ids = [];
			if($cf_response->hide_product_type == "sku"){
				$hide_product_ids = [];
				foreach($cf_response->hide_product_ids as $hp_sku){
					$hide_product_ids[] =  wc_get_product_id_by_sku($hp_sku);
				}
			}

			if($cf_response->hide_product_type == "id"){
				$hide_product_ids = [];
				foreach($cf_response->hide_product_ids as $hp_id){
					$hide_product_ids[] =  $hp_id;
				}
			}


			if(count($hide_product_ids) > 0){
				setcookie('cf_h_prods', json_encode($hide_product_ids), time()+86400, "/");
			}
			

			if($discount_data->enabled == "on"){
				if($discount_data->type == "Percentage"){
					$coupon_code = 'cf_p_' . uniqid();
					$amount = $discount_data->amount;
					$discount_type = 'percent'; 
					          
					$coupon = array(
					    'post_title' => $coupon_code,
					    'post_content' => '',
					    'post_status' => 'publish',
					    'post_author' => 1,
					    'post_type'     => 'shop_coupon'
					);
					            
					if(!wc_get_coupon_id_by_code( $coupon_code ) ) {
						$new_coupon_id = wp_insert_post( $coupon );
					            
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
						update_post_meta( $new_coupon_id, 'product_ids', '' );
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '1' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
					}

					setcookie('cf_campaign_discount_code', $coupon_code, time()+86400, "/");
				}

				if($discount_data->type == "Free shipping"){
					$coupon_code = 'cf_fs';
					$amount = 0;
					$discount_type = 'percent'; 
					          
					$coupon = array(
					    'post_title' => $coupon_code,
					    'post_content' => '',
					    'post_status' => 'publish',
					    'post_author' => 1,
					    'post_type'     => 'shop_coupon'
					);
					


					if(!wc_get_coupon_id_by_code( $coupon_code ) ) {

						$new_coupon_id = wp_insert_post( $coupon );
						            
						// Add meta
						update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
						update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
						update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
						update_post_meta( $new_coupon_id, 'product_ids', '' );
						update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
						update_post_meta( $new_coupon_id, 'usage_limit', '' );
						update_post_meta( $new_coupon_id, 'expiry_date', '' );
						update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
						update_post_meta( $new_coupon_id, 'free_shipping', 'yes' );
					}


					setcookie('cf_campaign_discount_code', $coupon_code, time()+86400, "/");
				}


				if($discount_data->type == "Custom coupon"){
					setcookie('cf_campaign_discount_code', $discount_data->amount, time()+86400, "/");
				}
			}

		}

		$redirect_to = get_site_url();
		$cf_query_params = cf_get_query_string_parameters();
		if($cf_query_params) $redirect_to .= '?' . $cf_query_params;

		if(isset($cf_response->redirect_url) && $cf_response->redirect_url != ""){
			$redirect_to = sanitize_url($cf_response->redirect_url);
		} 
		
		if(headers_sent()){
			echo "<script>window.location='". $redirect_to ."'</script>";
			exit();
		} else {
			wp_safe_redirect($redirect_to);
		}
		exit();

    }
}


add_action( 'pre_get_posts', 'cartfox_hide_if_already_bought', 9999);
add_action( 'woocommerce_product_query', 'cartfox_hide_if_already_bought', 9999);
 
function cartfox_hide_if_already_bought($q) {
    if ( is_admin() ) return;
    if(isset($_COOKIE["cf_h_prods"])){
    	$hide_products = json_decode($_COOKIE["cf_h_prods"]);
    	$q->set( 'post__not_in', $hide_products );
    }
}


if(isset($_COOKIE["cf_campaign_discount_code"])){
	add_action('woocommerce_add_to_cart', 'cartfox_apply_discount_to_order');
}

function cartfox_apply_discount_to_order() {
    global $woocommerce;

    WC()->cart->add_discount(sanitize_text_field($_COOKIE['cf_campaign_discount_code']));
    unset($_COOKIE['cf_campaign_discount_code']);
}




add_action( 'woocommerce_before_main_content', 'cartfox_check_if_cart_is_filled');
function cartfox_check_if_cart_is_filled() {
	if(isset($_COOKIE['cf_sid']) && isset($_COOKIE['cf_msg_id']) && $_COOKIE["cf_ref"] == "sms" && WC()->cart->get_cart_contents_count() == 0){
		$cm_options = get_option( 'cartfox_settings' );
	    $cm_api_key = sanitize_text_field($cm_options["cartfox_api_key"]);

	    $countries_obj   = new WC_Countries();
	    $default_country = $countries_obj->get_base_country();

	    $config = [
	    	"api_key" => $cm_api_key,
	    	"country" => $default_country,
	    	"autofill" => true,
	    	"order_id" => "",
	    	"checkout_url" => '?cartfox_return_to_checkout=1',
	    ]; 

	    $cf_sid = "";
	    if(isset($_COOKIE['cf_sid'])) $cf_sid = sanitize_text_field($_COOKIE['cf_sid']);

	    $cf_msg_id = "";
	    if(isset($_COOKIE['cf_msg_id'])) $cf_msg_id = sanitize_text_field($_COOKIE['cf_msg_id']);

	    $payload = [
	    	"domain_host" => get_site_url(),
	    	"config" => $config,
	    	"session_id" => $cf_sid,
			"msg_id" => $cf_msg_id,
	    ];

	    $payload = cartfox_add_integration_to_payload($payload);

	    $cf_api_endpoint = "https://api.cartfox.io/event_loader.php";
	    $cf_api_data = json_encode($payload);

	    $cf_api_args = [
	    	'body'        => $cf_api_data,
			'timeout'     => '10',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
	    ];


	    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
	    $cf_body = wp_remote_retrieve_body($cf_response);
	    $cf_response = json_decode($cf_body, false);
		if($cm_response->status == "success"){
			WC()->cart->empty_cart();
			foreach($cm_response->shopping_cart as $cart_item){
				WC()->cart->add_to_cart( $cart_item->wp_product_id, $cart_item->quantity);
			}

			if(headers_sent()){
				echo "<script>window.location='". wc_get_checkout_url() ."'</script>";
				exit();
			} else {
				wp_safe_redirect( wc_get_checkout_url() );
			}
			exit();
		}
	}	
}



add_action( 'woocommerce_new_order', 'cartfox_send_purchase_request');
add_action( 'woocommerce_payment_complete', 'cartfox_send_purchase_request');
add_action( 'woocommerce_checkout_order_processed', 'cartfox_send_purchase_request');
function cartfox_send_purchase_request( $order_id ) {
	$order = wc_get_order( $order_id );
    $order_number = $order->get_order_number();
    $order_total = $order->get_total();
    $currency = get_option('woocommerce_currency');


    $phone = $order->get_billing_phone();
	if ( empty($phone) ) {
	    $phone = $order->get_shipping_phone();
	}


	$email = '';
	if (empty($order->get_billing_email()) ) {
	    $shipping_address = $order->get_address('shipping');
	    $email = $shipping_address['email'] ?? '';
	} else {
		$email = $order->get_billing_email();
	}

	$contact_info = [
		"email" => $email,
		"phone" => $phone,
	];


    $cm_options = get_option( 'cartfox_settings' );
    $cm_api_key = $cm_options["cartfox_api_key"];
    $cm_default_country = $cm_options["cartfox_default_country"];

    $shopping_cart = [];

	foreach ( $order->get_items() as $item_id => $item ) {

		$product = $item->get_product(); // see link above to get $product info

		$shopping_cart[] = [
			"id" => $product->get_sku(),
	    	"name" => $product->get_title(),
	    	"currency" => get_option('woocommerce_currency'),
	    	"quantity" => $item->get_quantity(),
	    	"price" => $item->get_total(),
	    	"wp_product_id" => $item->get_product_id(),
	    	"wp_variation_id" => $item->get_variation_id(),
		];
	}





    $config = [
    	"api_key" => $cm_api_key,
    	"country" => $cm_default_country,
    	"autofill" => true,
    	"order_id" => $order_number,
    	"order_data" => [
    		"order_id" => $order_number,
    		"order_value" => $order_total,
    		"currency" => $currency,
    		"shopping_cart" => $shopping_cart,
    		"payment_method" => $order->get_payment_method(),
    	]
    ]; 

    $cf_sid = "";
    $cf_ref = "";
    $cf_msg_id = "";

    if(isset($_COOKIE['cf_sid'])) $cf_sid = sanitize_text_field($_COOKIE['cf_sid']);
    if(isset($_COOKIE['cf_ref'])) $cf_ref = sanitize_text_field($_COOKIE['cf_ref']);
    if(isset($_COOKIE['cf_msg_id'])) $cf_msg_id = sanitize_text_field($_COOKIE['cf_msg_id']);

    $payload = [
    	"domain_host" => get_site_url(),
    	"config" => $config,
    	"data" => $contact_info,
    	"session_id" => $cf_sid,
    	"sent_timestamp" => time(),
    	"order_reference" => $cf_ref,
		"msg_id" => $cf_msg_id,
    ];

    $payload = cartfox_add_integration_to_payload($payload);

    
    $cf_api_endpoint = "https://api.cartfox.io/event_receiver.php";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);
 
	unset($_COOKIE['cf_sid']);
	unset($_COOKIE['cf_ref']);
	unset($_COOKIE['cf_msg_id']);

}

// Move phone field to the top if option is selected
$options = get_option( 'cartfox_settings' );
if(isset($options['cartfox_move_phone']) && $options['cartfox_move_phone'] == 1){
	add_filter( 'woocommerce_billing_fields', 'cartfox_reorder_checkout_fields', 99999);
 
	function cartfox_reorder_checkout_fields( $address_fields ) {
	  	$address_fields['billing_phone']['priority'] = 1;

	  	return $address_fields;
	}
}

$options = get_option( 'cartfox_settings' );
if(isset($options['cartfox_optin'])){
	if($options['cartfox_optin'] == "show_checkbox" || $options['cartfox_optin'] == "only_checkbox"){
		add_filter( 'woocommerce_checkout_fields', 'cartfox_add_optin_checkbox', 2000);
	 
		function cartfox_add_optin_checkbox( $fields ) {

			$opt_in_text = get_option( 'cartfox_settings' )["cartfox_optin_text"];
	     	$priority = isset($fields['billing']['phone']) ? $fields['billing']['phone']['priority'] - 1 : 10;

			$fields['billing']['billing_cf_optin'] = array(
			    'type' => 'checkbox',
			    'label' => __(esc_html($opt_in_text), 'woocommerce'),
			    'placeholder' => _x('cf_optin', 'placeholder', 'woocommerce'),
			    'required' => false,
			    'class' => array('form-row-wide'),
			    'clear' => true,
			    'priority' => $priority
			);

		     return $fields;
		}
	}
}





// cron job - clear coupon codes
add_filter( 'cron_schedules', 'cartfox_everyday_clean_coupons' );
function cartfox_everyday_clean_coupons( $schedules ) {
    $schedules['cartfox_everyday'] = array(
            'interval'  => 60 * 60 * 24,
            'display'   => __( '[CartFox] Every day (clean coupons)', 'textdomain' )
    );
    return $schedules;
}

if ( ! wp_next_scheduled( 'cartfox_everyday_clean_coupons' ) ) {
    wp_schedule_event( time(), 'cartfox_everyday', 'cartfox_everyday_clean_coupons' );
}

add_action( 'cartfox_everyday_clean_coupons', 'cartfox_everyday_function_clean_coupons' );
function cartfox_everyday_function_clean_coupons() {
	 $coupon_posts = get_posts( array(
        'posts_per_page'   => -1,
        'orderby'          => 'name',
        'order'            => 'asc',
        'post_type'        => 'shop_coupon',
        'post_status'      => 'publish',
    ) );

 	foreach($coupon_posts as $coupon){
 		if(strtotime($coupon->post_date) < strtotime('-1 week')){
			if(strpos($coupon->post_name, 'cf_p_') !== false){
	    		wp_delete_post($coupon->ID);
	    	}
	    }
	}
}




// CartFox send Transactional API request
function cartfox_send_transactional_messages($template_id, $order_id){
	$options = get_option( 'cartfox_settings' );

	//$template_settings = cartfox_get_message_templates_settings($template_id);


	$order = wc_get_order( $order_id );
    $order_number = $order->get_order_number();

	$phone_number = $order->get_billing_phone();
	$firstname = $order->get_billing_first_name();
	$country = $order->get_billing_country();



	$variables = [
		"ORDER_NUMBER" => $order_number,
		"FIRSTNAME" => $firstname,
		"SHOP_URL" => get_site_url(),
		"SHOP_NAME" => get_bloginfo('name'),
	];

	$sender_name = $options["cartfox_sender_name"];
	if(!$sender_name){
		$sender_name = "CartFox";
	}


	$payload = [
    	"api_key" => $options['cartfox_transactional_api_key'],
    	"sender_name" => $sender_name,
    	"country" => $country,
    	"phone" => $phone_number,
    	"message" => [
    		"message_id" => $template_id,
    		"data" => $variables,
    	]
    ];

    $payload = cartfox_add_integration_to_payload($payload);

    
    $cf_api_endpoint = "https://api.cartfox.io/transactional/send";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);

    if($cf_response->status == "error"){
        $order_comment = "[CartFox Transactional]<br> <b>Status: </b>Error <br> <b>Message: </b>$cf_response->message";
    } else {
        $order_comment = "[CartFox Transactional]<br> <b>Status: </b> Success <br> <b>Message  reference: </b>" . $cf_response->data->message_reference . "<br> <b>Content: </b><br>" . $cf_response->data->message_content;
    }

    $order->add_order_note( $order_comment );

    if($cf_response->status == "success"){
    	return true;
    } else {
    	return false;
    }
}



// Status change
add_action('woocommerce_order_status_changed', 'cartfox_order_status_changed', 10, 3);
function cartfox_order_status_changed($order_id, $old_status, $new_status)
{

	$cartfox_send_message = false;
	$options = get_option( 'cartfox_settings' );
	foreach($options as $cf_op_key => $cf_op_value){
		if(strpos($cf_op_key, "cartfox_send_status_") !== false){
			if($cf_op_value != 0){
				$send_status = str_replace("cartfox_send_status_", "", $cf_op_key);
				if(strpos($cf_op_key, "wc-") !== false){
					$send_status = str_replace("wc-", "", $send_status);
					if($send_status == $new_status){
						cartfox_send_transactional_messages($cf_op_value, $order_id);
					}
				}
			}
		}
	}
}



function cartfox_get_message_templates(){
	$options = get_option( 'cartfox_settings' );

	$payload = [
    	"api_key" => $options['cartfox_transactional_api_key'],
    ];

    $payload = cartfox_add_integration_to_payload($payload);

    
    $cf_api_endpoint = "https://api.cartfox.io/transactional/templates";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);

    if($cf_response->status == "success"){
    	return $cf_response->message_templates;
    } else {
    	return $cf_response->message;
    }
}

function cartfox_get_sender_names(){
	$options = get_option( 'cartfox_settings' );

	$payload = [
    	"api_key" => $options['cartfox_transactional_api_key'],
    ];

    $payload = cartfox_add_integration_to_payload($payload);

    
    $cf_api_endpoint = "https://api.cartfox.io/transactional/sender_names";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);

    if($cf_response->status == "success"){
    	return $cf_response->sender_names;
    } else {
    	return $cf_response->message;
    }
}

function cartfox_get_message_templates_settings($template_id){
	$options = get_option( 'cartfox_settings' );

	$payload = [
    	"api_key" => $options['cartfox_transactional_api_key'],
    	"message_id" => $template_id,
    ];

    $payload = cartfox_add_integration_to_payload($payload);

    
    $cf_api_endpoint = "https://api.cartfox.io/transactional/templates";
    $cf_api_data = json_encode($payload);

    $cf_api_args = [
    	'body'        => $cf_api_data,
		'timeout'     => '10',
		'redirection' => '5',
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(),
		'cookies'     => array(),
    ];


    $cf_response = wp_remote_post($cf_api_endpoint, $cf_api_args);
    $cf_body = wp_remote_retrieve_body($cf_response);
    $cf_response = json_decode($cf_body, false);

    if($cf_response->status == "success"){
    	return $cf_response->data;
    } else {
    	return $cf_response->message;
    }
}


// SETTINGS
add_action( 'admin_menu', 'cartfox_add_admin_menu' );
add_action( 'admin_init', 'cartfox_settings_init' );


function cartfox_enqueue_select2_jquery() {
    wp_register_style( 'select2css', plugin_dir_url( __FILE__ ) . '/assets/select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . '/assets/select2.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}
add_action( 'admin_enqueue_scripts', 'cartfox_enqueue_select2_jquery' );

function cartfox_select2jquery_inline() {
    ?>

	<script type='text/javascript'>
	jQuery(document).ready(function ($) {
	        //$( '.select2' ).select2();
	        $( 'select[name="cartfox_settings\[cartfox_default_country\]"]' ).select2();
	        $( 'select[name="cartfox_settings\[cartfox_optin\]"]' ).select2();
	});
	</script>

    <?php
}
add_action( 'admin_head', 'cartfox_select2jquery_inline' );


function cartfox_add_admin_menu(  ) { 

	add_menu_page( 'CartFox', 'CartFox', 'manage_options', 'cart_fox', 'cartfox_options_page', plugin_dir_url( __FILE__ ) . '/assets/cf_logo.png' );

}


function cartfox_settings_init(  ) { 

	register_setting( 'pluginPage', 'cartfox_settings' );

	add_settings_section(
		'cartfox_pluginPage_section', 
		__( '<div class="card" style="text-align: center; font-size: 14px; max-width: none;width: 99%; z-index: 10;"><h1>Welcome to CartFox!</h1><img src="' . plugin_dir_url( __FILE__ ) . '/assets/cf-universe.svg" style="padding: 20px; width: 200px; display: block; padding-left: calc(50% - 100px);">Please, create a new account if you don\'t have it yet:  <br><hr><a style="margin: 0px 10px;background-color: #F48938;color: #FFFFFF;font-size: 10px;border-radius: 10px;text-align: center;font-weight: 700;padding: 3px 15px;border: 0;text-decoration: none; font-size: 18px; position: relative; top: 4px;" target="_blank" href="https://app.cartfox.io/auth/register">Register</a></div>', 'CartFox' ), 
		'cartfox_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'cartfox_api_key', 
		__( 'API Key', 'CartFox' ), 
		'cartfox_api_key_render', 
		'pluginPage', 
		'cartfox_pluginPage_section' 
	);

	add_settings_field( 
		'cartfox_default_country', 
		__( 'Default country', 'CartFox' ), 
		'cartfox_default_country_render', 
		'pluginPage', 
		'cartfox_pluginPage_section' 
	);

	add_settings_field( 
		'cartfox_move_phone', 
		__( 'Move phone field to the top', 'CartFox' ), 
		'cartfox_move_phone_render', 
		'pluginPage', 
		'cartfox_pluginPage_section' 
	);

	add_settings_field( 
		'cartfox_optin', 
		__( 'Opt-in settings', 'CartFox' ), 
		'cartfox_optin_render', 
		'pluginPage', 
		'cartfox_pluginPage_section' 
	);

	add_settings_field( 
		'cartfox_optin_message', 
		__( 'Opt-in text', 'CartFox' ), 
		'cartfox_optin_text_render', 
		'pluginPage', 
		'cartfox_pluginPage_section' 
	);


	


	// New section
	add_settings_section(
		'cartfox_pluginPage_section_transactional', 
		__( '', 'CartFox' ), 
		'cartfox_settings_transactional_section_callback', 
		'cartfox_pluginPage_transactional'
	);

	add_settings_field( 
		'cartfox_transactional_api_key', 
		__( 'Transactional API Key', 'CartFox' ), 
		'cartfox_transactional_api_key_render', 
		'cartfox_pluginPage_transactional', 
		'cartfox_pluginPage_section_transactional' 
	);

	$options = get_option('cartfox_settings');
	if(isset($options["cartfox_transactional_api_key"]) && trim($options["cartfox_transactional_api_key"]) != ""){
		add_settings_field( 
			'cartfox_transactional_sender_name', 
			__( 'Sender name', 'CartFox' ), 
			'cartfox_transactional_sender_name_render', 
			'cartfox_pluginPage_transactional', 
			'cartfox_pluginPage_section_transactional' 
		);

		add_settings_field( 
			'cartfox_status_change', 
			__( 'Status change messages', 'CartFox' ), 
			'cartfox_status_change_render', 
			'cartfox_pluginPage_transactional', 
			'cartfox_pluginPage_section_transactional' 
		);
	}



	add_settings_section(
		'cartfox_pluginPage_section_advanced', 
		__( '', 'CartFox' ), 
		'cartfox_settings_advanced_section_callback', 
		'cartfox_pluginPage_advanced'
	);

	add_settings_field( 
		'cartfox_dynamic_cart_url', 
		__( 'Dynamic checkout url', 'CartFox' ), 
		'cartfox_dynamic_cart_url_render', 
		'cartfox_pluginPage_advanced', 
		'cartfox_pluginPage_section_advanced' 
	);


	add_settings_field( 
		'cartfox_special_checkout_fields', 
		__( 'Special checkout fields (plugins used)', 'CartFox' ), 
		'cartfox_special_checkout_fields_render', 
		'cartfox_pluginPage_advanced', 
		'cartfox_pluginPage_section_advanced' 
	);


}


function cartfox_api_key_render(  ) { 

	$options = get_option( 'cartfox_settings' );
	?>
	<input style="width: 100%;" type='text' name='cartfox_settings[cartfox_api_key]' value='<?php if(isset($options['cartfox_api_key'])) echo esc_attr($options['cartfox_api_key']); else echo ''; ?>'>
	<?php

}


function cartfox_move_phone_render(  ) { 

	$options = get_option( 'cartfox_settings' );
	?>
	<input style="" type='checkbox' name='cartfox_settings[cartfox_move_phone]' value='1' <?php if(isset($options['cartfox_move_phone']) && esc_attr($options['cartfox_move_phone']) == 1) echo "checked"; ?>>
	(Moving phone field to the top of the checkout form results in a higher rate of potential customers)
	<?php

}

function cartfox_dynamic_cart_url_render(  ) { 

	$options = get_option( 'cartfox_settings' );
	?>
	<input style="" type='checkbox' name='cartfox_settings[cartfox_dynamic_cart_url]' value='1' <?php if(isset($options['cartfox_dynamic_cart_url']) && esc_attr($options['cartfox_dynamic_cart_url']) == 1) echo "checked"; ?>>
	(Check this if your checkout URL is not accessible on the default WooCommerce Checkout URL. (Default = false)).
	<?php

}


function cartfox_special_checkout_fields_render(  ) { 

	$options = get_option( 'cartfox_settings' );
	?>
	<input style="" type='checkbox' name='cartfox_settings[cartfox_special_checkout_fields]' value='1' <?php if(isset($options['cartfox_special_checkout_fields']) && esc_attr($options['cartfox_special_checkout_fields']) == 1) echo "checked"; ?>>
	(Check this if your checkout has special fields form (becuase of plugins). (Default = false)).
	<?php

}


function cartfox_default_country_render(  ) { 
	$countries_list  = new WC_Countries();
	$countries_list = $countries_list->get_countries();

	$options = get_option( 'cartfox_settings' );
	?>
	<select style="width: 100%;" class="select2" name='cartfox_settings[cartfox_default_country]'>
		<?php foreach($countries_list as $a2 => $country_name): ?>
			<option  value="<?php echo esc_attr($a2); ?>" <?php if(isset($options['cartfox_default_country']) && esc_attr($options['cartfox_default_country']) == $a2) echo "selected"; ?>><?php echo $country_name; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}

function cartfox_optin_render(  ) { 
	
	$options = get_option( 'cartfox_settings' );
	?>
	<select style="width: 100%;" class="select2" name='cartfox_settings[cartfox_optin]'>
		<option value="no_checkbox" <?php if(isset($options['cartfox_optin']) && esc_attr($options['cartfox_optin']) == "no_checkbox") echo "selected"; ?>>No additional opt-in checkbox</option>
		<option value="show_checkbox" <?php if(isset($options['cartfox_optin']) && esc_attr($options['cartfox_optin']) == "show_checkbox") echo "selected"; ?>>Show checkbox, but send to everyone</option>
		<option value="only_checkbox" <?php if(isset($options['cartfox_optin']) && esc_attr($options['cartfox_optin']) == "only_checkbox") echo "selected"; ?>>Show checkbox and send only to ones that checked it</option>
	</select>
	<?php
}

function cartfox_optin_text_render(  ) { 

	$options = get_option( 'cartfox_settings' );
	?>
	<input style="width: 100%;" type='text' name='cartfox_settings[cartfox_optin_text]' value='<?php if(isset($options['cartfox_optin_text'])) echo esc_attr($options['cartfox_optin_text']); else echo ''; ?>'>
	(Enter a text that will be shown for the Opt-In checkbox)
	<?php
}

function cartfox_status_change_render(  ) { 

	$message_templates = cartfox_get_message_templates();
	$options = get_option( 'cartfox_settings' );
	foreach(wc_get_order_statuses() as $cartfox_status_key => $cartfox_status_value){
	?>
	<select style="" type='' name='cartfox_settings[cartfox_send_status_<?php echo $cartfox_status_key; ?>]' value='<?php echo esc_attr($options['cartfox_send_status_' . $cartfox_status_key]); ?>'>
		<option value="0">None</option>
		<?php foreach($message_templates as $mt_id => $mt_name): ?>
			<option value="<?php echo esc_attr($mt_id); ?>" <?php if($options["cartfox_send_status_" . $cartfox_status_key] == $mt_id) echo "selected"; ?>><?php echo esc_attr($mt_name); ?></option>
		<?php endforeach; ?>
	</select>
	When status changes to "<b><?php echo $cartfox_status_value; ?></b>"<br><br>
	<?php
	}
}

function cartfox_transactional_api_key_render(  ) { 
	$options = get_option( 'cartfox_settings' );
	?>
	<input style="width: 100%;" type='text' name='cartfox_settings[cartfox_transactional_api_key]' value='<?php if(isset($options['cartfox_transactional_api_key'])) echo esc_attr($options['cartfox_transactional_api_key']); else echo ''; ?>'>
	<?php
	if(!isset($options["cartfox_transactional_api_key"]) || (isset($options["cartfox_transactional_api_key"]) && trim($options["cartfox_transactional_api_key"]) == "")) echo "First enter your Transactional API key, to set additional options.";

}

function cartfox_transactional_sender_name_render(  ) { 

	$sender_names = cartfox_get_sender_names();

	$options = get_option( 'cartfox_settings' );
	?>
	<select style="width: 100%;" name='cartfox_settings[cartfox_sender_name]' value='<?php if(isset($options['cartfox_sender_name'])) echo esc_attr($options['cartfox_sender_name']); else echo ''; ?>'>
		<?php foreach($sender_names as $sn): ?>
			<option <?php if(isset($options["cartfox_sender_name"]) && $options["cartfox_sender_name"] == $sn) echo "selected"; ?>><?php echo $sn; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}


function cartfox_settings_section_callback(  ) { 

	echo __( '<div class="card" style="max-width: none;width: 99%; z-index: 10;">', 'CartFox', "</div>" );

}

function cartfox_settings_transactional_section_callback(  ) { 
	echo __( '<div class="card" style="max-width: none;width: 99%; margin-top: 20px; z-index: 10;">', 'CartFox', "</div>" );

}

function cartfox_settings_advanced_section_callback(  ) { 

	echo __( '<div class="card" style="max-width: none;width: 99%; margin-top: 20px; z-index: 10;">', 'CartFox', "</div>" );

}


function cartfox_options_page(  ) { 
		?>
		<form action='options.php' method='post'>
			<div class="row">
				<div class="wrapper">
					<?php settings_fields( 'pluginPage' ); ?>
				</div>
				<div class="wrapper">
				<?php
					do_settings_sections( 'pluginPage' );
					submit_button();
				?>
				</div>
				<h2 style="margin-top: 20px; cursor: pointer;" onclick="$('#cf_advanced_options').slideToggle();">🔽 Advanced Settings</h2>
				<div class="wrapper" id="cf_advanced_options" >
					<?php
						do_settings_sections( 'cartfox_pluginPage_advanced' );
						submit_button();
					?>
				</div>
				<h2 style="margin-top: 20px; cursor: pointer;" onclick="$('#cf_transactional_options').slideToggle();">🔽 Transactional Messages</h2>
				<div class="wrapper" id="cf_transactional_options">
					<?php
						do_settings_sections( 'cartfox_pluginPage_transactional' );
						submit_button();
					?>
				</div></div>
			</div>
			

		</form>
		<?php

}

// sync endpoint
add_action('rest_api_init', function () {
    register_rest_route('cartfox/v1', '/sync', array(
        'methods' => 'GET',
        'callback' => 'cartfox_api_sync',
    ));

});


function cartfox_api_sync($data) {
    $request_data = (object)$data->get_params();

    $cf_options = get_option( 'cartfox_settings' );
    $cf_api_key = $cf_options["cartfox_api_key"];

    if(!isset($request_data->api_key) || trim($request_data->api_key) == ""){
    	$response = array('success' => false, 'data' => false);
    	return new WP_Error( 'rest_invalid_request', 'Invalid request.', array( 'status' => 400 ) );
    }

    if($request_data->api_key != $cf_api_key){
    	$response = array('success' => false, 'data' => false);
    	return new WP_Error( 'rest_invalid_request', 'Invalid request.', array( 'status' => 403 ) );
    }

	global $wpdb;


	if(isset($request_data->minutes)){
		$minutes_ago = $request_data->minutes;

		if($minutes_ago > 10080){
			$minutes_ago = 10080;
		}
	} else {
		$minutes_ago = 2;
	}

	$query = $wpdb->prepare("
	    SELECT 
	        p.ID AS order_id,
	        MAX(CASE WHEN pm.meta_key = '_billing_phone' THEN pm.meta_value END) AS customer_phone_number,
	        MAX(CASE WHEN pm.meta_key = '_billing_email' THEN pm.meta_value END) AS customer_email,
	        CONCAT(MAX(CASE WHEN pm.meta_key = '_billing_first_name' THEN pm.meta_value END), ' ', MAX(CASE WHEN pm.meta_key = '_billing_last_name' THEN pm.meta_value END)) AS customer_name,
	        MAX(CASE WHEN pm.meta_key = '_billing_country' THEN pm.meta_value END) AS billing_country,
	        p.post_date AS order_created_date,
	        MAX(CASE WHEN pm.meta_key = '_order_total' THEN pm.meta_value END) AS total_price,
	        MAX(CASE WHEN pm.meta_key = '_order_currency' THEN pm.meta_value END) AS currency
	    FROM 
	        {$wpdb->prefix}posts AS p
	    JOIN 
	        {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
	    WHERE 
	        p.post_type = 'shop_order'
	        AND p.post_date > DATE_SUB(NOW(), INTERVAL %d MINUTE)
	    GROUP BY 
	        p.ID
	    ORDER BY 
	        p.post_date DESC
	", $minutes_ago);

	// Execute the query
	$orders = $wpdb->get_results( $query );
	foreach($orders as &$order){
		$wc_order = wc_get_order($order->order_id);
		$order->order_number = $wc_order->get_order_number();
	}

    // Your custom handling logic here
    $response = array('success' => true, 'data' => $orders);
    return new WP_REST_Response($response, 200);
}

?>