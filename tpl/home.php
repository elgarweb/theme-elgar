<?php if(!$GLOBALS['domain']) exit;?>


<!-- ZONE ALERTE -->
<?php
$alert_view = false;

if(
	isset($GLOBALS['content']['alerte-texte']) and
	date('Y-m-d') >= @$GLOBALS['content']['alert-date-debut'] and
	date('Y-m-d') <= @$GLOBALS['content']['alert-date-fin']
)
	$alert_view = true;

if(!$alert_view){?>
	<button class="editable-hidden tc w100" onclick="$('#alert').slideToggle();">Éditer l'alerte <i class="fa fa-attention grey" aria-hidden="true"></i></button>
<?php }?>

<section id="alert" class="bg-color-3<?=(!$alert_view?' none':'');?>">

	<div class="editable-hidden pts tc">
		<label for="alert-date-debut">Date de début d'affichage de l'alerte</label> <?input('alert-date-debut', array('type' => 'date'))?>
	 	<label for="alert-date-fin">Date de fin</label> <?input('alert-date-fin', array('type' => 'date'))?>
	</div>

	<article class="mw960p mod flex wrap space-l aic jcc center pam">
					
		<?php media('alerte-img', array('size' => '500', 'lazy' => true)); ?>
		
		<div>
			<?php txt('alerte-texte', 'mw600p'); ?>
		</div>	
		
	</article>

</section>



<!-- ENCART INTRO -->
<section id="encart">

	<div class="mw960p center pbl pll prl">
	
		<?php
		// Si on demande le h1 au dessu de l'image et texte
		if(@$GLOBALS['home-titre-place'] != 'in' and @$GLOBALS['home-titre-place'] != 'noh1')
			h1('titre', 'picto');
		?>

		<article class="<?=((isset($GLOBALS['content']['intro-texte']) or isset($GLOBALS['content']['intro-visuel'])) ? 'row' : 'editable-hidden'); ?>">
			
			<div class="col brd">
				<?php
				// Taille de l'image par défaut
				if(!isset($GLOBALS['intro-visuel-size'])) $GLOBALS['intro-visuel-size'] = '550x260';
				if(!isset($GLOBALS['intro-visuel-crop'])) $GLOBALS['intro-visuel-crop'] = false;

				// Grand visuel
				media('intro-visuel', array('size' => $GLOBALS['intro-visuel-size'], 'crop' => $GLOBALS['intro-visuel-crop']));
				?>
			</div>
			
			<div class="col bg-color-2">

				<?php 
				// Si on demande le h1 dans le bloc texte
				if(@$GLOBALS['home-titre-place'] == 'in') h1('titre', 'picto');

				txt('intro-texte', 'pam pbt');
				?>

				<div class="plus mlm mrm <?=(isset($GLOBALS['content']['intro-lien'])?'':' editable-hidden')?>">
					<a <?href('intro-lien');?>><?php span('intro-texte-lien', ''); ?></a>
				</div>
			</div>
			
		</article>

	</div>

</section>



<!-- EN 1 CLIC -->
<section id="home-enunclic" class="bg-color ptl pbs">

	<div class="mw960p center">

		<?php h2('titre-clic', 'color-alt mbn'); ?>

		<div>

			<!-- .module pour bien identifier que ce sont les elements à dupliquer et a sauvegardé -->
			<ul id="enunclic" class="module unstyled grid space-l jic tc pan ptl mtn">
			<?php
			// nom du module "enunclic" = id du module, et au début des id des txt() media() ...
			$module = module("enunclic");
			foreach($module as $key => $val)
			{
				?>
				<li>

					<a <?php href("enunclic-lien-".$key); ?> class="white">

						<div>
							<?php media("enunclic-img-".$key, array('size' => '85x85', 'lazy' => true, 'class' => 'brd-alt brd-rad-100'));?>
						</div>
						
						<div class="ptm">
							<?php txt("enunclic-texte-".$key);?>						
						</div>

					</a>

				</li>
				<?php
			}
			?>
			</ul>

		</div>

	</div>

</section>



<!-- ACTUALITÉS -->
<?php

// ACTUALITÉ À LA UNE
$sql_alaune="
SELECT ".$tc.".* FROM ".$tc."
JOIN 
	".$tm." ON ".$tc.".id = ".$tm.".id AND ".$tm.".type = 'alaune' AND ".$tm.".cle = '".$lang."' 
WHERE 
	".$tc.".type = 'article' AND ".$tc.".lang = '".$lang."' AND date_insert <= NOW() AND state = 'active'
ORDER BY ".$tc.".date_insert DESC
LIMIT 1";

//echo $sql_alaune."<br>";
$sel_alaune = $connect->query($sql_alaune);
$res_alaune = $sel_alaune->fetch_assoc();
$num_alaune = $sel_alaune->num_rows;


// LES 3 ACTUS
$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;
$sql.=" WHERE (".$tc.".type='article' OR ".$tc.".type='article-intramuros')";
// Supprime l'article à la une de la liste des 3 actus
if(isset($res_alaune['id'])) $sql.=" AND id!='".(int)$res_alaune['id']."'";
$sql.=" AND ".$tc.".lang='".$lang."' AND date_insert <= NOW() AND state='active' ORDER BY ".$tc.".date_insert DESC
LIMIT 3";

