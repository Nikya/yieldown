<?php
	$blog = Yieldown::loadBlog();

	$targetArticle=null;
	if (isset($_GET['a']) and !empty($_GET['a']) )
		$targetArticle=$_GET['a'];

	////////////////////////////////////////////////////////////////////////////
	// Display summary
	if($targetArticle==null or !array_key_exists($targetArticle, $blog)) {
		echo '<h2>Le blog</h2>';

		foreach ($blog as $id => $article) {
			echo <<<HD

<dl>
	<dt>$article->title</dt>
	<dd>
		<p>$article->intro</p>
		<a href="?p=blog&a=$article->id">Lire la suite ...</a>
	</dd>
</dl>

HD;
		}

	////////////////////////////////////////////////////////////////////////////
	// Display a full article
	} else {
		$article = $blog[$targetArticle];

		// Keywords bar
		$keywordsBar = '';
		foreach ($article->keywords as $keyword) {
			$keywordsBar .= '<a href="https://twitter.com/search?q=%23'.$keyword.'">#'.$keyword.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
		}

		echo <<<ARTICLE

		<article class="blogArticle">
			<div class="blogArticleHead">
				<h2>$article->title</h2>
				<p>Auteur : $article->author</p>
				<p>Publier le : <time datetime="$article->date" pubdate>$article->date</time></p>
				<p>Mot clés : $keywordsBar</p>
				<h3>$article->intro</h3>
			</div>
			<div class="blogArticleContent">
				$article->body
			</div>
		</article>

ARTICLE;
	}
?>
