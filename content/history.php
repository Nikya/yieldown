<h2>Historique</h2>

<?php
	$history = Yieldown::loadCollection('history', array('event') );

	foreach ($history as $story) {
		echo <<<HD

<dl>
	<dt>$story->year</dt>
	<dd>$story->event</dd>
</dl>

HD;
	}
?>
