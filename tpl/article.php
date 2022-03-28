<?php
if(!$GLOBALS['domain']) exit;
if(!@$GLOBALS['content']['titre']) $GLOBALS['content']['titre'] = $GLOBALS['content']['title'];
?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<article class="flex wrap space-xl ptl">

		<?php if($res['tpl']=='article' or $res['tpl']=='event' or $res['tpl']=='annuaire') { ?>
		<div class="<?= (isset($GLOBALS['content']['visuel'])) ? '' : 'editable-hidden ' ?>prm">

			<figure>

				<?php 
				if($res['tpl']=='article') 
				{
					media('visuel', array('size' => '300x225', 'lazy' => true, 'dir' => 'actualites', 'class' => 'brd')); 
				}
				elseif($res['tpl']=='event')
				{
					media('visuel', array('size' => '300x225', 'lazy' => true, 'dir' => 'agenda', 'class' => 'brd')); 
				}
				else
				{
					media('visuel', array('size' => '300x225', 'lazy' => true, 'dir' => 'annuaire', 'class' => 'brd')); 
				}
				?>

				<figcaption>

					<?php txt('texte-legende-visuel', 'italic ptt plt'); ?>

				</figcaption>


			</figure>
			
		</div>
		<?php } ?>
	
		<div class="mw600p">

			<!-- Tag -->
			<div id="tags">
				
				<div class="editable-hidden bold"><?php _e("Categories"); ?></div>

				<!-- Champs saisie tags -->
				<div>

					<?php 
					// Champs saisie tags
					if($res['tpl']=='article')
						tag('actualites', array('tag' => 'span')); 
					elseif ($res['tpl']=='event')
						tag('agenda', array('tag' => 'span')); 
					elseif ($res['tpl']=='annuaire')
						tag('annuaire', array('tag' => 'span')); 
					else
						tag('arretes', array('tag' => 'span'));
				
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

					<?php } else if($res['tpl']=='annuaire') { ?>
						<script>
						if(!$(".editable-tag").text()) $("#annuaire").prev("h3").hide();
						else $("#annuaire").addClass("mbm");
						</script>

					<?php } else { ?>
						<script>
						if(!$(".editable-tag").text()) $("#arretes").prev("h3").hide();
						else $("#arretes").addClass("mbm");
						</script>
					<?php } ?>

				</div>

			</div>

			<!-- Date événement -->
			<?php 
				if(stristr($res['tpl'], 'event') or stristr($res['tpl'], 'arrete'))
				{
			?>
					<div class="editable-hidden bold"><?= _e("Date");?></div>

					<div>
						<?php 
							if(@$GLOBALS["content"]["aaaa-mm-jj"])
							{
								echo '<div>';
								echo date_lang($GLOBALS["content"]["aaaa-mm-jj"]);

								if(@$GLOBALS["content"]["heure-ouverture"])
									echo ', '.date_format(date_create($GLOBALS["content"]["heure-ouverture"]), 'H\hi');

								if(@$GLOBALS["content"]["heure-fermeture"])
									echo ' '.__("to").' '.date_format(date_create($GLOBALS["content"]["heure-fermeture"]), 'H\hi');

								echo '</div>';						
							}

							input("aaaa-mm-jj", array("type" => "hidden", "autocomplete" => "off", "class" => "meta tc"));

							if(stristr($res['tpl'], 'event')) 
							{
								input('heure-ouverture', array("type" => "hidden", "autocomplete" => "off"));			
								input('heure-fermeture', array("type" => "hidden", "autocomplete" => "off"));
							}
						?>
					</div>
					
			<?php 
				}
			?>

			<!-- Chapô -->
			<?php 
			if($res['tpl']=='annuaire' or  $res['tpl']=='event') 
			{ 
				echo '<div class="bold pts'.(!@$GLOBALS['content']['url-site-web']?' editable-hidden':'').'"><i class="fa fa-fw fa-globe" aria-hidden="true"></i> <a href="'.@$GLOBALS['content']['url-site-web'].'" target="_blank">'.__('Website').'</a></div>';

				input('url-site-web', array('type' => 'hidden'));


				echo '<div class="bold pts'.(!@$GLOBALS['content']['telephone']?' editable-hidden':'').'"><i class="fa fa-fw fa-phone" aria-hidden="true"></i> <a href="javascript:void(0)" class="tel">'.__('Telephone').'</a></div>';

				input('telephone', array('type' => 'hidden', 'class' => 'encode'));
	

				echo '<div class="bold pts'.(!@$GLOBALS['content']['mail-contact']?' editable-hidden':'').'"><i class="fa fa-fw fa-mail-alt" aria-hidden="true"></i> <a href="javascript:void(0)" class="mailto">'.__('Mail').'</a></div>';

				input('mail-contact', array('type' => 'hidden', 'class' => 'encode'));


				echo '<div class="bold pts'.(!@$GLOBALS['content']['adresse']?' editable-hidden':'').'"><i class="fa fa-fw fa-location" aria-hidden="true"></i> '.__('Address').'</div>';

				txt('adresse', 'plt');
			}


			// Description : s'affiche sur la liste
			if($res['tpl']=='article' or $res['tpl']=='event') 

				txt('description', 'ptl');
			?>

		</div>

	</article>

	<!-- Contenu de l'article -->
	<article class="clear ptl">

		<?php 
		if($res['tpl']=='article') 
		{
			txt('texte', array('dir' => 'actualites'));
		}
		elseif($res['tpl']=='event')
		{
			txt('texte', array('dir' => 'agenda')); 
		}
		else
		{
			txt('texte', array('dir' => 'annuaire')); 
		}
		?>
		
		
	</article>
	
	<!-- Bouton vers toutes les actualités/agenda/annuaire -->
	<div class="tc ptl pbl">

		<a href="<?= make_url(($res['type']=='event' ? __('agenda') : ($res['type']=='article' ? __('news') : __('directory'))), array("domaine" => true))?>" class="bt pas">

			<?= ($res['type']=='event' ? __("Go back to the agenda") : ($res['type']=='article' ? __("Go back to the news") : __("Go back to the directory"))); ?>

		</a>

	</div>

</section>

<script>
$(function()
{
	// Décode
	$(".tel, .mailto").on("click", function(event) { 
		//event.preventDefault();
		document.location.href = $(event.target).attr("class") + ":" + atob($(event.target).parent().next(".encode").val());
	});

	// Avant la sauvegarde
	before_save.push(function() {
		// Encode
		if(data["content"]["mail-contact"] != undefined) 
			data["content"]["mail-contact"] = btoa(data["content"]["mail-contact"]);

		if(data["content"]["telephone"] != undefined)
			data["content"]["telephone"] = btoa(data["content"]["telephone"]);
	});

	// Action si on lance le mode d'edition
	edit.push(function()
	{
		// Décode
		$("#mail-contact, #telephone").val(function(index, value) {
			if(value) return atob(value);
		});

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
});
</script>

<!-- Actualité à la une -->
<? if($res['type'] == 'article') include("theme/".$GLOBALS['theme']."/admin/alaune.php");?>