<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="textarea">

	<label for="">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>
	</label>

	<textarea id="<?=$GLOBALS['editkey']?>"></textarea>
	<?php
	// Message d'erreur

	// placeholder
	// required

	$GLOBALS['editkey']++;
	?>
</li>