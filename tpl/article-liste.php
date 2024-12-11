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
			$sql="SELECT distinct ".$tt.".encode, ".$tt.".name FROM ".$tt;

			// Filtre par date pour les tags des events
			if($res['url'] == 'agenda')
			{
				$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tt.".id AND event_deb.type='aaaa-mm-jj'";
				$sql.=" LEFT JOIN ".$tm." AS event_fin ON event_fin.id=".$tt.".id AND event_fin.type='aaaa-mm-jj-fin'";
			}
			// Si actualités // Filtre trop, il est obligé d'avoir des dates de fin pour que le tag apparaisse
			// elseif($res['url'] == encode(__('News')))
			// 	$sql.=" JOIN ".$tm." AS event_fin ON event_fin.id=".$tt.".id AND event_fin.type='aaaa-mm-jj-fin'";

			// AND ".$tc."date_insert <= NOW() AND ".$tc."state='active'
			$sql.=" WHERE ".$tt.".zone='".$res['url']."' AND ".$tt.".lang='".$lang."' ";

			// Que les tags des évènements >= date de début OU <= date de fin
			if($res['url']=='agenda')
				$sql.=" AND (event_fin.cle >= '".date("Y-m-d")."' OR event_deb.cle >= '".date("Y-m-d")."')";
			// Si actualités
			// elseif($res['url'] == encode(__('News')))
			// 	$sql.=" AND event_fin.cle >= '".date("Y-m-d")."'";

			$sql.=" GROUP BY ".$tt.".encode, ".$tt.".name ORDER BY ".$tt.".encode ASC";

			//"SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC"

			//echo $sql;
			
			$sel_tag_list = $connect->query($sql);

			//echo $connect->error;


			// Filtre la liste de l'agenda (tag/public/lieu)
			if(isset($GLOBALS['filtre-agenda']) and $res['url'] == 'agenda')
			{
			?>
				<form id="filtre-agenda" class="">

					<div class="grid">

						<div class="mbs">
							<label for="tag"><?_e("Type d'événement")?></label>
							<select id="tag" class="block">
								<option value="" <?=(!@$tag?'selected':'')?>><?_e("Tous les types")?></option>
								<?php 
								while($res_tag_list = $sel_tag_list->fetch_assoc())
								{ 
									echo'<option value="'.$res_tag_list['encode'].'"'.(@$tag == $res_tag_list['encode']?' selected':'').'>'.$res_tag_list['name'].'</option>';
								}
								?>
							</select>
						</div>

						<div class="mbs">
							<label for="public"><?_e("Public")?></label>
							<select id="public" class="block">
								<option value="" <?=(!@$GLOBALS['filter']['public']?'selected':'')?>><?_e("Tous les publics")?></option>
								<?php 
								$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='public' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC");
								echo "SELECT distinct encode, name FROM ".$table_tag." WHERE zone='public' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC";
								while($res_tag_list = $sel_tag_list->fetch_assoc())
								{
									echo'<option value="'.$res_tag_list['encode'].'"'.(@$GLOBALS['filter']['public'] == $res_tag_list['encode']?' selected':'').'>'.$res_tag_list['name'].'</option>';
								}
								?>
							</select>
						</div>

						<div class="mbs">
							<label for="lieu"><?_e("Lieu")?></label>
							<select id="lieu" class="block">
								<option value="" <?=(!@$GLOBALS['filter']['lieu']?'selected':'')?>><?_e("Tous les lieux")?></option>
								<?php 
								$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='lieu' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC");
								while($res_tag_list = $sel_tag_list->fetch_assoc())				
								{
									echo'<option value="'.$res_tag_list['encode'].'"'.(@$GLOBALS['filter']['lieu'] == $res_tag_list['encode']?' selected':'').'>'.$res_tag_list['name'].'</option>';
								}
								?>
							</select>
						</div>

					</div>

					<div class="tc mtm">
						<button type="submit" class="bt" title="<?_e("Filter (reloads the page)")?>"><?_e("Filtrer les événements")?></button>
					</div>

				</form>

				<script>
				$("#filtre-agenda").on("submit", function(event) {
					event.preventDefault();

					var url = "/<?=encode($res['url'])?>";

					//if(tag) url = url+'/'+tag;

					if($("#tag").val()) url = url + '/' + $("#tag").val();
					if($("#public").val()) url = url + '/public_' + $("#public").val();
					if($("#lieu").val()) url = url + '/lieu_' + $("#lieu").val();

					document.location.href = url;
				});
				</script>

			<?php
			}
			else
			{
				// Que les tags
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
				<?php 
				}
			}
			?>

			
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
		if(isset($GLOBALS['num-event'])) 
			$num_pp = $GLOBALS['num-event'];
		else 
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
		

		// Si filtre public
		if(@$GLOBALS['filter']['public'])
		$sql.=" JOIN ".$tt." AS tag_public
		ON
		(
			tag_public.id = ".$tc.".id AND
			tag_public.zone = 'public' AND
			tag_public.lang = '".$lang."' AND
			tag_public.encode = '".encode($GLOBALS['filter']['public'])."'
		)";

		// Si filtre lieu
		if(@$GLOBALS['filter']['lieu'])
		$sql.=" JOIN ".$tt." AS tag_lieu
		ON
		(
			tag_lieu.id = ".$tc.".id AND
			tag_lieu.zone = 'lieu' AND
			tag_lieu.lang = '".$lang."' AND
			tag_lieu.encode = '".encode($GLOBALS['filter']['lieu'])."'
		)";


		// Pour le tri par date pour les events
		if($res['url']=='agenda'){
			//$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";
			$sql.=" JOIN ".$tm." AS event_deb ON event_deb.id=".$tc.".id AND event_deb.type='aaaa-mm-jj'";
			$sql.=" LEFT JOIN ".$tm." AS event_fin ON event_fin.id=".$tc.".id AND event_fin.type='aaaa-mm-jj-fin'";
		}
		else
		{		
			// Les dates de fin des actu
			$sql.=" LEFT JOIN ".$tm." AS actu_fin ON actu_fin.id=".$tc.".id AND actu_fin.type='aaaa-mm-jj-fin'";
		}

		$sql.=" WHERE ".$tc.".lang='".$lang."' AND date_insert <= NOW() ".$sql_state."";

		// Type de contenu event ou article
		if($res['url']=='agenda') {
			$sql.=" AND (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft')";

			// Que les évènements >= date de début OU <= date de fin
			$sql.=" AND (event_fin.cle >= '".date("Y-m-d")."' OR event_deb.cle >= '".date("Y-m-d")."')";
		}
		else {
			$sql.=" AND (".$tc.".type='article' OR ".$tc.".type='article-intramuros')";

			// Que les actu en cours (avec date de fin), ou pas documentés sur la date de fin
			$sql.=" AND (actu_fin.id IS NULL OR actu_fin.cle >= '".date("Y-m-d")."')";
		}

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

		// Pas de résultats
		if($sel_fiche->num_rows == 0) echo'<p class="mtl tc">'.__("No result").'.</p>';

		// Liste des articles/events
		while($res_fiche = $sel_fiche->fetch_assoc())
		{
			block($res_fiche);

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