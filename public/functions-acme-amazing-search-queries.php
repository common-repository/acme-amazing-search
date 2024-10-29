<?php

	/**
	 * Functions related to cache writing
	 *
	 * @link       http://acmemk.com
	 * @since      1.1.0
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/public
	 */

	/**
	 * Check if WMPL is installed
	 * @return bool
	 */
	function is_wpml(){
		if ( function_exists( 'icl_object_id' ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * If WPML, gives us a list of active languages are active and which one is the current
	 *
	 * @return array|bool
	 */
	function get_active_languages( ) {
		if ( is_wpml() ) {
			$languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
			$lan       = array();
			foreach ( $languages as $code => $array ) {
				$lan[] = $code;
				if ($array['active'] == 1 ) {
					$lan['current'] = $code;
				}
			}

			return $lan;
		} else {

			return false;
		}
	}


	/**
	 * Launch the query post. If cache is true, it writes a cache json file to disk
	 *
	 * @param $post_type    mixed value with post types
	 * @param $cache        bool check if we are searching terms or updating the cache
	 *
	 * @return array|bool|mixed
	 */
	function retrieve_posts($post_type, $cache) {
		/**
		 * Setu WP_Query
		 */
		$args = array(
			'post_parent'            => '0',
			'post_type'              => is_array( $post_type ) ? $post_type : array( $post_type ),
			'post_status'            => array( 'publish' ),
			'orderby'                => array( 'menu_order', 'post_title' ),
			'cache_results'          => true,
			'posts_per_page'         => 1,          //limit results to 1 in order to access the 'request' method
			'no_found_rows'          => true,       // counts posts, remove if pagination required
			'update_post_term_cache' => false,      // grabs terms, remove if terms required (category, tag...)
			'update_post_meta_cache' => false,      // grabs post meta, remove if post meta required
		);

		$languages = null;
		/**
		 * Checking WMPL and retrieving language list
		 */
		if ( is_wpml() ) {
			$languages = get_active_languages();
		}
		/**
		 * Run the main query: we just need the `request` method for performance purposes
		 */
		$query = new WP_Query( $args );
		/**
		 * Declaring the $wpdb as global
		 */
		global $wpdb;
		/**
		 * If cache is true, we are going to write to cache
		 */
		$QStat = null;
		if ( true == $cache ) {
			/**
			 * Let's loop throught langages
			 */
			$i = 0;
			$results = null;
			if ( is_array( $languages ) ) {
				foreach ( $languages as $k => $lang ) {
					if ( $k !== 'current' ) {
						/**
						 * We are going to update our Query String
						 */
						$QStat    = aas_build_query_string( $query->request, $lang );
						$filename = 'post-' . $lang;
						/**
						 * Let's write to cache
						 */
						$obj = $wpdb->get_results( $QStat );
						aas_write_cache( $filename, aas_encode( $obj ) );
						//$results = $i == 0 ? $obj : $results;
						$i ++;
					}
				}
			} else {
				/**
				 * WPML is not installed, let's update our Query
				 */
				$QStat = aas_build_query_string( $query->request, null );
				$filename = 'post';
				/**
				 * ... and write into cache
				 */
				$obj = $wpdb->get_results( $QStat );
				aas_write_cache( $filename, aas_encode( $obj ) );
				//$results = $obj;
			}

			return $results;
		}


		/**
		 * Let's try to read from Cache
		 */
		$filename = $languages ? 'post-' . $languages['current'] : 'post';
		$ext = 'json';
		$file = plugin_dir_path( __FILE__ ) . '../cache/' . $filename . '.' . $ext;
		if( file_exists( $file ) && $handle = fopen( $file, "r" )) {
			/**
			 * We are decoding our cache into an array
			 */
			$results = json_decode (fread( $handle, filesize( $file ) ) );
			fclose( $handle );
		} else {
			/**
			 * If no cache, let's fallback to standard query method
			 */
			$results = $wpdb->get_results( aas_build_query_string( $query->request, null ) );

		}
		return $results;
	}

	/**
	 * Launch the term post. If cache is true, it writes a cache json file to disk
	 *
	 * @param $taxonomy mixed value with list of taxonomies
	 * @param null $showTax bool if true save the current taxonomy into result array
	 * @param null $cache   bool check if we are searching terms or updating the cache
	 *
	 * @return array|bool|mixed
	 */
	function retrieve_terms($taxonomy, $showTax=null, $cache=null) {
		/**
		 * Setu WP_Term_Query
		 */
		$args = array(
			'taxonomy'   => is_array( $taxonomy ) ? $taxonomy : array( $taxonomy ),
			'order'      => 'DESC',
			'orderby'    => 'count',
			'hide_empty' => true,
			'get'        => 'all',
			'icl_t.language_code'=>'en'
		);

		$languages = null;
		/**
		 * Checking WMPL and retrieving language list
		 */
		if ( is_wpml() ) {
			$languages = get_active_languages();
		}
		/**
		 * Run the main query: we just need the `request` method for performance purposes
		 */
		$term_query = new WP_Term_Query( $args );

		/**
		 * Declaring the $wpdb as global
		 */
		global $wpdb;
		if ( ! empty( $term_query ) && ! is_wp_error( $term_query ) ) {
			/**
			 * If cache is true, we are going to write to cache
			 */
			if ( true == $cache ) {
				/**
				 * Let's loop throught languages
				 */
				if ( is_array( $languages ) ) {
					foreach ( $languages as $k => $lang ) {
						if ( $k !== 'current' ) {
							/**
							 * We are going to update our Query String
							 */
							$QStat = aas_build_query_string( $term_query->request, $lang, true );
							$myQuery = $wpdb->get_results( $QStat );
							$result = null;
							foreach ( $myQuery as $term ) {
								/**
								 * For compatibility with WP_Term_Query, we are transforming our $term array into an object
								 */
								$term = json_decode( json_encode( $term ), false );
								$inspect[ $term->term_id ] = $term->name;
								$result[ $term->term_id ]  = array(
									'title'     => $term->name,
									'parent_id' => $term->parent,
									'id'        => $term->term_id,
									'count'     => $term->count,
									'taxonomy'  => $showTax ? $term->taxonomy : null,
									'url'       => get_term_link( $term )
								);
							}

							foreach ( $result as $id => $row ) {
								if ( $row['parent_id'] > 0 ) {
									$result[ $id ]['parent'] = $result[ $row['parent_id'] ]['title'];
								}
							}

							/**
							 * Let's write to cache
							 */
							$filename = 'term-' . $lang;
							aas_write_cache( $filename, aas_encode( $result ) );
						}
					}
				} else {
					/**
					 * WPML is not installed, let's update our Query
					 */
					foreach ( $term_query->terms as $term ) {
						$inspect[ $term->term_id ] = $term->name;
						$result[ $term->term_id ]  = array(
							'title'     => $term->name,
							'parent_id' => $term->parent,
							'id'        => $term->term_id,
							'count'     => $term->count,
							'taxonomy'  => $showTax ? $term->taxonomy : null,
							'url'       => get_term_link( $term->term_id )
						);
					}

					foreach ( $result as $id => $row ) {
						if ( $row['parent_id'] > 0 ) {
							$result[ $id ]['parent'] = $result[ $row['parent_id'] ]['title'];
						}
					}

					/**
					 * ... and write into cache
					 */
					$filename = 'term';
					aas_write_cache( $filename, aas_encode( $result ) );
				}

				return false;
			}

			/**
			 * Let's try to read from Cache
			 */
			$filename = $languages ? 'term-' . $languages['current'] : 'term';
			$ext = 'json';
			$file = plugin_dir_path( __FILE__ ) . '../cache/' . $filename . '.' . $ext;
			if( file_exists( $file ) && $handle = fopen( $file, "r" )) {
				/**
				 * We are decoding our cache into an array
				 */
				$result = json_decode (fread( $handle, filesize( $file ) ) );
				fclose( $handle );
			} else {
				/**
				 * If no cache, let's fallback to standard query method
				 */
				foreach ( $term_query->terms as $term ) {
					$inspect[ $term->term_id ] = $term->name;
					$result[ $term->term_id ]  = array(
						'title'     => $term->name,
						'parent_id' => $term->parent,
						'id'        => $term->term_id,
						'count'     => $term->count,
						'taxonomy'  => $showTax ? $term->taxonomy : null,
						'url'       => get_term_link( $term->term_id )
					);
				}

				foreach ( $result as $id => $row ) {
					if ( $row['parent_id'] > 0 ) {
						$result[ $id ]['parent'] = $result[ $row['parent_id'] ]['title'];
					}
				}
			}

			return $result;
		}
	}

	/**
	 * Launch the meta query for SKU.
	 *
	 * @param $post_type    mixed value with post types
	 * @param $cache        bool check if we are searching terms or updating the cache
	 *
	 * @return array|bool|mixed
	 */
	function retrieve_meta() {
		$meta_key = '_sku';
		$sku = null;

		global $wpdb;
		$meta_qstat = "SELECT post_id, meta_value as $meta_key FROM $wpdb->postmeta WHERE meta_key='$meta_key';";
		$results    = $wpdb->get_results( $meta_qstat );
		if ( $results )
			foreach ( $results as $obj ) {
				$sku[ $obj->post_id ] = $obj->_sku;
			}

		return $sku;
	}
