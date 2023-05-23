<?php
if(!$GLOBALS['domain']) exit;
if(!@$GLOBALS['content']['titre']) $GLOBALS['content']['titre'] = $GLOBALS['content']['title'];

$infos = false;

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
		$infos = true;
	break;

	case 'annuaire':
		$type = 'annuaire';
		$media = true;
		$dir = encode(__('Directory'));// 'annuaire'
		$url_back = encode(__('Directory'));
		$text_back = __("Go back to the directory");
		$infos = true;
	break;

	case 'annuaire-autre':
		$type = 'autre';
		$media = true;
		$dir = encode(__('Other Directory'));
		$url_back = encode(__('Other Directory'));
		$text_back = __("Go back to the directory");
		$infos = true;
	break;

	case 'commerce':
		$type = 'commerce';
		$media = true;
		$dir = encode(__('Commerce'));// 'commerce'
		$url_back = encode(__('Commerce'));
		$text_back = __("Go back to the directory");
		$infos = true;
	break;

	/*case 'arrete':
		$type = 'arrete';
		$media = false;
		$dir = encode(__('Decrees'));// 'arretes'
		$url_back = encode(__('Decrees'));
		$text_back = __("Go back to decrees");
	break;*/

	case 'publication':
		$type = 'publication';
		$media = false;
		$dir = encode(__('Publications'));
		$url_back = encode(__('Publications'));
		$text_back = __("Back to publications");
	break;
} 
?>
<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>


