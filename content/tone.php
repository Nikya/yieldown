<h2>Les différents type de tons</h2>

<?php
	$tones = Yieldown::loadCollection('tone', array('desc') );

	foreach ($tones as $tone) {
		echo <<<HD

<dl>
	<dt>$tone->type</dt>
	<dd>$tone->desc</dd>
</dl>

HD;
	}
?>
