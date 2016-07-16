<?php
require_once('Formater.php');
require_once('DataLoader.php');
require_once('vendors/erusev/parsedown/Parsedown.php');

/**
* The Core class of Yieldown Engine.
*
* @author nikya
* @date Creation 2016-07-14
*/
class Yieldown {

	/** Yieldown version name*/
	const VERSION = "1.0";

	/***************************************************************************
	* Load a text from data/text folder (A plain text markdown file)
	*
	* @param String $fileId File id to load (File name without extention)
	* @param Boolean $toFormat [optional default true] To format readed text
	*
	* @return String Readed data
	*/
	public static function loadtext($fileId, $toFormat=true) {
		$text = DataLoader::loadText($fileId);

		if ($toFormat)
			$text = Formater::formatText($text);

		return $text;
	}

	/***************************************************************************
	* Load a collection from data/collection folder (A json file)
	*
	* @param String $fileId File id to load (File name without extention)
	* @param array $keyToFormatList [optional] Json label list which apply formatting
	*
	* @return object(stdClass) Readed data
	*/
	public static function loadCollection($fileId, $keyToFormatList = null) {
		$collection = DataLoader::loadCollection($fileId);

		if ($keyToFormatList != null)
			$collection = Formater::formatCollection($collection, $keyToFormatList);

		return $collection;
	}

	/***************************************************************************
	* Load all article into data/blog folder (Plain text markdown files)
	*
	* @return array(Article) All article
	*/
	public static function loadBlog() {
		$rawBlog = DataLoader::loadBlog();

		$blog = Formater::formatBlog($rawBlog);

		return $blog;
	}

	/**
	* Enable staticallize process when your website is ready.
	* Call this fucntion at begining of each page to generate. Must finish with finalize function call.
	*
	* @param type $enable To enable the process
	* @param type $fileName [optional : default use the current php file name] To specify the corresponding static file name
	*/
	public static function staticallize($enable = false) {
		Statiker::$processEnable = $enable;

		if (Statiker::$processEnable) {
			ob_start();
		}
	}

	/** Must be call at the end of each page to get corresponding static page*/
	public static function finalize() {
		Statiker::traceDynamicMode();

		if (Statiker::$processEnable) {

			// Get and stop outputbuffering
			$pageContent = ob_get_contents();
			ob_end_clean();

			// Writter catched buffer in static file
			$currentStaticFileName = Statiker::buildCurrentStaticFileName();
			$staticFile = fopen('./statikOut/'.$currentStaticFileName, "w");

			if ($staticFile !== false) {
				fputs($staticFile, $pageContent);
				fclose($staticFile);
				echo $pageContent;
			} else {
				echo "Can't generate static file : " . $currentStaticFileName;
			}
		}
	}
}
