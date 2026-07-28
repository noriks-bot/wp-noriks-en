<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->



<!-- suport section -->

	<section class="info-banner">
	     <div class="info-items-container">
  <div class="info-item">
    <img loading="lazy" decoding="async" src="<?php echo get_field("footer_top_icon_1","option"); ?>" alt="Shirt Icon" class="info-icon">
    <h3><?php echo get_field("footer_top_heading_1", "option"); ?></h3>
    <p><?php echo get_field("footer_top_text_1", "option"); ?></p>
  </div>
  <div style="" class="info-item">
    <img loading="lazy" decoding="async" src="<?php echo get_field("footer_top_icon_2","option"); ?>" alt="Customer Support Icon" class="info-icon">
    <h3><?php echo get_field("footer_top_heading_2", "option"); ?></h3>
    <p><?php echo get_field("footer_top_text_2", "option"); ?></p>


  </div>
  <div style="" class="info-item">
    <img loading="lazy" decoding="async" src="<?php echo get_field("footer_top_icon_3","option"); ?>" alt="Shipping Icon" class="info-icon">
    <h3><?php echo get_field("footer_top_heading_3", "option"); ?></h3>
    <p><?php echo get_field("footer_top_text_3", "option"); ?></p>
  </div>
  </div>
</section>


<!-- Info banner styles moved to css/footer.css -->


