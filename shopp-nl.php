<?php
/*
Plugin Name: Shopp (nl)
Plugin URI: http://pronamic.eu/wp-plugins/shopp-nl/
Description: Extends the Shopp plugin and add-ons with the Dutch language: <strong>Shopp</strong> 1.2.8

Version: 0.1.4
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/
License: GPL

Text Domain: shopp_nl
Domain Path: /languages/

GitHub URI: https://github.com/pronamic/wp-shopp-nl
*/

class ShoppNLPlugin {
	/**
	 * The current langauge
	 *
	 * @var string
	 */
	private $language;

	/**
	 * Flag for the dutch langauge, true if current langauge is dutch, false otherwise
	 *
	 * @var boolean
	 */
	private $is_dutch;

	////////////////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public function __construct( $file ) {
		$this->file = $file;

		add_filter( 'load_textdomain_mofile' , array( $this, 'load_mo_file' ), 10, 2 );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Load text domain MO file
	 *
	 * @param string $moFile
	 * @param string $domain
	 */
	public function load_mo_file( $mo_file, $domain ) {
		if ( $this->language == null ) {
			$this->language = get_option( 'WPLANG', WPLANG );
			$this->is_dutch = ( $this->language == 'nl' || $this->language == 'nl_NL' );
		}

		// The ICL_LANGUAGE_CODE constant is defined from an plugin, so this constant
		// is not always defined in the first 'load_textdomain_mofile' filter call
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$this->is_dutch = ( ICL_LANGUAGE_CODE == 'nl' );
		}

		if ( $this->is_dutch ) {
			$domains = array(
				'Shopp' => array(
					'lang/Shopp-nl_NL.mo' => 'shopp/nl_NL.mo'
				)
			);

			if ( isset( $domains[$domain] ) ) {
				$paths = $domains[$domain];

				foreach ( $paths as $path => $file ) {
					if ( substr( $mo_file, -strlen( $path ) ) == $path ) {
						$new_file = dirname( $this->file ) . '/languages/' . $file;

						if ( is_readable( $new_file ) ) {
							$mo_file = $new_file;
						}
					}
				}
			}
		}

		return $mo_file;
	}
}

global $shopp_nl_plugin;

$shopp_nl_plugin = new ShoppNLPlugin( __FILE__ );
