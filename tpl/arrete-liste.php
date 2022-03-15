<?php  if(!$GLOBALS['domain']) exit; ?>

<section class="mw960p mod center mbl">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	
	<?php h1('title', 'picto'); ?>

	<nav role="navigation" class="flex wrap space jcc tc ptl pbm">
		<?php 
		// Liste les tags pour filtrer la page
		$i = 1;
		$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' GROUP BY encode, name ORDER BY encode ASC");
		//echo $connect->error;
		
		while($res_tag_list = $sel_tag_list->fetch_assoc()) {
			echo'<a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="bt-tag'.($tag==$res_tag_list['encode']?' selected':'').'">'.$res_tag_list['name'].'</a>';
			$i++;
		}
		?>
	</nav>

	<?php txt('description'); ?>


	<form id="filtre-date-arrete" class="tc">

		<div class="mbt"><?_e("Filter by date (format DD/MM/YYYY)")?> :</div>

		<label for="start"><?_e("Start")?></label>
		<input type="date" id="start" value="<?=@$GLOBALS['filter']['start']?>" placeholder="jj-mm-aaaa" autocomplete="off">

		<label for="end"><?_e("End")?></label>
		<input type="date" id="end" value="<?=@$GLOBALS['filter']['end']?>" placeholder="jj-mm-aaaa" autocomplete="off">
		
		<button type="submit" class="bg-green pat"><?_e("Search")?></button>

	</form>
	<script>
		$("#filtre-date-arrete").on("submit", function(event) {
			event.preventDefault();

			var url = "/<?=encode(__("Arrêtés"))?>";

			if(tag) url = url+'/'+tag;

			if($("#start").val()) url = url + '/start_' + $("#start").val();
			if($("#end").val()) url = url + '/end_' + $("#end").val();

			document.location.href = url;
		});
	</script>
	
</section>

<section class="mw960p mod center">

	<ul>
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-arrete']) $sql_state = "AND state='active'";
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
				".$tt.".zone = '".$res['url']."' AND
				".$tt.".encode = '".$tag."'
			)";


		// Filtre par date
		if(@$GLOBALS['filter']['start'] or @$GLOBALS['filter']['end']) {
			$sql.=" JOIN ".$tm." ON (
				".$tm.".id=".$tc.".id AND
				".$tm.".type='aaaa-mm-jj' AND";

				if(@$GLOBALS['filter']['start']) $sql.=" ".$tm.".cle>='".date("Y-m-d", strtotime(encode($GLOBALS['filter']['start'])))."'";
				if(@$GLOBALS['filter']['start'] and @$GLOBALS['filter']['end']) $sql.=" AND ";
				if(@$GLOBALS['filter']['end']) $sql.=" ".$tm.".cle<='".date("Y-m-d", strtotime(encode($GLOBALS['filter']['end'])))."'";

			$sql.=")";
		}


		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state." AND";

		$sql.=" ".$tc.".type='arrete'";

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

			<li>
				<a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>"><?=$res_fiche['title']; ?></a> - <?=date_lang($content_fiche['aaaa-mm-jj']); ?>
			</li>

		<?php	
		}
		?>

	</div>

	<div class="tc ptm mtl pbl">

		<?php
		page($num_total, $page);
		?>

	</div>

</section>