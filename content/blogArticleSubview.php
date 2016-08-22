<?php
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


		<div class="markdown-body blogArticleContent">
			$article->body
		</div>


	</article>

ARTICLE;
?>
