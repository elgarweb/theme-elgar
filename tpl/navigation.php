<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p center relative">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php');?>

	<div class="bg-color-3 mod pbl">

		<?php h1('title', '');?>

		<?php txt('texte', '');?>

		<ul class="navigation">
	 	<?php
		// Récupération du filtre dans l'url pour rechercher les pages connexes

		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-page']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Navigation par page
		$num_pp = 20;

		if(isset($GLOBALS['filter']['page'])) $page = (int)$GLOBALS['filter']['page']; else $page = 1;

		$start = ($page * $num_pp) - $num_pp;


		// Version avec les tags
		/*$sql="SELECT ".$tc.".url, ".$tc.".title, ".$tc.".state FROM ".$tc;//SQL_CALC_FOUND_ROWS ".$tc.".id, 
		$sql.=" RIGHT JOIN ".$tt."
		ON
		(
			".$tt.".id = ".$tc.".id AND
			".$tt.".zone = 'navigation' AND
			".$tt.".encode = '".$tag."'
		)";
		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state."";
		$sql.=" ORDER BY ".$tc.".date_insert DESC";
		$sql.=" LIMIT ".$start.", ".$num_pp;*/


		// Version avec select dans le fil d'ariane
		$sql ="SELECT ".$tc.".url, ".$tc.".title, ".$tc.".state FROM ".$tc;
		$sql.=" JOIN ".$tm."
		ON
		(
			".$tm.".id = ".$tc.".id AND
			".$tm.".type='navigation' AND
			".$tm.".cle='".$res['url']."'
		)";
		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state."";
		//$sql.=" ORDER BY ".$tm.".ordre ASC";
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
