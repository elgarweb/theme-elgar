<?php
if(!$GLOBALS['domain']) exit;
if(!@$GLOBALS['content']['titre']) $GLOBALS['content']['titre'] = $GLOBALS['content']['title'];
?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title')?>

	<article class="pbm">

		<div class="fl prm">

			<figure>
				<?php media('visuel', array('size' => '300x225', 'lazy' => true)); ?>
				<figcaption><?php txt('texte-legende-visuel', 'italic'); ?></figcaption>
			</figure>
			
		</div>
	
		<div>

			<!-- Tag -->

			<div class="editable-hidden bold"><?php _e("Category")?></div>

			<?php 
			if($res['tpl']=='article')
				tag('actualites'); 
			elseif ($res['tpl']=='event')
				tag('agenda'); 
			else
				tag('annuaire'); 
			?>

			<?php if($res['tpl']=='article') { ?>
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

			<!-- Chapô -->
			<?php 
			if($res['tpl']=='article' or  $res['tpl']=='event') 
				txt('description', 'pbm');
			else 
				txt('texte-coordonnees-intro', 'pbm');
				txt('texte-coordonnees-suite');
			?>


			<!-- Infos événement -->
			<?php 
				// Date évènement
				if(stristr($res['tpl'], 'event'))
				{
			?>


					<div class="mbm">
						<?php 
							if(@$GLOBALS["content"]["aaaa-mm-jj"])
							{
								//@todo faire une transformation de la date en une ligne au lieu du explode
								$date_debut = explode("-", $GLOBALS["content"]["aaaa-mm-jj"]);
								echo'<div class="bold">'.__("Date").'</div>'.$date_debut['2'].'/'.$date_debut['1'].'/'.$date_debut['0'].'<br>';
							}

							input("aaaa-mm-jj", array("type" => "hidden", "class" => "meta tc"));

						?>
					</div>

					<?php txt('texte-evenement'); ?>
					
				<?php 
					}
				?>

				

		</div>


	</article>

	<article class="clear ptm">

		<?php txt('texte'); ?>
		
	</article>
	
	<!-- Bouton vers toutes les actualités/agenda/annuaire -->
	<div class="tc ptl">

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