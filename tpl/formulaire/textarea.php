<?php if(!$GLOBALS['domain']) exit;?>

<li data-builder="textarea">

	<label for="textarea-<?=$GLOBALS['editkey']+1?>">
		<?php txt('', array('tag' => 'span', 'placeholder' => 'Label'))?>

		<?if(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true){?>
			<span class="required">*</span>
		<?}?>
	</label>

	<textarea id="textarea-<?=$GLOBALS['editkey']?>" name="textarea-<?=$GLOBALS['editkey']?>" <?=(@$GLOBALS['content']['required-'.$GLOBALS['editkey']] == true?' required':'').(@$GLOBALS['content']['maxlength-'.$GLOBALS['editkey']]?' maxlength="'.(int)$GLOBALS['content']['maxlength-'.$GLOBALS['editkey']].'"':'')?>></textarea>
	
	<!-- Option -->
	<div class="inbl vam small">

		<div class="editable-hidden">

			<label>Champ obligatoire</label>
			<?checkbox("required-".($GLOBALS['editkey']));?>

			<?//input("maxlength-".($GLOBALS['editkey']-1), array("type" => "number", "class" => "w50p"));?>
			
		</div>

	</div>

	<?php
	$GLOBALS['editkey']++;
	?>
</li>