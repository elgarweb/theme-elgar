<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="checkbox">
	
	<?checkbox('', array('fa' => false));?>

	<label for="input-<?=$GLOBALS['editkey']?>"><?php txt('', array('tag' => 'span', 'placeholder' => 'Label case à cocher'))?></label>

</li>