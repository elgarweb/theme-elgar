<?php 
// Utilise https://johnny.github.io/jquery-sortable/

// Ajouter ces fichiers dans votre dossier "tpl"
//// Ajouter un dossier builder/ dans le dossier tpl/
//// y déposer des fichiers .php avec des sections éditables

//@todo si choix tpl builder on regarde dans la base de donnée les pages qui l'uilisent et propose de reprendre la tpl
//@todo tous les attributs de l'édition ne sont pas dans les fonctions _event du coup l'edition n'est pas complete lors de l'ajout à la volé d'un élément editable

// @todo
// - corriger la construction du formulaire pour faire fonctionner les éléments imbriqué dans les fieldsets. voir si besoin lors de la sauvegarde et après la lecture du json
// - ajout filedset editable
// - checkbox réel en input et pas en interpréter
// - ajout de radio dans un filedset déjà existant
// - ajout js sort imbricable
// - savegarde avec 2 profondeurs en cas de champs dans un fieldset
// - restitution en fonction des imbrications avec les fieldset
// - tableau avec nom compréhensible des tpl dans le dossier builder
// - lors du drag&drop masquer la toolbox et éviter les erreurs de memo_focus
// - ne pas rendre supprimable le premier li aria-hidden des fieldsets

// Plus tard
// - voir pour une version sans ul/li, mais visiblement complexe de changer le tag à la volé pour faire le tri une fois edit lancer

