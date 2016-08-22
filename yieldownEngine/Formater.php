<?php

/**
* To apply the formatting of the text
*
* @author nikya
* @date Creation 2014-01-02
*/
class Formater {

	/***************************************************************************
	* Apply on a text
	*
	* @param String $text Text to format
	*
	* @return String Formated text
	*/
	public static function formatText($text) {
		$text = Parsedown::instance()->text($text);
	//	$text = trim(trim(trim ($text), '<p>'), '</p>');
		Formater::addClass($text);
		Formater::simpleEncode($text);

		return $text;
	}

	/***************************************************************************
	* Apply format on specifics object attributs
	*
	* @param array $collection Collection to pocess
	* @param array $keyToFormatList Attribut name list which apply formatting
	*
	* @return array Formated collection
	*/
	public static function formatCollection($collection, $keyToFormatList) {
		SELF::reflect($collection, $keyToFormatList);
		return $collection;
	}

	/***************************************************************************
	* Recursive browsing of the collection to searh String attribut
	* Then apply format is the attribut name is in $keyToFormatList.
	*
	* @param array $collection Collection to pocess
	* @param array $keyToFormatList Attribut name list which apply formatting
	*
	* @return array Formated collection
	*/
	private static function reflect(&$mixed, $keyToFormatList) {
		foreach ($mixed as $key => &$value) {
			if (is_object($value)) {
				//echo "$key : It's object\n";
				SELF::reflect($value, $keyToFormatList);
			} else if (is_array($value)) {
				//echo "$key : It's array\n";
				SELF::reflect($value, $keyToFormatList);
			} else if (is_string($value) and in_array($key, $keyToFormatList)) {
				//echo "$key : It's String\n";
				$value = SELF::formatText($value);
				$value = preg_replace(":^<p>:", '', $value);
				$value = preg_replace(":</p>$:", '', $value);
			}/* else {
				echo "$key : It's other\n";
			}*/
		}
	}


	/***************************************************************************
	* Apply format and extract data from blog article
	*
	* @param array $rawBlog All article in a raw format
	*
	* @return array Article objects list
	*/
	public static function formatBlog($rawBlog) {
		$blog = array();

		foreach ($rawBlog as $id => $rawArticle) {
			try {
				$article = ArticleBuilder::parse($id, $rawArticle);
				$article->body = SELF::formatText($article->body);
				$blog[$id] = $article;
			} catch (Exception $e) {
				throw new Exception("Can't read article '$id' - {$e->getMessage()}");
			}
		}

		return $blog;
	}

	/***************************************************************************
	* Add CSS class to a text section.
	*
	* Convert [[myClass]My special text to format]
	* into <span class="myClass">My special text to format</span>
	*
	* @param refString TExt to process
	* @return formated text
	*/
	private static function addClass(&$text) {
		$pattern = "/\\[\\[(.+?)\\](.+?)\\]/";
		$replacement = '<span class="\1">\2</span>';
		$text = preg_replace($pattern, $replacement, $text);
	}

	/***************************************************************************
	* Convert some applicable characters to HTML entities (Exluding HTML tags)
	*
	* @param refString Text to process
	* @return formated text
	*
	*/
	private static function simpleEncode(&$text) {
		$text = Formater::htmlentitiesOutsideHTMLTags($text);
	}

	/***************************************************************************
	* htmlentities in PHP but preserving html tags.
	*
	* Source : http://stackoverflow.com/questions/1364933/htmlentities-in-php-but-preserving-html-tags
	*
	* @param refString Text to process
	* @return changed text
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

/*******************************************************************************
********************************************************************************
* Sub class to manage Blog article data extraction
*
* @author nikya
* @date Creation 2016-07-17
*/
class ArticleBuilder {

	/***************************************************************************
	* Read a raw article into a Article Object
	*
	* @param String $ID unique article id
	* @param array $rawArticle article in a raw format
	*
	* @return Article objects
	*/
	public static function parse ($id, $rawArticle) {
		$article = new stdClass();
		$article->id = $id;
		$head = null;

		// HEAD / BODY
		$res = preg_split(':-{3,}:', $rawArticle);
		if (count($res)>=2) {
			$head = $res[0];
			unset($res[0]); // Remove header
			$article->body = implode(' ', $res); // Merge the rest into the body
		}
		else
			throw new Exception("No header found ! PREG#".preg_last_error());

		// TITLE
		$matches = array();
		$res = preg_match (':#{1}(.*):', $head, $matches);
		if ($res !== false and $res==1) {
			$article->title = trim($matches[1]);
			$head = str_replace($matches[0], '', $head);
		}
		else
			throw new Exception("No title found ! PREG#".preg_last_error());

		// AUTHOR
		$matches = array();
		$res = preg_match (':#{2}(.*):', $head, $matches);
		if ($res !== false and $res==1) {
			$article->author = trim($matches[1]);
			$head = str_replace($matches[0], '', $head);
		}
		else
			throw new Exception("No author found ! PREG#".preg_last_error());

		// DATE
		$matches = array();
		$res = preg_match (':#{3}(.*):', $head, $matches);
		if ($res !== false and $res==1) {
			$article->date = trim($matches[1]);
			$head = str_replace($matches[0], '', $head);
		}
		else
			throw new Exception("No date found ! PREG#".preg_last_error());

		// INTRO
		$matches = array();
		$res = preg_match (':#{4}(.*):', $head, $matches);
		if ($res !== false and $res==1) {
			$article->intro = trim($matches[1]);
			$head = str_replace($matches[0], '', $head);
		}
		else
			throw new Exception("No introduction found ! PREG#".preg_last_error());

		// KEYWORDS
		$matches = array();
		$res = preg_match_all (':\*{1}\s*(.*)\s*:', $head, $matches);
		if ($res !== false and $res>0) {
			$article->keywords = $matches[1];
		}
		else
			throw new Exception("No keywords found ! PREG#".preg_last_error());

		// Illustration
		$matches = array();
		$res = preg_match (':(\!\[(.*?)\])(\((.*?)("(.*?)")*\)):', $head, $matches);
		if ($res !== false and $res==1)  {
			$article->illustrationAlt = $matches[2];
			$article->illustrationSrc = $matches[4];
			$article->illustrationTitle = isset($matches[6]) ? $matches[6] : '';
		}
		else {
			$article->illustrationAlt = null;
			$article->illustrationSrc = null;
			$article->illustrationTitle = null;
		}

		return $article;
	}
}
