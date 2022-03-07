<!--
	@todo Stéphanie :
	- Affichage tag sur chaque actu
-->

<?php  if(!$GLOBALS['domain']) exit; ?>

<section class="mw960p mod center pbm">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	
	<?php h1('title', 'vague mtn pbm'); ?>

	<nav role="navigation" class="flex space-xl jcc tc ptl pbl">
		<?php 
		// Liste les tags pour filtrer la page
		$i = 1;
		$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' GROUP BY encode, name ORDER BY encode ASC");
		//echo $connect->error;

		while($res_tag_list = $sel_tag_list->fetch_assoc()) {
			echo'<a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="inbl tc bg-color-alt brd-rad tdn pts pbs plm prm">'.$res_tag_list['name'].'</a>';
			$i++;
		}
		?>
	</nav>
	
	<?php txt('description', 'tc ptm pbm'); ?>
	
</section>

<section class="mw960p mod center">

	<div class="grid-3 space-xl">
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
		else $sql_state = "";

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

		// Pour le tri par date pour les events
		if($res['url']=='agenda')
			$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state." AND";

		// Type de contenu event ou article
		if($res['url']=='agenda') 
			$sql.=" (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft')";
		else
			$sql.=" ".$tc.".type='article'";

		// Si event on tri par date de l'evenement
		if($res['url']=='agenda') $sql.=" ORDER BY event.cle ASC";
		else $sql.=" ORDER BY ".$tc.".date_insert DESC";

		// On ressort les 3 premiers articles à afficher avec les images
		$sql_prems = $sql;

		$sql_prems.=" LIMIT 3";

		//echo $sql;
		$sel_fiche = $connect->query($sql_prems);

		// Navigation par page
		if(isset($GLOBALS['filter']['page'])) $page = (int)$GLOBALS['filter']['page']; else $page = 1;
		
		if($page == 1)
		$num_pp = 3;
		else
		$num_pp = 6;

		if($page == 1)
		$start = ($page * $num_pp) - $num_pp + 3;
		else
		$start = ($page * $num_pp) - $num_pp;

		// Nombre total de fiche
		$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];

		// On n'affiche les 3 premières actus avec images que sur la première page
		if($page == 1)
		while($res_fiche = $sel_fiche->fetch_assoc())
		{
			// Affichage du message pour dire si l'article est invisible ou pas
			if($res_fiche['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
			else $state = "";

			$content_fiche = json_decode($res_fiche['content'], true);

			block(@$content_fiche['visuel'], $res_fiche['url'], $res_fiche['title'], @$content_fiche['description'], @$content_fiche['aaaa-mm-jj'], 'tags');
		}
		?>

	</div>
		<div class="grid-3 space-xl ptl">
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Construction de la requete
		$sql_suite = $sql;
		$sql_suite.=" LIMIT ".$start.", ".$num_pp;

		//echo $sql;
		$sel_fiche_suite = $connect->query($sql_suite);

		while($res_fiche_suite = $sel_fiche_suite->fetch_assoc())
		{
			// Affichage du message pour dire si l'article est invisible ou pas
			if($res_fiche_suite['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
			else $state = "";

			$content_fiche_suite = json_decode($res_fiche_suite['content'], true);

			@$content_fiche['visuel'] = '';

			block(@$content_fiche['visuel'], $res_fiche_suite['url'], $res_fiche_suite['title'], @$content_fiche_suite['description'], @$content_fiche_suite['aaaa-mm-jj'], 'tags');
		}
		?>

	</div>

	<div class="tc ptm mtl pbl">

		<?php
		page($num_total, $page);
		?>

	</div>

</section>