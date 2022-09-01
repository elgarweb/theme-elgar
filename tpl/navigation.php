<?php
switch(@$_GET['mode'])
{
	default:
	if(!$GLOBALS['domain']) exit;?>

	<section class="mw960p center relative">

		<?php include('theme/'.$GLOBALS['theme'].'/ariane.php');?>

		<div class="bg-color-3 pbl">

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
			$sql ="SELECT ".$tc.".id,".$tc.".url, ".$tc.".title, ".$tc.".state FROM ".$tc;
			$sql.=" JOIN ".$tm."
			ON
			(
				".$tm.".id = ".$tc.".id AND
				".$tm.".type='navigation' AND
				".$tm.".cle='".$res['url']."'
			)";
			$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state."";
			$sql.=" ORDER BY ".$tm.".ordre ASC, ".$tc.".date_insert DESC";
			//$sql.=" ORDER BY ".$tc.".date_insert DESC";
			$sql.=" LIMIT ".$start.", ".$num_pp;


			//echo $sql;
			$sel_nav = $connect->query($sql);

			//$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

			while($res_nav = $sel_nav->fetch_assoc())
			{
				// Page invisible ou pas
				if($res_nav['state'] != "active") $state=" <i class='fa fa-eye-off' title='Désactivé'></i>";
				else $state="";

				echo'<li data-id="'.$res_nav['id'].'"><a href="'.make_url($res_nav['url'], array("domaine" => true)).'">'.$res_nav['title'].$state.'</a></li>';
			}

			//page($num_total, $page);
			?>
			</ul>


			<div class="highlight no-after-before mbn pbt <?=(isset($GLOBALS['content']['highlight']) ? '' : 'editable-hidden')?>">
				<?txt("highlight");?>
			</div>

		</div>

	</section>



	<?
	tag("agenda", array('class'=>'editable-hidden mw960p center mbm', 'tag' => 'div'));

	if(isset($GLOBALS['tags']))
	{?>
	<!-- AGENDA -->
	<div class=" mbl">
		
		<section id="home-agenda" class="bg-color-3 ptl pbl">

			<article class="mw960p center">

				<h2 id="titre-events"><?php _e('Agenda')?></h2>

				<div class="blocks grid-3 space-xl">
					
					<?php 
					// Construction de la requete
					$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

					// Si filtre tag
					if(isset($GLOBALS['tags']))
					{
						$sql.=" RIGHT JOIN ".$tt."
						ON
						(
							".$tt.".id = ".$tc.".id AND
							".$tt.".zone = 'agenda' AND
							".$tt.".lang = '".$lang."' AND (";

							$i = 1;
							foreach($GLOBALS['tags'] as $cle => $val)
							{
								if($i > 1) $sql.=" OR ";

								$sql.= $tt.".encode = '".$cle."'";

								++$i;
							}

						$sql.="))";
					}

					// Pour le tri par date pour les events
					$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

					$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' AND state='active'";
					
					// Tri par date de l'evenement
					$sql.=" ORDER BY event.cle ASC";
					$sql.=" LIMIT 3";

					//echo $sql;

					$sel_event = $connect->query($sql);

					while($res_event = $sel_event->fetch_assoc())
					{
						$content_event = json_decode($res_event['content'], true);

						block(@$content_event['visuel'], $res_event['url'], $res_event['title'], @$content_event['description'], @$content_event['aaaa-mm-jj'], @$content_event['aaaa-mm-jj-fin']);
					}
					?>

				</div>

				<!-- Bouton vers tous les événements -->
				<div class="lien-bt ptl">
					<a href="<?=make_url(__('agenda'), array("domaine" => true))?>" class="bt">
						<?php span('txt-lien-agenda', array('default' => __("See all the events"))); ?>
					</a>
				</div>		

			</article>

		</section>

	</div>
	<?}?>
	


	<script>
		// Ordre editable
		edit.push(function()
		{
			// Désactive les liens
			$(".content .navigation a").click(function() { return false; });

			// Element déplaçable
			$(".content .navigation").sortable({ stop: function(event, ui) { tosave(); } });
		});

		// Sauvegarde de l'ordre
		after_save.push(function()
		{
			// Liste l'ordre des li
			navigation = {};
			var i = 1;
			$(document).find(".content .navigation li").each(function() {
				navigation[$(this).data("id")] = i;
				i++;
			});

			// Sauvegarde l'ordre
			$.ajax({
				type: "POST",
				url: path+"theme/<?=$GLOBALS['theme']?>/tpl/navigation.php?mode=save",
				data: {
					"navigation": navigation,
					"permalink": permalink,
					"nonce": $("#nonce").val()
				},
				success: function(html){ }
			});
		});
	</script>

	<?
	break;



	case'save':
		include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');// Les variables si on ajax
		include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');// Les fonctions si on ajax
		include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');// Connexion à la db

		login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

		//print_r($_POST['navigation']);

		foreach($_POST['navigation'] as $id => $ordre)
		{
			$connect->query("UPDATE ".$table_meta." SET ordre='".(int)$ordre."' WHERE id='".(int)$id."' AND type='navigation' AND cle='".encode($_POST['permalink'])."' LIMIT 1");
		}

	break;
}
?>