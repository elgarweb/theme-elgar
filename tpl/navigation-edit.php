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
			// $sql ="SELECT ".$tc.".id,".$tc.".url, ".$tc.".title, ".$tc.".state, ".$tm.".val FROM ".$tc;
			// $sql.=" JOIN ".$tm."
			// ON
			// (
			// 	".$tm.".id = ".$tc.".id AND
			// 	".$tm.".type='navigation' AND
			// 	".$tm.".cle='".$res['url']."'
			// )";
			// $sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state."";
			// $sql.=" ORDER BY ".$tm.".ordre ASC, ".$tc.".date_insert DESC";
			// //$sql.=" ORDER BY ".$tc.".date_insert DESC";
			// $sql.=" LIMIT ".$start.", ".$num_pp;


			// Version avec des entrées ajoutée manuellement
			$sql ="SELECT ".$tc.".id,".$tc.".url, ".$tc.".title, ".$tc.".state, ".$tm.".val";
			$sql.=" FROM ".$tm;
			$sql.=" LEFT JOIN ".$tc."
			ON
			(
				".$tm.".id = ".$tc.".id AND
				".$tc.".lang='".$lang."'
				".$sql_state."
			)";
			$sql.=" WHERE ".$tm.".type='navigation' AND	".$tm.".cle='".$res['url']."' ";
			$sql.=" ORDER BY ".$tm.".ordre ASC, ".$tc.".date_insert DESC";
			//$sql.=" ORDER BY ".$tc.".date_insert DESC";
			$sql.=" LIMIT ".$start.", ".$num_pp;
			
			$sel_nav = $connect->query($sql);

			//echo $sql; echo $connect->error;

			//$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

			$li = 1;
			while($res_nav = $sel_nav->fetch_assoc())
			{
				// Page invisible ou pas
				if($res_nav['state'] != "active") 
					$state=" <i class='fa fa-eye-off ' title='Désactivé'></i>";
				else 
					$state="";

				// Extraction des données spécifique val
				$val = json_decode($res_nav['val'], true);
				$GLOBALS['content']["visuel-".$li] = @$val["media"];
				$GLOBALS['content']["titre-".$li] = @$val["titre"];
				$GLOBALS['content']["description-".$li] = @$val["description"];
				
				//$(".navigation").append("<li><span class='dragger'></span><div class='mod'><span id='visuel-"+num+"' class='editable-media fl' data-dir='navigation' data-width='300'></span><div class='fl mls'><div id='titre-"+num+"' class='editable titre' placeholder='Titre avec lien'></div></div></div></li>");

				if($res_nav['url']) $url = make_url($res_nav['url'], array("domaine" => true));
				else $url = '';

				echo"
				<li ".($res_nav['id']?"data-id='".$res_nav['id']."'":"")." ".($url?"data-url='".$url."'":"").">					
					<span class='dragger'></span>
					<div class='content'>";

						// media
						media("visuel-".$li."", array("dir" => "navigation", "size" => "300"));
						//echo"<span id='visuel-".$li."' class='editable-media fl' data-dir='navigation' data-width='300'>".."</span>";

						echo"<div class='texte'>";

							if(@$val["titre"])
								// titre
								txt("titre-".$li, "titre");
								//echo"<div id='titre-".$li."' class='editable titre' placeholder='Titre avec lien'></div>";
							else
								// lien simple
								echo"<div id='titre-".$li."' class='titre'><a href='".$url."'>".$res_nav['title']."</a> ".$state."</div>";//.$state						

							// description
							txt("description-".$li, "description");
							//echo"<div id='description-".$li."' class='editable description' placeholder='Description'></div>";

						echo"</div>

					</div>
				</li>";

				++$li;
			}

			//page($num_total, $page);
			?>
			</ul>


			<div class="editable-hidden tr mts mbm"><a href="javascript:add_li();void(0)">Ajouter un élément <i class="fa fa-fw fa-plus vam" aria-hidden="true"></i></a></div>


			<div class="highlight no-after-before mbn pbt <?=(isset($GLOBALS['content']['highlight']) ? '' : 'editable-hidden')?>">
				<?txt("highlight");?>
			</div>

		</div>

	</section>



	<?
	// Tag pour les actualités connexes
	tag(encode(__('News')), array('class'=>'editable-hidden mw960p center mbm', 'tag' => 'div'));

	// Si Tag on charge les atualités connexes
	if(isset($GLOBALS['tags']))
	{?>
	<!-- Actu -->
	<div class="ptm mbm">
		
		<section id="home-agenda" class="bg-color-3 ptl pbl">

			<article class="mw960p center">

				<?php h2('titre-events', '')?>

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
							".$tt.".zone = '".encode(__('News'))."' AND
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
					//$sql.=" JOIN ".$tm." AS event ON event.id=".$tc.".id AND event.type='aaaa-mm-jj'";

					//$sql.=" WHERE (".$tc.".type='event' OR ".$tc.".type='event-tourinsoft') AND ".$tc.".lang='".$lang."' AND state='active'";
					$sql.=" WHERE (".$tc.".type='article') AND ".$tc.".lang='".$lang."' AND state='active'";
					
					// Tri par date de l'evenement
					$sql.=" ORDER BY ".$tc.".date_insert DESC";
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
					<a <?href('url-lien-agenda');?>>
						<?php span('txt-lien-agenda', array('default' => __("See all the events"), 'class' => 'bt')); ?>
					</a>
				</div>		

			</article>

		</section>

	</div>
	<?}?>
	


	<script>
		num = <?=$li;?>;
		
		//***** Ajoute un élément à la navigation *****//
		function add_li() 
		{
			$(".navigation").append("<li><i onclick='$(this).parent().remove();tosave();' class='fa fa-cancel red' title='"+ __("Remove") +"'></i><span class='dragger'></span><div class='mod'><span id='visuel-"+num+"' class='editable-media' data-dir='navigation' data-width='300'></span><div class='fl mls'><div id='titre-"+num+"' class='editable titre' placeholder='Titre avec lien'></div><div id='description-"+num+"' class='editable description' placeholder='Description'></div></div></div></li>");
			// contenteditable='true'

			// Rend les champs éditables
			$(".editable").off();
			$(".editable-media").off(".editable-media");

			editable_event();
			editable_media_event();

			num = num + 1;
		}
		

		// Mode édition
		edit.push(function()
		{
			//***** Recherche dans les contenus du site *****//
			// $(document).on("keydown.autocomplete", ".titre", function() 
			// {
			// 	$(this).autocomplete({
			// 		minLength: 0,
			// 		source: path + "api/ajax.admin.php?mode=links&nonce="+ $("#nonce").val() +"&dir="+ ($(memo_node).closest(".editable").data("dir") || ""),
			// 		select: function(event, ui) 
			// 		{ 	
			// 			// id, label => nom, type=page,article,media..., value=url		
			// 			// Champ titre	
			// 			$(this).html('<a href="'+ui.item.value+'">'+ui.item.label+'</a>');

			// 			// data-id dans le li
			// 			$(this).closest("li").attr("data-id", ui.item.id).attr("data-url", ui.item.value);
			
			// 			return false;// Coupe l'execution automatique d'ajout du terme
			// 		}
			// 	})
			// 	.autocomplete("instance")._renderItem = function(ul, item) {// Mise en page des résultats
			// 		return $("<li>").append("<div title='"+item.value+"'>"+item.label+" <span class='grey italic'>"+item.type+"</span></div>").appendTo(ul);
			// 	};
			// });


			//***** Ajoute un outil pour supprimer la ligne ******//
			$("ul.navigation li:not([data-id])").append("<i onclick='$(this).parent().remove();tosave();' class='fa fa-cancel red' title='"+ __("Remove") +"'></i>");

			
			//***** Ordre editable ******//
			// Désactive les liens
			$(".content .navigation a").click(function() { return false; });

			// Element déplaçable
			$(".content .navigation").sortable({ handle: ".dragger", axis: "y", stop: function(event, ui) { tosave(); } });
		});

		// Sauvegarde de l'ordre
		// before_save.push(function()
		// {
		// 	// On vérifie que les URL pointe sur des pages spécifiques, sinon on supprime la connexion réciproque (fil d'aryenne)
		// 	$(".navigation [data-url]").each(function(event) {
		// 		//console.log($(this).attr("data-url"));
		// 		//console.log($(".titre a", this).attr("href"));
		// 		if($(this).attr("data-url") != $(".titre a", this).attr("href"))
		// 			$(this).removeAttr("data-id").removeAttr("data-url");
		// 	});
		// });

		// Sauvegarde de l'ordre
		after_save.push(function()
		{
			// Liste l'ordre des li
			navigation = {};
			var i = 1;
			$(document).find(".content .navigation li").each(function() {
				var li_id = $(this).data("id");
				if(li_id == undefined) li_id = -i;
				navigation[li_id] = {};
				navigation[li_id]["ordre"] = i;
				navigation[li_id]["media"] = $(".editable-media img", this).attr("src");
				navigation[li_id]["titre"] = $(".titre", this).html();
				if($(".description", this).html()) navigation[li_id]["description"] = $(".description", this).html();
				i++;
			});
			//console.log(navigation)

			// Sauvegarde l'ordre
			$.ajax({
				type: "POST",
				url: path+"theme/<?=$GLOBALS['theme']?>/tpl/navigation-edit.php?mode=save",
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

		print_r($_POST['navigation']);

		// Supprime pour nétoyer les entrées
		$connect->query("DELETE FROM ".$table_meta." WHERE type='navigation' AND cle='".encode($_POST['permalink'])."'");

		// Ajoute et remplace les entrées
		foreach($_POST['navigation'] as $id => $navigation)
		{
			$ordre = $navigation['ordre'];

			if($id>0) unset($navigation['titre']);// Nettoie le tableau du titre s'il est connecté à une page

			unset($navigation['ordre']);// Nettoie le tableau des donnée supplementaire
			
			$json = json_encode($navigation, JSON_UNESCAPED_UNICODE);

			$sql = "REPLACE INTO ".$table_meta." SET id='".(int)$id."', type='navigation', cle='".encode($_POST['permalink'])."', ordre='".(int)$ordre."', val='".addslashes($json)."'";
			
			$connect->query($sql);

			echo $sql."\n";
			echo $connect->error."\n";
		}

	break;


	// case"del":

	// 	include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');// Les variables si on ajax
	// 	include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');// Les fonctions si on ajax
	// 	include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');// Connexion à la db

	// 	login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

	// 	// Supprime l'entrée
	// 	$connect->query("DELETE FROM ".$table_meta." WHERE id='".(int)$id."' AND type='navigation' AND cle='".encode($_POST['permalink'])."'");

	// 	echo $connect->error;

	// 	exit;
		
	// break;
}
?>