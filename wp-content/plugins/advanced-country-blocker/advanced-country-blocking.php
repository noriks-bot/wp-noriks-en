<?php
/**
 * Plugin Name: Advanced Country Blocker
 * Plugin URI: https://sparkcan.com/acb.html
 * Description: Blocks all traffic to the website unless it meets the country filtering rules or accesses via a secret URL parameter. On activation, the admin’s country is auto‐added to the country list. Supports logging, blacklisting of IP addresses, custom block page, admin bypass, and optional email alerts. You can choose whether the country list acts as an allow‑list or a block‑list.
 * Version: 2.2.0
 * Author: Sparkcan
 * Author URI: https://sparkcan.com
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-advcb-geoip-locator.php';

add_action( 'plugins_loaded', 'advcb_load_textdomain' );
function advcb_load_textdomain() {
        load_plugin_textdomain( 'advcb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * HELPER FUNCTION: Returns an array of ISO country codes mapped to country names.
 */
function advcb_get_countries() {
	return array(
		''   => 'Select a country',
		'AF' => 'Afghanistan',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BR' => 'Brazil',
		'BN' => 'Brunei',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo - Brazzaville',
		'CD' => 'Congo - Kinshasa',
		'CR' => 'Costa Rica',
		'CI' => 'Côte d’Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'North Korea',
		'KR' => 'South Korea',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'North Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar (Burma)',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territories',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'QA' => 'Qatar',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'SM' => 'San Marino',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syria',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
}

/**
 * Plugin Activation:
 * 1) Detect the activating admin’s IP and set that country in the country list.
 * 2) Create a custom DB table to log blocked attempts.
 * 3) Set default options including the filtering mode and logging.
 */
register_activation_hook( __FILE__, 'advcb_plugin_activation' );
function advcb_plugin_activation() {
	// Set the activating admin's country (fallback is RS)
	$admin_ip     = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$country_code = 'RS';

        if ( ! empty( $admin_ip ) ) {
                $detected_country = advcb_get_country_code_for_ip( $admin_ip );
                if ( ! empty( $detected_country ) ) {
                        $country_code = $detected_country;
                }
        }
	// In allow mode, the admin’s country is the only allowed country.
	update_option( 'advcb_allowed_countries', array( $country_code ) );

	// Create DB table for logs.
	global $wpdb;
	$table_name      = $wpdb->prefix . 'advcb_block_logs';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        ip varchar(100) NOT NULL,
        country_code varchar(5) DEFAULT '' NOT NULL,
        blocked_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        reason varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// Set default options.
        add_option( 'advcb_secret_key', 'OpenSesame' );
        add_option( 'advcb_blacklisted_ips', array() );
        add_option( 'advcb_trusted_ips', array() );
        add_option( 'advcb_send_email_alerts', false );
        add_option( 'advcb_alert_email', get_option( 'admin_email' ) );
        add_option( 'advcb_mode', 'allow' );
        // New option: enable logging (default true).
        add_option( 'advcb_enable_logs', true );
        add_option( 'advcb_block_page_title', 'Access Restricted' );
        add_option( 'advcb_block_message', 'We’re sorry, but your location is not allowed to view this site.' );
        add_option( 'advcb_enable_redirect', false );
        add_option( 'advcb_redirect_url', '' );
        add_option( 'advcb_redirect_status_code', 302 );
        add_option( 'advcb_http_status_code', 403 );
        add_option( 'advcb_log_retention_days', 30 );
        add_option( 'advcb_geoip_source', 'api' );
        add_option( 'advcb_geoip_db_path', '' );

        if ( ! wp_next_scheduled( 'advcb_cleanup_logs_event' ) ) {
                wp_schedule_event( time(), 'daily', 'advcb_cleanup_logs_event' );
        }
}

register_deactivation_hook( __FILE__, 'advcb_plugin_deactivation' );
function advcb_plugin_deactivation() {
        wp_clear_scheduled_hook( 'advcb_cleanup_logs_event' );
}

add_action( 'init', 'advcb_ensure_cleanup_schedule' );
function advcb_ensure_cleanup_schedule() {
        if ( ! wp_next_scheduled( 'advcb_cleanup_logs_event' ) ) {
                wp_schedule_event( time(), 'daily', 'advcb_cleanup_logs_event' );
        }
}

/**
 * MAIN BLOCKING LOGIC
 */
function advcb_block_non_allowed_countries() {
	// Allow admins to bypass the blocking logic.
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		return;
	}

	// Retrieve settings.
	$allowed_countries       = get_option( 'advcb_allowed_countries', array() );
	$secret_key              = get_option( 'advcb_secret_key', 'OpenSesame' );
	$temporary_access_duration = HOUR_IN_SECONDS;
        $blacklisted_ips         = get_option( 'advcb_blacklisted_ips', array() );
        $trusted_ips             = get_option( 'advcb_trusted_ips', array() );
	$send_email_alerts       = get_option( 'advcb_send_email_alerts', false );
	$alert_email             = get_option( 'advcb_alert_email', get_option( 'admin_email' ) );
	$mode                    = get_option( 'advcb_mode', 'allow' ); // 'allow' or 'block'

	// Get visitor’s IP address.
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';

        if ( ! is_array( $blacklisted_ips ) ) {
                $blacklisted_ips = explode( ',', $blacklisted_ips );
        }
        if ( ! is_array( $trusted_ips ) ) {
                $trusted_ips = explode( ',', $trusted_ips );
        }

        $blacklisted_ips = array_map( 'trim', $blacklisted_ips );
        $trusted_ips     = array_map( 'trim', $trusted_ips );

        // 1) Check if IP is whitelisted explicitly.
        if ( in_array( $ip, $trusted_ips, true ) ) {
                return;
        }

        // 2) Check if IP is blacklisted.
        if ( in_array( $ip, $blacklisted_ips, true ) ) {
                $reason = __( 'Blacklisted IP', 'advcb' );
                advcb_record_block( $ip, 'XX', $reason, $send_email_alerts, $alert_email );
                advcb_show_block_page( array(
                        'ip'           => $ip,
                        'country_code' => 'XX',
                        'reason'       => $reason,
                ) );
                exit;
        }

        // 3) Check if IP is in the temporary whitelist.
        $whitelisted_ips = get_transient( 'advcb_whitelisted_ips' );
        if ( is_array( $whitelisted_ips ) && in_array( $ip, $whitelisted_ips ) ) {
                return; // temporary access granted
	}

        // 4) Determine visitor’s country code.
	$country_cache_key = 'advcb_country_' . md5( $ip );
	$country_code      = get_transient( $country_cache_key );

        if ( ! $country_code ) {
                $country_code = advcb_get_country_code_for_ip( $ip );

                if ( $country_code ) {
                        set_transient( $country_cache_key, $country_code, DAY_IN_SECONDS );
                } else {
                        // If we cannot determine the visitor's country, allow access to avoid blocking legitimate users.
                        return;
                }
        }

        /*
         * 5) Country Filtering Logic:
	 * In "allow" mode: if the visitor’s country is NOT in the list, then block.
	 * In "block" mode: if the visitor’s country IS in the list, then block.
	 * In both cases, if the secret key parameter is provided, grant temporary access.
	 */
	if ( $country_code ) {
		if ( $mode === 'allow' && ! in_array( $country_code, $allowed_countries ) ) {
			if ( isset( $_GET[ $secret_key ] ) ) {
				// Grant temporary access.
				if ( ! is_array( $whitelisted_ips ) ) {
					$whitelisted_ips = array();
				}
				$whitelisted_ips[] = $ip;
				$whitelisted_ips = array_unique( $whitelisted_ips );
				set_transient( 'advcb_whitelisted_ips', $whitelisted_ips, $temporary_access_duration );

				// Optional: enqueue a redirect alert.
                                add_action( 'wp_enqueue_scripts', function() use ( $secret_key ) {
                                        wp_enqueue_script( 'advcb-alert', plugin_dir_url( __FILE__ ) . 'advcb-alert.js', array(), '1.0', true );
					wp_localize_script( 'advcb-alert', 'advcb_redirect', array(
						'url' => esc_url( remove_query_arg( $secret_key ) ),
					) );
				} );
				return;
			}
                        $reason = __( 'Country not allowed', 'advcb' );
                        advcb_record_block( $ip, $country_code, $reason, $send_email_alerts, $alert_email );
                        advcb_show_block_page( array(
                                'ip'           => $ip,
                                'country_code' => $country_code,
                                'reason'       => $reason,
                        ) );
                        exit;
                } elseif ( $mode === 'block' && in_array( $country_code, $allowed_countries ) ) {
			if ( isset( $_GET[ $secret_key ] ) ) {
				// Grant temporary access.
				if ( ! is_array( $whitelisted_ips ) ) {
					$whitelisted_ips = array();
				}
				$whitelisted_ips[] = $ip;
				$whitelisted_ips = array_unique( $whitelisted_ips );
				set_transient( 'advcb_whitelisted_ips', $whitelisted_ips, $temporary_access_duration );

                                add_action( 'wp_enqueue_scripts', function() use ( $secret_key ) {
                                        wp_enqueue_script( 'advcb-alert', plugin_dir_url( __FILE__ ) . 'advcb-alert.js', array(), '1.0', true );
					wp_localize_script( 'advcb-alert', 'advcb_redirect', array(
						'url' => esc_url( remove_query_arg( $secret_key ) ),
					) );
				} );
				return;
			}
                        $reason = __( 'Country blocked', 'advcb' );
                        advcb_record_block( $ip, $country_code, $reason, $send_email_alerts, $alert_email );
                        advcb_show_block_page( array(
                                'ip'           => $ip,
                                'country_code' => $country_code,
                                'reason'       => $reason,
                        ) );
                        exit;
                }
        }
}
add_action( 'init', 'advcb_block_non_allowed_countries' );

