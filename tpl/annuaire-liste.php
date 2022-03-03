<!--
	@todo Stéphanie :
	- Affichage tag sur chaque actu
-->
<?php  if(!$GLOBALS['domain']) exit; ?>

<section class="mw1044p mod center mbl plm prm">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	
	<?php h1('title'); ?>

	<nav role="navigation" class="tc ptm">
		<?php 
		// Liste les tags pour filtrer la page
		$i = 1;
		$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' GROUP BY encode, name ORDER BY encode ASC");
		//echo $connect->error;

		while($res_tag_list = $sel_tag_list->fetch_assoc()) {
			echo'<a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="color tdn dash prs">'.$res_tag_list['name'].'</a>';
			$i++;
		}
		?>
	</nav>
	
	<?php txt('description', 'tc ptl'); ?>
	
</section>

<section class="mw1044p mod center plm prm">

	<div>
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-annuaire']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Navigation par page
		$num_pp = 3;

		if(isset($GLOBALS['filter']['page'])) $page = (int)$GLOBALS['filter']['page']; else $page = 1;

		$start = ($page * $num_pp) - $num_pp;

		// Construction de la requete
		$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

		// Si filtre tag
		if(isset($tag))
		$sql.=" RIGHT JOIN ".$tt."
		ON
		(
			".$tt.".id = ".$tc.".id AND
			".$tt.".zone = '".$res['url']."' AND
			".$tt.".encode = '".$tag."'
		)";

		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state." AND";

		$sql.=" ".$tc.".type='annuaire'";

		$sql.=" LIMIT ".$start.", ".$num_pp;

		//echo $sql;
		$sel_fiche = $connect->query($sql);

		$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

		while($res_fiche = $sel_fiche->fetch_assoc())
		{
			// Affichage du message pour dire si l'article est invisible ou pas
			if($res_fiche['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
			else $state = "";

			$content_fiche = json_decode($res_fiche['content'], true);
			
			?>


			<div class="brd brd-rad-bot-right mbm">

				<article class="flex">

					<!-- Image -->
					<div class="w30 asc tc">
						<img src="<?=(isset(parse_url($content_fiche['visuel'])['scheme'])?'':$GLOBALS['home']).$content_fiche['visuel']; ?>" class="pts pbs" alt="">
					</div>
					<!-- <figure class="w30">

						<div class="nor" data-bg="<?=(isset(parse_url($content_fiche['visuel'])['scheme'])?'':$GLOBALS['home']).$content_fiche['visuel']; ?>" data-lazy="bg" style="width: 100%; height: 225px;">
						</div>

					</figure> -->

					<div class="w70 pam brd-left">
						
						<!-- <div><?= $content_fiche['name']; ?></div> -->

						<!-- Titre -->
						<h2 class="h3-like tl">
							<a href="<?=make_url($res_fiche['url'], array("domaine" => true)); ?>" class="tdn"><?=$res_fiche['title']?>
							</a>
						</h2>

						<!-- Extrait texte -->
						<p class="mbn"><?= $content_fiche['texte-coordonnees-intro']; ?></p>

						<!-- Lien vers détail -->
						<div class="fr">

							<a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>"><span class=""><?php _e("See the sheet")?></span></a>
							
						</div>

					</div>

				</article>

			</div>


		<?php	
		}
		?>

	</div>

	<div class="tc mtl">

		<?php
		page($num_total, $page);
		?>

	</div>

</section>