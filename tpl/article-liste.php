<!--
	@todo Stéphanie :
	- Affichage tag sur chaque actu
-->

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
			echo'<a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="color tdn dash prs">'.$res_tag_list['name'].'</a>';
			$i++;
		}
		?>
	</nav>
	
	<?php txt('description'); ?>
	
</section>

<section class="mw1044p mod center plm prm">

	<div class="grid-3 space-xl">
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Navigation par page
		$num_pp = 6;

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

			block(@$content_fiche['visuel'], $res_fiche['url'], $res_fiche['title'], @$content_fiche['texte-chapo'], @$content_fiche['aaaa-mm-jj']);
		}
		?>

	</div>

	<div class="tc mtl">

		<?php
		page($num_total, $page);
		?>

	</div>

</section>