/**
 * RECORD BLOCKED ATTEMPT IN DATABASE & (optionally) SEND EMAIL
 */
function advcb_record_block( $ip, $country_code, $reason, $send_email_alerts, $alert_email ) {
	// Check if logging is enabled.
	if ( ! get_option( 'advcb_enable_logs', true ) ) {
		// Logging is disabled; optionally still send an email alert.
		if ( $send_email_alerts && ! empty( $alert_email ) ) {
			$subject = 'Country Blocker Alert: A visitor was blocked';
			$message = sprintf(
				"A visitor from IP: %s (country: %s) was blocked.\nReason: %s\nTime: %s",
				$ip,
				$country_code,
				$reason,
				current_time( 'mysql' )
			);
			wp_mail( $alert_email, $subject, $message );
		}
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'advcb_block_logs';

	$wpdb->insert( $table_name, array(
		'ip'           => $ip,
		'country_code' => $country_code ?: '',
		'reason'       => $reason,
	), array( '%s', '%s', '%s' ) );

        if ( $send_email_alerts && ! empty( $alert_email ) ) {
                $subject = 'Country Blocker Alert: A visitor was blocked';
                $message = sprintf(
                        "A visitor from IP: %s (country: %s) was blocked.\nReason: %s\nTime: %s",
                        $ip,
                        $country_code,
                        $reason,
                        current_time( 'mysql' )
                );
                wp_mail( $alert_email, $subject, $message );
        }

        advcb_cleanup_logs();
}

/**
 * SHOW A CUSTOM BLOCK PAGE (instead of default 403).
 */
function advcb_show_block_page( $context = array() ) {
        $redirect_enabled = (bool) get_option( 'advcb_enable_redirect', false );
        $redirect_url     = get_option( 'advcb_redirect_url', '' );

        if ( $redirect_enabled && ! empty( $redirect_url ) ) {
                $redirect_status = (int) get_option( 'advcb_redirect_status_code', 302 );
                $allowed_redirect_statuses = array( 301, 302, 307, 308 );

                if ( ! in_array( $redirect_status, $allowed_redirect_statuses, true ) ) {
                        $redirect_status = 302;
                }

                wp_safe_redirect( esc_url_raw( $redirect_url ), $redirect_status );
                exit;
        }

        $title       = get_option( 'advcb_block_page_title', 'Access Restricted' );
        $message     = get_option( 'advcb_block_message', 'We’re sorry, but your location is not allowed to view this site.' );
        $status_code = (int) get_option( 'advcb_http_status_code', 403 );

        $message     = advcb_replace_placeholders( $message, $context );
        $status_code = in_array( $status_code, apply_filters( 'advcb_allowed_http_status_codes', array( 403, 410, 451 ) ), true )
                ? $status_code
                : 403;

        $content = '<h1>' . esc_html( $title ) . '</h1>' . wpautop( wp_kses_post( $message ) );

        wp_die(
                $content,
                esc_html( $title ),
                array( 'response' => $status_code )
        );
}

/**
 * REGISTER/INITIALIZE SETTINGS
 */
function advcb_register_settings() {
	// Register and sanitize the country codes list.
	register_setting( 'advcb_options_group', 'advcb_allowed_countries', array(
		'sanitize_callback' => 'advcb_sanitize_allowed_countries',
	) );

	// Register and sanitize secret key.
	register_setting( 'advcb_options_group', 'advcb_secret_key', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	// Register and sanitize blacklisted IPs.
        register_setting( 'advcb_options_group', 'advcb_blacklisted_ips', array(
                'sanitize_callback' => 'advcb_sanitize_blacklisted_ips',
        ) );

        register_setting( 'advcb_options_group', 'advcb_trusted_ips', array(
                'sanitize_callback' => 'advcb_sanitize_blacklisted_ips',
        ) );

        // Register and sanitize email alert toggle.
        register_setting( 'advcb_options_group', 'advcb_send_email_alerts', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

	// Register and sanitize alert email.
	register_setting( 'advcb_options_group', 'advcb_alert_email', array(
		'sanitize_callback' => 'sanitize_email',
	) );

        // Register and sanitize the filtering mode.
        register_setting( 'advcb_options_group', 'advcb_mode', array(
                'sanitize_callback' => 'advcb_sanitize_mode',
        ) );

        // Register and sanitize the logging option.
        register_setting( 'advcb_options_group', 'advcb_enable_logs', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

        register_setting( 'advcb_options_group', 'advcb_block_page_title', array(
                'sanitize_callback' => 'sanitize_text_field',
        ) );

        register_setting( 'advcb_options_group', 'advcb_block_message', array(
                'sanitize_callback' => 'advcb_sanitize_textarea',
        ) );

        register_setting( 'advcb_options_group', 'advcb_http_status_code', array(
                'sanitize_callback' => 'advcb_sanitize_http_status',
        ) );

        register_setting( 'advcb_options_group', 'advcb_enable_redirect', array(
                'sanitize_callback' => 'advcb_sanitize_boolean',
        ) );

        register_setting( 'advcb_options_group', 'advcb_redirect_url', array(
                'sanitize_callback' => 'advcb_sanitize_url',
        ) );

        register_setting( 'advcb_options_group', 'advcb_redirect_status_code', array(
                'sanitize_callback' => 'advcb_sanitize_redirect_status',
        ) );

        register_setting( 'advcb_options_group', 'advcb_log_retention_days', array(
                'sanitize_callback' => 'advcb_sanitize_positive_int',
        ) );

        register_setting( 'advcb_options_group', 'advcb_geoip_source', array(
                'sanitize_callback' => 'advcb_sanitize_geoip_source',
        ) );

        register_setting( 'advcb_options_group', 'advcb_geoip_db_path', array(
                'sanitize_callback' => 'advcb_sanitize_file_path',
        ) );
}
add_action( 'admin_init', 'advcb_register_settings' );

/**
 * Clean up log entries based on retention setting.
 */
function advcb_cleanup_logs() {
        $retention_days = absint( get_option( 'advcb_log_retention_days', 30 ) );

        if ( $retention_days <= 0 ) {
                return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'advcb_block_logs';

        $threshold = gmdate( 'Y-m-d H:i:s', time() - ( $retention_days * DAY_IN_SECONDS ) );

        $wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE blocked_time < %s", $threshold ) );
}
add_action( 'advcb_cleanup_logs_event', 'advcb_cleanup_logs' );

/**
 * ADD MENU PAGE
 */
function advcb_register_options_page() {
	add_menu_page(
		'Country Blocker',
		'Country Blocker',
		'manage_options',
		'advcb_settings',
		'advcb_options_page',
		'dashicons-location-alt',
		60
	);

	// Add a sub-page for logs.
	add_submenu_page(
		'advcb_settings',
		'Block Logs',
		'Block Logs',
		'manage_options',
		'advcb_block_logs',
		'advcb_block_logs_page'
	);
}
add_action( 'admin_menu', 'advcb_register_options_page' );

/**
 * MAIN SETTINGS PAGE with Dynamic Country Select Boxes
 */
function advcb_options_page() {
	// Get current filtering mode to adjust labels.
	$mode       = get_option( 'advcb_mode', 'allow' );
	$list_label = ( $mode === 'block' ) ? 'Blocked Country Codes' : 'Allowed Country Codes';
        $list_desc  = ( $mode === 'block' )
                ? 'Select ISO country codes that should be blocked from accessing the site. Everyone else will be allowed automatically.'
                : 'Select ISO country codes that are allowed to access the site. Visitors from all other countries will be blocked.';

	// Retrieve the saved countries. Ensure we have an array.
	$selected_countries = get_option( 'advcb_allowed_countries', array() );
	if ( ! is_array( $selected_countries ) ) {
		$selected_countries = explode( ',', $selected_countries );
	}
	// Always display at least one select box.
	if ( empty( $selected_countries ) ) {
		$selected_countries = array( '' );
	}

        // Get the complete list of countries.
        $countries = advcb_get_countries();

        $trusted_ips = get_option( 'advcb_trusted_ips', array() );
        if ( is_array( $trusted_ips ) ) {
                $trusted_ips = implode( ',', array_filter( array_map( 'trim', $trusted_ips ) ) );
        }

        $geoip_source      = get_option( 'advcb_geoip_source', 'api' );
        $geoip_db_path     = advcb_get_geoip_database_path();
        $geoip_db_readable = $geoip_db_path && file_exists( $geoip_db_path ) && is_readable( $geoip_db_path );
        $geoip_storage_dir = advcb_get_geoip_storage_dir();

        $block_page_title    = get_option( 'advcb_block_page_title', 'Access Restricted' );
        $block_message       = get_option( 'advcb_block_message', 'We’re sorry, but your location is not allowed to view this site.' );
        $http_status_code    = (int) get_option( 'advcb_http_status_code', 403 );
        $enable_redirect     = (bool) get_option( 'advcb_enable_redirect', false );
        $redirect_url        = get_option( 'advcb_redirect_url', '' );
        $redirect_status     = (int) get_option( 'advcb_redirect_status_code', 302 );
        $log_retention_days  = absint( get_option( 'advcb_log_retention_days', 30 ) );
        ?>
    <div class="wrap">
        <h1>Advanced Country Blocker Settings</h1>
        <form method="post" action="options.php" id="advcb-settings-form">
			<?php settings_fields( 'advcb_options_group' ); ?>
			<?php do_settings_sections( 'advcb_options_group' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Country Filter Mode</th>
                    <td>
                        <!-- Hidden field to ensure a value is always sent -->
                        <input type="hidden" name="advcb_mode" value="allow">
                        <label>
                            <input type="checkbox" name="advcb_mode" value="block" <?php checked( 'block', get_option( 'advcb_mode', 'allow' ) ); ?> />
                            Use Blacklist Mode (the list below will block visitors from those countries)
                        </label>
                        <p class="description">Stay in allowlist mode to only let the selected countries in. Switch to blacklist mode when you only need to name the countries you want to block.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html( $list_label ); ?></th>
                    <td>
                        <p><?php echo esc_html( $list_desc ); ?></p>
                        <div id="advcb_country_selector_container">
                                                        <?php foreach ( $selected_countries as $country ) : ?>
                                <div class="advcb_country_selector" style="margin-bottom:5px;">
                                    <select name="advcb_allowed_countries[]">
                                                                                <?php foreach ( $countries as $code => $name ) : ?>
                                            <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $country, $code ); ?>>
                                                                                                <?php echo esc_html( $name ); ?>
                                            </option>
                                                                                <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="button advcb_remove_country">Remove</button>
                                </div>
                                                        <?php endforeach; ?>
                        </div>
                        <button type="button" id="advcb_add_country" class="button">Add Another Country</button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">IP Lookup Method</th>
                    <td>
                        <select name="advcb_geoip_source">
                            <option value="api" <?php selected( $geoip_source, 'api' ); ?>>Remote API (ip-api.com)</option>
                            <option value="database" <?php selected( $geoip_source, 'database' ); ?>>Local MaxMind database</option>
                        </select>
                        <p class="description">Use the remote API for quick setup. Switch to the local database once you have configured the GeoLite2 file below for fully offline lookups.</p>
                                                <?php if ( 'database' === $geoip_source && ! $geoip_db_readable ) : ?>
                            <p class="description"><span class="dashicons dashicons-warning" aria-hidden="true"></span> Local lookup is selected but the database path is not readable yet, so the plugin will fall back to the remote API.</p>
                                                <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">GeoIP Database</th>
                    <td>
                                                <?php if ( $geoip_db_readable ) : ?>
                            <p class="description"><span class="dashicons dashicons-yes-alt" aria-hidden="true"></span> Using <code><?php echo esc_html( basename( $geoip_db_path ) ); ?></code> stored in the plugin-managed directory.</p>
                                                        <?php
                                                        $db_details = array();
                                                        if ( $geoip_db_path ) {
                                                                if ( function_exists( 'size_format' ) ) {
                                                                        $size = @filesize( $geoip_db_path );

                                                                        if ( false !== $size ) {
                                                                                $db_details[] = size_format( $size );
                                                                        }
                                                                }

                                                                $modified = @filemtime( $geoip_db_path );
                                                                if ( $modified ) {
                                                                        $db_details[] = 'updated ' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $modified );
                                                                }
                                                        }

                                                        if ( ! empty( $db_details ) ) {
                                                                echo '<p class="description">(' . esc_html( implode( ', ', $db_details ) ) . ')</p>';
                                                        }
                                                        ?>
                                                <?php else : ?>
                            <p class="description"><span class="dashicons dashicons-warning" aria-hidden="true"></span> No readable database found yet. Download or upload a GeoLite2 Country (or compatible) <code>.mmdb</code> file and the plugin will store it automatically.</p>
                                                <?php endif; ?>
                                                <?php if ( $geoip_storage_dir ) : ?>
                            <p class="description">Downloaded and uploaded databases are stored in <code><?php echo esc_html( $geoip_storage_dir ); ?></code>.</p>
                                                <?php else : ?>
                            <p class="description">Downloaded and uploaded databases are stored inside your WordPress uploads directory. The plugin will handle the path automatically.</p>
                                                <?php endif; ?>
                        <h4>Quick download</h4>
                        <div class="advcb-geoip-download-buttons" style="margin-bottom:10px;">
                            <button type="submit" class="button" form="advcb-download-geoip-gitio">Download from Git.io</button>
                            <button type="submit" class="button" form="advcb-download-geoip-mirror" style="margin-left:8px;">Download from Mirror</button>
                        </div>
                        <div class="advcb-geoip-custom-download" style="margin-top:10px;">
                            <label class="screen-reader-text" for="advcb_geoip_custom_url">Custom GeoIP download URL</label>
                            <input type="url" class="regular-text" id="advcb_geoip_custom_url" name="advcb_geoip_url" placeholder="https://example.com/GeoLite2-Country.mmdb" form="advcb-download-geoip-custom" />
                            <button type="submit" class="button button-primary" style="margin-top:5px;" form="advcb-download-geoip-custom">Download from URL</button>
                        </div>
                        <h4 style="margin-top:20px;">Upload a database</h4>
                        <div class="advcb-geoip-upload">
                            <input type="file" name="advcb_geoip_file" accept=".mmdb" form="advcb-upload-geoip" />
                            <button type="submit" class="button button-primary" style="margin-top:5px;" form="advcb-upload-geoip">Upload &amp; Use Database</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Block Page Title</th>
                    <td>
                        <input type="text" class="regular-text" name="advcb_block_page_title" value="<?php echo esc_attr( $block_page_title ); ?>" />
                        <p class="description">Displayed as the heading when a visitor is blocked.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Block Page Message</th>
                    <td>
                        <textarea name="advcb_block_message" rows="5" class="large-text"><?php echo esc_textarea( $block_message ); ?></textarea>
                        <p class="description">Use placeholders <code>{ip}</code>, <code>{country_code}</code>, and <code>{reason}</code> to personalize the message.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">HTTP Status Code</th>
                    <td>
                        <select name="advcb_http_status_code">
                            <option value="403" <?php selected( $http_status_code, 403 ); ?>>403 Forbidden</option>
                            <option value="410" <?php selected( $http_status_code, 410 ); ?>>410 Gone</option>
                            <option value="451" <?php selected( $http_status_code, 451 ); ?>>451 Unavailable for Legal Reasons</option>
                        </select>
                        <p class="description">Choose the HTTP status served with the block message.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Redirect Instead of Block Page</th>
                    <td>
                        <label>
                            <input type="checkbox" name="advcb_enable_redirect" value="1" <?php checked( $enable_redirect ); ?> />
                            Send blocked visitors to a custom URL.
                        </label>
                        <p>
                            <input type="url" class="regular-text" name="advcb_redirect_url" value="<?php echo esc_attr( $redirect_url ); ?>" placeholder="https://example.com/blocked" />
                        </p>
                        <p>
                            <label>Redirect status
                                <select name="advcb_redirect_status_code">
                                    <option value="302" <?php selected( $redirect_status, 302 ); ?>>302 Temporary Redirect</option>
                                    <option value="301" <?php selected( $redirect_status, 301 ); ?>>301 Permanent Redirect</option>
                                    <option value="307" <?php selected( $redirect_status, 307 ); ?>>307 Temporary Redirect</option>
                                    <option value="308" <?php selected( $redirect_status, 308 ); ?>>308 Permanent Redirect</option>
                                </select>
                            </label>
                        </p>
                        <p class="description">Leave the URL blank to keep showing the custom block page even if the redirect option is enabled.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Secret Key for Temporary Access</th>
                    <td>
                        <input type="text" name="advcb_secret_key" value="<?php echo esc_attr( get_option( 'advcb_secret_key', 'OpenSesame' ) ); ?>" />
                        <p>
                            Append <code>?<?php echo esc_html( get_option( 'advcb_secret_key', 'OpenSesame' ) ); ?>=1</code> to the URL to gain temporary access.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Blacklisted IP Addresses</th>
                    <td>
                        <p>Enter comma-separated IP addresses that should be blocked regardless of country filtering.</p>
                        <input type="text" name="advcb_blacklisted_ips" value="<?php echo esc_attr( is_array( get_option( 'advcb_blacklisted_ips', array() ) ) ? implode( ',', get_option( 'advcb_blacklisted_ips', array() ) ) : get_option( 'advcb_blacklisted_ips', '' ) ); ?>" style="width: 100%; max-width: 400px;" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Trusted IP Addresses</th>
                    <td>
                        <p>Enter comma-separated IP addresses that should always bypass the blocker. Handy for uptime monitors and service partners.</p>
                        <input type="text" name="advcb_trusted_ips" value="<?php echo esc_attr( $trusted_ips ); ?>" style="width: 100%; max-width: 400px;" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Email Alerts</th>
                    <td>
                        <label>
                            <input type="checkbox" name="advcb_send_email_alerts" value="1" <?php checked( true, (bool) get_option( 'advcb_send_email_alerts', false ) ); ?> />
                            Send email alerts for blocked attempts?
                        </label>
                        <p>Email to notify:</p>
                        <input type="email" name="advcb_alert_email" value="<?php echo esc_attr( get_option( 'advcb_alert_email', get_option( 'admin_email' ) ) ); ?>" style="width: 100%; max-width: 400px;" />
                    </td>
                </tr>
                <!-- New option: Enable Logging -->
                <tr>
                    <th scope="row">Enable Logging</th>
                    <td>
                        <label>
                            <input type="checkbox" name="advcb_enable_logs" value="1" <?php checked( true, (bool) get_option( 'advcb_enable_logs', true ) ); ?> />
                            Keep a record of blocked attempts?
                        </label>
                        <p>Disable logging if you do not wish to store blocked attempts in your database.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Automatic Log Cleanup</th>
                    <td>
                        <input type="number" min="0" name="advcb_log_retention_days" value="<?php echo esc_attr( $log_retention_days ); ?>" /> days
                        <p class="description">Entries older than this many days will be purged automatically. Use 0 to keep logs forever.</p>
                    </td>
                </tr>
            </table>
                        <?php submit_button(); ?>
        </form>
        <form id="advcb-download-geoip-gitio" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;" aria-hidden="true">
                <?php wp_nonce_field( 'advcb_geoip_download' ); ?>
            <input type="hidden" name="action" value="advcb_geoip_download" />
            <input type="hidden" name="advcb_geoip_url" value="https://git.io/GeoLite2-Country.mmdb" />
        </form>
        <form id="advcb-download-geoip-mirror" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;" aria-hidden="true">
                <?php wp_nonce_field( 'advcb_geoip_download' ); ?>
            <input type="hidden" name="action" value="advcb_geoip_download" />
            <input type="hidden" name="advcb_geoip_url" value="https://github.com/P3TERX/GeoLite.mmdb/raw/download/GeoLite2-Country.mmdb" />
        </form>
        <form id="advcb-download-geoip-custom" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:none;" aria-hidden="true">
                <?php wp_nonce_field( 'advcb_geoip_download' ); ?>
            <input type="hidden" name="action" value="advcb_geoip_download" />
        </form>
        <form id="advcb-upload-geoip" class="advcb-hidden-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" style="display:none;" aria-hidden="true">
                <?php wp_nonce_field( 'advcb_geoip_upload' ); ?>
            <input type="hidden" name="action" value="advcb_geoip_upload" />
        </form>
    </div>
    <!-- Inline JavaScript to handle dynamic country select boxes -->
    <script>
        jQuery(document).ready(function($) {
            // Add new select box when "Add Another Country" is clicked.
            $('#advcb_add_country').on('click', function(){
                // Clone the first selector, reset its value and append.
                var $clone = $('#advcb_country_selector_container .advcb_country_selector:first').clone();
                $clone.find('select').val('');
                $('#advcb_country_selector_container').append($clone);
            });
            // Remove a select box when its "Remove" button is clicked.
            $(document).on('click', '.advcb_remove_country', function(){
                if ($('#advcb_country_selector_container .advcb_country_selector').length > 1) {
                    $(this).closest('.advcb_country_selector').remove();
                } else {
                    alert('At least one country must be selected.');
                }
            });
        });
    </script>
	<?php
}

/**
 * SHOW THE BLOCK LOGS IN THE ADMIN
 */
function advcb_block_logs_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'advcb_block_logs';

        advcb_cleanup_logs();

        // Check for a truncate action and validate nonce.
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'truncate_logs' && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'truncate_logs' ) ) {
                $wpdb->query( "TRUNCATE TABLE $table_name" );
                echo '<div class="updated notice"><p>Logs have been cleared.</p></div>';
	}

	$paged  = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
	$limit  = 20;
	$offset = ( $paged - 1 ) * $limit;

	$results = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM $table_name ORDER BY blocked_time DESC LIMIT %d OFFSET %d",
		$limit,
		$offset
	) );

	$total       = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	$total_pages = ceil( $total / $limit );
        ?>
    <div class="wrap">
        <h1>Blocked Attempts Log</h1>
        <p class="description">
            <?php
            $retention_days = absint( get_option( 'advcb_log_retention_days', 30 ) );
            if ( $retention_days > 0 ) {
                    printf( esc_html__( 'Entries older than %d day(s) are removed automatically.', 'advcb' ), $retention_days );
            } else {
                    esc_html_e( 'Automatic log cleanup is currently disabled.', 'advcb' );
            }
            ?>
        </p>
        <!-- Add a Clear Logs button -->
        <p>
            <a href="<?php echo esc_url( add_query_arg( array(
                                'action'   => 'truncate_logs',
                                '_wpnonce' => wp_create_nonce( 'truncate_logs' )
			) ) ); ?>" class="button button-secondary" onclick="return confirm('Are you sure you want to clear all logs?');">
                Clear Logs
            </a>
        </p>
		<?php if ( $results ) : ?>
            <table class="widefat fixed striped">
                <thead>
                <tr>
                    <th width="50px">ID</th>
                    <th width="150px">IP</th>
                    <th width="100px">Country Code</th>
                    <th>Reason</th>
                    <th width="200px">Time</th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ( $results as $row ) : ?>
                    <tr>
                        <td><?php echo esc_html( $row->id ); ?></td>
                        <td><?php echo esc_html( $row->ip ); ?></td>
                        <td><?php echo esc_html( $row->country_code ); ?></td>
                        <td><?php echo esc_html( $row->reason ); ?></td>
                        <td><?php echo esc_html( $row->blocked_time ); ?></td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
			<?php if ( $total_pages > 1 ) : ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
						<?php
						// Limit pagination to a maximum of 10 buttons.
						if ( $total_pages > 10 ) {
							if ( $paged <= 6 ) {
								$start = 1;
								$end   = 10;
							} elseif ( $paged > $total_pages - 5 ) {
								$start = $total_pages - 9;
								$end   = $total_pages;
							} else {
								$start = $paged - 5;
								$end   = $paged + 4;
							}
						} else {
							$start = 1;
							$end   = $total_pages;
						}

						// Optionally, add a "Previous" button.
						if ( $paged > 1 ) {
							echo '<a class="button" href="' . esc_url( add_query_arg( array( 'page' => 'advcb_block_logs', 'paged' => $paged - 1 ) ) ) . '">&laquo; Prev</a> ';
						}

						for ( $i = $start; $i <= $end; $i++ ) {
							$class = ( $i == $paged ) ? ' class="button button-primary disabled"' : ' class="button"';
							echo '<a' . wp_kses_post($class) . ' href="' . esc_url( add_query_arg( array( 'page' => 'advcb_block_logs', 'paged' => $i ) ) ) . '">' . esc_html( $i ) . '</a> ';
						}

						// Optionally, add a "Next" button.
						if ( $paged < $total_pages ) {
							echo '<a class="button" href="' . esc_url( add_query_arg( array( 'page' => 'advcb_block_logs', 'paged' => $paged + 1 ) ) ) . '">Next &raquo;</a>';
						}
						?>
                    </div>
                </div>
			<?php endif; ?>
		<?php else : ?>
            <p>No blocked attempts logged yet.</p>
		<?php endif; ?>
    </div>
	<?php
}


