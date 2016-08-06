<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	require_once("yieldownEngine/Yieldown.php");

////////////////////////////////////////////////////////////////////////////////
	// MANDATORY VARS

	/** Pre-processing : Scripting before building the page */
	$pre = 'homePre.php';

	/** Post-processing : Scripting after building the page */
	$post = 'standardPost.php';

	/** View : The global page template */
	$view = 'standardView.php';

	/** Sub-view : The sub content to load into the view */
	$subview = 'homeSubview.php';

	/** Title : The HTML/Head title */
	$title = "L'histoire du Jeans";

	/** Description : The HTML/Head description */
	$description = "L'histoire du jeans : pantalon à coutures, coupé dans une toile denim.";

	/** Keywords : The HTML/Head keywords */
	$keywords = "jeans, markdown, yieldown, demo";

////////////////////////////////////////////////////////////////////////////////
	// Altering mandatory vars

	if (isset($_GET['p']) and !empty($_GET['p']) ) {
		$p=$_GET['p'];

		switch ($p) {
			case 'blog':
				$pre = 'blogPre.php';
				break;

			case 'history':
				$subview = 'historySubview.php';
				$pre = 'historyPre.php';
				break;

			case 'tone':
				$subview = 'toneSubview.php';
				$pre = 'tonePre.php';
				break;

			case 'cut':
				$subview = 'cutSubview.php';
				$pre = 'cutPre.php';
				break;

			case 'error':
			default:
				$subview = 'errorSubview.php';
				$pre = 'errorPre.php';
				break;
		}
	}

////////////////////////////////////////////////////////////////////////////////
	// Assembling the page

	include('content/'.$pre);
	include('content/'.$view); // Using $subview, $title, $description, $keywords
	include('content/'.$post);
?>
