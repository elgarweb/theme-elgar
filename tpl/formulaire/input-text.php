<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="input-text">

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

	// Message d'erreur
	// Message par défaut
	$errors = Array(
		'email' => "E-mail invalide. Format attendu : dupont@exemple.com",
	);
	// Si message d'erreur on ajoute le lien à l'input
	if(@$GLOBALS['content']['error-'.$GLOBALS['editkey']]) 
		$array['aria-describedby'] = $GLOBALS['content']['error-'.$GLOBALS['editkey']];

	// Type de champs et autocomplete ?
	// 'autocomplete' = 'type'
	// text, url, tel, email, date, time, datetime-local, number, password
	// given-name (Le prénom), family-name (nom de famille), email, organization-title (titre du poste), organization (nom de l'entreprise/organisation), street-address (adresse postale), postal-code, bday (date de naissance complète), tel, url

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
	//print_r($types);

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

	//echo 'type_value: '.@$type_value." // ";
	//echo 'types[type_value]: '.@$types[$type_value]." // ";
	//echo 'type : '.@$array['type'].' // ';
	//echo 'autoc : '.@$array['autocomplete'].' ';


	input('', $array);//, array('builder' => 'input')
	?>

	<!-- Option -->
	<div class="inbl vam small">

		<details class="editable-hidden">

			<summary class="inline pointer">
				<i class="fa fa-fw fa-cog o50" aria-hidden="true"></i>Option
			</summary>

			<div class="inline mls">

				<label for="required-<?=$GLOBALS['editkey']-1;?>">Champ obligatoire</label>
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
				<label for="type-<?=$GLOBALS['editkey']-2;?>">Type de champ</label>
				<select id="type-<?=$GLOBALS['editkey']-2;?>" data-id="<?=$GLOBALS['editkey']-2;?>" class="editable-select type">
					<?php
					foreach($types as $cle => $val)
					{
						echo'<option value="'.$cle.'"'.($type_value == $cle?' selected':'').(isset($errors[$cle])?' data-error="'.$errors[$cle].'"':'').'>'.$val.'</option>';
					}?>
				</select>
				
			</div>

		</details>

	</div>

	<?php
	// Message d'erreur s'il en existe un
	if(@$GLOBALS['content']['error-'.$GLOBALS['editkey']-2]) 
		txt("error-".($GLOBALS['editkey']-2), array("tag" => "span"));
	
	//print_r($GLOBALS['content']);
	//unset($GLOBALS['content'][($GLOBALS['editkey'])]);

	//unset($GLOBALS['content']['txt-'.($GLOBALS['editkey']-2)]);
	//unset($GLOBALS['content']['input-'.($GLOBALS['editkey']-1)]);
	?>

</li>