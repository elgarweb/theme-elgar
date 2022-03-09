<?php
if(!$GLOBALS['domain']) exit;
if(!@$GLOBALS['content']['titre']) $GLOBALS['content']['titre'] = $GLOBALS['content']['title'];
?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto mtn pbm'); ?>

	<article class="flex space-xl ptl pbm">

		<div class="prm">

			<figure>

				<?php media('visuel', array('size' => '300x225', 'lazy' => true)); ?>

				<figcaption>

					<?php txt('texte-legende-visuel', 'italic ptt plt'); ?>

				</figcaption>


			</figure>
			
		</div>
	
		<div>

			<!-- Tag -->
			<div>
				
				<div class="editable-hidden bold"><?php _e("Categories"); ?></div>

				<!-- Champs saisie tags -->
				<div class="pbm">

					<?php 
					// Champs saisie tags
					if($res['tpl']=='article')
						tag('actualites', array('tag' => 'span')); 
					elseif ($res['tpl']=='event')
						tag('agenda', array('tag' => 'span')); 
					else
						tag('annuaire', array('tag' => 'span')); 
				
					// Scripts		
					if($res['tpl']=='article') { ?>
						<script>
						if(!$(".editable-tag").text()) $("#actualites").prev("h3").hide();
						else $("#actualites").addClass("mbm");
						</script>

					<?php } elseif($res['tpl']=='event') { ?>
						<script>
						if(!$(".editable-tag").text()) $("#agenda").prev("h3").hide();
						else $("#agenda").addClass("mbm");
						</script>

					<?php } else ?>
						<script>
						if(!$(".editable-tag").text()) $("#annuaire").prev("h3").hide();
						else $("#annuaire").addClass("mbm");
						</script>

				</div>

			</div>

			<!-- Date événement -->
			<?php 
				if(stristr($res['tpl'], 'event'))
				{
			?>
					<div class="editable-hidden bold"><?= _e("Start date");?></div>

					<div>
						<?php 
							if(@$GLOBALS["content"]["aaaa-mm-jj"])
								echo '<div>'.date_lang($GLOBALS["content"]["aaaa-mm-jj"]).'</div>';						

							input("aaaa-mm-jj", array("type" => "hidden", "class" => "meta tc"));
						?>

					</div>
					
			<?php 
				}
			?>

			<!-- Chapô -->
			<?php 
			// Description : s'affiche sur la liste
			if($res['tpl']=='article') 

				txt('description', 'ptm');

			if($res['tpl']=='annuaire' or  $res['tpl']=='event') ?>

				<div class="bold"><?= _e('Website'); ?></div>
				<?php txt('site-web', 'pbm'); ?>

				<div class="bold"><?= _e('Telephone'); ?></div>
				<?php txt('tel', 'pbm'); ?>

				<div class="bold"><?= _e('Mail'); ?></div>
				<?php txt('mail', 'pbm'); ?>

				<div class="bold"><?= _e('Address'); ?></div>
				<?php txt('adresse', 'pbm');

			// Détails de l'événement (horaires, contact...)
			if($res['tpl']=='event')

				txt('texte-details-evenement', 'ptm');

			?>


		</div>

	</article>

	<!-- Contenu de l'article -->
	<article class="clear ptl">

		<?php txt('texte'); ?>
		
	</article>
	
	<!-- Bouton vers toutes les actualités/agenda/annuaire -->
	<div class="tc ptl pbl">

		<a href="<?= make_url(($res['type']=='event' ? __('agenda') : ($res['type']=='article' ? __('news') : __('directory'))), array("domaine" => true))?>" class="bt pas">

			<?= ($res['type']=='event' ? __("Go back to the agenda") : ($res['type']=='article' ? __("Go back to the news") : __("Go back to the directory"))); ?>

		</a>

	</div>

</section>

<script>
	// Action si on lance le mode d'edition
	edit.push(function()
	{
		// DATEPIKER pour la date de l'event
		$.datepicker.setDefaults({
	        altField: "#datepicker",
	        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
	        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
	        dateFormat: 'yy-mm-dd',
	        firstDay: 1
	    });
		$("#aaaa-mm-jj").datepicker();
	});
</script>

<!-- Actualité à la une -->
<? include("theme/".$GLOBALS['theme']."/admin/alaune.php"); ?>