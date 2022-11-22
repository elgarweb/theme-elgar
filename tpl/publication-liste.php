<?php
if(!$GLOBALS['domain']) exit;

//$url_back = encode(__('Decrees'));
$url_back = encode(__('Publications'));

$mois = array(
	1 => 'january',
	2 => 'february',
	3 => 'march',
	4 => 'april',
	5 => 'may',
	6 => 'june',
	7 => 'july',
	8 => 'august',
	9 => 'september',
	10 => 'october',
	11 => 'november',
	12 => 'december',
);
?>

<section class="mw960p mod center mbl">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	
	<?php
	h1('title', 'picto');

	// Liste les tags pour filtrer la page
	$i = 1;
	$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC");
	//echo $connect->error;
	
	if($sel_tag_list->num_rows > 0) {
	?>
		<nav role="navigation" aria-label="<?php _e("Filter by")?>" class="flex wrap space jcc tc ptl pbm">
			<ul class="unstyled pln">
				<?php 
				while($res_tag_list = $sel_tag_list->fetch_assoc()) {
					echo'<li class="inbl prs"><a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="bt-tag'.($tag==$res_tag_list['encode']?' selected':'').'">'.$res_tag_list['name'].'</a></li>';
					$i++;
				}
				?>
			</ul>
		</nav>
	<?php
	}

	txt('description', array('class'=>'ptm'));//,'tag'=>'p'
	?>

	<form id="filtre-date-publication" class="bg-color-3 pam">

		<!-- 
		<div class="mbt"><?_e("Filter by date (format DD/MM/YYYY)")?> :</div>

		<label for="start"><?_e("Start")?></label>
		<input type="date" id="start" value="<?=@$GLOBALS['filter']['start']?>" placeholder="jj-mm-aaaa" autocomplete="off">

		<label for="end"><?_e("End")?></label>
		<input type="date" id="end" value="<?=@$GLOBALS['filter']['end']?>" placeholder="jj-mm-aaaa" autocomplete="off"> -->

		<fieldset class="inbl">

			<legend>
				<?_e("Filter by date")?>
			</legend>

			<?php echo txt("comment-filtrer");?>

			<div class="mts">

				<label for="year"><?_e("Year")?> :</label>
				<select id="year" class="pat" aria-describedby="comment-filtrer">
					<option value="" <?=(!@$GLOBALS['filter']['year']?'selected':'')?>><?_e("Year")?></option>
					<?php 
					for($i=1980; $i<=date("Y"); $i++) { 
						echo'<option value="'.$i.'"'.(@$GLOBALS['filter']['year']==$i?' selected':'').'>'.$i.'</option>';
					}
					?>
				</select>

				<label for="month" class="mlm"><?_e("Month")?> :</label>
				<select class="pat" id="month">
					<option value="" <?=(!@$GLOBALS['filter']['month']?'selected':'')?>><?_e("Month")?></option>
					<?php 
					foreach($mois as $num => $nom){
						echo'<option value="'.$num.'"'.(@$GLOBALS['filter']['month']==$num?' selected':'').'>'.__($nom).'</option>';
					}
					?>
				</select>

			</div>

		</fieldset>
		
		<button type="submit" class="bt fr mtl mrl"><?_e("Filter")?></button>

	</form>
	<script>
		$("#filtre-date-publication").on("submit", function(event) {
			event.preventDefault();

			var url = "/<?=encode(__("Publications"))?>";

			if(tag) url = url+'/'+tag;

			if($("#month").val()) url = url + '/month_' + $("#month").val();
			if($("#year").val()) url = url + '/year_' + $("#year").val();

			if($("#start").val()) url = url + '/start_' + $("#start").val();
			if($("#end").val()) url = url + '/end_' + $("#end").val();

			document.location.href = url;
		});
	</script>
	
</section>

<section class="mw960p mod center">

	<div class="blocks grid-3 space-xl">
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-publication']) $sql_state = "AND state='active'";
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
				".$tt.".lang = '".$lang."' AND
				".$tt.".encode = '".$tag."'
			)";


		// Pour le tri par date de publication
		$sql.=" JOIN ".$tm." ON (
				".$tm.".id=".$tc.".id AND
				".$tm.".type='aaaa-mm-jj-publication'";

		// Filtre par date
		if(@$GLOBALS['filter']['month'] or @$GLOBALS['filter']['year']) {
			/*$sql.=" JOIN ".$tm." ON (
				".$tm.".id=".$tc.".id AND
				".$tm.".type='aaaa-mm-jj-publication' AND";*/
			$sql.=" AND";

			if(@$GLOBALS['filter']['month'] and @$GLOBALS['filter']['year'])
				$sql.=" ".$tm.".cle LIKE '".(int)$GLOBALS['filter']['year']."-".sprintf("%02d", (int)$GLOBALS['filter']['month'])."-%'";

			if(@$GLOBALS['filter']['month'] and !@$GLOBALS['filter']['year'])
				$sql.=" ".$tm.".cle LIKE '%-".sprintf("%02d", (int)$GLOBALS['filter']['month'])."-%'";

			if(!@$GLOBALS['filter']['month'] and @$GLOBALS['filter']['year'])
				$sql.=" ".$tm.".cle LIKE '".(int)$GLOBALS['filter']['year']."-%'";
		}

		$sql.=")";


		// Filtre par date
		/*if(@$GLOBALS['filter']['start'] or @$GLOBALS['filter']['end']) {
			$sql.=" JOIN ".$tm." ON (
				".$tm.".id=".$tc.".id AND
				".$tm.".type='aaaa-mm-jj' AND";

				if(@$GLOBALS['filter']['start']) $sql.=" ".$tm.".cle>='".date("Y-m-d", strtotime(encode($GLOBALS['filter']['start'])))."'";
				if(@$GLOBALS['filter']['start'] and @$GLOBALS['filter']['end']) $sql.=" AND ";
				if(@$GLOBALS['filter']['end']) $sql.=" ".$tm.".cle<='".date("Y-m-d", strtotime(encode($GLOBALS['filter']['end'])))."'";

			$sql.=")";
		}*/


		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state." AND ".$tc.".type='publication'";

		//if(@$GLOBALS['filter']['start'] or @$GLOBALS['filter']['end'])
			$sql.=" ORDER BY ".$tm.".cle DESC";
		//else 
			//$sql.=" ORDER BY ".$tc.".date_insert DESC";

		$sql.=" LIMIT ".$start.", ".$num_pp;


		//echo $sql;
		$sel_fiche = $connect->query($sql);

		$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

		if($num_total == 0) echo'<p>'.__("No result").' !</p>';

		while($res_fiche = $sel_fiche->fetch_assoc())
		{
			// Affichage du message pour dire si l'article est invisible ou pas
			if($res_fiche['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
			else $state = "";

			$content_fiche = json_decode($res_fiche['content'], true);

			block(@$content_fiche['visuel'], $res_fiche['url'], $res_fiche['title'], @$content_fiche['description'], @$content_fiche['aaaa-mm-jj-publication']);				
		}
		?>

	</div>

	<div class="tc ptm mtl pbl">

		<?php
		page($num_total, $page, array('aria-label'=>__("Browsing by page")));
		?>

	</div>

</section>