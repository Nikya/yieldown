<h2><?php echo $subTitle ?></h2>

<?php
	foreach ($tones as $tone) {
		echo <<<SUBVIEW

<dl>
	<dt>$tone->type</dt>
	<dd>$tone->desc</dd>
</dl>

SUBVIEW;
	}
?>