/**
 * ADMIN HELPERS FOR MANAGING THE GEOIP DATABASE
 */
function advcb_geoip_notice_key() {
        $user_id = get_current_user_id();

        return 'advcb_geoip_notice_' . ( $user_id ? $user_id : '0' );
}

function advcb_add_geoip_notice( $message, $type = 'success' ) {
        $allowed_types = array( 'success', 'error', 'warning', 'info' );

        if ( ! in_array( $type, $allowed_types, true ) ) {
                $type = 'success';
        }

        set_transient(
                advcb_geoip_notice_key(),
                array(
                        'type'    => $type,
                        'message' => $message,
                ),
                MINUTE_IN_SECONDS
        );
}

function advcb_geoip_admin_notices() {
        if ( ! current_user_can( 'manage_options' ) ) {
                return;
        }

        $notice = get_transient( advcb_geoip_notice_key() );

        if ( false === $notice ) {
                return;
        }

        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

        if ( $screen && ! in_array( $screen->id, array( 'toplevel_page_advcb_settings', 'country-blocker_page_advcb_block_logs' ), true ) ) {
                return;
        }

        delete_transient( advcb_geoip_notice_key() );

        $type  = isset( $notice['type'] ) ? $notice['type'] : 'success';
        $class = 'notice notice-' . sanitize_html_class( $type );

        echo '<div class="' . esc_attr( $class ) . '"><p>' . esc_html( $notice['message'] ) . '</p></div>';
}
add_action( 'admin_notices', 'advcb_geoip_admin_notices' );

