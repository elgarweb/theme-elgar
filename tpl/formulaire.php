<?php 

/**** @todo
- lors du drag&drop masquer la toolbox et éviter les erreurs de memo_focus
- le legend du fieldset n'a pas le bon id lors de la récup...
- finalisé le tri et connexion entre les élément et le formulaire
- faire des test massif sur la modification et suppression d'élément
- tester depuis une page vide
*****/

/**** Plus tard
- voir pour une version sans ul/li, mais visiblement complexe de changer le tag à la volé pour faire le tri une fois edit lancer
- si choix tpl builder on regarde dans la base de donnée les pages qui l'uilisent et propose de reprendre la tpl
- tous les attributs de l'édition ne sont pas dans les fonctions _event du coup l'edition n'est pas complete lors de l'ajout à la volé d'un élément editable
*****/

switch(@$_GET['mode'])
{
	// AFFICHAGE de la page
	default:
		if(!$GLOBALS['domain']) exit;

		// Encrypte le mail pour permettre des envois de mail que vers des mails ajoutés par l'admin
		$GLOBALS['content']['email-hash'] = hash("sha256", base64_decode(@$GLOBALS['content']['email-to']) . $GLOBALS['pub_hash']);
		?>

		<section class="mw960p center">

			<?php include('theme/'.$GLOBALS['theme'].'/ariane.php'); ?>

			<article>

				<?php
				h1('title');

				txt('description');

				//highlight_string(print_r($GLOBALS['content'], true));
				?>
				<p class="none isrequired"><?_e("Les champs marqués d'une <span class='red'>*</span> sont obligatoires.")?></p>

				<form id="formulaire" method="post">

					<div class="editable-hidden small grey mtm mbm">

						<label for="email-to"><?php _e("Recipient email")?> (contact@test.fr)<span class="red">*</span> :</label>
						
						<div>
						<?input("email-to", array('name' => 'email-to', 'placeholder' => __("Recipient email")));?>
						<?input("email-hash", array('name' => 'email-hash', 'type' => 'hidden', 'class' => 'hidden'));?>
						</div>

					</div>


					<ul>

						<li class="exclude editable-hidden small grey">Ajouter vos champs au formulaire :</li>

						<?php
						function builder_array($builder_array, $level = 0)
						{
							global $fieldset;

							// Include les éléments du builder pour affichage
							if(isset($builder_array) and is_array($builder_array))
							foreach($builder_array[$level] as $index => $array)
							{
								// print_r("=> ".$index." | ");
								// if(is_array($array))
								// {
								// 	print_r($array);
								// 	echo count($array)."<br>";
								// }

								// if(is_array($array) and count($array) > 1) 
								// {
								// 	//builder_array($array);
								// 	//break;
								// }
								// else
								{
									// init les clé
									if(is_array($array))// Si tableau d'éléments
									{
										//$GLOBALS['editkey'] = trim(key($array), "key");
										$current = current($array);
									}
									else// Si un seul élément
									{
										$GLOBALS['editkey'] = trim($index, "key");// Clean la clé
										$level = trim($index, "key");// Niveau de profondeur
										$current = $array;// index courant
									}

									// print_r("index:".$index." | key:".$GLOBALS['editkey']." ");
									// print_r($array);
									// print_r("|current:".$current);

									// Insert l'élément
									include($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['path']."theme/".$GLOBALS['theme']."/tpl/formulaire/".$current.".php");

									// pour l'ajout d'élément builder
									if(isset($GLOBALS['editkey']) and isset($_SESSION['editkey']) and $GLOBALS['editkey'] > $_SESSION['editkey']) 
										$_SESSION['editkey'] = $GLOBALS['editkey'];
								}
							}
						}

						if(isset($GLOBALS['content']['builder']))
							builder_array($GLOBALS['content']['builder']);
						?>

						<!-- Pour initialiser la possibilité d'imbrication @todo voir si toujours utile avec le nouveau sortable !!! none -->						
						<li aria-hidden="true" class="none">
							<fieldset>
								<legend></legend>
								<ul class="fieldset"><li></li></ul>
							</fieldset>
						</li>

					</ul>


					<!-- Bouton envoyer -->
					<button type="submit" id="send" class="bt">
						<?php _e("Send")?>
					</button>


					<!-- RGPB -->
					<?php txt('texte-rgpd', 'mtl')?>

					<input type="hidden" name="rgpd_text" value="<?=htmlspecialchars(@$GLOBALS['content']['rgpd']);?>">


					<input type="hidden" name="nonce_formulaire" value="<?=nonce("nonce_formulaire");?>">

				</form>

			</article>

			<script>
				// Titre de la page en cours
				origin_title = document.title;

				// Pour rétablir le fonctionnement du formulaire
				function activation_form(){
					desactive = false;

					$("#formulaire #send .fa-cog").removeClass("fa-spin fa-cog").addClass("fa-mail-alt");

					// Activation des champs du formulaire
					$("#formulaire input, #formulaire textarea, #formulaire button").removeClass("disabled");// .attr("disabled", false) .attr("readonly", false) aria-disabled disabled

					// On peut soumettre le formulaire avec la touche entrée
					//$("#formulaire").on("submit", function(event) { send_mail(event) });
					$("#formulaire button").attr("aria-disabled", false);
				}

				desactive = false;
				function send_mail(event)
				{
					event.preventDefault();					

					if($("#question").val()=="" || $("#rgpdcheckbox").prop("checked") == false)
						error(__("Thank you for completing all the required fields!"));
					else if(!desactive)
					{
						desactive = true;

						// Icone envoi en cours
						$("#formulaire #send .fa-mail-alt").removeClass("fa-mail-alt").addClass("fa-spin fa-cog");

						// Désactive le formulaire
						$("#formulaire input, #formulaire textarea, #formulaire button").addClass("disabled");// .attr("disabled", true) .attr("readonly", true) aria-disabled disabled

						// Désactive le bouton submit (pour les soumissions avec la touche entrée)
						//$("#formulaire").off("submit");
						$("#formulaire button").attr("aria-disabled", true);// => ne permet pas le focus sur le bt une fois envoyer

						$.ajax(
							{
								type: "POST",
								url: path+"theme/"+theme+(theme?"/":"")+"tpl/formulaire.php?mode=send-mail",
								data: $("#formulaire").serializeArray(),
								success: function(html){ $("body").append(html); }
							});
					}
				}

				$(function()
				{
					// Champs avec message d'erreur/format custom
					$("#formulaire .type").on("change", function(event)
					{
						var input_id = $(this).data('id');
						var parent = $(this).parentsUntil('[data-builder="input-text"]').last();
						
						
						// Message d'erreur
						var text_error = $("option:selected", this).data('error');

						if(text_error)
						{
							// Ajout d'un message d'erreur customisable
							if(!parent.next(".text_error").length)
							{
								parent.after("<div class='text_error'>Message en cas d'erreur : <span class='editable red' id='error-"+input_id+"'>"+text_error+"</span></div>");

								editable_event();
							}
						}
						else
						{
							// On supprime les messages d'erreur custom
							if(parent.next(".text_error").length) 
								parent.next(".text_error").remove();
						}


						// Format attendu
						var text_format = $("option:selected", this).data('format');
						var prev_input = parent.prev(".editable-input");

						if(text_format)
						{
							prev_input.prev(".text_format").remove();

							// Ajout d'un message de format attendu customisable
							prev_input.before("<div class='text_format'>Message de format attendu : <span class='editable green' id='format-"+input_id+"'>"+text_format+"</span></div>");

							editable_event();						
						}
						else
						{
							// On supprime les messages de format attendu custom
							if(prev_input.prev(".text_format").length) 
								prev_input.prev(".text_format").remove();
						}
					});


					// Si texte rgpd, on le lie au bouton d'envoi
					if($("#texte-rgpd").text()) $("#send").attr("aria-describedby","texte-rgpd");


					// Fieldset radio required
					$("#formulaire .fieldset .exclude .editable-checkbox.yes").each(function() 
					{
						// Met la radio en required
						$(this).parentsUntil("ul").nextAll("[data-builder=radio]").children("input[type='radio']").attr("required", true)
					});


					// Fieldset checkbox required
					$("#formulaire .fieldset .exclude .editable-checkbox.yes").each(function() 
					{
						// Ajout sur le parent class required pour simplifier le controle des checkbox
						$(this).parentsUntil("ul").parent().addClass("required");

						// Recherche des checkbox dans le fieldset
						var that = $(this).parentsUntil("ul").nextAll("[data-builder=checkbox]").children("input[type='checkbox']");

						// Si checkbox
						if(that.length > 0)
						{
							// Met les checkbox en required
							that.attr("required", true);

							// Message d'erreur personnalisé sur la 1er checkbox seulement
							that[0].addEventListener("invalid", function() {
								that[0].setCustomValidity("Veuillez cocher au moins une case si vous souhaitez continuer.")
							}, false);
						}
					});

					// Action au click sur une checkbox
					$("#formulaire .required > li input[type='checkbox']").on("click", function() 
					{
						// On remonte au parent pour changer l'état de toutes les checkbox
						var that = $(this).parentsUntil("ul").parent();
						
						if($(this).prop("checked") == true)//:checkbox:checked
						{
							// On passe toutes les checkbox en required=off
							$("input[type='checkbox']", that).attr("required", false);

							// On retire le message d'erreur custom sur la 1er checkbox
							$("input[type='checkbox']:first", that)[0].setCustomValidity("");
						}
						else 
						{
							// On remet en required si pas de checkbox checked
							if($("input[type='checkbox']:checked", that).length == 0)
								$("input[type='checkbox']", that).attr("required", true);
						}
					});

					
					// Parcours les radio/checkbox
					$("#formulaire input[type='radio'], #formulaire input[type='checkbox']").each(function() 
					{
						// Affecte les id des radio/checkbox au for des labels
						$(this).next("label").attr('for', $(this).attr("id"));

						// Si radio on affect un name commun en fonction du fieldset
						if($(this).attr("type") == "radio") 
						{
							var fieldset = $(this).closest(".fieldset").data("fieldset");
							if(fieldset != undefined) $(this).attr("name", "fieldset-"+ fieldset);
						}
					});


					// Affichage informatif s'il y a des champs requis
					if($("#formulaire .required").length) $(".isrequired").show();


					// Soumettre le formulaire
					$("#formulaire").submit(function(event)
					{
						send_mail(event)
					});


					// Avant la sauvegarde
					before_save.push(function() {
						// Encode
						if(data["content"]["email-to"] != undefined)
							data["content"]["email-to"] = btoa(data["content"]["email-to"]);
					});

					// Mode édition
					edit.push(function()
					{
						// Décode
						$("#email-to").val(function(index, value) {
							if(value) return atob(value);
						});

						// Outil d'édition du formulaire
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
			main { min-height: 500px; }

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


			#builder {
				box-shadow: 1px 1px 3px rgb(0 0 0 / 30%);
				background-color: rgba(240, 240, 240, 1);
				border-radius: 5px;
				position: fixed;
				top: 45px;
				right: 5px;
				z-index: 10;
				padding: 0;
				transition: background-color .3s linear;
				animation: slide-up .3s 1 ease-out;
			}
			/* #builder:hover { background-color: rgba(240, 240, 240, 0.95); } */
				#builder li:not(.nodrag) {
					background-color: rgba(61, 128, 179, 0.05);
					border: 1px dotted rgba(61, 128, 179, 0.2);
					border-radius: 5px;
					text-align: left;
					/* cursor: move; */
				}

				/* .lucide [data-builder] { position: relative; } */

				.allowed { background-color: #cff2d5; }
				.notallowed { background-color: #9e1e1e45; }


			[data-builder] .fa-cancel { font-size: 1.4rem; }


			#formulaire li:not(.exclude), #builder li:not(.nodrag) {
				/* display: block;
				margin: 5px;
				padding: 5px;
				border: 1px solid #cccccc;
				color: #0088cc;
				background: #eeeeee; */
				
				border: 1px dashed #cccccc;
				padding: 0.5rem;
				margin: 1rem;
			}
				#formulaire li.placeholder {
					position: relative;
					margin: 0;
					padding: 0;
					height: 30px;
					border: 1px dashed #008891; 
				}
					#formulaire li.placeholder:before {
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

			.active { border: 1px solid #333333; }

			.exclude .fa-move,
			.exclude .fa-cancel:not(.editable-checkbox)
			{ display: none; }
			
		</style>


		<ul id="builder" class="unstyled tc small">
			<li class="nodrag bold mtt mlm mrm pointer">Liste des éléments à ajouter <i class="fa fa-resize-small grey"></i></li>
			<?php
			// Liste les elements du builder - boucle dossier builder
			$dir = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['path']."theme/".$GLOBALS['theme']."/tpl/formulaire/";
			if(is_dir($dir))// Le dossier existe
			{
				// Les tpl
				$tpl_builder = array(
					"h2" => "Titre niveau 2 (H2)",
					"txt" => "Texte",
				
					"fieldset" => "Ensemble de champs (fieldset)",
					"checkbox" => "Case à cocher (checkbox)",
					"radio" => "Case d'option (radio)",

					"select" => "Liste déroulante (select)",

					"textarea" => "Grande zone de texte (textarea)",
					"input-text" => "Champ texte simple (text)",
				);

				foreach($tpl_builder as $filename => $name)
				{
					echo'<li data-file="'.$filename.'.php">'.$name.'</li>';
				}

				// Ancienne méthode en fonction des fichiers dans le dossier
				// $scandir = array_diff(scandir($dir), array('..', '.'));// Nettoyage
				// foreach($scandir as $cle => $filename)
				// {
				// 	$pathinfo = pathinfo($filename, PATHINFO_FILENAME);
				// 	echo'<li data-file="'.$filename.'">'.(isset($GLOBALS['tpl_name'][$pathinfo])?$GLOBALS['tpl_name'][$pathinfo]:$pathinfo).'</li>';
				// }
			}
			?>
		</ul>

		<!-- <a href='javascript:move_builder();' class="bt-move-builder" title="Déplacer les éléments">Déplacer <i class='fa fa-fw fa-move big'></i></a> -->


		<script src='<?=$GLOBALS['path']."theme/".$GLOBALS['theme'];?>/admin/jquery-mjs.nestedSortable.js'></script>

		<script>
			$(function()
			{
				// Supprime les <filedset> pour pouvoir faire les tris
				//$('fieldset').contents().unwrap();


				// Réduit le menu des éléments disponible
				$("#builder .nodrag").on("click", function() { 
					$("#builder li:not(.nodrag)").slideToggle();
				});
				

				// DÉPLACEMENT & AJOUT d'un élément
				// Ajout d'une zone de drag pour chaque élément du builder
				$("#builder li").not(".nodrag").prepend("<i class='fa fa-move'></i>");//[data-builder],


				// Tri
				// Déplacement des éléments dans le formulaire, avec imbrication
				$('#formulaire ul').nestedSortable({
					listType: 'ul',
					items: 'li',
					handle: '.fa-move',
					placeholder: 'placeholder',
					isTree: true,// stabilise les déplacements
					tolerance: "pointer",

					//maxLevels: 3,
					//toleranceElement: '> div'
					isAllowed: function (placeholder, placeholderParent, currentItem)
					{						
						//console.log(placeholderParent)
						//console.log(currentItem)
						//console.log(placeholderParent.data("builder"))

						// Autorise le drop que si c'est un filedset
						//!placeholderParent.hasClass("exclude")
						if(!placeholderParent || placeholderParent[0].nodeName == "FIELDSET")
						{
							$(currentItem).removeClass("notallowed").addClass("allowed");
							return true;
						}
						else 
						{
							$(currentItem).removeClass("allowed").addClass("notallowed");
							return false;
						}
					}
				});

				// Drag&drop Depuis la liste des éléments disponibles vers le formulaire
				$("#builder li").draggable({
					connectToSortable: "#formulaire ul",
					handle: ".fa-move",
					helper: "clone",
					//revert: "invalid",// retourne à l'emplacement initial si pas dropé
					scrollSensitivity: 100,// distance haut et bas à la quel on déclanche le scrooling
				});

				// Quand on drop un élément depuis la liste des éléments disponibles
				$("#formulaire ul").droppable({
					//accept: "#builder li",
					drop: function(event, ui)
					{
						console.log("drop", ui)

						// L'élément en cours
						$item = ui.draggable;

						// Si élément zone non droppable on supp l'élément
						if($($item).hasClass("notallowed") && $($item).data("file")) 
							$($item).remove();
						else
						{
							// Clean les class lors des déplacement d'élément déjà posé
							$($item).removeClass("allowed notallowed");

							// Si demande d'injection de nouvel élément de formulaire
							if($($item).data("file") != undefined)
							{
								console.log("inject")

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
										$(".editable-bg").off(".editable-bg");
										$(".editable-checkbox, .lucide [for]").not(".lucide #admin-bar [for]").off();
										
										// Insertion du contenu éditable
										$($item).replaceWith(html);

										// Ajout de l'outil de suppression et déplacement
										add_tools();

										// Relance les events d'edition
										editable_event();
										editable_media_event();
										editable_href_event();
										editable_bg_event();
										editable_checkbox_event();

										// Affiche les options
										$(".editable-hidden").fadeIn();

										//@todo supp car liée à l'ancienne méthode de sortable
										// Si tri fieldset on re-init le tri
										/*var array_fieldset = ["fieldset.php", "radio.php", "checkbox.php"];
										if($.inArray($($item).data("file"), array_fieldset) !== -1){
											console.log("re-init")
											unsorter();
											sorter();
										}*/

										tosave();// A sauvegarder
									}
								});
							}
						}
					}
				});



				// TOOLS : Ajout des icons de Suppression & Déplacement
				add_tools = function(that)
				{
					// On parcourt tous les éléments du formulaire
					$("#formulaire li").each(function(key, val)//[data-builder]
					{
						// Ajout du dragger, si pas déjà présent
						if($(".fa-move", this).length <= 0)
							$(this).prepend("<i class='fa fa-move'></i>");

						// Ajout de la suppression au survol d'un bloc, si pas déjà présent
						if($("a .fa-cancel", this).length <= 0)
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
					increment = 0;
					data["content"]["builder"][increment] = {};
					$(document).find(".content [data-builder]").each(function(index, element)
					{
						console.log("element", $(element));
						console.log("element", $(element).data("builder"));
						//console.log("fieldset", $(element).parent().data("fieldset"));
						//console.log("parent", $(element).parent());
						
						
						// && $(element).parent().data("fieldset") != undefined
						if($(element).parent().data("fieldset") != increment) 
						{
							increment = $(element).parent().data("fieldset");

							console.log(data["content"]["builder"][increment]);

							// On vérifie que le tableau n'existe pas déjà
							if(data["content"]["builder"][increment] == undefined) 
								data["content"]["builder"][increment] = {};// index pour l'ordre d'affichage des éléments
						}
						
						if(increment == undefined) increment = 0;

						//console.log("increment", increment);
						
						// index pour l'ordre d'affichage des éléments
						//data["content"]["builder"][index] = {};

						// Clé de l'élément build en cours
						var key = find_key(element);

						// Si c'est un groupe d'élément éditable on cherche la 1er clé d'élément editable
						if(key == undefined)
						{
							//console.log("elem")
							// Récupère le 1er élément editable
							var elem = $(element).find(".editable, .editable-input, editable-media, [data-href], [data-bg]").first();

							// Récupère le numéro de l'element en fonction de son type d'edition
							var key = find_key(elem);
						}

						//console.log("index:"+index, key+" element:"+$(element).data("builder"))
						
						// Ajoute l'élément à la liste du builder avec le bon numéro d'id
						data["content"]["builder"][increment]["key"+key] = $(element).data("builder");
					});

					//console.log("[content][builder]", data["content"]["builder"]);
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