switch(@$_GET['mode'])
{
	// AFFICHAGE de la page
	default:
		if(!$GLOBALS['domain']) exit;

		?>
		<section class="mw960p center">

			<?php 
			include('theme/'.$GLOBALS['theme'].'/ariane.php');

			h1('title');

			txt('description');

			//highlight_string(print_r($GLOBALS['content'], true));

			?>
			<article>

				<ul id="formulaire">

					<?php
					// Include les éléments du builder pour affichage
					if(isset($GLOBALS['content']['builder']) and is_array($GLOBALS['content']['builder']))
					foreach($GLOBALS['content']['builder'] as $index => $array)
					{
						// init les clé
						$GLOBALS['editkey'] = key($array);

						// Insert l'élément
						include($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['path']."theme/".$GLOBALS['theme']."/tpl/formulaire/".current($array).".php");

						// pour l'ajout d'élément builder
						if(@$GLOBALS['editkey'] and $GLOBALS['editkey'] > $_SESSION['editkey']) 
							$_SESSION['editkey'] = $GLOBALS['editkey'];
					}
					?>

					<!-- Pour initialiser la possibilité d'imbrication -->
					<li aria-hidden="true" class="none">
						<fieldset>
							<legend></legend>
							<ul class="fieldset"><li></li></ul>
						</fieldset>
					</li>

				</ul>

			</article>

			<script>
			$(function()
			{
				edit.push(function()
				{
					$.ajax(
					{
						type: "POST",
						url: path+"theme/"+theme+"/tpl/formulaire.php?mode=edit",
						success: function(html){ $("body").append(html); }
					});
				});
			});
			</script>

		</section>
		<?php
	break;


	// EDITION DES ÉLÉMENTS DU BUILDER
	case "edit":
		include_once("../../../config.php");// Les variables
		//include_once("../../../api/function.php");// Fonction

		if(!isset($_SESSION['editkey'])) $_SESSION['editkey'] = 1;

		?>
		<style>
			main {
				min-height: 500px;
			}

			/* supprime le bouton pour remonter en haut */
			.bt.fixed.top { display: none !important; }

			
			body.dragging, body.dragging * { cursor: move !important; }

			.dragged {
				position: absolute;
				/* top: 0 !important; */
				/* left: 0 !important; */
				opacity: 0.5;
				z-index: 2000;
			}
			/* ul.formulaire li.placeholder { position: relative; }
			ul.formulaire li.placeholder:before { position: absolute; } */

			#formulaire > li, #builder li {
				/* display: block;
				margin: 5px;
				padding: 5px;
				border: 1px solid #cccccc;
				color: #0088cc;
				background: #eeeeee; */
				
				border: 1px dashed #cccccc;
				padding: 0.5rem;
				margin: 0.5rem;
			}
				#formulaire li.placeholder, #builder li.placeholder {
					position: relative;
					margin: 0;
					padding: 0;
					border: none; 
				}
					#formulaire li.placeholder:before, #builder li.placeholder:before {
						position: absolute;
						content: "";
						width: 0;
						height: 0;
						margin-top: -5px;
						left: -5px;
						top: -4px;
						border: 5px solid transparent;
						border-left-color: #C51A1B;
						border-right: none; 
					}

			#formulaire .fa-move, #builder .fa-move {
				font-size: 1.5rem !important;
				margin-right: 0.5rem !important;
			}


			#builder {
				box-shadow: -1px 0 3px rgb(0 0 0 / 30%);
				background-color: rgba(240, 240, 240, 1);
				border-radius: 5px;
				position: fixed;
				top: 50px;
				right: 0;
				z-index: 10;
				padding: 0;
				transition: background-color .3s linear;
				animation: slide-up .3s 1 ease-out;
			}
			/* #builder:hover { background-color: rgba(240, 240, 240, 0.95); } */
				#builder li {
					background-color: rgba(61, 128, 179, 0.05);
					border: 1px dotted rgba(61, 128, 179, 0.2);
					border-radius: 5px;
					text-align: left;
				}

				/* .lucide [data-builder] { position: relative; } */

				[data-builder] .fa-cancel { font-size: 1.4rem; }
		</style>


		<ul id="builder" class="unstyled tc">
			<?php
			// Liste les elements du builder - boucle dossier builder
			$dir = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['path']."theme/".$GLOBALS['theme']."/tpl/formulaire/";
			if(is_dir($dir))// Le dossier existe
			{
				$scandir = array_diff(scandir($dir), array('..', '.'));// Nettoyage
				foreach($scandir as $cle => $filename)
				{
					$pathinfo = pathinfo($filename, PATHINFO_FILENAME);
					echo'<li data-file="'.$filename.'">'.$pathinfo.'</li>';
				}
			}
			?>
		</ul>

		<a href='javascript:move_builder();' class="bt-move-builder" title="Déplacer les éléments">Déplacer <i class='fa fa-fw fa-move big'></i></a>


		<script src='<?=$GLOBALS['path']."theme/".$GLOBALS['theme']."/";?>jquery-sortable.min.js'></script>

		<script>
			// Fonction pour trié les éléments du formulaire
			function sorter()
			{
				if($(".tpl-formulaire .fieldset").length > 0)
					selecter = $(".tpl-formulaire .fieldset");
				else 
					selecter = $("#formulaire");

				console.log("sorter", selecter);

				// Déplacement dans les fieldset + ajout depuis le builder
				selecter.sortable({
					group: 'connected',
					handle: ".fa-move",
					
					// Duplique l'item draggé depuit la liste de choix
					onDragStart: function ($item, container, _super) {
						
						if(!container.options.drop) 
							$item.clone().insertAfter($item);

						_super($item, container);

					},

					// Inject l'élément de formulaire demandé
					onDrop: function  ($item, container, _super) {
						//console.log("item", $($item).data("file"));
						//console.log("container", container);

						// Execute l'action drop
						_super($item, container);
						
						// Si demande d'injection de nouvel élément de formulaire
						if($($item).data("file") != undefined)
						$.ajax(
						{
							type: "POST",
							url: path+"theme/"+theme+"/tpl/formulaire.php?mode=add",
							data: {
								"file": $($item).data("file"),
								"nonce": $("#nonce").val()// Pour la signature du formulaire
							},
							success: function(html)
							{
								// Unbind les events d'edition
								$(".editable").off();
								$(".editable-media").off(".editable-media");
								$(".editable-href").off(".editable-href");

								// Insertion du contenu éditable
								$($item).replaceWith(html);

								// Ajout de l'outil de suppression et déplacement
								add_tools();

								// Relance les events d'edition
								editable_event();
								editable_media_event();
								editable_href_event();
								editable_bg_event();

								// Si tri fieldset on re-init le tri
								var array_fieldset = ["fieldset.php", "radio.php", "checkbox.php"];
								if($.inArray($($item).data("file"), array_fieldset) !== -1){
									console.log("re-init")
									unsorter();
									sorter();
								}
							}
						});
					},
				});
				

				// Déplacement des éléments du formulaire global
				if($(".tpl-formulaire .fieldset").length > 0){
					console.log("#formulaire")
					$("#formulaire").sortable({
						group: 'connected',
					});
				}
				
				
				// Déplacement des éléments du builder pour ajouter dans le formulaire
				$("#builder").sortable({
					group: 'connected',
					drop: false
				});
			}


			// Désactive le tri
			function unsorter() 
			{
				console.log("unsorter");

				if($(".tpl-formulaire .fieldset").length > 0)
					$(".fieldset").sortable("destroy");

				if($("#formulaire").length > 0)
					$("#formulaire").sortable("destroy");

				$("#builder").sortable("destroy");
			}



			$(function()
			{
				// DÉPLACEMENT & AJOUT d'un élément
				// Ajout d'une zone de drag pour chaque élément du builder
				$("#builder li").prepend("<i class='fa fa-move'></i>");//[data-builder],

				// Tri
				sorter();


				// TOOLS : Suppression & Déplacement
				add_tools = function(that)
				{
					// On parcourt tous les éléments du formulaire
					$("#formulaire li").each(function(key, val)//[data-builder]
					{
						// Ajout du dragger, si pas déjà présent
						if($(".fa-move", this).length <= 0)
							$(this).prepend("<i class='fa fa-move'></i>");

						// Ajout de la suppression au survol d'un bloc, si pas déjà présent
						if($(".fa-cancel", this).length <= 0)
							$(this).append("<a href='javascript:void(0)' onclick='remove_builder(this)'><i class='fa fa-cancel absolute none red pointer' style='top: 0; right: 0; z-index: 10;' title='"+ __("Remove") +"'></i></a>");
					});
				};

				add_tools();// Execution

				// Fonction pour supprimer un bloc
				remove_builder = function(that) {
					$(that).closest("[data-builder]").slideUp("slow", function() {
						this.remove();
					});
				};



				// SAVE
				// Trouve une clé
				find_key = function(elem)
				{
					// Récupère le numéro de l'element en fonction de son type d'edition
					if($(elem).hasClass("editable") || $(elem).hasClass("editable-media") || $(elem).hasClass("editable-input"))
						return $(elem).attr("id").split("-").pop();
					else if($(elem).data("id"))
						return $(elem).data("id").split("-").pop();
					else if($(elem).data("href"))
						return $(elem).data("href").split("-").pop();
				}

				// Avant de récolter les contenus on les nettoie des fonctions admin
				before_data.push(function()
				{
					// Désactive le déplacement avant sauvegarde
					if($("main").hasClass("ui-sortable")) unmove_builder();

					// Supprime de la dom les supp
					$("[onclick='remove_builder(this)']").remove();
				});

				// Crée une liste json des éléments builder pour save
				before_save.push(function()
				{
					// Envoie les datas
					data["content"]["builder"] = {};
					$(document).find(".content [data-builder]").each(function(index, element)
					{
						data["content"]["builder"][index] = {};// index pour l'ordre d'affichage des éléments

						// Clé de l'élément build en cours
						var key = find_key(element);

						// Si c'est un groupe d'élément éditable on cherche la 1er clé d'élément editable
						if(key == undefined)
						{
							//console.log("elem")
							// Récupère le 1er élément editable
							var elem = $(element).find(".editable, editable-media, [data-href], [data-bg]").first();

							// Récupère le numéro de l'element en fonction de son type d'edition
							var key = find_key(elem);
						}
						
						// Ajoute l'élément à la liste du builder avec le bon numéro d'id
						data["content"]["builder"][index][key] = $(element).data("builder");
					});
				});

				// Après la sauvegarde
				before_save.push(function()
				{
					// Remets les options pour supprimer un élément
					add_tools();
				});

			});
		</script>			
		<?php
	break;


	// AJOUT D'UN ÉLÉMENT
	case "add":
		include_once("../../../config.php");// Les variables
		include_once("../../../api/function.php");// Fonction

		// On récupère l’incrémental en cours des id de contenu éditable
		$GLOBALS['editkey'] = (int)$_SESSION['editkey'];

		// Ajoute un élément
		include('formulaire/'.$_REQUEST['file']);

		// On sauvegarde l'incrémental d'id de contenu editable
		$_SESSION['editkey'] = $GLOBALS['editkey'];

	break;


	// ENVOI DU FORMULAIRE
	case "send":
		

	break;
}
?>