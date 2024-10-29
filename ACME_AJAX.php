<?php
	define('ACME_AJAX', true);

	if (!isset( $_REQUEST['action']))
		die('-1');


	require_once( '../../../wp-load.php' );
	ini_set('html_errors', 0);

//Typical headers
	header('Content-Type: application/json; charset=utf-8');
	//send_nosniff_header();

//Disable caching
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');


	$action = esc_attr(trim($_REQUEST['action']));

	$allowed_actions = array( 'do_search' );

	if(in_array($action, $allowed_actions)){
		if(is_user_logged_in())
			do_action('ACME_AJAX_'.$action);
		else
			do_action('ACME_AJAX_nopriv_'.$action);
	}
	exit();
