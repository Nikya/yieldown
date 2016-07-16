<h2>Les différentes type de coupes</h2>

<?php
	$cuts = Yieldown::loadCollection('cut', array('description') );

	foreach ($cuts as $cut) {
		echo <<<HD

<dl>
	<dt>$cut->type</dt>
	<dd>$cut->description</dd>
</dl>

HD;
	}
?>
