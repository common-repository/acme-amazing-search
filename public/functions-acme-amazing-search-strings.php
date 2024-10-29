<?php
	/**
	 * We do clean and transform strings
	 *
	 * @link       http://acmemk.com
	 * @since      1.1.0
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/public
	 */


	/**
	 * Builds queries for the WPDB class, also injects the language code for WPML compatibility
	 *
	 * @since   1.0.0
	 *
	 * @param $original_str
	 * @param $lang
	 * @param bool $only_lang
	 *
	 * @return mixed
	 */
	function aas_build_query_string($original_str, $lang, $only_lang = false ) {
		$sFrom      = '/\bFROM\b/';
		$rFrom      = ', post_title , post_excerpt , post_type FROM';
		$sNoContent = '/\bOR \(wp_posts.post_content\b(.*\%)\b(.*\%(.?)\))/';
		$rNoContent = '';
		$sLimit     = '/\bLIMIT.*/';
		$rLimit     = '';
		$sLang      = null;
		$rLang      = null;


		$regex   = array(
			$sFrom,
			$sNoContent,
			$sLimit,
		);
		$replace = array(
			$rFrom,
			$rNoContent,
			$rLimit
		);
		if ( $lang ) {
			$sLang = '/t.language_code[ ]?=[ ]?\'[a-z]{2}\'/';
			$rLang = "t.language_code = '" . $lang . "'";
			if ( true == $only_lang ) {
				$regex   = array();
				$replace = array();
			}
			$regex[]   = $sLang;
			$replace[] = $rLang;
		}
		$QStat = preg_replace( $regex, $replace, $original_str );

		return $QStat;
	}

	/**
	 * Clean strings in order to compare needles and haystacks
	 *
	 * @since 1.0.0
	 *
	 *
	 * @param $str
	 * @param bool $haystack
	 *
	 * @return mixed|null|string
	 */
	function aas_cleanupStrings($str,$haystack=false) {
		if ( null == $str ) {
			return null;
		}
		$result = htmlentities( str_replace( array(
			"-",
			" ",
			"&nbsp;"
		), "", $str ), ENT_IGNORE );
		$result = strtolower( $result );
		$result = str_replace( array(
			"-",
			"/"
		), "", $result );
		$result = utf8_encode( str_replace( " ", "", $result ) );

		if ( true == $haystack ) {
			$result = "__$result";
		}

		return $result;
	}

	/**
	 * Converts bytes into human readable file size.
	 *
	 * @param string $bytes
	 * @return string human readable file size (2,87 Мб)
	 * @author Mogilev Arseny
	 */
	function hrFileSize($bytes)
	{
		$bytes = floatval($bytes);
		$arBytes = array(
			0 => array(
				"UNIT" => "TB",
				"VALUE" => pow(1024, 4)
			),
			1 => array(
				"UNIT" => "GB",
				"VALUE" => pow(1024, 3)
			),
			2 => array(
				"UNIT" => "MB",
				"VALUE" => pow(1024, 2)
			),
			3 => array(
				"UNIT" => "KB",
				"VALUE" => 1024
			),
			4 => array(
				"UNIT" => "B",
				"VALUE" => 1
			),
		);

		foreach($arBytes as $arItem)
		{
			if($bytes >= $arItem["VALUE"])
			{
				$result = $bytes / $arItem["VALUE"];
				$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
				break;
			}
		}
		return $result;
	}

	/**
	 * Remove html tags form results end encode them before writing to cache
	 * 
	 * @param	mixed $data
	 * @return	encoded json-object or $data
	 * @since	2.0.4
	 */
	function aas_encode($data){
		$result = array();
		$i = 0;
		if(!is_string($data)){
			foreach($data as $ar){
				foreach((array)$ar as $key => $val){
					if ( is_string( $val ) ) {
						$result[ $i ][ $key ] = strip_tags($val);
					} else {
						$result[ $i ][ $key ] = $val;
					}
				}
				$i ++;
			}
			return json_encode( $result, JSON_UNESCAPED_UNICODE );
		} else {
			return $data;
		}
	}
