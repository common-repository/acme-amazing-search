<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       http://acmemk.com
	 * @since      1.0.0
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/admin
	 * @author     Mirko Bianco <mirko@acmemk.com>
	 */
	class Acme_Amazing_Search_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->aas_options = get_option( $this->plugin_name );
		}

		public function drop_data(){
			$plugin_data = array(
				'plugin_name'=>$this->plugin_name,
				'aas_options'=>$this->aas_options
			);

			return $plugin_data;
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/acme-amazing-search-admin.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/acme-amazing-search-admin.js', array( 'jquery' ), $this->version, true );

		}
		/**
		 * Register the administration menu for this plugin into the WordPress Dashboard menu.
		 *
		 * @since    1.0.0
		 */
		public function add_plugin_admin_menu() {

			$parent = 'acme_plugin_panel';

			$this->create_acme_admin_menu();
			add_submenu_page( $parent, 'ACME Amazing Search Setup', 'Search Tools', 'manage_options', $this->plugin_name, array(
				$this,
				'display_plugin_setup_page'
			) );
			remove_submenu_page( $parent, 'acme_plugin_panel' );

		}

		/**
		 * If not exists, create a ACME Menu for all plugins
		 */
		public function create_acme_admin_menu() {
			global $admin_page_hooks;
			if( ! isset( $admin_page_hooks['acme_plugin_panel'] ) ){
				add_menu_page( 'acme_plugin_panel', 'ACME', 'manage_options', 'acme_plugin_panel', NULL, 'dashicons-carrot', 81 );
			}

			return false;
		}
		/**
		 * Add settings action link to the plugins page.
		 *
		 * @since    1.0.0
		 */

		public function add_action_links( $links ) {
			$settings_link = array(
				'<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
			);
			return array_merge(  $settings_link, $links );

		}

		/**
		 * Retrieve the post types used by the CMS
		 *
		 * @since   1.1.5
		 *
		 * @return array
		 */
		public function get_post_types(){
			$array = get_post_types( array( 'public' => true ) );
			$response = array();

			foreach ( $array as $post_type ) {
				if ( count( get_object_taxonomies( $post_type ) ) > 0 ) {
					$response[] = $post_type;
				}
			}

			return $response;
		}



		/**
		 * Setup Cron
		 *
		 * @since 2.0.7
		 */
		function setup_cron() {
			if ( isset( $this->aas_options['cron_cache'] ) && $this->aas_options['cron_cache'] > 0 ) {
				if( !wp_next_scheduled( 'aas_cron_cache' ) ) {
					wp_schedule_event( time(), 'hourly', 'aas_cron_cache' );
				}

			} else {
				wp_clear_scheduled_hook( 'aas_cron_cache' );
			}
		}


		/**
		 * Render the settings page for this plugin.
		 *
		 * @since    1.0.0
		 */

		public function display_plugin_setup_page() {
			include_once( 'partials/acme-amazing-search-admin-display.php' );
		}
		/**
		 * Save plugin options.
		 *
		 * @since    1.0.0
		 */


		public function options_update() {
			register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
		}
		/**
		 * Validate Input Fields.
		 *
		 * @since    1.0.0
		 */

		public function validate($input) {
			// All checkboxes inputs
			$valid = array();

			//Cleanup
			$valid['cache']              = absint( $input['cache'] );
			$valid['auto_cache']         = ( isset( $input['auto_cache'] ) && ! empty( $input['auto_cache'] ) ) ? 1 : 0;
			$valid['cron_cache']         = ( isset( $input['cron_cache'] ) && ! empty( $input['cron_cache'] ) ) ? 1 : 0;
			$valid['posts']              = ( isset( $input['posts'] ) && ! empty( $input['posts'] ) ) ? 1 : 0;
			$valid['pages']              = ( isset( $input['pages'] ) && ! empty( $input['pages'] ) ) ? 1 : 0;
			$valid['products']           = ( isset( $input['products'] ) && ! empty( $input['products'] ) ) ? 1 : 0;
			$valid['aas_sku']            = ( isset( $input['aas_sku'] ) && ! empty( $input['aas_sku'] ) ) ? 1 : 0;
			$valid['post_type']          = sanitize_text_field( $input['post_type'] );
			$valid['categories']         = ( isset( $input['categories'] ) && ! empty( $input['categories'] ) ) ? 1 : 0;
			$valid['tags']               = ( isset( $input['tags'] ) && ! empty( $input['tags'] ) ) ? 1 : 0;
			$valid['brands']             = ( isset( $input['brands'] ) && ! empty( $input['brands'] ) ) ? 1 : 0;
			$valid['product_categories'] = ( isset( $input['product_categories'] ) && ! empty( $input['product_categories'] ) ) ? 1 : 0;
			$valid['product_tags']       = ( isset( $input['product_tags'] ) && ! empty( $input['product_tags'] ) ) ? 1 : 0;
			$valid['terms']              = sanitize_text_field( $input['terms'] );
			$valid['title']              = ( isset( $input['title'] ) && ! empty( $input['title'] ) ) ? 1 : 0;
			$valid['excerpt']            = ( isset( $input['excerpt'] ) && ! empty( $input['excerpt'] ) ) ? 1 : 0;
			$valid['behaviour']          = absint( $input['behaviour'] );
			$valid['append_post_type'] = ( isset( $input['append_post_type'] ) && $input['append_post_type'] != '0' ) ? sanitize_text_field( $input['append_post_type'] ) : 0;
			$valid['show_all_text']      = sanitize_text_field( $input['show_all_text'] );
			$valid['show_title']         = ( isset( $input['show_title'] ) && ! empty( $input['show_title'] ) ) ? 1 : 0;
			$valid['show_excerpt']       = ( isset( $input['show_excerpt'] ) && ! empty( $input['show_excerpt'] ) ) ? 1 : 0;
			$valid['show_taxonomy']      = ( isset( $input['show_taxonomy'] ) && ! empty( $input['show_taxonomy'] ) ) ? 1 : 0;
			$valid['results']            = absint( $input['results'] );
			$valid['trim']               = absint( $input['trim'] );
			$valid['separator']          = sanitize_text_field( str_replace(array(' ',"\n","\t","\r"),'',$input['separator']) );


			return $valid;
		}
	}
