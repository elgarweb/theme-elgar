<?php  if(!$GLOBALS['domain']) exit; ?>

<section class="mw1044p mod center mbl plm prm">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	
	<?php h1('title'); ?>

	<nav role="navigation" class="mts tc italic">
		<?php 
		// Liste les tags pour filtrer la page
		$i = 1;
		$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' GROUP BY encode, name ORDER BY encode ASC");
		//echo $connect->error;

		while($res_tag_list = $sel_tag_list->fetch_assoc()) {
			echo'<a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="color tdn dash">'.$res_tag_list['name'].'</a>';
			$i++;
		}
		?>
	</nav>
	
	<?php txt('description'); ?>
	
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
			".$tt.".zone = 'annuaire' AND
			".$tt.".encode = '".$tag."'
		)";

		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state." AND";

		// Type de contenu event ou article
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


			<div class="brd-top-alt brd brd-rad-bot-right pbl">

				<article class="flex">

					<!-- Image -->
					<figure>

						<div class="cover" data-bg="<?=(isset(parse_url($content_fiche['visuel'])['scheme'])?'':$GLOBALS['home']).$content_fiche['visuel']; ?>" data-lazy="bg" style="width: 100%; height: 225px;">
						</div>

					</figure>

					<!-- Titre -->
					<div class=" pam brd-top">
		
						<h2 class="h3-like tl">
							<a href="<?=make_url($res_fiche['url'], array("domaine" => true)); ?>" class="tdn"><?=$res_fiche['title']?>
							</a>
						</h2>

						<!-- Extrait texte -->
						<div class="ptm">
							<?php 
							if(isset($content_fiche['texte-chapo'])) echo word_cut($content_fiche['texte-chapo'], '100', '...');
							?>
						</div>

						<!-- Lien vers détail -->
						<div class="">

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