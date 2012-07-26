<?php
/*
Plugin Name: Shopp (nl)
Plugin URI: http://pronamic.eu/wp-plugins/shopp-nl/
Description: Extends the Shopp plugin and add-ons with the Dutch language: <strong>Shopp</strong> 1.2.2

Version: 0.1
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/
License: GPL
*/

class ShoppNL {
	/**
	 * The current langauge
	 *
	 * @var string
	 */
	private static $language;

	/**
	 * Flag for the dutch langauge, true if current langauge is dutch, false otherwise
	 *
	 * @var boolean
	 */
	private static $isDutch;

	////////////////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap( ) {
		add_filter( 'load_textdomain_mofile' , array( __CLASS__, 'load_mo_file' ), 10, 2 );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Load text domain MO file
	 *
	 * @param string $moFile
	 * @param string $domain
	 */
	public static function load_mo_file( $mo_file, $domain ) {
		if( self::$language == null ) {
			self::$language = get_option( 'WPLANG', WPLANG );
			self::$isDutch = ( self::$language == 'nl' || self::$language == 'nl_NL' );

			if( defined( 'ICL_LANGUAGE_CODE' ) ) {
				self::$isDutch = ICL_LANGUAGE_CODE == 'nl';
			}
		}

		$new_mo_file = null;

		// Shopp
		$is_shopp_domain = ( $domain == 'Shopp' );

		if( $is_shopp_domain ) {
			$is_shopp = strpos( $mo_file, '/shopp/' ) !== false;

			if( $is_shopp ) {
				$version = defined( 'SHOPP_VERSION' ) ? SHOPP_VERSION : null;

				$new_mo_file = self::get_mo_file( 'shopp', $version );
			}
		}

		if( is_readable( $new_mo_file ) ) {
			$mo_file = $new_mo_file;
		}

		return $mo_file;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get the MO file for the specified domain, version and language
	 */
	public static function get_mo_file( $domain, $version, $path = '' ) {
		$dir = dirname( __FILE__ );

		$mo_file = $dir . '/languages/' . $domain . '/' . $version . '/' . $path . self::$language . '.mo';

		// if specific version MO file is not available point to the current public release (cpr) version
		if( ! is_readable( $mo_file ) ) {
			$mo_file = $dir . '/languages/' . $domain . '/cpr/' . $path . self::$language . '.mo';
		}

		return $mo_file;
	}
}

ShoppNL::bootstrap();