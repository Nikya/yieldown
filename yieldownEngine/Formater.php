<?php

/**
* To format text into HTML
*
* @author nikya
* @date Creation 2014-01-02
*/
class Formater {

	/** Apply formats
	*
	* @param String $text Text to format
	* @return String Formated text
	*/
	public static function formatText($text) {
		$text = Parsedown::instance()->text($text);
		Formater::addClass($text);
		Formater::simpleEncode($text);
		return $text;
	}

	/** Add CSS class to a text section.
	* Convert {style:redText}Important styled text{style}
	* to <span class="redText">Important styled text</span>
	*
	* @param String $text Text to format
	*/
	private static function addClass(&$text) {
		$pattern = "/\\[\\[(.+?)\\](.+?)\\]/";
		$replacement = '<span class="\1">\2</span>';
		$text = preg_replace($pattern, $replacement, $text);
	}

	/** Convert some applicable characters to HTML entities (Exluding HTML tags)*/
	private static function simpleEncode(&$text) {
		$text = Formater::htmlentitiesOutsideHTMLTags($text);
	}

	/** htmlentities in PHP but preserving html tags.
	* Source : http://stackoverflow.com/questions/1364933/htmlentities-in-php-but-preserving-html-tags
	*
	* @param String $htmlText Input text
	* @return String Changed text
	*/
	private static function htmlentitiesOutsideHTMLTags($htmlText) {
		$matches = Array();
		$sep = '###HTMLTAG###';

		preg_match_all(":</{0,1}[a-z]+[^>]*>:i", $htmlText, $matches);

		$tmp = preg_replace(":</{0,1}[a-z]+[^>]*>:i", $sep, $htmlText);
		$tmp = explode($sep, $tmp);

		for ($i = 0; $i < count($tmp); $i++)
			$tmp[$i] = htmlentities($tmp[$i], ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8', false);

		$tmp = join($sep, $tmp);

		for ($i = 0; $i < count($matches[0]); $i++)
			$tmp = preg_replace(":$sep:", $matches[0][$i], $tmp, 1);

		return $tmp;
	}

}
