<?php
/**
 * Local GeoIP lookup helper.
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

use MaxMind\Db\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;

if ( ! function_exists( 'advcb_get_country_code_for_ip' ) ) {
        /**
         * Returns the ISO country code for an IP address using the configured lookup method.
         *
         * @param string $ip The IP address.
         *
         * @return string|null The ISO country code or null if not available.
         */
        function advcb_get_country_code_for_ip( $ip ) {
                if ( empty( $ip ) || ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                        return null;
                }

                $method = advcb_get_geoip_lookup_method();

                if ( 'database' === $method ) {
                        $code = advcb_geoip_lookup_via_database( $ip );

                        if ( ! empty( $code ) ) {
                                return strtoupper( $code );
                        }

                        $method = 'api';
                }

                if ( 'api' === $method ) {
                        $code = advcb_geoip_lookup_via_api( $ip );

                        if ( ! empty( $code ) ) {
                                return strtoupper( $code );
                        }

                        $fallback = advcb_geoip_lookup_via_database( $ip );

                        if ( ! empty( $fallback ) ) {
                                return strtoupper( $fallback );
                        }

                        return null;
                }

                $code = advcb_geoip_lookup_via_api( $ip );

                if ( ! empty( $code ) ) {
                        return strtoupper( $code );
                }

                return advcb_geoip_lookup_via_database( $ip );
        }
}

if ( ! function_exists( 'advcb_geoip_lookup_via_api' ) ) {
        /**
         * Looks up the country code for an IP address using the remote API.
         *
         * @param string $ip The IP address.
         *
         * @return string|null The ISO country code or null if unavailable.
         */
        function advcb_geoip_lookup_via_api( $ip ) {
                if ( empty( $ip ) || ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                        return null;
                }

                $default_url = 'http://ip-api.com/json/' . rawurlencode( $ip );
                $api_url     = apply_filters( 'advcb_geoip_api_url', $default_url, $ip );

                if ( empty( $api_url ) ) {
                        return null;
                }

                $response = wp_remote_get( $api_url, array( 'timeout' => 5 ) );

                if ( is_wp_error( $response ) ) {
                        return null;
                }

                $body = wp_remote_retrieve_body( $response );

                if ( empty( $body ) ) {
                        return null;
                }

                $data = json_decode( $body );

                if ( ! $data || ! is_object( $data ) ) {
                        return null;
                }

                $code = null;

                if ( isset( $data->countryCode ) && is_string( $data->countryCode ) ) {
                        $code = $data->countryCode;
                }

                /**
                 * Filters the country code returned from the remote API lookup.
                 *
                 * @param string|null $code The country code returned by the API.
                 * @param object      $data The decoded API response body.
                 * @param string      $ip   The IP address that was looked up.
                 */
                $code = apply_filters( 'advcb_geoip_api_country_code', $code, $data, $ip );

                return $code ? strtoupper( $code ) : null;
        }
}

if ( ! function_exists( 'advcb_geoip_lookup_via_database' ) ) {
        /**
         * Looks up the country code for an IP address using local MaxMind resources.
         *
         * @param string $ip The IP address.
         *
         * @return string|null The ISO country code or null if unavailable.
         */
        function advcb_geoip_lookup_via_database( $ip ) {
                if ( empty( $ip ) || ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                        return null;
                }

                $db_path = advcb_get_geoip_database_path();

                // Prefer the legacy PHP GeoIP extension if it exists.
                if ( function_exists( 'geoip_country_code_by_name' ) ) {
                        $code = @geoip_country_code_by_name( $ip ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

                        if ( ! empty( $code ) ) {
                                return strtoupper( $code );
                        }
                }

                // Try the PECL maxminddb extension if it is present.
                if ( $db_path && function_exists( 'maxminddb_open' ) && function_exists( 'maxminddb_lookup_string' ) && function_exists( 'maxminddb_close' ) ) {
                        $reader = @maxminddb_open( $db_path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

                        if ( is_resource( $reader ) ) {
                                $lookup = maxminddb_lookup_string( $reader, $ip );

                                if ( is_array( $lookup ) && isset( $lookup['record'] ) && is_array( $lookup['record'] ) ) {
                                        $record = $lookup['record'];

                                        if ( isset( $record['country']['iso_code'] ) ) {
                                                maxminddb_close( $reader );
                                                return strtoupper( $record['country']['iso_code'] );
                                        }

                                        if ( isset( $record['registered_country']['iso_code'] ) ) {
                                                maxminddb_close( $reader );
                                                return strtoupper( $record['registered_country']['iso_code'] );
                                        }

                                        if ( isset( $record['traits']['iso_code'] ) ) {
                                                maxminddb_close( $reader );
                                                return strtoupper( $record['traits']['iso_code'] );
                                        }
                                }

                                maxminddb_close( $reader );
                        }
                }

                static $reader = null;
                static $reader_failed = false;
                static $reader_path = null;

                if ( $reader_failed ) {
                        return null;
                }

                if ( empty( $db_path ) || ! file_exists( $db_path ) || ! is_readable( $db_path ) ) {
                        if ( $reader && method_exists( $reader, 'close' ) ) {
                                $reader->close();
                        }
                        $reader         = null;
                        $reader_path    = null;
                        $reader_failed  = false;
                        return null;
                }

                if ( null !== $reader_path && $reader_path !== $db_path && $reader && method_exists( $reader, 'close' ) ) {
                        $reader->close();
                        $reader        = null;
                        $reader_failed = false;
                }

                if ( null === $reader ) {
                        if ( ! class_exists( '\\MaxMind\\Db\\Reader' ) ) {
                                require_once plugin_dir_path( __FILE__ ) . 'MaxMind/Db/Reader.php';
                                require_once plugin_dir_path( __FILE__ ) . 'MaxMind/Db/Reader/Decoder.php';
                                require_once plugin_dir_path( __FILE__ ) . 'MaxMind/Db/Reader/InvalidDatabaseException.php';
                                require_once plugin_dir_path( __FILE__ ) . 'MaxMind/Db/Reader/Metadata.php';
                                require_once plugin_dir_path( __FILE__ ) . 'MaxMind/Db/Reader/Util.php';
                        }

                        try {
                                $reader      = new Reader( $db_path );
                                $reader_path = $db_path;
                        } catch ( InvalidDatabaseException $e ) {
                                $reader_failed = true;

                                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                                        error_log( 'Advanced Country Blocker: Invalid MaxMind database - ' . $e->getMessage() );
                                }

                                return null;
                        } catch ( \InvalidArgumentException $e ) {
                                $reader_failed = true;

                                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                                        error_log( 'Advanced Country Blocker: Unable to open MaxMind database - ' . $e->getMessage() );
                                }

                                return null;
                        }
                }

                if ( ! $reader ) {
                        return null;
                }

                try {
                        $record = $reader->get( $ip );
                } catch ( \Exception $e ) {
                        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                                error_log( 'Advanced Country Blocker: GeoIP lookup failed - ' . $e->getMessage() );
                        }

                        return null;
                }

                if ( isset( $record['country']['iso_code'] ) ) {
                        return strtoupper( $record['country']['iso_code'] );
                }

                if ( isset( $record['registered_country']['iso_code'] ) ) {
                        return strtoupper( $record['registered_country']['iso_code'] );
                }

                if ( isset( $record['traits']['iso_code'] ) ) {
                        return strtoupper( $record['traits']['iso_code'] );
                }

                return null;
        }
}

