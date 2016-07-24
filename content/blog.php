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
			// Illustration
			$illustration = '';
			if($article->illustrationSrc!=null)
				$illustration = '<img src="'.$article->illustrationSrc.'" alt="'.$article->illustrationAlt.'" title="'.$article->illustrationAlt.'" />';

			echo <<<HD

<dl class="blogSummary">
	<dt>$article->title</dt>
	<dd>
		$illustration
		<p>
			$article->intro
			<br/>
			<a href="?p=blog&a=$article->id">Lire la suite ...</a>
		</p>
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

		// Share bar
		$currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		echo <<<ARTICLE

		<article class="blogArticle">
			<div class="blogArticleHeadOut" style="background:url('$article->illustrationSrc') no-repeat center;     background-size: 100%; background-color: #000; ">
				<h2>$article->title</h2>
				<div class="blogArticleHead">
					<img src="$article->illustrationSrc" alt="$article->illustrationAlt" title="$article->illustrationAlt" />
					<aside>
						<p>Auteur : $article->author</p>
						<p>Publier le : <time datetime="$article->date" pubdate>$article->date</time></p>
						<p>Mot clés : $keywordsBar</p>
						<p>Partager :
							<a title="Facebook" target="_blank" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=$currentUrl" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;">
								Facebook</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<a title="Twitter" target="_blank" rel="nofollow" href="https://twitter.com/home?status=$article->title%20%3A%20$currentUrl%20%40HistoireDuJeans" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700');return false;">
								Twitter</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<a title="Google+" target="_blank" rel="nofollow" href="https://plus.google.com/share?url=$currentUrl" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=700,width=700');return false;">
								Google+</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<a title="Email" target="_blank" rel="nofollow" href="mailto:?&subject=$article->title&body=$article->title%20%3A%20$currentUrl%20--HistoireDuJeans" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=700,width=700');return false;">
								Email</a>&nbsp;&nbsp;&nbsp;&nbsp;
						</p>
					</aside>
				</div>
				<h3>$article->intro</h3>
			</div>
			<div class="blogArticleContent">
				$article->body
			</div>
		</article>

ARTICLE;
	}
?>