<!-- suport section -->


	<?php do_action( 'storefront_before_footer' ); ?>

	<?php
	// ===== Klaviyo newsletter =====
	// List: "footer-subscribed" (Lists & Segments -> Settings -> List ID).
	$nf_klaviyo_list = 'REPLACE_LIST_ID'; // TODO: vpisi EN Klaviyo List ID

	$nf_social  = get_field( 'social_list', 'options' );
	$nf_col2_h  = get_field( 'footer_midle_col2_header', 'option' );
	$nf_col2_l  = get_field( 'footer_midle_col2_links', 'option' );
	$nf_col3_h  = get_field( 'footer_midle_col3_header', 'option' );
	$nf_col3_l  = get_field( 'footer_midle_col3_links', 'option' );
	$nf_col4_h  = get_field( 'footer_midle_col4_header', 'option' );
	$nf_col4_c  = get_field( 'footer_midle_col4_content', 'option' );
	$nf_brand   = get_field( 'footer_brand_desc', 'option' );
	if ( ! $nf_brand ) {
		$nf_brand = 'NORIKS designs timeless pieces for a stronger build — longer, more comfortable and thoughtfully made where it matters most.';
	}
	$nf_bg      = get_field( 'footer_bg_image', 'option' ); // background photo behind the brand band
	$nf_legal   = get_field( 'footer_legal_text', 'option' );
	if ( ! $nf_legal ) {
		$nf_legal = 'NORIKS BRAND. All rights reserved. Prices include VAT. Images are for illustration purposes only.';
	}
	?>

	<footer class="nf">

		<!-- ============ Newsletter signup ============ -->
		<section class="nf-signup">
			<div class="nf-wrap nf-signup-grid">
				<div class="nf-signup-copy">
					<h2 class="nf-signup-title">Join the NORIKS family</h2>
					<p class="nf-signup-sub">Exclusive offers and new product drops — be the first to know.</p>
				</div>
				<div class="nf-signup-form-col">
					<form class="nf-signup-form" data-klaviyo-list="<?php echo esc_attr( $nf_klaviyo_list ); ?>" novalidate>
						<label class="nf-field-label" for="nf-email">Your email *</label>
						<div class="nf-field-row">
							<input type="email" id="nf-email" name="email" class="nf-input" placeholder="Your email address" required autocomplete="email">
							<button type="submit" class="nf-btn nf-btn-light">Sign up</button>
						</div>
						<p class="nf-signup-note">
							By signing up you agree to receive emails. You can unsubscribe at any time.
							<a href="/privacy-policy/">Privacy policy.</a>
						</p>
						<p class="nf-signup-msg" role="status" aria-live="polite"></p>
					</form>
				</div>
			</div>
		</section>

		<div class="nf-bg<?php echo $nf_bg ? ' has-img' : ''; ?>"<?php if ( $nf_bg ) : ?> style="background-image:url('<?php echo esc_url( $nf_bg ); ?>')"<?php endif; ?>>

		<!-- ============ Main: brand + link columns ============ -->
		<section class="nf-main">
			<div class="nf-wrap nf-main-grid">

				<div class="nf-brand-col">
					<p class="nf-brand-tagline">Clothing for a stronger build, cut to actually fit.</p>
					<p class="nf-brand-desc"><?php echo esc_html( $nf_brand ); ?></p>
					<a class="nf-btn nf-btn-outline" href="/shop">See the collection</a>
				</div>

				<nav class="nf-links">
					<?php if ( $nf_col2_h || $nf_col2_l ) : ?>
						<div class="nf-link-col">
							<h4 class="nf-link-h"><?php echo esc_html( $nf_col2_h ); ?></h4>
							<?php if ( $nf_col2_l ) : foreach ( $nf_col2_l as $item ) : ?>
								<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
							<?php endforeach; endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $nf_col3_h || $nf_col3_l ) : ?>
						<div class="nf-link-col">
							<h4 class="nf-link-h"><?php echo esc_html( $nf_col3_h ); ?></h4>
							<?php if ( $nf_col3_l ) : foreach ( $nf_col3_l as $item ) : ?>
								<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
							<?php endforeach; endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $nf_col4_h || $nf_col4_c ) : ?>
						<div class="nf-link-col nf-link-col-rich">
							<h4 class="nf-link-h"><?php echo esc_html( $nf_col4_h ); ?></h4>
							<div class="nf-rich"><?php echo $nf_col4_c; ?></div>
						</div>
					<?php endif; ?>
				</nav>

			</div>
		</section>

		<!-- ============ Region + copyright + payments ============ -->
		<div class="nf-wrap nf-meta">
			<div class="nf-meta-left">
				<span class="nf-region">
					<img loading="lazy" decoding="async" src="https://static.devit.software/countries/flags/rectangle/eu.svg" alt="" class="nf-flag">
					English (Europe)
				</span>
				<span class="nf-copy">© <?php echo date( 'Y' ); ?> NORIKS. All rights reserved.</span>
			</div>
			<div class="nf-payments">
				<ul class="nf-pay-list">
						<li><svg class="payment-icon" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-visa"><title id="pi-visa">Visa</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"></path><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"></path><path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"></path></svg></li>
						<li><svg class="payment-icon" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-master"><title id="pi-master">Mastercard</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"></path><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"></path><circle fill="#EB001B" cx="15" cy="12" r="7"></circle><circle fill="#F79E1B" cx="23" cy="12" r="7"></circle><path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z"></path></svg></li>
						<li><svg class="payment-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" role="img" x="0" y="0" width="38" height="24" viewBox="0 0 165.521 105.965" xml:space="preserve" aria-labelledby="pi-apple_pay"><title id="pi-apple_pay">Apple Pay</title><path fill="#000" d="M150.698 0H14.823c-.566 0-1.133 0-1.698.003-.477.004-.953.009-1.43.022-1.039.028-2.087.09-3.113.274a10.51 10.51 0 0 0-2.958.975 9.932 9.932 0 0 0-4.35 4.35 10.463 10.463 0 0 0-.975 2.96C.113 9.611.052 10.658.024 11.696a70.22 70.22 0 0 0-.022 1.43C0 13.69 0 14.256 0 14.823v76.318c0 .567 0 1.132.002 1.699.003.476.009.953.022 1.43.028 1.036.09 2.084.275 3.11a10.46 10.46 0 0 0 .974 2.96 9.897 9.897 0 0 0 1.83 2.52 9.874 9.874 0 0 0 2.52 1.83c.947.483 1.917.79 2.96.977 1.025.183 2.073.245 3.112.273.477.011.953.017 1.43.02.565.004 1.132.004 1.698.004h135.875c.565 0 1.132 0 1.697-.004.476-.002.952-.009 1.431-.02 1.037-.028 2.085-.09 3.113-.273a10.478 10.478 0 0 0 2.958-.977 9.955 9.955 0 0 0 4.35-4.35c.483-.947.789-1.917.974-2.96.186-1.026.246-2.074.274-3.11.013-.477.02-.954.022-1.43.004-.567.004-1.132.004-1.699V14.824c0-.567 0-1.133-.004-1.699a63.067 63.067 0 0 0-.022-1.429c-.028-1.038-.088-2.085-.274-3.112a10.4 10.4 0 0 0-.974-2.96 9.94 9.94 0 0 0-4.35-4.35A10.52 10.52 0 0 0 156.939.3c-1.028-.185-2.076-.246-3.113-.274a71.417 71.417 0 0 0-1.431-.022C151.83 0 151.263 0 150.698 0z"></path><path fill="#FFF" d="M150.698 3.532l1.672.003c.452.003.905.008 1.36.02.793.022 1.719.065 2.583.22.75.135 1.38.34 1.984.648a6.392 6.392 0 0 1 2.804 2.807c.306.6.51 1.226.645 1.983.154.854.197 1.783.218 2.58.013.45.019.9.02 1.36.005.557.005 1.113.005 1.671v76.318c0 .558 0 1.114-.004 1.682-.002.45-.008.9-.02 1.35-.022.796-.065 1.725-.221 2.589a6.855 6.855 0 0 1-.645 1.975 6.397 6.397 0 0 1-2.808 2.807c-.6.306-1.228.511-1.971.645-.881.157-1.847.2-2.574.22-.457.01-.912.017-1.379.019-.555.004-1.113.004-1.669.004H14.801c-.55 0-1.1 0-1.66-.004a74.993 74.993 0 0 1-1.35-.018c-.744-.02-1.71-.064-2.584-.22a6.938 6.938 0 0 1-1.986-.65 6.337 6.337 0 0 1-1.622-1.18 6.355 6.355 0 0 1-1.178-1.623 6.935 6.935 0 0 1-.646-1.985c-.156-.863-.2-1.788-.22-2.578a66.088 66.088 0 0 1-.02-1.355l-.003-1.327V14.474l.002-1.325a66.7 66.7 0 0 1 .02-1.357c.022-.792.065-1.717.222-2.587a6.924 6.924 0 0 1 .646-1.981 6.386 6.386 0 0 1 1.18-1.623 6.386 6.386 0 0 1 1.624-1.18 6.96 6.96 0 0 1 1.98-.646c.865-.155 1.792-.198 2.586-.22.452-.012.905-.017 1.354-.02l1.677-.003h135.875"></path><g><path fill="#000" d="M43.508 35.77c1.404-1.755 2.356-4.112 2.105-6.52-2.054.102-4.56 1.355-6.012 3.112-1.303 1.504-2.456 3.959-2.156 6.266 2.306.2 4.61-1.152 6.063-2.858"></path><path fill="#000" d="M45.587 39.079c-3.35-.2-6.196 1.9-7.795 1.9-1.6 0-4.049-1.8-6.698-1.751-3.447.05-6.645 2-8.395 5.1-3.598 6.2-.95 15.4 2.55 20.45 1.699 2.5 3.747 5.25 6.445 5.151 2.55-.1 3.549-1.65 6.647-1.65 3.097 0 3.997 1.65 6.696 1.6 2.798-.05 4.548-2.5 6.247-5 1.95-2.85 2.747-5.6 2.797-5.75-.05-.05-5.396-2.101-5.446-8.251-.05-5.15 4.198-7.6 4.398-7.751-2.399-3.548-6.147-3.948-7.447-4.048"></path></g></svg></li>
						<li><svg class="payment-icon" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="pi-american_express" viewBox="0 0 38 24" width="38" height="24"><title id="pi-american_express">American Express</title><path fill="#000" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3Z" opacity=".07"></path><path fill="#006FCF" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32Z"></path><path fill="#FFF" d="M22.012 19.936v-8.421L37 11.528v2.326l-1.732 1.852L37 17.573v2.375h-2.766l-1.47-1.622-1.46 1.628-9.292-.02Z"></path><path fill="#006FCF" d="M23.013 19.012v-6.57h5.572v1.513h-3.768v1.028h3.678v1.488h-3.678v1.01h3.768v1.531h-5.572Z"></path><path fill="#006FCF" d="m28.557 19.012 3.083-3.289-3.083-3.282h2.386l1.884 2.083 1.89-2.082H37v.051l-3.017 3.23L37 18.92v.093h-2.307l-1.917-2.103-1.898 2.104h-2.321Z"></path><path fill="#FFF" d="M22.71 4.04h3.614l1.269 2.881V4.04h4.46l.77 2.159.771-2.159H37v8.421H19l3.71-8.421Z"></path><path fill="#006FCF" d="m23.395 4.955-2.916 6.566h2l.55-1.315h2.98l.55 1.315h2.05l-2.904-6.566h-2.31Zm.25 3.777.875-2.09.873 2.09h-1.748Z"></path><path fill="#006FCF" d="M28.581 11.52V4.953l2.811.01L32.84 9l1.456-4.046H37v6.565l-1.74.016v-4.51l-1.644 4.494h-1.59L30.35 7.01v4.51h-1.768Z"></path></svg></li>
						<li><svg class="payment-icon" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" width="38" height="24" role="img" aria-labelledby="pi-paypal"><title id="pi-paypal">PayPal</title><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"></path><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"></path><path fill="#003087" d="M23.9 8.3c.2-1 0-1.7-.6-2.3-.6-.7-1.7-1-3.1-1h-4.1c-.3 0-.5.2-.6.5L14 15.6c0 .2.1.4.3.4H17l.4-3.4 1.8-2.2 4.7-2.1z"></path><path fill="#3086C8" d="M23.9 8.3l-.2.2c-.5 2.8-2.2 3.8-4.6 3.8H18c-.3 0-.5.2-.6.5l-.6 3.9-.2 1c0 .2.1.4.3.4H19c.3 0 .5-.2.5-.4v-.1l.4-2.4v-.1c0-.2.3-.4.5-.4h.3c2.1 0 3.7-.8 4.1-3.2.2-1 .1-1.8-.4-2.4-.1-.5-.3-.7-.5-.8z"></path><path fill="#012169" d="M23.3 8.1c-.1-.1-.2-.1-.3-.1-.1 0-.2 0-.3-.1-.3-.1-.7-.1-1.1-.1h-3c-.1 0-.2 0-.2.1-.2.1-.3.2-.3.4l-.7 4.4v.1c0-.3.3-.5.6-.5h1.3c2.5 0 4.1-1 4.6-3.8v-.2c-.1-.1-.3-.2-.5-.2h-.1z"></path></svg></li>
					</ul>
			</div>
		</div>

		<!-- ============ Wordmark + socials ============ -->
		<div class="nf-wrap nf-brandbar">
			<a class="nf-wordmark" href="<?php echo home_url(); ?>">NORIKS</a>
			<?php if ( $nf_social ) : ?>
				<ul class="nf-social" role="list">
					<?php foreach ( $nf_social as $item ) : ?>
						<li>
							<a target="_blank" rel="noreferrer noopener" href="<?php echo esc_url( $item['link'] ); ?>">
								<img loading="lazy" decoding="async" src="<?php echo esc_url( $item['icon'] ); ?>" alt="">
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		</div><!-- .nf-bg -->

		<!-- ============ Legal fine print ============ -->
		<div class="nf-wrap nf-legal">
			<p class="nf-legal-text"><?php echo wp_kses_post( $nf_legal ); ?></p>
		</div>

	</footer>

	<script>
	(function () {
		var form = document.querySelector('.nf-signup-form');
		if (!form) return;
		var msg = form.querySelector('.nf-signup-msg');
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var list  = form.getAttribute('data-klaviyo-list') || '';
			var email = (form.querySelector('#nf-email') || {}).value || '';
			if (!email || email.indexOf('@') === -1) {
				msg.textContent = 'Please enter a valid email address.'; msg.className = 'nf-signup-msg is-error'; return;
			}
			if (!list || list === 'REPLACE_LIST_ID') {
				msg.textContent = 'The newsletter is not connected yet (Klaviyo List ID missing).'; msg.className = 'nf-signup-msg is-error'; return;
			}
			msg.textContent = 'Signing up…'; msg.className = 'nf-signup-msg';
			var body = 'g=' + encodeURIComponent(list) + '&email=' + encodeURIComponent(email) + '&$fields=&$list_fields=';
			fetch('https://manage.kmail-lists.com/ajax/subscriptions/subscribe', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body
			}).then(function (r) { return r.json(); }).then(function (d) {
				if (d && d.success) {
					msg.textContent = 'Thanks! Check your email to confirm.'; msg.className = 'nf-signup-msg is-ok';
					form.reset();
				} else {
					msg.textContent = (d && d.errors && d.errors[0]) ? d.errors[0] : 'Something went wrong, please try again.';
					msg.className = 'nf-signup-msg is-error';
				}
			}).catch(function () {
				msg.textContent = 'Something went wrong, please try again.'; msg.className = 'nf-signup-msg is-error';
			});
		});
	})();
	</script>

<!-- Footer styles moved to css/footer.css -->


	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
