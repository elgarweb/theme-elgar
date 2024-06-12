<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="radio">
	
	<fieldset>
		
		<legend><?php txt('', array('tag' => 'span', 'placeholder' => 'Légende'))?></legend>

		<ul class="fieldset">
			<li aria-hidden="true" class="exclude">Liste</li>
			<li><label for=""><?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?></label> <?radio();?></li>
		</ul>

	</fieldset>

</li>