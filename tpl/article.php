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
		$dir = encode(__('News'));// 'actualites'
		$url_back = encode(__('News'));
		$text_back = __("Go back to the news");
	break;

	case 'event':
		$type = $res['type'];
		$media = true;
		$dir = encode(__('Agenda'));
		$url_back = encode(__('Agenda'));
		$text_back = __("Go back to the agenda");
	break;

	case 'annuaire':
		$type = 'annuaire';
		$media = true;
		$dir = encode(__('Directory'));// 'annuaire'
		$url_back = encode(__('Directory'));
		$text_back = __("Go back to the directory");
	break;

	case 'commerce':
		$type = 'commerce';
		$media = true;
		$dir = encode(__('Commerce'));// 'commerce'
		$url_back = encode(__('Commerce'));
		$text_back = __("Go back to the directory");
	break;

	case 'arrete':
		$type = 'arrete';
		$media = false;
		$dir = encode(__('Decrees'));// 'arretes'
		$url_back = encode(__('Decrees'));
		$text_back = __("Go back to decrees");
	break;
} 
?>
<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>


<section class="<?= $res['tpl'] == 'event' ? 'bg-grey' : ''; ?>">

	<div class="mw960p mod center">


		<?php h1('title', 'picto'); ?>

		<div class="flex wrap space-xl ptl">

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
						tag($dir, array('tag' => 'ul', 'class'=>'unstyled pln flex'));
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
					<p class="editable-hidden bold"><?= _e("Date");?></p>

					<?php 
					if(@$GLOBALS["content"]["aaaa-mm-jj"])
					{
						echo '<p class="mbn">';
						if($lang == 'eu') echo str_replace('-', '/', $GLOBALS["content"]["aaaa-mm-jj"]);
						else echo date_lang($GLOBALS["content"]["aaaa-mm-jj"]);

						if(@$GLOBALS["content"]["heure-ouverture"]){
							echo ', '.date_format(date_create($GLOBALS["content"]["heure-ouverture"]), 'H:i');

							// Si basque
							if($lang == 'eu')										
								if(date_format(date_create($GLOBALS["content"]["heure-ouverture"]), 'i') == 0) 
									echo"etatik";// pluriel
								else 
									echo"etik";// singulier
						}

						if(@$GLOBALS["content"]["heure-fermeture"]){
							echo ' '.__("to").' '.date_format(date_create($GLOBALS["content"]["heure-fermeture"]), 'H:i');

							// Si basque
							if($lang == 'eu')										
								if(date_format(date_create($GLOBALS["content"]["heure-fermeture"]), 'i') == 0)
									echo"etara";// pluriel
								else
									echo"era";// singulier
						}

						echo '</p>';						
					}

					input("aaaa-mm-jj", array("type" => "hidden", "autocomplete" => "off", "class" => "meta tc"));

					if(stristr($res['tpl'], 'event')) 
					{
						input('heure-ouverture', array("type" => "hidden", "autocomplete" => "off"));			
						input('heure-fermeture', array("type" => "hidden", "autocomplete" => "off"));
					}
				}
				?>

				<!-- Chapô -->
				<?php 
				if($res['tpl']=='annuaire' or  $res['tpl']=='event' or  $res['tpl']=='commerce') 
				{ 
					echo '<ul class="unstyled pln">';
					echo '<li class="bold pts pbn'.(!@$GLOBALS['content']['url-site-web']?' editable-hidden':'').'"><i class="fa fa-fw fa-globe" aria-hidden="true"></i> <a href="'.@$GLOBALS['content']['url-site-web'].'" target="_blank">'.__('Website').'</a>';

					input('url-site-web', array('type' => 'hidden'));

				  	echo '</li><li class="pbn"><details class="pts'.(!@$GLOBALS['content']['telephone']?' editable-hidden':'').'" aria-live="polite"><summary href="javascript:void(0)" class="tel color pointer tdu bold  inbl" data-encode="'.@$GLOBALS['content']['telephone'].'"  aria-label="'.__("Click to display the").' '.__('Telephone').'"><i class="fa fa-fw fa-phone" aria-hidden="true"></i>'.__('Telephone').'</summary><span class="pls bold"></span></details>';

				  	input('telephone', array('type' => 'hidden', 'class' => 'encode'));
	
				  	echo '</li><li class="pbn"><details class="pts'.(!@$GLOBALS['content']['mail-contact']?' editable-hidden':'').'" aria-live="polite"><summary href="javascript:void(0)" class="tel color pointer tdu bold inbl" data-encode="'.@$GLOBALS['content']['mail-contact'].'"  aria-label="'.__("Click to display the").' '.__('Email').'"><i class="fa fa-fw fa-mail-alt" aria-hidden="true"></i>'.__('Email').'</summary><span class="pls bold mtm"></span></details>';

					input('mail-contact', array('type' => 'hidden', 'class' => 'encode'));

					echo '</li><li class="bold pts pbn'.(!@$GLOBALS['content']['adresse']?' editable-hidden':'').'"><i class="fa fa-fw fa-location" aria-hidden="true"></i>'.__('Address');

					txt('adresse', array('class'=>'plt mbt','tag'=>'p'));
					echo '</li></ul>';
				}

				// Description : s'affiche sur la liste
				if($res['tpl']=='article' or $res['tpl']=='event')
					txt('description', array('class'=>'mbn'));//,'tag'=>'p'
				?>

			</div>

		</div>
    
		<!-- Contenu de l'article -->
		<article class="clear ptl">

			<?php txt('texte', array('dir' => $dir));?>
					
		</article>
		
		<!-- Bouton vers toutes les actualités/agenda/annuaire -->
		<div class="tc ptl pbl">		

			<a href="<?=make_url($url_back, array("domaine" => true))?>" class="bt pas"><?=$text_back;?></a>

		</div>

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