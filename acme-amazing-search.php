<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://acmemk.com
 * @since             1.0.0
 * @package           Acme_Amazing_Search
 *
 * @wordpress-plugin
 * Plugin Name:       ACME Amazing Search
 * Plugin URI:        http://acmemk.com/acme-amazing-search
 * Description:       A google style ultra fast search engine for WP and WooCommerce
 * Version:           2.0.13
 * Author:            Mirko Bianco
 * Author URI:        http://acmemk.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acme-amazing-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acme-amazing-search-activator.php
 */
function activate_acme_amazing_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acme-amazing-search-activator.php';
	Acme_Amazing_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acme-amazing-search-deactivator.php
 */
function deactivate_acme_amazing_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acme-amazing-search-deactivator.php';
	Acme_Amazing_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_acme_amazing_search' );
register_deactivation_hook( __FILE__, 'deactivate_acme_amazing_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-acme-amazing-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acme_amazing_search() {

	$plugin = new Acme_Amazing_Search();
	$plugin->run();

}
run_acme_amazing_search();
