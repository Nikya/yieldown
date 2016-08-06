<h2><?php echo $subTitle ?></h2>

<?php
	foreach ($history as $story) {
		echo <<<SUBVIEW

<dl>
	<dt>$story->year</dt>
	<dd>$story->event</dd>
</dl>

SUBVIEW;
	}
?>
