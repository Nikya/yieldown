<?php

	///// THE ONLY ONE ENTRY POINT \\\\\

	ini_set('display_errors',1);
	error_reporting(E_ALL);

	require_once("yieldownEngine/Yieldown.php");
	require_once("yieldownEngine/Cache.php");

/*******************************************************************************
* MANDATORY VARS
* To build page or to identify cached pages
*
*/

	/** Pre-processing controler : Scripting before building the page */
	$ctrl = 'homeCtrl.php';

	/** View : The global page template */
	$view = 'standardView.php';

	/** Sub-view : The sub content to load into the view */
	$subview = 'homeSubview.php';

	/** [optional] Another sub-subview */
	$subsubview = null;

	/** Title : The HTML/Head title */
	$title = "L'histoire du Jeans";

	/** Description : The HTML/Head description */
	$description = "L'histoire du jeans : pantalon à coutures, coupé dans une toile denim.";

	/** Keywords : The HTML/Head keywords */
	$keywords = "jeans, markdown, yieldown, demo";

	/** Caching : Enable or not the cache functionality */
	$cacheEnable = false;

/*******************************************************************************
* Altering mandatory vars
*
*/

	if (isset($_GET['p']) and !empty($_GET['p']) ) {
		$p=$_GET['p'];

		switch ($p) {
			case 'blog':
				$subview = null;
				$ctrl = 'blogCtrl.php';
				break;

			case 'history':
				$subview = 'historySubview.php';
				$ctrl = 'historyCtrl.php';
				break;

			case 'tone':
				$subview = 'toneSubview.php';
				$ctrl = 'toneCtrl.php';
				break;

			case 'cut':
				$subview = 'cutSubview.php';
				$ctrl = 'cutCtrl.php';
				break;

			case 'error':
			default:
				$errMsg = "La page '$p' n'a pas était trouvée.";
				$subview = 'errorSubview.php';
				$ctrl = 'errorCtrl.php';
				$cacheEnable = false;
				break;
		}
	}

/*******************************************************************************
* Assembling the page
*
*/
	Cache::enable($cacheEnable);

	Cache::shortcut(); // Try to return the cached page if it clearly identify (view+subview+ctrl)

	try {
		require('controller/standardCtrl.php');
		require('controller/'.$ctrl);
	} catch (Exception $e) {
		$cacheEnable = false;
		$errMsg = $e->getMessage();
		$subview = 'errorSubview.php';
		require('controller/errorCtrl.php');
	}

	Cache::shortcut(); // Try again to return the cached page if it clearly identify (view+subview+ctrl)

	Cache::start(); // Start to generate a new cached page
	require('view/'.$view); // Using $subview, $title, $description, $keywords
	Cache::end(); // Save the cached page
	
?>
