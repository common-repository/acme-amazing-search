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
	 * Simply writes query json results into txt files
	 *
	 * @param $filename
	 * @param $content
	 *
	 * @return bool
	 */
	function aas_write_cache($filename,$content) {
		$ext    = 'json';
		$file   = plugin_dir_path( __FILE__ ) . '../cache/' . $filename . '.' . $ext;
		$handle = fopen( $file, "w" );
		fwrite( $handle, $content );
		fclose( $handle );
		touch( $file );

		return false;
	}
