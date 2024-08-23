<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="input-text">

	<label for="input-<?=$GLOBALS['editkey']+1?>">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>

		<?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true){?>
		<span class="required">*</span>
		<?}?>
	</label>

	<?php
	$array = null;

	if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true) $array['required'] = true;

	input('', $array);//, array('builder' => 'input')	
	?>
	<div class="inbl vam small">

		<details class="editable-hidden">

			<summary class="inline pointer">
				<i class="fa fa-fw fa-cog o50" aria-hidden="true"></i>Option
			</summary>

			<div class="inline mls">

				<label for="required-<?=$GLOBALS['editkey']-1;?>">Champ obligatoire</label>
				<?checkbox("required-".($GLOBALS['editkey']-1));?>
				
			</div>

		</details>

	</div>
	<?php
	// Message d'erreur
	// type (influe sur l'autocomplete)
	// placeholder
	// required
	// autocomplete

	//print_r($GLOBALS['content']);
	//unset($GLOBALS['content'][($GLOBALS['editkey'])]);

	//unset($GLOBALS['content']['txt-'.($GLOBALS['editkey']-2)]);
	//unset($GLOBALS['content']['input-'.($GLOBALS['editkey']-1)]);
	?>

</li>