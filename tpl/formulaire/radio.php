<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="radio">
	
	<?radio('', 'fieldset-'.@$fieldset);?>
	<label for=""><?php txt('', array('tag' => 'span', 'placeholder' => 'Label radio'))?></label>
	
</li>