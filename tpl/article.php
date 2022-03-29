<?php
if(!$GLOBALS['domain']) exit;
if(!@$GLOBALS['content']['titre']) $GLOBALS['content']['titre'] = $GLOBALS['content']['title'];

// Variables
switch($res['tpl']) {
	default:
		$type = '';
		$media = false;
		$dir = '';
		$url_back = encode(__('News'));
		$text_back = __("Go back to the news");
	break;

	case 'article':
		$type = 'article';
		$media = true;
		$dir = 'actualites';
		$url_back = encode(__('News'));
		$text_back = __("Go back to the news");
	break;

	case 'event':
		$type = $res['type'];
		$media = true;
		$dir = 'agenda';
		$url_back = encode(__('Agenda'));
		$text_back = __("Go back to the agenda");
	break;

	case 'annuaire':
		$type = 'annuaire';
		$media = true;
		$dir = 'annuaire';
		$url_back = encode(__('Directory'));
		$text_back = __("Go back to the directory");
	break;

	case 'arrete':
		$type = 'arrete';
		$media = false;
		$dir = 'arretes';
		$url_back = encode(__('Decrees'));
		$text_back = __("Go back to decrees");
	break;
} 
?>
<section class="mw960p mod center">


	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>


	<?php h1('title', 'picto'); ?>


	<article class="flex wrap space-xl ptl">


		<?php if($media) { ?>
		<div class="<?=(isset($GLOBALS['content']['visuel'])) ? '' : 'editable-hidden ' ?>prm">

			<figure>

				<?php media('visuel', array('size' => '300x225', 'lazy' => true, 'dir' => $dir, 'class' => 'brd'));	?>

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
					tag($dir, array('tag' => 'span'));
					?>
				 
					<script>
						if(!$(".editable-tag").text()) $("#<?=$dir?>").prev("h3").hide();
						else $("#<?=$dir?>").addClass("mbm");
					</script>

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


				echo '<div class="bold pts'.(!@$GLOBALS['content']['telephone']?' editable-hidden':'').'"><i class="fa fa-fw fa-phone" aria-hidden="true"></i> <a href="javascript:void(0)" class="tel" title="'.__("Click to see the").' '.__('Telephone').'">'.__('Telephone').'</a><span class="pls" aria-live="polite"></span></div>';

				input('telephone', array('type' => 'hidden', 'class' => 'encode'));
	

				echo '<div class="bold pts'.(!@$GLOBALS['content']['mail-contact']?' editable-hidden':'').'"><i class="fa fa-fw fa-mail-alt" aria-hidden="true"></i> <a href="javascript:void(0)" class="mailto" title="'.__("Click to see the").' '.__('Email').'">'.__('Email').'</a><span class="pls" aria-live="polite"></span></div>';

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

		<?php txt('texte', array('dir' => $dir));?>
				
	</article>


	
	<!-- Bouton vers toutes les actualités/agenda/annuaire -->
	<div class="tc ptl pbl">		

		<a href="<?=make_url($url_back, array("domaine" => true))?>" class="bt pas"><?=$text_back;?></a>

	</div>

</section>

<script>
$(function()
{
	// Décode
	$(".tel, .mailto").on("click", function(event) { 
		//event.preventDefault();
		//document.location.href = $(event.target).attr("class") + ":" + atob($(event.target).parent().next(".encode").val());
		$(event.target).next('span').html(atob($(event.target).parent().next(".encode").val()));
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