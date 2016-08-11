<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);

////////////////////////////////////////////////////////////////////////////////
	$msg = "";
	define('MSG_OK', 'ok. ');
	define('MSG_KO', 'ko. ');

	include('adminPre.php');

////////////////////////////////////////////////////////////////////////////////
	// Format notification message
	$msgClass = 'msg';

	if (!empty($msg)) {
		if (stripos($msg, MSG_OK)!==false) {
			$msg = str_replace(MSG_OK, '', $msg);
			$msgClass .= 'Ok';
		}
		else if (stripos($msg, MSG_KO)!==false) {
			$msg = str_replace(MSG_KO, '', $msg);
			$msgClass .= 'Ko';
		}
	}

////////////////////////////////////////////////////////////////////////////////
	// Assembling the page
	include('adminView.php');
?>
