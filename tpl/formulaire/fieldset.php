<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="fieldset">

	<fieldset>
		<?
		//print_r($GLOBALS['content']['builder']);
		//$level = $GLOBALS['editkey'];
		$fieldset = $GLOBALS['editkey'];
		?>
		
		<legend>
			<?php txt('', array('tag' => 'span', 'placeholder' => "Légende de l'ensemble de champs"))?>

			<?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']-1] == true){?>
				<span class="required">*</span>
			<?}?>
		</legend>

		<ul class="fieldset" data-fieldset="<?=$fieldset?>">

			<li class="exclude editable-hidden small grey">
				Ensemble de champs
				
				(<label>radio/checkbox obligatoire</label>
				<?checkbox("required-".($GLOBALS['editkey']-1));?>)
			</li>

			<?
			if(isset($level) and isset($GLOBALS['content']['builder'][$level]) and is_array($GLOBALS['content']['builder'][$level])) 
			{
				//echo"in filedset "; print_r($array);

				builder_array($GLOBALS['content']['builder'], $level);
			} 
			?>
		</ul>

	</fieldset>

</li>