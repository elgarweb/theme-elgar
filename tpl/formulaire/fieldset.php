<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="fieldset">

	<fieldset>
		<?
		//print_r($GLOBALS['content']['builder']);
		//$level = $GLOBALS['editkey'];
		$fieldset = $GLOBALS['editkey'];
		?>
		
		<legend><?php txt('', array('tag' => 'span', 'placeholder' => 'Légende'))?></legend>

		<ul class="fieldset" data-fieldset="<?=$fieldset?>">
			<li aria-hidden="true" class="exclude">Liste :</li>

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