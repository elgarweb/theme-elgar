<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="textarea">

	<label for="textarea-<?=$GLOBALS['editkey']+1?>">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>
	</label>

	<textarea id="textarea-<?=$GLOBALS['editkey']?>"></textarea>
	
	<?php
	// Message d'erreur

	// placeholder
	// required

	$GLOBALS['editkey']++;
	?>
</li>