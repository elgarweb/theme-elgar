<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="input-text">

	<label for="">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>
		<span class="red">*</span>
	</label>

	<?php
	// Message d'erreur

	// type (influe sur l'autocomplete)
	// placeholder
	// required
	// autocomplete

	input('');//, array('builder' => 'input')

	//print_r($GLOBALS['content']);
	//unset($GLOBALS['content'][($GLOBALS['editkey'])]);

	//unset($GLOBALS['content']['txt-'.($GLOBALS['editkey']-2)]);
	//unset($GLOBALS['content']['input-'.($GLOBALS['editkey']-1)]);
	?>

</li>