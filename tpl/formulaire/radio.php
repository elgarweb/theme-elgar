<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="radio">
	
	<?radio('');//, 'radios-'.@$fieldset?>

	<label for="radio-<?=$GLOBALS['editkey']-1?>"><?php txt('', array('tag' => 'span', 'placeholder' => 'Label radio'))?></label>
	
</li>