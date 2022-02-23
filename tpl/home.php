<?php if(!$GLOBALS['domain']) exit;?>

<!--
@todo Stéphanie :
- en 1 clic : modules ?
-->

<!-- ZONE ALERTE -->
<section class="<?=(isset($GLOBALS['content']['alerte-texte']) ? 'bg-color-grey' : 'editable-hidden'); ?>">
	
	<div class="mw1044p mod flex wrap space-l aic jcc center ptl pbl pll prl">
		
		<article class="">
			<?php media('alerte-img', array('size' => '300', 'lazy' => true)); ?>
		</article>
		
		<article class="mw600p">
			<?php txt('alerte-texte', 'bold bigger'); ?>
		</article>
		
	</div>

</section>

<!-- ENCART INTRO -->
<section class="mw1044p mod center ptm pbl pll prl">
	
	<?php h1('titre', 'vague mtn pbm'); ?>

	<div class="<?=(isset($GLOBALS['content']['intro-texte']) ? 'row' : 'editable-hidden'); ?>">
		
		<article class="col brd-rad-top-left">
			<?php media('intro-img', array('size' => '550', 'lazy' => true)); ?>
		</article>
		
		<article class="col bg-color-alt brd-rad-bot-right">
			<?php txt('intro-texte', 'pal'); ?>
		</article>
		
	</div>
	<!-- <div class="grid aic bg-color-alt">
		
		<article class="brd-r-tl">
			<?php media('intro-img', '550'); ?>
		</article>
		
		<article class="brd-r-br">
			<?php txt('intro-texte', 'pal'); ?>
		</article>
		
	</div> -->
</section>


<!-- EN 1 CLIC -->
<section class="bg-color">

	<div class="mw1044p mod center ptl pbl pls prs">

		<?php h2('titre-clic'); ?>

		<div>

			<!-- .module pour bien identifier que ce sont les elements à dupliquer et a sauvegardé -->
			<ul id="enunclic" class="module unstyled grid space-l jic tc pan ptm">
			<?php
			// nom du module "enunclic" = id du module, et au début des id des txt() media() ...
			$module = module("enunclic");
			foreach($module as $key => $val)
			{
				?>
				<li class="mw140p">

					<div>
						<?php media("enunclic-img-".$key, array('size' => '85x85', 'lazy' => true, 'class' => 'brd-rad-100'));?>
					</div>
					
					<div class="ptm">
						<?php txt("enunclic-text-".$key, array("tag" => "span"));?>
					</div>

				</li>
				<?php
			}
			?>
			</ul>

		</div>


	</div>


</section>


<!-- ACTUALITÉS -->
<section class="mw1044p mod center ptl pbl pls prs">
	
	<?php h2('titre-actus', 'vague'); ?>
	
	<!-- Actualité à la une -->
	<article>
		<?php
		$sql_alaune="
		SELECT ".$tc.".* FROM ".$tc."
		JOIN 
			".$tm." ON ".$tc.".id = ".$tm.".id AND ".$tm.".type = 'article' 
		WHERE 
			".$tc.".type = 'article' AND ".$tc.".lang = '".$lang."' AND state = 'active'
		LIMIT 1";

		//echo $sql_alaune."<br>";

		$sel_alaune = $connect->query($sql_alaune);

		while($res_alaune = $sel_alaune->fetch_assoc())
		{
			$articles[$res_alaune['id']] = $res_alaune;
			$articles[$res_alaune['id']]['content'] = json_decode($res_alaune['content'], true);
		}
		
		if(isset($articles))
		foreach($articles as $key => $article)
		{?>
		<!-- Image -->
		<figure class="fl">

			<div class="cover" data-bg="<?= $content_fiche['visuel'] ?>" data-lazy="bg" style="width: 100%; height: 225px;">
			</div>

		</figure>

		<div>
			</div>
			<!-- Titre -->
			<h3 class="tl mtn">
				<a href="<?php make_url($res_fiche['title'], array("domaine" => true)) ?>" class="tdn"></a>
			</h3>
			
			<!-- Extrait texte -->
			<div class="ptm">
				<?php 
			if(isset($text)) echo word_cut($text, '100', '...');
			?>
		</div>
		
		<div class="absolute bot15 bold">
			
			<a href="<?=make_url($url_title, array("domaine" => true));?>"><span class=""><?php _e("Read more")?></span></a>
			
		</div>
		
		<?php
		}
		?>
	</article>

	<!-- Dernières actualités -->
	<article>

		<div class="grid-3 space-l">
			
			<?php 
			// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
			if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
			else $sql_state = "";

			// Construction de la requete
			$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

			$sql.=" WHERE ".$tc.".type='article' AND ".$tc.".lang='".$lang."' ".$sql_state." ORDER BY ".$tc.".date_insert DESC
			LIMIT 3";

			$sel_article = $connect->query($sql);

			while($res_article = $sel_article->fetch_assoc())
			{
				// Affichage du message pour dire si l'article est invisible ou pas
				if($res_article['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
				else $state = "";

				$content_article = json_decode($res_article['content'], true);

				block(@$content_article['visuel'], $res_article['url'], $res_article['title'], @$content_article['texte-chapo'], @$content_article['aaaa-mm-jj']);
			}
			?>

		</div>
	</article>
		
	<!-- Bouton vers toutes les actualités -->
	<div class="tc ptl">
		<a href="<?=make_url(__('news'), array("domaine" => true))?>" class="bt pas">
			<?= __("Read all the news"); ?>
		</a>
	</div>

</section>


<!-- AGENDA -->
<section class="bg-color-grey ptl pbl">

	<div class="mw1044p mod center pls prs">

		<?php h2('titre-events', 'vague')?>

		<div class="grid-3 space-l">
			
			<?php 
			// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
			if(!@$_SESSION['auth']['edit-event']) $sql_state = "AND state='active'";
			else $sql_state = "";

			// Construction de la requete
			$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

			// Pour le tri par date pour les events
			$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

			$sql.=" WHERE ".$tc.".type='event' AND ".$tc.".lang='".$lang."' ".$sql_state;
			
			// Tri par date de l'evenement
			$sql.=" ORDER BY event.cle DESC";
			$sql.=" LIMIT 3";

			$sel_event = $connect->query($sql);

			while($res_event = $sel_event->fetch_assoc())
			{
				// Affichage du message pour dire si l'article est invisible ou pas
				if($res_event['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
				else $state = "";

				$content_event = json_decode($res_event['content'], true);

				block(@$content_event['visuel'], $res_event['url'], $res_event['title'], @$content_event['texte-chapo'], @$content_event['aaaa-mm-jj']);
			}
			?>

		</div>

		<!-- Bouton vers tous les événements -->
		<div class="tc ptl">
			<a href="<?=make_url(__('agenda'), array("domaine" => true))?>" class="bt pas">
				<?= __("See all the events"); ?>
			</a>
		</div>
		

	</div>


</section>
<!-- Fin Event -->




