<?php

/**
* To read data from raw file.
*
* @author nikya
* @date Creation 2014-01-02
*/
class DataLoader {

	/** Load raw text data from text file
	*
	* @param String $fileId file Name without extention
	* @return string Readed data
	*/
	public static function loadText($fileId) {
		$text = '';

		$fullFileName = './data/text/' . $fileId . '.md';

		if (is_file($fullFileName)) {
			$text = file_get_contents($fullFileName);
		} else {
			$text = "Unknow text data id '$fileId'. File not exist : $fullFileName";
		}

		return $text;
	}

	/** Load json data from .json file
	*
	* @param String $fileId file Name without extention
	* @return string Readed data in associative Array
	*/
	public static function loadArray($fileId) {
		$array = array();

		$fullFileName = './data/' . $fileId . '.json';

		if (is_file($fullFileName)) {
			$jsonStr = file_get_contents($fullFileName);
			$array = json_decode($jsonStr, true);
		} else {
			$array = array("Unknow data id $fileId. File not exist in data folder : $fullFileName");
		}

		DataLoader::simplifyArray($fileId, $array);

		return $array;
	}

	/** To simplify array, remove first associative value
	* if there is the only one and if is the same than data id
	*
	* @param String $dataId Data Id
	* @param type $array Array to simplify
	*/
	private static function simplifyArray($dataId, &$array) {
		// If the json convertion return onlyone sub array
		if (count($array)==1);{
			// If this sub array get the same name than data id
			if (array_key_exists ($dataId, $array)) {
				// Array can be simplify
				$array = $array[$dataId];
			}
		}
	}

}
