<?php

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * @link       http://acmemk.com
	 * @since      1.0.0
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/public
	 */

	/**
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/public
	 * @author     Mirko Bianco <mirko@acmemk.com>
	 */
	class Acme_Amazing_Search_Public {

		private $cache;

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
		 * @param      string    $plugin_name       The name of the plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->aas_options = get_option( $this->plugin_name );
		}


		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/acme-amazing-search-public.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			/**
			 * load custom script and WP predefined jQuery and jQueryUI
			 */
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/acme-amazing-search-public.js', array( 'jquery','jquery-ui-autocomplete' ), $this->version, true );
			/**
			 * Register 'ajax' url to be used in custom js
			 *
			 */
			//wp_localize_script( $this->plugin_name, 'ajax', array('url' => admin_url( 'admin-ajax.php' )));
			wp_localize_script( $this->plugin_name, 'ajax', array('url' => content_url( '/plugins/acme-amazing-search/ACME_AJAX.php' )));
			wp_localize_script( $this->plugin_name, 'home', array('siteurl' => get_option('siteurl')));

		}

		/**
		 * Print number of results in the Html Headers so JS can display properly
		 *
		 * @since   2.0.0
		 */
		public function add_meta(){
			echo '<meta name="aas_results" content="' . $this->aas_options['results'] . '" />';
		}


		/**
		 * Echo Html unsorted list with current caching files
		 *
		 * @since   2.0.0
		 * @return string
		 */
		public function cache_info_html() {
			$src    = $this->cache_info();
			$result = '<div>';
			if ( is_array( $src ) ) {
				$result .= '<ul>';
				foreach ( $src as $file_info ) {
					$result .= "<li>$file_info;</li>";
				}
				$result .= "</ul>";
			} else {
				$result .= "<strong>$src</strong>";
			}
			$result .= '</div>';

			return $result;
		}

		/**
		 * Create an array of current caching files with size and timestamp. Return a string on empty cache folder
		 *
		 * @return array|string|void
		 */
		private function cache_info() {
			$cache_path  = plugin_dir_path( __FILE__ ) . '../cache';
			$cache_files = scandir( $cache_path );
			$response    = array();
			foreach ( $cache_files as $fname ) {
				$handle = $cache_path . '/' . $fname;
				if ( true == is_file( $handle ) ) {
					$response[] = $fname . ' [' . hrFileSize( filesize( $handle ) ) . '] => ' . date_i18n( get_option( 'date_format' ) . ' - ' . get_option( 'time_format' ), filemtime( $handle ), true );
				}
			}
			if ( count( $response ) > 0 ) {
				return $response;
			} else {
				return __( 'Cache Folder is empty. Actually you are not caching results.', $this->plugin_name );
			}

		}

		/**
		 * Manage the creation of cache files. Return an AJAX response if called via API
		 *
		 * @since   2.0.0
		 */
		public function do_cache() {
			$response = false;
			if ( $this->aas_options['cache'] ) {
				if ( $this->aas_options['auto_cache'] ) {
					$this->do_search();
				}
				if ( $_REQUEST['cache_now'] ) {
					$this->do_search();
					$response = '<div class="notice notice-warning"><p>' . __( 'Cache run. Cache status follows:', $this->plugin_name ) . '</p>';
					$response .= $this->cache_info_html();
					$response .= '</div>';
					echo $response;

					wp_die();
				}
			}
		}


		/**
		 * Create the response JSON to Ajax Call
		 *
		 * @since    1.0.0
		 */
		public function do_search() {
			$term = false;
			$whole_results = array();
			$base_url = get_home_url();
			$shop_url = null;
			if ( isset ( $this->aas_options['products'] ) && $this->aas_options['products'] > 0 ) {
				$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
			}
			if ( isset ( $_REQUEST['term'] ) ) {

				$term    = aas_cleanupStrings( $_REQUEST['term'] );
			}
			if( false == $term ) {
				$this->cache = true;
			}

			$results = $this->aas_options['results'];
			$sep     = $this->aas_options['separator'] ? " {$this->aas_options['separator']} " : " - ";
			//$sku = retrieve_sku();
			/**
			 * Let's read plugin options in order to choose the taxonomy we want to search in
			 */
			$taxonomy = array();
			if ( $this->aas_options['brands'] ) {
				$taxonomy[] = "product_brand";
			}
			if ( $this->aas_options['categories'] ) {
				$taxonomy[] = "category";
			}
			if ( $this->aas_options['tags'] ) {
				$taxonomy[] = "post_tag";
			}
			if ( $this->aas_options['product_categories'] ) {
				$taxonomy[] = "product_cat";
			}
			if ( $this->aas_options['product_tags'] ) {
				$taxonomy[] = "product_tag";
			}
			if ( $this->aas_options['terms'] ) {
				$add_terms = explode( ',', $this->aas_options['terms'] );
				$taxonomy  = array_merge( $taxonomy, $add_terms );
			}
			$i = 0;
			if ( count( $taxonomy ) > 0 ) {
				$categories = retrieve_terms( $taxonomy, $this->aas_options['show_taxonomy'], $this->cache );
				if ( is_array( $categories ) ) {
					foreach ( $categories as $ID => $row ) {
						$haystack = $row->title;
						if ( null != $haystack ) {
							$haystack = aas_cleanupStrings( $haystack, true );
							$title = null;
							$title = $this->aas_options['show_taxonomy'] ? "[$row->taxonomy]" . $sep : null;
							$title .= $row->parent_id > 0 ? "$row->parent => " : null;
							$title .= "$row->title ($row->count)";
							$whole_results[] = array(
								'label'    => mb_convert_encoding( $title,'utf-8' ),
								'value'    => aas_cleanupStrings(mb_convert_encoding( $title,'utf-8' )),
								'url'      => $row->url,
								'taxonomy' => 1,
							);
							if ( strpos( $haystack, $term ) ) {
								if ( ! $i > $results ) {
									$caturl = $row->url;
									$search_results[] = array(
										'value' => mb_convert_encoding( $title,'utf-8' ),
										'url'   => $row->url
									);
									$i ++;
								}
							}
						}
					}
				}
			}

			/**
			 * Now we start counting results
			 */
			$count       = $i;
			/**
			 * $cat_results is needed for the "Search All" button behaviour
			 */
			$cat_results = $i;
			/**
			 * Let's see how many results we need before function end
			 */
			$results -= $count;


			/**
			 * Let's find out which post_types we have to query
			 */
			$post_type = array();
			if ( $this->aas_options['posts'] ) {
				$post_type[] = "post";
			}
			if ( $this->aas_options['pages'] ) {
				$post_type[] = "page";
			}
			if ( $this->aas_options['products'] ) {
				$post_type[] = "product";
			}
			if ( $this->aas_options['post_type'] ) {
				$p_types   = explode( ',', $this->aas_options['post_type'] );
				$post_type = array_merge( $post_type, $p_types );
			}


			if ( count( $post_type ) > 0 ) {
				$posts = retrieve_posts( $post_type, $this->cache );

				if ( is_array( $posts ) ) {
					/**
					 * Let's see where we have to look for our terms (title, excerpt or both?)
					 */
					$title   = $this->aas_options['title'];
					$excerpt = $this->aas_options['excerpt'];
					$i       = 0;
					$skus    = null;
					if ( $this->aas_options['aas_sku'] ) {
						$skus = retrieve_meta();
					}
					foreach ( $posts as $row ) {
						if ( $skus ) {
							$sku = $skus[ $row->ID ];
						}

						$haystack = $title ? $row->post_title : null;
						$haystack .= $excerpt ? wp_strip_all_tags( $row->post_excerpt ) : null;
						$haystack .= $skus ? $skus : null;
						if ( null != $haystack ) {
							$haystack = aas_cleanupStrings( $haystack, true );
							$title    = $skus ? '[' . $sku . '] ' : null;
							$title .= $this->aas_options['show_title'] ? $row->post_title : null;
							if ( $title && $this->aas_options['show_excerpt'] ) {
								$title .= $sep;
							}
							$title .= $this->aas_options['show_excerpt'] ? wp_strip_all_tags( $row->post_excerpt ) : null;
							$title    = $this->aas_options['trim'] && strlen( $title ) > $this->aas_options['trim'] ? substr( $title, 0, $this->aas_options['trim'] ) . "[...]" : $title;
							$page_url = 'product' == $row->post_type ? $shop_url : $base_url;
							$url      = $page_url . '?p=' . $row->ID;
							if ( strpos( $haystack, $term ) ) {
								if ( ! $i > $results ) {
									$search_results[] = array(
										'value' => mb_convert_encoding( $title, 'utf-8' ),
										'url'   => $url,
									);
									$i ++;
								}
							}
							$whole_results[] = array(
								'label' => mb_convert_encoding( $title, 'utf-8' ),
								'value' => aas_cleanupStrings(mb_convert_encoding( $title, 'utf-8' )),
								'url'   => $url
							);
						}
					}
				}
			}
			if ( true == $this->cache ) {
				return false;
			}
			/**
			 * Show All button behaviour
			 */
			$append_post_type = null;
			if ( $this->aas_options['append_post_type'] ) {
				$append_post_type = '&post_type=' . $this->aas_options['append_post_type'];
			}
			$show_all_text = $this->aas_options['show_all_text'] ? $this->aas_options['show_all_text'] : __( "Show All", $this->plugin_name );
			if ( isset ( $term ) ) {
				switch ( $this->aas_options['behaviour'] ) {
					case 1:
						if ( $cat_results == 1 ) {
							$search_results[] = array(
								'value' => mb_convert_encoding( $show_all_text ,'utf-8'),
								'url'   => $caturl,
							);
						} else {
							$search_results[] = array(
								'value' => mb_convert_encoding( $show_all_text,'utf-8' ),
								'url'   => get_bloginfo( 'url' ) . '/?s=' . $_REQUEST['term'] . $append_post_type
							);
							$whole_results[] = array(
								'value'  => mb_convert_encoding( $show_all_text,'utf-8' ),
								'label'  => mb_convert_encoding( $show_all_text,'utf-8' ),
								'url'    => $caturl,
								'search' => 1
							);
						}
						break;
					default:
						$search_results[] = array(
							'value' => mb_convert_encoding( $show_all_text,'utf-8' ),
							'url'   => get_bloginfo( 'url' ) . '/?s=' . $_REQUEST['term'] . $append_post_type
						);

						break;
				}
			}
			$whole_results[] = array(
				'value'  => mb_convert_encoding( $show_all_text ,'utf-8'),
				'url'    => get_bloginfo( 'url' ) . '/?' . $append_post_type . '&s=',
				'search' => 2
			);

			ob_clean();
			if ( $term == 'whole_results' ) {
				echo json_encode( $whole_results,JSON_UNESCAPED_UNICODE );
				exit();
			}
			echo json_encode( $search_results );
			exit();
		}


	}
