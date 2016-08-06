<h2><?php echo $subTitle ?></h2>

<?php
	foreach ($blog as $id => $article) {
		// Illustration
		$illustration = '';
		if($article->illustrationSrc!=null)
			$illustration = '<img src="'.$article->illustrationSrc.'" alt="'.$article->illustrationAlt.'" title="'.$article->illustrationAlt.'" />';

		echo <<<SUBVIEW

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

SUBVIEW;
	}
