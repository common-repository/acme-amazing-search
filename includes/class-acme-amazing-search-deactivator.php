<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://acmemk.com
 * @since      1.0.0
 *
 * @package    Acme_Amazing_Search
 * @subpackage Acme_Amazing_Search/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Acme_Amazing_Search
 * @subpackage Acme_Amazing_Search/includes
 * @author     Mirko Bianco <mirko@acmemk.com>
 */
class Acme_Amazing_Search_Deactivator {

	/**
	 * deactivate the cron settings
	 *
	 *
	 * @since    2.0.7
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'aas_cron_cache' );
	}

}
