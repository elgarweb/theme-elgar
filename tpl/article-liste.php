<?php  if(!$GLOBALS['domain']) exit; ?>

<section class="mw1180p mod center mbl plm prm">

	<?php 
	h1('title');
	
	txt('texte-intro', 'mw1180p mod center ptm plm prm');
	?>
	
</section>

<section class="mw1044p mod center plm prm">

	<div class="grid-3 space-xl">
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Navigation par page
		$num_pp = 18;

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
			".$tt.".zone = 'actualites' AND
			".$tt.".encode = '".$tag."'
		)";


		// Pour le tri par date pour les events
		if($res['url']=='agenda')
		$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";


		$sql.=" WHERE ".$tc.".type='".($res['url']=='agenda'?"event":"article")."' AND ".$tc.".lang='".$lang."' ".$sql_state;
		

		// Si event on tri par date de l'evenement
		if($res['url']=='agenda') $sql.=" ORDER BY event.cle DESC";
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

	<div class="tc">

		<?php
		page($num_total, $page);
		?>

	</div>

</section>

<script>
$(function()
{
	// Met le lien sur zone la box et supprime le lien sur le h2
	$(".link article").wrapInner(function() {
		return "<a href='"+ $("a", this).attr("href") +"'"+ ($(this).attr("class")?" class='"+ $(this).attr("class") +"'":"")+ ($(this).attr("title") ? " title='"+ $(this).attr("title") +"'":"") +" />";
	}).children(0).unwrap();
	//$(".link article").contents().unwrap();

	// Mode admin
	edit.push(function() {
		// Supprime l'action de click sur le lien
		$(".link a").on("click", function(event) { event.preventDefault(); });
	});
});
</script>