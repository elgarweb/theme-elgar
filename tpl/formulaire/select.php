<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="select">
	
	<!-- Label -->
	<label for="select-<?=$GLOBALS['editkey']+1?>">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>

		<?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true){?>
			<span class="required">*</span>
		<?}?>
	</label>

	<select id="select-<?=$GLOBALS['editkey'];?>" name="select-<?=$GLOBALS['editkey'];?>"<?=(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true?' required':'')?>>
		<option label="<?_e("Selectionner une option")?>"></option>
		<?php
		// Des valeurs dans le input ? => on créer un tableau avec
		if(isset($GLOBALS['content']['input-'.$GLOBALS['editkey']])) 
			$array = explode(";",$GLOBALS['content']['input-'.$GLOBALS['editkey']]);

		$optgroup = false;

		if(is_array($array))
		foreach($array as $cle => $val)
		{
			if(str_starts_with(trim($val), '#'))
			{
				if($optgroup == true) echo'</optgroup>';

				$optgroup = true;
				echo'<optgroup label="'.ltrim(trim(htmlspecialchars(strip_tags($val))), '#').'">';
			}
			else
				echo'<option value="'.trim(htmlspecialchars(strip_tags($val))).'">'.trim(strip_tags($val)).'</option>';
		}

		if($optgroup == true) echo'</optgroup>';
		?>
	</select>

	<!-- Input pour les données du select -->
	<?php
	input('', array("class" => "editable-hidden", "placeholder" => "Valeur 1;Valeur 2;#Groupe;Valeur 3..."));
	?>

	<!-- Option -->
	<div class="inbl vam small">

		<div class="editable-hidden">

			<label>Champ obligatoire</label>
			<?checkbox("required-".($GLOBALS['editkey']-1));?>
	
		</div>

	</div>
	
</li>