<?php

/**
* To manage the cache engine
*
* @author nikya
* @date Creation 2016-07-14
*/
class Cache {

	/***************************************************************************
	* Return the cached page if it available and clealy identify,
	* then stop the script
	*
	*/
	public static function shortcut() {
		$cacheFileId = Cache::getCacheFileId();

		if ($cacheFileId != null) {
		 	if (file_exists($cacheFileId)) {
				readfile($cacheFileId);
				exit;
			}
		}
	}

	/***************************************************************************
	* Start to record the script output
	*
	*/
	public static function start() {
		ob_start();
	}

	/***************************************************************************
	* Stop to record the script output and store it in the cache fomder and file
	*
	*/
	public static function end() {
		$pageContent = ob_get_contents(); // copie du contenu du tampon dans une chaîne
		ob_end_clean(); // effacement du contenu du tampon et arrêt de son fonctionnement

		$cacheFileId = Cache::getCacheFileId();

		if ($cacheFileId != null) {
			file_put_contents($cacheFileId, $pageContent) ; // on écrit la chaîne précédemment récupérée ($pageContent) dans un fichier ($cacheKey)
			echo $pageContent ; // on affiche notre page :D
		}
	}

	/***************************************************************************
	* Generate the CACHE KEY if all mandatory vars exists
	*
	* @return the cache key, or null if is invalid status
	*/
	private static function getCacheFileId() {
		global $view;
		global $subview;
		global $subsubview;
		global $pre;
		$key = null;

		if($view!=null and $subview!=null and $pre!=null) {
			$key = $view.$subview.$pre.$subsubview;
			$key = str_replace('.php', '_', $key);
			$key = str_replace('.phtml', '_', $key);
			$key = str_replace('.htm', '_', $key);
			$key = str_replace('.html', '_', $key);
		}

		return "cache/$key.html";
	}
}
