<?php if(!$GLOBALS['domain']) exit;?>

<!-- ZONE ALERTE -->
<section class="<?=(isset($GLOBALS['content']['alerte-texte']) ? 'bg-color-grey' : 'editable-hidden'); ?>">
	
	<div class="mw960p mod flex wrap space-l aic jcc center pam">
		
		<article class="">
			<?php media('alerte-img', array('size' => '300', 'lazy' => true)); ?>
		</article>
		
		<article class="mw600p">
			<?php txt('alerte-texte', 'bold bigger'); ?>
		</article>
		
	</div>

</section>

<!-- ENCART INTRO -->
<section class="mw960p mod center ptm pbl pll prl">
	
	<?php h1('titre', 'picto mtn pbm'); ?>

	<div class="<?=(isset($GLOBALS['content']['intro-texte']) ? 'row' : 'editable-hidden'); ?>">
		
		<article class="col brd-rad-top-left">
			<?php media('intro-visuel', array('size' => '550', 'lazy' => true)); ?>
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

	<div class="mw960p mod center ptl pbl">

		<?php h2('titre-clic', 'color-alt'); ?>

		<div>

			<!-- .module pour bien identifier que ce sont les elements à dupliquer et a sauvegardé -->
			<ul id="enunclic" class="module unstyled grid space-l jic tc pan ptm">
			<?php
			// nom du module "enunclic" = id du module, et au début des id des txt() media() ...
			$module = module("enunclic");
			foreach($module as $key => $val)
			{
				?>
				<li class="mw150p">

					<a <?php href("enunclic-lien-".$key); ?> class="white">

						<div>
							<?php media("enunclic-img-".$key, array('size' => '85x85', 'lazy' => true, 'class' => 'brd-alt brd-rad-100'));?>
						</div>
						
						<div class="ptm">
							<?php txt("enunclic-texte-".$key, array("tag" => "span"));?>
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
<section class="mw960p mod center ptl pbl">
	
	<?php h2('titre-actus', 'picto pbm'); ?>
	
	<!-- Actualité à la une -->
	<article class="">
		<?php
		$sql_alaune="
		SELECT ".$tc.".* FROM ".$tc."
		JOIN 
			".$tm." ON ".$tc.".id = ".$tm.".id AND ".$tm.".type = 'article' 
		WHERE 
			".$tc.".type = 'article' AND ".$tc.".lang = '".$lang."' AND state = 'active'
		ORDER BY ".$tc.".date_insert DESC
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
		{
			// var_dump($article['id']);
			?>

			<div class="<?=(isset($article['title']) ? 'flex wrap space-l aic center pam brd brd-rad-bot-right mbl' : 'none'); ?>">

				<!-- Image -->
				<figure class="">

					<div class="cover" data-bg="<?= $article['content']['visuel']; ?>" data-lazy="bg" style="width: 300px; height: 225px;">
					</div>

				</figure>

				<div class="mw600p">

					<!-- Titre -->
					<h3 class="tl mtn">
						<a href="<?= make_url($article['title'], array("domaine" => true)); ?>" class="tdn"><?= $article['title']; ?></a>
					</h3>
					
					<!-- Extrait texte -->
					<div class="pbm">
						<?php 
					if(isset($article['content']['texte'])) echo word_cut($article['content']['texte'], '100', '...');
					?>
				
					<!-- Lien Lire la suite -->
					<a href="<?=make_url($article['title'], array("domaine" => true));?>"><span class=""><?php _e("Read more")?></span></a>
						
				</div>
			</div>
		
		<?php
		}
		?>
	</article>

	<!-- Dernières actualités -->
	<article class="clear">

		<div class="grid-3 space-xl">
			
			<?php 
			// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
			if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
			else $sql_state = "";

			// Construction de la requete
			$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

			$sql.=" WHERE ".$tc.".type='article'";
			if(isset($articles)){
			$sql.=" AND id!='".$article['id']."'";
			}
			$sql.=" AND ".$tc.".lang='".$lang."' ".$sql_state." ORDER BY ".$tc.".date_insert DESC
			LIMIT 3";

			$sel_article = $connect->query($sql);

			while($res_article = $sel_article->fetch_assoc())
			{
				// Affichage du message pour dire si l'article est invisible ou pas
				if($res_article['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
				else $state = "";

				$content_article = json_decode($res_article['content'], true);

				block(@$content_article['visuel'], $res_article['url'], $res_article['title'], @$content_article['description'], @$content_article['aaaa-mm-jj']);
			}
			?>

		</div>
	</article>
		
	<!-- Bouton vers toutes les actualités -->
	<div class="tc ptl">
		<a href="<?=make_url(__('news'), array("domaine" => true))?>" class="bt">
			<?= __("Read all the news"); ?>
		</a>
	</div>

</section>


<!-- AGENDA -->
<section class="bg-color-grey ptl pbl">

	<div class="mw960p mod center">

		<?php h2('titre-events', 'picto')?>

		<div class="grid-3 space-xl">
			
			<?php 
			// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
			if(!@$_SESSION['auth']['edit-event']) $sql_state = "AND state='active'";
			else $sql_state = "";

			// Construction de la requete
			$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

			// Pour le tri par date pour les events
			$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

			$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' ".$sql_state;
			
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

				block(@$content_event['visuel'], $res_event['url'], $res_event['title'], @$content_event['description'], @$content_event['aaaa-mm-jj']);
			}
			?>

		</div>

		<!-- Bouton vers tous les événements -->
		<div class="tc ptl">
			<a href="<?=make_url(__('agenda'), array("domaine" => true))?>" class="bt">
				<?= __("See all the events"); ?>
			</a>
		</div>
		

	</div>


</section>
<!-- Fin Event -->




