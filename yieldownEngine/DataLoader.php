<?php

/**
* To read data from file.
*
* @author nikya
* @date Creation 2014-01-02
*/
class DataLoader {

	/***************************************************************************
	* Read full file content
	*
	* @param String $fileId file ID
	* @param String $path File path
	*
	* @return string Readed file content
	*/
	private static function fileGetContents($fileId, $path) {
		if (is_file($path))
			return file_get_contents($path);
		else
			throw new Exception("Can't load data '$fileId' - File not exists : $path");
	}

	/***************************************************************************
	* Load raw text data from text file
	*
	* @param String $fileId file name without extention
	*
	* @return string Readed data
	*/
	public static function loadText($fileId) {
		$path = "./data/text/$fileId.md";

		return SELF::fileGetContents($fileId, $path);
	}

	/***************************************************************************
	* Load a json data from text file
	*
	* @param String $fileId file name without extention
	*
	* @return string Readed data
	*/
	public static function loadCollection($fileId) {
		$path = "./data/collection/$fileId.json";

		$fileContent = SELF::fileGetContents($fileId, $path);

		$collection = json_decode($fileContent);
		if (json_last_error() === JSON_ERROR_NONE)
			return $collection;
		else
			throw new Exception("Can't decode data '$fileId' into Json - " . json_last_error_msg());
	}

	/***************************************************************************
	* Load all article
	*
	* @return array All article
	*/
	public static function loadBlog() {
		$path = "./data/blog/";
		$rawBlog = array();

		$artFiles = scandir($path);
		arsort($artFiles);

		if ($artFiles===false)
			throw new Exception("Can't read articles in $path");
		else {
			foreach ($artFiles as $file) {
				if ($file!='.' and $file!='..' and $file!='_template.md') {
					$fileContent = SELF::fileGetContents('article', $path.$file);
					$rawBlog[$file] = $fileContent;
				}
			}
		}

		return $rawBlog;
	}
}
