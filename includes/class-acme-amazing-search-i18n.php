<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://acmemk.com
 * @since      1.0.0
 *
 * @package    Acme_Amazing_Search
 * @subpackage Acme_Amazing_Search/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Acme_Amazing_Search
 * @subpackage Acme_Amazing_Search/includes
 * @author     Mirko Bianco <mirko@acmemk.com>
 */
class Acme_Amazing_Search_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'acme-amazing-search',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