$sel_article = $connect->query($sql);
$num_article = $sel_article->num_rows;

?>
<section id="home-actualites" class="ptl pbl <?=(($num_alaune>0 or $num_article>0)?'':' editable-hidden')?>">

	<div class="mw960p center">
		
		<?php h2('titre-actus', 'picto pbm'); ?>
		
		<?php
		if(isset($res_alaune['id']))
		{
			$res_alaune['content'] = json_decode($res_alaune['content'], true);

			// var_dump($res_alaune['id']);
			?>
			<!-- Actualité à la une -->
			<article id="actualaune">

				<div class="relative flex aic brd3 mbl">

					<!-- Image -->
					<?php if(isset($res_alaune['content']['visuel'])){?>
					<figure class="brd-right">
						
						<div class="nor" data-bg="<?=$res_alaune['content']['visuel'];?>" data-lazy="bg">
						</div>

					</figure>
					<?php }?>

					<div class="ptm pbl plm prm">

						<!-- Titre -->
						<h3 class="mtn bold">
							<a href="<?= make_url($res_alaune['url'], array("domaine" => true)); ?>" class="tdn"><?= $res_alaune['title']; ?></a>
						</h3>
						
						<!-- Extrait texte -->
						<p class="pbm">
							<?php if(isset($res_alaune['content']['description'])) echo word_cut($res_alaune['content']['description'], '150', '...');?>
						</p>

						<!-- Lien Lire la suite -->
						<div class="plus">
							<a class="absolute bot15 right15" href="<?=make_url($res_alaune['url'], array("domaine" => true));?>" aria-label="<?php echo __("Read more")." ". $res_alaune['title'];?> "><?php _e("Read more")?></a>
						</div>
					</div>
				</div>

			</article>
		<?php
		}
		?>


		<!-- Dernières actualités -->
		<div class="clear">

			<div class="blocks grid-3 space-xl">
				
				<?php 
				while($res_article = $sel_article->fetch_assoc())
				{
					$content_article = json_decode($res_article['content'], true);

					block(@$content_article['visuel'], $res_article['url'], $res_article['title'], @$content_article['description'], @$content_article['aaaa-mm-jj']);
				}
				?>

			</div>

		</div>
			
		<!-- Bouton vers toutes les actualités -->
		<div class="lien-bt ptl">
			<a href="<?=make_url(__('news'), array("domaine" => true))?>" class="bt">
				<?php span('txt-lien-actus', array('default' => __("Read all the news"))); ?>
			</a>
		</div>
		
	</div>

</section>



<!-- AGENDA -->
<?php
// Construction de la requete
$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

// Tous les évènements
//$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

// Que les évènements a venir
$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tc.".id AND event_deb.type='aaaa-mm-jj' AND event_deb.cle >= '".date("Y-m-d")."'";

$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' AND date_insert <= NOW() AND state='active'";

// Que les évènements a venir
$sql.=" ORDER BY event_deb.cle ASC";

$sql.=" LIMIT 3";

//echo "<b>A venir :</b> ".$sql;

$sel_event = $connect->query($sql);
$num_event = $sel_event->num_rows;


// Si peut d'évènement à venir, on prend aussi les en cours
if($num_event < 3) 
{
	// Construction de la requete
	$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

	// Que les évènements en cours
	$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tc.".id AND event_deb.type='aaaa-mm-jj'";
	$sql.=" LEFT JOIN ".$tm." AS event_fin ON event_fin.id=".$tc.".id AND event_fin.type='aaaa-mm-jj-fin'";

	$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' AND date_insert <= NOW() AND state='active'";

	// Que les évènements en cours
	$sql.=" AND (event_fin.cle >= '".date("Y-m-d")."' OR event_deb.cle >= '".date("Y-m-d")."')";

	// Que les évènements en cours
	$sql.=" ORDER BY event_deb.cle ASC, event_fin.cle ASC";

	$sql.=" LIMIT 3";

	//echo "<br><b>En cours :</b> ".$sql;

	$sel_event = $connect->query($sql);
	$num_event = $sel_event->num_rows;
}

?>
<section id="home-agenda" class="bg-color-3 ptl pbl<?=($num_event>0?'':' editable-hidden')?>">

	<article class="mw960p center">

		<?php h2('titre-events', 'picto')?>

		<div class="blocks grid-3 space-xl">
			
			<?php 
			while($res_event = $sel_event->fetch_assoc())
			{
				$content_event = json_decode($res_event['content'], true);

				block(@$content_event['visuel'], $res_event['url'], $res_event['title'], @$content_event['description'], @$content_event['aaaa-mm-jj'], @$content_event['aaaa-mm-jj-fin']);
			}
			?>

		</div>

		<!-- Bouton vers tous les événements -->
		<div class="lien-bt ptl">
			<a href="<?=make_url(__('agenda'), array("domaine" => true))?>" class="bt">
				<?php span('txt-lien-agenda', array('default' => __("See all the events"))); ?>
			</a>
		</div>		

	</article>

</section>
<!-- Fin Event -->