function advcb_geoip_redirect_to_settings() {
        $redirect = admin_url( 'admin.php?page=advcb_settings' );
        wp_safe_redirect( $redirect );
        exit;
}

function advcb_save_geoip_database_content( $content, $filename = 'GeoLite2-Country.mmdb' ) {
        if ( ! function_exists( 'wp_mkdir_p' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $storage_dir = advcb_get_geoip_storage_dir();

        if ( ! $storage_dir ) {
                return new WP_Error( 'advcb_geoip_storage', __( 'Unable to determine the GeoIP storage directory.', 'advcb' ) );
        }

        if ( ! wp_mkdir_p( $storage_dir ) ) {
                return new WP_Error( 'advcb_geoip_storage', __( 'Unable to create the GeoIP storage directory.', 'advcb' ) );
        }

        $filename = sanitize_file_name( $filename );

        if ( '' === $filename ) {
                $filename = 'GeoLite2-Country.mmdb';
        }

        if ( 'mmdb' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
                $filename .= '.mmdb';
        }

        $destination = trailingslashit( $storage_dir ) . $filename;
        $written     = file_put_contents( $destination, $content );

        if ( false === $written ) {
                return new WP_Error( 'advcb_geoip_write', __( 'Failed to save the GeoIP database file.', 'advcb' ) );
        }

        @chmod( $destination, 0644 );

        update_option( 'advcb_geoip_db_path', $filename );

        return array(
                'path'     => $destination,
                'filename' => $filename,
                'bytes'    => $written,
        );
}

function advcb_handle_geoip_download() {
        if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'You do not have permission to perform this action.', 'advcb' ) );
        }

        check_admin_referer( 'advcb_geoip_download' );

        $url = isset( $_POST['advcb_geoip_url'] ) ? wp_unslash( $_POST['advcb_geoip_url'] ) : '';
        $url = esc_url_raw( trim( $url ) );

        if ( empty( $url ) ) {
                advcb_add_geoip_notice( __( 'Please provide a valid download URL.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $response = wp_safe_remote_get(
                $url,
                array(
                        'timeout' => 20,
                )
        );

        if ( is_wp_error( $response ) ) {
                advcb_add_geoip_notice( sprintf( __( 'Download failed: %s', 'advcb' ), $response->get_error_message() ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $status_code = (int) wp_remote_retrieve_response_code( $response );

        if ( 200 !== $status_code ) {
                advcb_add_geoip_notice( sprintf( __( 'Download failed with status code %d.', 'advcb' ), $status_code ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $body = wp_remote_retrieve_body( $response );

        if ( empty( $body ) ) {
                advcb_add_geoip_notice( __( 'The downloaded file appears to be empty.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $parsed_url = wp_parse_url( $url );
        $filename   = isset( $parsed_url['path'] ) ? basename( $parsed_url['path'] ) : 'GeoLite2-Country.mmdb';

        $result = advcb_save_geoip_database_content( $body, $filename );

        if ( is_wp_error( $result ) ) {
                advcb_add_geoip_notice( $result->get_error_message(), 'error' );
        } else {
                advcb_add_geoip_notice( sprintf( __( 'GeoIP database saved as %s.', 'advcb' ), $result['filename'] ), 'success' );
        }

        advcb_geoip_redirect_to_settings();
}
add_action( 'admin_post_advcb_geoip_download', 'advcb_handle_geoip_download' );

function advcb_handle_geoip_upload() {
        if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'You do not have permission to perform this action.', 'advcb' ) );
        }

        check_admin_referer( 'advcb_geoip_upload' );

        if ( empty( $_FILES['advcb_geoip_file'] ) || ! is_array( $_FILES['advcb_geoip_file'] ) ) {
                advcb_add_geoip_notice( __( 'No file was uploaded.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $file = $_FILES['advcb_geoip_file'];

        if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== (int) $file['error'] ) {
                advcb_add_geoip_notice( __( 'There was an error uploading the file.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $filename = isset( $file['name'] ) ? sanitize_file_name( wp_unslash( $file['name'] ) ) : '';

        if ( '' === $filename ) {
                $filename = 'GeoLite2-Country.mmdb';
        }

        if ( ! function_exists( 'wp_check_filetype_and_ext' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $file_type = wp_check_filetype_and_ext( $file['tmp_name'], $filename, array( 'mmdb' => 'application/octet-stream' ) );

        if ( isset( $file_type['ext'] ) && 'mmdb' !== $file_type['ext'] ) {
                advcb_add_geoip_notice( __( 'Please upload a valid .mmdb database file.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
                advcb_add_geoip_notice( __( 'The uploaded file could not be validated.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        $storage_dir = advcb_get_geoip_storage_dir();

        if ( ! $storage_dir ) {
                advcb_add_geoip_notice( __( 'Unable to determine the GeoIP storage directory.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( ! function_exists( 'wp_mkdir_p' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if ( ! wp_mkdir_p( $storage_dir ) ) {
                advcb_add_geoip_notice( __( 'Unable to create the GeoIP storage directory.', 'advcb' ), 'error' );
                advcb_geoip_redirect_to_settings();
        }

        if ( 'mmdb' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
                $filename .= '.mmdb';
        }

        $destination = trailingslashit( $storage_dir ) . $filename;

        if ( ! move_uploaded_file( $file['tmp_name'], $destination ) ) {
                if ( ! @copy( $file['tmp_name'], $destination ) ) {
                        advcb_add_geoip_notice( __( 'Failed to move the uploaded file.', 'advcb' ), 'error' );
                        advcb_geoip_redirect_to_settings();
                }

                @unlink( $file['tmp_name'] );
        }

        @chmod( $destination, 0644 );

        update_option( 'advcb_geoip_db_path', $filename );

        advcb_add_geoip_notice( sprintf( __( 'GeoIP database uploaded as %s.', 'advcb' ), $filename ), 'success' );

        advcb_geoip_redirect_to_settings();
}
add_action( 'admin_post_advcb_geoip_upload', 'advcb_handle_geoip_upload' );


/**
 * SANITIZE INPUTS
 */
function advcb_sanitize_allowed_countries( $input ) {
	$countries = is_array( $input ) ? $input : explode( ',', $input );
	return array_map( 'sanitize_text_field', array_map( 'trim', $countries ) );
}

function advcb_sanitize_blacklisted_ips( $input ) {
        $ips = is_array( $input ) ? $input : explode( ',', $input );
        return array_map( 'sanitize_text_field', array_map( 'trim', $ips ) );
}

function advcb_sanitize_boolean( $input ) {
        return (bool) $input;
}

function advcb_sanitize_mode( $input ) {
        return ( $input === 'block' ) ? 'block' : 'allow';
}

function advcb_sanitize_textarea( $input ) {
        return wp_kses_post( $input );
}

function advcb_sanitize_http_status( $input ) {
        $allowed = array( 403, 410, 451 );
        $input   = (int) $input;
        return in_array( $input, $allowed, true ) ? $input : 403;
}

function advcb_sanitize_url( $input ) {
        return esc_url_raw( trim( $input ) );
}

function advcb_sanitize_redirect_status( $input ) {
        $allowed = array( 301, 302, 307, 308 );
        $input   = (int) $input;
        return in_array( $input, $allowed, true ) ? $input : 302;
}

function advcb_sanitize_positive_int( $input ) {
        return absint( $input );
}

function advcb_sanitize_file_path( $input ) {
        if ( is_array( $input ) ) {
                return '';
        }

        $input = is_string( $input ) ? wp_unslash( $input ) : '';
        $input = trim( $input );

        if ( '' === $input ) {
                return '';
        }

        $normalized = wp_normalize_path( $input );

        if ( function_exists( 'path_is_absolute' ) && path_is_absolute( $normalized ) ) {
                return sanitize_text_field( $normalized );
        }

        if ( preg_match( '#^[a-zA-Z]:/#', $normalized ) ) {
                return sanitize_text_field( $normalized );
        }

        return sanitize_file_name( wp_basename( $normalized ) );
}

function advcb_sanitize_geoip_source( $input ) {
        if ( ! is_string( $input ) ) {
                return 'api';
        }

        $input = strtolower( trim( $input ) );
        $allowed = array( 'api', 'database' );

        return in_array( $input, $allowed, true ) ? $input : 'api';
}

function advcb_replace_placeholders( $message, $context = array() ) {
        $placeholders = array(
                '{ip}'           => isset( $context['ip'] ) ? $context['ip'] : '',
                '{country_code}' => isset( $context['country_code'] ) ? $context['country_code'] : '',
                '{reason}'       => isset( $context['reason'] ) ? $context['reason'] : '',
        );

        foreach ( $placeholders as $placeholder => $value ) {
                $message = str_replace( $placeholder, esc_html( $value ), $message );
        }

        return $message;
}

add_filter( 'pre_update_option_advcb_allowed_countries', 'advcb_sanitize_allowed_countries' );
add_filter( 'pre_update_option_advcb_blacklisted_ips', 'advcb_sanitize_blacklisted_ips' );
?>
