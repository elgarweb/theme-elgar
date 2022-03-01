<?php
if(!$GLOBALS['domain']) exit;
if(!@$GLOBALS['content']['titre']) $GLOBALS['content']['titre'] = $GLOBALS['content']['title'];
?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('titre')?>

	<article class="pbm">

		<div class="fl prm">

			<figure>
				<?php media('visuel', array('size' => '500', 'lazy' => true)); ?>
				<figcaption><?php txt('texte-legende-visuel', 'italic'); ?></figcaption>
			</figure>
			
		</div>
	
		<div>

			<!-- Tag -->

			<div class="bold"><?php _e("Catégories")?></div>

			<?php tag('actualites'); ?>

			<script>
			if(!$(".editable-tag").text()) $("#actualites").prev("h3").hide();
			else $("#actualites").addClass("mbm");
			</script>

			<!-- Chapô -->
			<?php txt('texte-chapo', 'pbm'); ?>

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
	
	<!-- Bouton vers toutes les actualités -->
	<div class="tc ptl">
		<a href="<?=make_url(($res['type']=='event'?__('agenda'):__('news')), array("domaine" => true))?>" class="bt pas">
			<? echo ($res['type']=='event'?__("Go back to the agenda"):__("Go back to the news")); ?>
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
<? include("theme/".$GLOBALS['theme'].($GLOBALS['theme']?"/":"")."alaune.php"); ?>