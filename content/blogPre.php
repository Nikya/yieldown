<?php
	$blog = Yieldown::loadBlog();

	$subTitle = "Le Blog";
	$title .= " - $subTitle";
	$description = $subTitle;
	$keywords .= ", blog";

	$targetArticle=null;

	if (isset($_GET['a']) and !empty($_GET['a']) ) {
		$targetArticle=$_GET['a'];
		if (!array_key_exists($targetArticle, $blog))
			$targetArticle=null;
	}

	// Display summary
	if ($targetArticle == null) {
		$subview = "blogSummarySubview.php";
	}

	// Display a full article
	else {
		$subview = "blogArticleSubview.php";
		$subsubview = $targetArticle;

		$article = $blog[$targetArticle];

		$subTitle = $article->title;
		$title .= " - $subTitle";
		$description = $article->intro;

		// Keywords bar & HTML
		$keywordsBar = '';
		foreach ($article->keywords as $keyword) {
			$keywordsBar .= '<a href="https://twitter.com/search?q=%23'.$keyword.'">#'.$keyword.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
			$keywords = "$keyword, $keywords";
		}

		// Share bar
		$currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}
