<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="select">
	
	<!-- Label -->
	<label for="input-<?=$GLOBALS['editkey']+1?>">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>

		<?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true){?>
			<span class="required">*</span>
		<?}?>
	</label>

	<!-- Input -->
	<?php
	$array = null;

	// Champ requis ?
	if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true) $array['required'] = true;

	select('', $array);
	?>

	<!-- Option -->
	<div class="inbl vam small">

		<div class="editable-hidden">

			<label for="required-<?=$GLOBALS['editkey']-1;?>">Champ obligatoire</label>
			<?checkbox("required-".($GLOBALS['editkey']-1));?>
	
		</div>

	</div>
	
</li>