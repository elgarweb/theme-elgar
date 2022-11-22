<?php
if(!$GLOBALS['domain']) exit;

$url_back = encode($res['url']);
?>

<section>

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<div class="<?= $res['url'] == 'agenda' ? 'bg-color-3' : ''; ?>">

		<div class="mw960p mod center">

			<?php
			h1('title', 'picto');

			// Liste les tags pour filtrer la page
			$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC");
			//echo $connect->error;

			if($sel_tag_list->num_rows > 0) {
			?>
				<nav role="navigation" aria-label="<?php _e("Filter by")?>" class="flex wrap space jcc tc ptl pbm">
					<ul class="unstyled pln">
						<?php 
						while($res_tag_list = $sel_tag_list->fetch_assoc()) {
							echo'<li class="inbl prs"><a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="bt-tag'.($tag==$res_tag_list['encode']?' selected':'').'">'.$res_tag_list['name'].'</a></li>';
						}
						?>
					</ul>
				</nav>
			<?}?>
			
			<?php txt('description', array('class'=>'ptm'));//tc ?>

		</div>

	</div>
	
</section>

<section class="<?= $res['url'] == 'agenda' ? 'bg-color-3' : ''; ?>">

	<div class="blocks mw960p center grid-3 space-xl">
		
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
			".$tt.".lang = '".$lang."' AND
			".$tt.".encode = '".$tag."'
		)";

		// Pour le tri par date pour les events
		if($res['url']=='agenda'){
			//$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";
			$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tc.".id AND event_deb.type='aaaa-mm-jj'";
			$sql.=" LEFT JOIN ".$tm." AS event_fin ON event_fin.id=".$tc.".id AND event_fin.type='aaaa-mm-jj-fin'";
		}

		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state."";

		// Type de contenu event ou article
		if($res['url']=='agenda') {
			$sql.=" AND (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft')";

			// Que les évènements >= date de début OU <= date de fin
			$sql.=" AND (event_fin.cle >= '".date("Y-m-d")."' OR event_deb.cle >= '".date("Y-m-d")."')";
		}
		else
			$sql.=" AND ".$tc.".type='article'";

		// Si event on tri par date de l'evenement
		if($res['url']=='agenda') 
			$sql.=" ORDER BY event_deb.cle ASC, event_fin.cle ASC";
			//$sql.=" ORDER BY event.cle ASC";
		else 
			$sql.=" ORDER BY ".$tc.".date_insert DESC";

		$sql.=" LIMIT ".$start.", ".$num_pp;

		//echo $sql;
		$sel_fiche = $connect->query($sql);

		$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

		$num_fiche = 1;

		while($res_fiche = $sel_fiche->fetch_assoc())
		{
			// Affichage du message pour dire si l'article est invisible ou pas
			if($res_fiche['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
			else $state = "";

			$content_fiche = json_decode($res_fiche['content'], true);

			block(@$content_fiche['visuel'], $res_fiche['url'], $res_fiche['title'], @$content_fiche['description'], @$content_fiche['aaaa-mm-jj'], @$content_fiche['aaaa-mm-jj-fin'], 'tags');

			$num_fiche++;
		}
		?>

	</div>

	<div class="tc ptm mtl pbl">

		<?php
		page($num_total, $page, array('aria-label'=>__("Browsing by page")));
		?>

	</div>

</section>