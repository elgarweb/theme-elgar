<?php
if(!$GLOBALS['domain']) exit;
//@todo: stylisé la liste avec des fleches vertes
//@todo voire pour surligner dans le menu le bon éléments en fonction de la ou on se trouve (page tag + page interne qui passe par un tag)
?>

<section class="mw960p center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php');?>

	<div class="bg-color-grey mod plm pbl">

		<?php h1('title', 'tl');?>

		<ul class="">
	 	<?php
		// Récupération du filtre dans l'url pour rechercher les pages connexes

		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Navigation par page
		$num_pp = 50;

		if(isset($GLOBALS['filter']['page'])) $page = (int)$GLOBALS['filter']['page']; else $page = 1;

		$start = ($page * $num_pp) - $num_pp;


		// Construction de la requete
		$sql="SELECT ".$tc.".url, ".$tc.".title, ".$tc.".state FROM ".$tc;//SQL_CALC_FOUND_ROWS ".$tc.".id, 

		$sql.=" RIGHT JOIN ".$tt."
		ON
		(
			".$tt.".id = ".$tc.".id AND
			".$tt.".zone = 'navigation' AND
			".$tt.".encode = '".$tag."'
		)";

		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state."";

		$sql.=" ORDER BY ".$tc.".date_insert DESC";

		$sql.=" LIMIT ".$start.", ".$num_pp;

		//echo $sql;
		$sel_nav = $connect->query($sql);

		//$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

		while($res_nav = $sel_nav->fetch_assoc())
		{
			// Page invisible ou pas
			if($res_nav['state'] != "active") $state=" <i class='fa fa-eye-off' title='Désactivé'></i>";
			else $state="";

			echo'<li><a href="'.make_url($res_nav['url'], array("domaine" => true)).'">'.$res_nav['title'].$state.'</a></li>';
		}

		//page($num_total, $page);
		?>
		</ul>

	</div>

</section>
