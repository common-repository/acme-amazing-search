<?php

	/**
	 * The shortcode functionality of the plugin.
	 *
	 * @link       http://acmemk.com
	 * @since      1.0.0
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/public
	 */

	/**
	 * Default shortcode
	 *
	 * Main [aas] shortcode to display search form
	 *
	 * @since	1.0.0
	 *
	 * @uses		search_form			Action the shortcode parameters
	 *
	 *
	 * @return   string						AJAX Search Form
	 */

	function search_form() {
		$plugin_data = apply_filters( 'aas_plugin_data', null );
		$options     = $plugin_data['aas_options'];
		$str         = __( "Search...", 'acme-amazing-search' );
		$search_term = sprintf( '<div class="aas-container"><input id="aas-search-term" class="aas-search-term" value="%1$s" autocomplete="off" append="%2$s"/></div>',
			$str,
			$options['append_post_type'] ? '&post_type=' . $options['append_post_type'] : null
		);

		return $search_term;

	}

	add_shortcode( 'aas', 'search_form' );
