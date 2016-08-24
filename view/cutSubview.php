<h2><?php echo $subTitle ?></h2>

<?php
	foreach ($cuts as $cut) {
		echo <<<SUBVIEW

<dl>
	<dt>$cut->type</dt>
	<dd>$cut->description</dd>
</dl>

SUBVIEW;
	}
?>