if ( ! function_exists( 'advcb_get_geoip_storage_dir' ) ) {
        /**
         * Returns the directory path used to store GeoIP databases managed by the plugin.
         *
         * @return string Empty string if the uploads directory could not be determined.
         */
        function advcb_get_geoip_storage_dir() {
                $uploads = wp_upload_dir();

                if ( ! empty( $uploads['error'] ) ) {
                        return '';
                }

                $base_dir = isset( $uploads['basedir'] ) ? $uploads['basedir'] : '';

                if ( empty( $base_dir ) ) {
                        return '';
                }

                return trailingslashit( wp_normalize_path( $base_dir ) ) . 'advanced-country-blocker';
        }
}

if ( ! function_exists( 'advcb_get_geoip_database_path' ) ) {
        /**
         * Retrieves the configured path to the MaxMind GeoIP database.
         *
         * @return string|null
         */
        function advcb_get_geoip_database_path() {
                $db_path = get_option( 'advcb_geoip_db_path', '' );
                $db_path = apply_filters( 'advcb_geoip_database_path', $db_path );

                $db_path = is_string( $db_path ) ? trim( $db_path ) : '';

                if ( '' === $db_path ) {
                        $storage_dir = advcb_get_geoip_storage_dir();

                        if ( $storage_dir && is_dir( $storage_dir ) ) {
                                $files = glob( trailingslashit( $storage_dir ) . '*.mmdb' );

                                if ( ! empty( $files ) ) {
                                        $first_file = reset( $files );

                                        if ( $first_file ) {
                                                update_option( 'advcb_geoip_db_path', basename( $first_file ) );

                                                return $first_file;
                                        }
                                }
                        }

                        return null;
                }

                $normalized_path = wp_normalize_path( $db_path );

                if ( function_exists( 'path_is_absolute' ) && path_is_absolute( $normalized_path ) ) {
                        return $normalized_path;
                }

                if ( preg_match( '#^[a-zA-Z]:/#', $normalized_path ) ) { // Windows absolute paths may not be caught above.
                        return $normalized_path;
                }

                $storage_dir = advcb_get_geoip_storage_dir();

                if ( ! $storage_dir ) {
                        return $normalized_path;
                }

                return trailingslashit( $storage_dir ) . basename( $normalized_path );
        }
}

if ( ! function_exists( 'advcb_get_geoip_lookup_method' ) ) {
        /**
         * Retrieves the selected GeoIP lookup method.
         *
         * @return string Either 'api' or 'database'.
         */
        function advcb_get_geoip_lookup_method() {
                $method = get_option( 'advcb_geoip_source', 'api' );
                $method = apply_filters( 'advcb_geoip_lookup_method', $method );

                if ( ! is_string( $method ) ) {
                        return 'api';
                }

                $method = strtolower( trim( $method ) );

                return in_array( $method, array( 'api', 'database' ), true ) ? $method : 'api';
        }
}
