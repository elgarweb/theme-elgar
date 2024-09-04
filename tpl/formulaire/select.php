<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="select">
	
	<!-- Label -->
	<label for="input-<?=$GLOBALS['editkey']+1?>">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>

		<?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true){?>
			<span class="required">*</span>
		<?}?>
	</label>

	<select id="select-<?=$GLOBALS['editkey'];?>"<?=(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true?' required':'')?>>
		<option></option>
		<?php
		// Des valeurs dans le input ? => on créer un tableau avec
		if(isset($GLOBALS['content']['input-'.$GLOBALS['editkey']])) 
			$array = explode(";",$GLOBALS['content']['input-'.$GLOBALS['editkey']]);

		if(is_array($array))
		foreach($array as $cle => $val)
		{
			echo'<option value="'.$cle.'">'.trim(strip_tags($val)).'</option>';
		}
		?>
	</select>

	<!-- Input pour les données du select -->
	<?php
	input('', array("class" => "editable-hidden", "placeholder" => "Valeur 1; Valeur 2; Valeur 3..."));
	?>

	<!-- Option -->
	<div class="inbl vam small">

		<div class="editable-hidden">

			<label for="required-<?=$GLOBALS['editkey']-1;?>">Champ obligatoire</label>
			<?checkbox("required-".($GLOBALS['editkey']-1));?>
	
		</div>

	</div>
	
</li>