<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="checkbox">
	
	<label for=""><?php txt('', array('tag' => 'span', 'placeholder' => 'Label case à cocher'))?></label>
	<?checkbox();?>

</li>