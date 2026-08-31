<?php
/**
 * Nezno preverjanje telefonske stevilke na blagajni.
 *
 * Ustavi SAMO ocitne napake: crke v polju, prekratko ali absurdno dolgo stevilko.
 * Ne preverja predpon in NE zavraca stacionarnih stevilk (Dejan, 26. 8. 2026).
 * Tuje stevilke z + spusti, ce imajo 9-15 cifer.
 *
 * Poleg tega stevilko ob oddaji naročila tiho pretvori v mednarodno obliko (+...),
 * ker je Metakocka del narocil zavracala samo zaradi presledkov in oklepajev.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'NORIKS_TEL_CC',    '' );
define( 'NORIKS_TEL_TRUNK', '' );
define( 'NORIKS_TEL_MIN',   8 );
define( 'NORIKS_TEL_MAX',   15 );

/**
 * Vrne nacionalno stevilko brez klicne in vodilne nicle,
 * ali WP_Error z razlogom, zakaj je ocitno napacna.
 */
function noriks_tel_national( $raw ) {
    $s = trim( (string) $raw );
    if ( $s === '' ) {
        return new WP_Error( 'prazno' );
    }
    if ( preg_match( '/\p{L}/u', $s ) ) {
        return new WP_Error( 'crke' );
    }
    $s   = preg_replace( '/[\s().\-\/]/u', '', $s );
    $dig = preg_replace( '/\D/', '', $s );
    if ( $dig === '' ) {
        return new WP_Error( 'prazno' );
    }
    $explicit = ( strpos( $s, '+' ) === 0 || strpos( $s, '00' ) === 0 );
    if ( strpos( $s, '00' ) === 0 ) {
        $dig = substr( $dig, 2 );
    }
    $cc = NORIKS_TEL_CC;
    if ( $explicit ) {
        if ( $cc === '' || strpos( $dig, $cc ) !== 0 ) {
            // tuja stevilka: spustimo, ce je verjetna
            $len = strlen( $dig );
            return ( $len >= 9 && $len <= 15 ) ? '' : new WP_Error( 'tuja' );
        }
        $dig = substr( $dig, strlen( $cc ) );
    } elseif ( $cc !== '' && strpos( $dig, $cc ) === 0 && ( strlen( $dig ) - strlen( $cc ) ) >= NORIKS_TEL_MIN ) {
        $dig = substr( $dig, strlen( $cc ) );
    }
    $trunk = NORIKS_TEL_TRUNK;
    if ( $trunk !== '' && strpos( $dig, $trunk ) === 0 ) {
        $dig = substr( $dig, strlen( $trunk ) );
    } elseif ( strpos( $dig, '0' ) === 0 ) {
        $dig = ltrim( $dig, '0' );
    }
    return $dig;
}

/** true, ce je stevilka sprejemljiva */
function noriks_tel_ok( $raw ) {
    $d = noriks_tel_national( $raw );
    if ( is_wp_error( $d ) ) { return false; }
    if ( $d === '' )         { return true; }   // tuja, ze preverjena
    $len = strlen( $d );
    return ( $len >= NORIKS_TEL_MIN && $len <= NORIKS_TEL_MAX );
}

/** mednarodna oblika za Metakocko; ce ne gre, vrne original */
function noriks_tel_intl( $raw ) {
    $d = noriks_tel_national( $raw );
    if ( is_wp_error( $d ) || $d === '' || NORIKS_TEL_CC === '' ) {
        return preg_replace( '/[\s().\-\/]/u', '', trim( (string) $raw ) );
    }
    return '+' . NORIKS_TEL_CC . $d;
}

/* --- strezniska obramba: naročila z ocitno napacno stevilko ne spustimo --- */
add_action( 'woocommerce_checkout_process', function () {
    $p = isset( $_POST['billing_phone'] ) ? wp_unslash( $_POST['billing_phone'] ) : '';
    if ( $p !== '' && ! noriks_tel_ok( $p ) ) {
        wc_add_notice( 'Please check the phone number — it looks incomplete.', 'error' );
    }
}, 5 );

/* --- tiha pretvorba zapisa ob shranjevanju --- */
add_filter( 'woocommerce_process_checkout_field_billing_phone', function ( $v ) {
    return noriks_tel_ok( $v ) ? noriks_tel_intl( $v ) : $v;
}, 20 );

/* --- sprotno opozorilo pod poljem --- */
add_action( 'wp_footer', function () {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) { return; }
    ?>
    <style>
    #billing_phone_field .noriks-tel-msg { display:none; margin-top:6px; color:#c62828; font-size:13px; line-height:1.35; }
    #billing_phone_field.noriks-tel-bad .noriks-tel-msg { display:block; }
    #billing_phone_field.noriks-tel-bad input { border-color:#c62828 !important; }
    </style>
    <script id="noriks-tel-check">
    jQuery(function($){
      var CC = '', TRUNK = '', MIN = 8, MAX = 15;
      var MSG = <?php echo wp_json_encode( 'Please check the phone number — it looks incomplete.' . ' ' . 'e.g. +44 7700 900123' ); ?>;
      function national(raw){
        var s = (raw||'').trim();
        if (!s) return null;
        if (/\p{L}/u.test(s)) return false;
        s = s.replace(/[\s().\-\/]/g,'');
        var d = s.replace(/\D/g,'');
        if (!d) return null;
        var explicit = s.indexOf('+')===0 || s.indexOf('00')===0;
        if (s.indexOf('00')===0) d = d.slice(2);
        if (explicit){
          if (!CC || d.indexOf(CC)!==0) return (d.length>=9 && d.length<=15) ? '' : false;
          d = d.slice(CC.length);
        } else if (CC && d.indexOf(CC)===0 && (d.length-CC.length)>=MIN){
          d = d.slice(CC.length);
        }
        if (TRUNK && d.indexOf(TRUNK)===0) d = d.slice(TRUNK.length);
        else d = d.replace(/^0+/,'');
        return d;
      }
      function ok(raw){
        var d = national(raw);
        if (d===null) return true;       // prazno pusti WooCommercu
        if (d===false) return false;
        if (d==='') return true;         // tuja, ze preverjena
        return d.length>=MIN && d.length<=MAX;
      }
      function paint(){
        var $f = $('#billing_phone_field'), $i = $('#billing_phone');
        if (!$i.length) return;
        if (!$f.find('.noriks-tel-msg').length) $f.append($('<div class="noriks-tel-msg"></div>').text(MSG));
        var v = $i.val();
        $f.toggleClass('noriks-tel-bad', v !== '' && !ok(v));
      }
      $(document).on('blur change keyup', '#billing_phone', paint);
      $(document.body).on('updated_checkout', paint);
      $('form.checkout').on('submit', function(e){
        var v = $('#billing_phone').val();
        if (v !== '' && !ok(v)){
          paint();
          $('html,body').animate({scrollTop: $('#billing_phone_field').offset().top - 120}, 250);
          $('#billing_phone').focus();
          e.preventDefault(); e.stopImmediatePropagation();
          return false;
        }
      });
      paint();
    });
    </script>
    <?php
}, 98 );
