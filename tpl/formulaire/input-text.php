<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="input-text">

	<!-- Label -->
	<label>
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?> <?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true){?><span class="required">*</span><?}?>
	</label>
	
	<?php
	// Variable du input
	$array = null;

	// Champ requis ?
	if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true) $array['required'] = true;

	// Format attendu
	$formats = Array(
		'date' => "Format attentu : jour/mois/année",
		'datetime-local' => "Format attentu : jour/mois/année heure:minute",
		'time' => "Format attentu : heure:minute",
		'email' => "Format attendu : dupont@exemple.com",
	);
	// Format attendu, s'il en existe un
	if(@$GLOBALS['content']['format-'.$GLOBALS['editkey']]) {
		txt("format-".$GLOBALS['editkey'], array("tag" => "span", "placeholder" => "Format attendu", "class" => "text_format mrt italic"));
		$GLOBALS['editkey'] = $GLOBALS['editkey']-1;
	}

	// Message d'erreur
	// Message par défaut
	$errors = Array(
		'email' => "E-mail invalide. Format attendu : dupont@exemple.com",
	);
	// Si message d'erreur on ajoute le lien à l'input // Plus utilisé car on utilise les setCustomValidity
	//if(@$GLOBALS['content']['error-'.$GLOBALS['editkey']]) $array['aria-describedby'] = 'error-'.$GLOBALS['editkey'];

	// Type de champs + autocomplete | 'autocomplete' = 'type'
	// Type : text, url, tel, email, date, time, datetime-local, number, password
	// Autocomplete : given-name (Le prénom), family-name (nom de famille), email, organization-title (titre du poste), organization (nom de l'entreprise/organisation), street-address (adresse postale), postal-code, bday (date de naissance complète), tel, url

	$types = Array(
		'number' => false,
		'date' => false,
		'time' => false,
		'datetime-local' => false,
		'email' => 'email',
		'tel' => 'tel',
		'given-name' => 'text',
		'family-name' => 'text',
		'organization' => 'text',
		'street-address' => 'text',
		'postal-code' => 'text',
		'bday' => 'date',
	);	

	$type_value = @$GLOBALS['content']['type-'.$GLOBALS['editkey']];
	if(@$type_value != 'text')// Si ce n'est pas un champs texte simple
	{
		// Autocomplete
		$array['autocomplete'] = null;
		if(@$types[$type_value]) $array['autocomplete'] = $type_value;

		// Type		
		if(@$types[$type_value] and @$types[$type_value] != $type_value) 
		{
			$array['type'] = @$types[$type_value];
		}
		else
			$array['type'] = $type_value;
	}

	// Name pour le serialize
	$array['name'] = 'input-'.$GLOBALS['editkey'];

	//echo 'type_value: '.@$type_value." // ";
	//echo 'types[type_value]: '.@$types[$type_value]." // ";
	//echo 'type : '.@$array['type'].' // ';
	//echo 'autoc : '.@$array['autocomplete'].' ';
	?>


	<!-- Input -->
	<?php
	input('', $array);//, array('builder' => 'input')
	?>

	<!-- Option -->
	<div class="inbl vam small">

		<details class="editable-hidden">

			<summary class="inline pointer">
				<i class="fa fa-fw fa-cog o50" aria-hidden="true"></i>Option
			</summary>

			<div class="inline mls">

				<label>Champ obligatoire</label>
				<?checkbox("required-".($GLOBALS['editkey']-1));?>

				|

				<?php
				$types = null;
				$types = Array(
					'text' => "Texte",
					'number' => "Nombre",
					'date' => "Date",
					'time' => "Heure",
					'datetime-local' => "Date et heure",
					'email' => "Courriel",
					'tel' => "Téléphone",
					'given-name' => "Prénom",
					'family-name' => "Nom de famille",
					'organization' => "Nom d'organisation",
					'street-address' => "Adresse postale",
					'postal-code' => "Code postal",
					'bday' => "Date de naissance",
					'text' => "Texte",
				);	
				?>
				<label for="type-<?=$GLOBALS['editkey']-2;?>">Type</label>
				<select id="type-<?=$GLOBALS['editkey']-2;?>" data-id="<?=$GLOBALS['editkey']-2;?>" class="editable-select type">
					<?php
					foreach($types as $cle => $val)
					{
						echo'<option value="'.$cle.'"'.($type_value == $cle?' selected':'').(isset($errors[$cle])?' data-error="'.$errors[$cle].'"':'').(isset($formats[$cle])?' data-format="'.$formats[$cle].'"':'').'>'.$val.'</option>';
					}?>
				</select>
				
			</div>

		</details>

	</div>

	<?php
	// Message d'erreur, s'il en existe un
	if(@$GLOBALS['content']['error-'.$GLOBALS['editkey']-2]) 
	{
		$editkey_2 = $GLOBALS['editkey']-2;
		?>
		<div class='text_error editable-hidden'>Message en cas d'erreur : <?php
		txt("error-".$editkey_2, array("tag" => "span", "class" => "red"));
		?>
		</div>
		<script>
			$(function()
			{
				// Message d'erreur en cas de mauvaise saisie du mail. Pour l'accessibilité
				var error_<?=$editkey_2?> = document.getElementById("input-<?=$editkey_2?>");
				error_<?=$editkey_2?>.addEventListener("invalid", function() {
					error_<?=$editkey_2?>.setCustomValidity("<?=$GLOBALS['content']['error-'.$editkey_2]?>")
				}, false);
				error_<?=$editkey_2?>.addEventListener("input", function() {
					error_<?=$editkey_2?>.setCustomValidity("");
				}, false);
			});
		</script>
		<?php
	}
	
	//print_r($GLOBALS['content']);
	?>

</li>