<section class="<?= $res['tpl'] == 'event' ? 'bg-color-3' : ''; ?>">

	<div class="mw960p mod center">


		<?php h1('title', 'picto'); ?>

		<div class="flex wrap space-xl ptl">

			<?php if($media) { ?>
			<div class="<?=(isset($GLOBALS['content']['visuel'])) ? '' : 'editable-hidden ' ?>prm">

				<figure role="group"<?=(isset($GLOBALS['content']['texte-legende-visuel']))?' aria-label="'.strip_tags($GLOBALS['content']['texte-legende-visuel']).'"':''?>>

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

							// Si tag vide on change le TagName pour ne pas avoir un ul vide
							if(!$(".editable-tag").text())
							{
								$(".editable-tag").replaceWith(function(){
									return this.outerHTML.replace("<ul", "<div").replace("</ul", "</div")
								});
							}
						</script>

					</div>

				</div>

				<!-- Date événement -->
				<?php 
				if(stristr($res['tpl'], 'event') or stristr($res['tpl'], 'publication'))
				{
					// Si arreté ou conseil
					if($res['tpl'] == 'publication')
					{
						?>
						<div class="editable-hidden bold red"><?= _e("Published on");?></div>
						<?php
						input("aaaa-mm-jj-publication", array("type" => "hidden", "autocomplete" => "off", "class" => "meta tc"));
					}
					else
					{

						?>
						<div class="editable-hidden bold"><?= _e("Start date");?></div>
						<?php
						input("aaaa-mm-jj", array("type" => "hidden", "autocomplete" => "off", "class" => "meta tc"));

						if(stristr($res['tpl'], 'event')) 
						{
							input('heure-ouverture', array("type" => "hidden", "autocomplete" => "off", "class" => "w150p"));			
							input('heure-fermeture', array("type" => "hidden", "autocomplete" => "off", "class" => "w150p"));
						}
						?>

						<div class="editable-hidden bold"><?= _e("End date");?></div>
						<?php
						input("aaaa-mm-jj-fin", array("type" => "hidden", "autocomplete" => "off", "class" => "meta tc"));

						if(stristr($res['tpl'], 'event')) 
						{
							input('heure-ouverture-fin', array("type" => "hidden", "autocomplete" => "off", "class" => "w150p"));			
							input('heure-fermeture-fin', array("type" => "hidden", "autocomplete" => "off", "class" => "w150p"));
						}

					}



					// Affichage Date de publication
					if(@$GLOBALS["content"]["aaaa-mm-jj-publication"])
					{
						echo '<p>'.__("Published on").' ';

						if($lang == 'eu') echo str_replace('-', '/', $GLOBALS["content"]["aaaa-mm-jj-publication"]);
						else echo date_lang($GLOBALS["content"]["aaaa-mm-jj-publication"]);

						echo '</p>';						
					}



					// Affichage Date de début
					if(@$GLOBALS["content"]["aaaa-mm-jj"])
					{
						echo '<p class="mbn">';

						if(@$GLOBALS["content"]["aaaa-mm-jj-fin"]) echo __("From").' ';

						if($lang == 'eu') {
							echo str_replace('-', '/', $GLOBALS["content"]["aaaa-mm-jj"]);
							if(@$GLOBALS["content"]["aaaa-mm-jj-fin"]) echo 'tik';
						}
						else echo date_lang($GLOBALS["content"]["aaaa-mm-jj"]);

						if(@$GLOBALS["content"]["heure-ouverture"]){
							echo ', '.@date_format(date_create($GLOBALS["content"]["heure-ouverture"]), 'H:i');

							// Si basque
							if($lang == 'eu')										
								if(@date_format(date_create($GLOBALS["content"]["heure-ouverture"]), 'i') == 0) 
									echo"etatik";// pluriel
								else 
									echo"etik";// singulier
						}

						if(@$GLOBALS["content"]["heure-fermeture"]){
							echo ' '.__("at").' '.@date_format(date_create($GLOBALS["content"]["heure-fermeture"]), 'H:i');

							// Si basque
							if($lang == 'eu')										
								if(@date_format(date_create($GLOBALS["content"]["heure-fermeture"]), 'i') == 0)
									echo"etara";// pluriel
								else
									echo"era";// singulier
						}

						if(@$GLOBALS["content"]["aaaa-mm-jj-fin"]) echo ' '.__("to").' ';
						else echo '</p>';						
					}



					// Affichage date de fin
					if(@$GLOBALS["content"]["aaaa-mm-jj-fin"])
					{
						if(!@$GLOBALS["content"]["aaaa-mm-jj"]) echo '<p class="mbn">';

						if($lang == 'eu') {
							echo str_replace('-', '/', $GLOBALS["content"]["aaaa-mm-jj-fin"]);
							if(@$GLOBALS["content"]["aaaa-mm-jj"]) echo 'ra';
						}
						else echo date_lang($GLOBALS["content"]["aaaa-mm-jj-fin"]);

						if(@$GLOBALS["content"]["heure-ouverture-fin"]){
							echo ', '.@date_format(date_create($GLOBALS["content"]["heure-ouverture-fin"]), 'H:i');

							// Si basque
							if($lang == 'eu')										
								if(@date_format(date_create($GLOBALS["content"]["heure-ouverture-fin"]), 'i') == 0) 
									echo"etatik";// pluriel
								else 
									echo"etik";// singulier
						}

						if(@$GLOBALS["content"]["heure-fermeture-fin"]){
							echo ' '.__("at").' '.@date_format(date_create($GLOBALS["content"]["heure-fermeture-fin"]), 'H:i');

							// Si basque
							if($lang == 'eu')										
								if(@date_format(date_create($GLOBALS["content"]["heure-fermeture-fin"]), 'i') == 0)
									echo"etara";// pluriel
								else
									echo"era";// singulier
						}

						echo '</p>';						
					}
				}
				?>
				

				<!-- Chapô -->
				<?php 
				if($infos) 
				{ 
					if(!@$GLOBALS['content']['url-site-web'] and
						!@$GLOBALS['content']['telephone'] and
						!@$GLOBALS['content']['mail-contact'] and
						!@$GLOBALS['content']['adresse'])
						$hidden = true;
					else 
						$hidden = false;

					echo '<ul class="unstyled pln'.($hidden?' editable-hidden':'').'">';

						echo '<li class="bold pts pbn'.(!@$GLOBALS['content']['url-site-web']?' editable-hidden':'').'"'.(!@$GLOBALS['content']['url-site-web']?' aria-hidden="true"':'').'><i class="fa fa-fw fa-globe" aria-hidden="true"></i> '.(@$GLOBALS['content']['url-site-web']?'<a href="'.@$GLOBALS['content']['url-site-web'].'" target="_blank">'.__('Website').'</a>':'').'';

						input('url-site-web', array('type' => 'hidden'));

					  	echo '</li><li class="pbn'.(!@$GLOBALS['content']['telephone']?' editable-hidden':'').'"'.(!@$GLOBALS['content']['telephone']?' aria-hidden="true"':'').'><details class="pts" aria-live="polite"><summary class="tel color pointer tdu bold  inbl" data-encode="'.@$GLOBALS['content']['telephone'].'"><i class="fa fa-fw fa-phone" aria-hidden="true"></i>'.__('Telephone').'</summary>'.(@$GLOBALS['content']['telephone']?'<p class="inline pls bold"></p>':'').'</details>';

					  	input('telephone', array('type' => 'hidden', 'class' => 'encode'));
		
					  	echo '</li><li class="pbn'.(!@$GLOBALS['content']['mail-contact']?' editable-hidden':'').'"'.(!@$GLOBALS['content']['mail-contact']?' aria-hidden="true"':'').'><details class="pts" aria-live="polite"><summary class="tel color pointer tdu bold inbl" data-encode="'.@$GLOBALS['content']['mail-contact'].'"><i class="fa fa-fw fa-mail-alt" aria-hidden="true"></i>'.__('Email').'</summary>'.(@$GLOBALS['content']['mail-contact']?'<p class="inline pls bold"></p>':'').'</details>';

						input('mail-contact', array('type' => 'hidden', 'class' => 'encode'));

						echo '</li><li class="bold pts pbn'.(!@$GLOBALS['content']['adresse']?' editable-hidden':'').'"'.(!@$GLOBALS['content']['adresse']?' aria-hidden="true"':'').'><i class="fa fa-fw fa-location" aria-hidden="true"></i>'.__('Address');

						txt('adresse', array('class'=>'plt mbt'));//,'tag'=>'p'

					echo '</li></ul>';
				}

				// Description : s'affiche sur la liste
				if($res['tpl']!='publication')// Affichage dans les actu, agenda, annuaire
					txt('description', array('class'=>'mbn'));//,'tag'=>'p'
				?>

			</div>

		</div>
    
		<!-- Contenu de l'article -->
		<article class="clear ptl">

			<?php
			txt('texte', array('dir' => $dir));

			// Téléchargement
			if($res['tpl'] == 'publication')
			{
				?><div class="highlight<?=(!@$GLOBALS['content']['telechargement']?' editable-hidden':'');?>">
					<h2 class="mtn"><?php _e("To download");?></h2><?php
					txt('telechargement', array('dir' => $dir));
				?></div><?php
			}
			?>
					
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
		$(event.target).next('p').html(atob($(event.target).parent().next(".encode").val()));
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
		$("#aaaa-mm-jj-publication").datepicker();
		$("#aaaa-mm-jj").datepicker();
		$("#aaaa-mm-jj-fin").datepicker();
	});
});
</script>

<!-- Actualité à la une -->
<? if($res['type'] == 'article') include("theme/".$GLOBALS['theme']."/admin/alaune.php");?>