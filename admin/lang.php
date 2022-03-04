<?
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');// Les variables si on ajax
include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');// Les fonctions si on ajax
include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');// Connexion à la db

// @todo finir le test suppression, ajout avec autocomplete + duplication + gestion des erreurs lors de l'ajout

switch(@$_REQUEST['mode'])
{
	default:

		?>
		<script>
			// Action si on lance le mode d'edition
			edit.push(function()
			{
				// Ajout du bouton langue avec la langue en cours
				$("#admin-bar #del").after('<div id="lang" class="fr"><button class="mat small o50 ho1 t5" title="<?_e("Language")?>"><span class="noss"><?_e("Language")?></span> '+$("html").attr("lang")+' <i class="fa fa-fw fa-language"></i></button></div>');

				// Ouverture de l'admin des langues au clique sur le bouton
				$("#lang button").on("click",// mouseenter touchstart
					function(event) {

						event.stopPropagation();
						event.preventDefault();		

						if(!$("#lang .absolute").length)// Si pas de layer on l'inject
						{
							$.ajax({
								type: "POST",
								url: path+"theme/<?=$GLOBALS['theme']?>/admin/lang.php?mode=tool&id=<?=$id?>",
								data: {"nonce": $("#nonce").val()},
								success: function(html){ 
									$("#lang").append(html);						
									close = false;
								}
							});
						}

						// Si on click et que l'ajax a déjà été fait
						else if($("#lang .absolute").length && !$("#lang .absolute").is(":visible") && close == true)
							$("#lang .absolute").fadeIn("fast", function(){ close = false; });

						// Si on click sur le bt lang de l'admin-bar
						else if(event.type == 'click' && $("#lang .absolute").is(":visible") && close == false )
							$("#lang .absolute").fadeOut("fast", function(){ close = true; });
					}
				);

			});
		</script>
	<?
	break;


	case"tool":

		$lang = get_lang();// Sélectionne  la langue
		load_translation('api');// Chargement des traductions du système
		load_translation('theme');// Chargement des traductions du theme

		login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

		// - proposer de créer la meme page dans une autre langue (menu select de langue), checkbox pour copier le contenu de la page en cours. si toutes les lang prise on ne propose pas d'ajout
			// -> créer la page + copie le contenu + créer les connexions dans table lang
		// - propose de relier un contenu existant comme traduction (champs avec autocomplete), saisie du titre, ou id
		// - suppression d'une connexion

		// @todo : lors de la suppression d'un contenu supp aussi les liaisons

		?>
		<div class="absolute tooltip pas mas mlt small mod">

			<!-- Liste les pages connecter -->
			<div>Traduction :</div>
			<ul id="list-trad">
		 	<?php
			$sql='SELECT '.$tc.'.id, '.$tc.'.url, '.$tc.'.lang AS lang, '.$tc.'.title, '.$tc.'.state FROM '.$tc;
			$sql.=' JOIN '.$tl.'
			ON
			(
				'.$tl.'.id = '.(int)$_REQUEST['id'].' AND
				'.$tl.'.trad = '.$tc.'.id

			)';
			$sql.=' ORDER BY '.$tc.'.lang ASC';
			$sql.=' LIMIT '.count($GLOBALS['language']);
			//echo $sql;
			$sel_lang = $connect->query($sql);
			if(!empty($sel_lang->num_rows))// Si des résultat & que la table existe
			{
				while($res_lang = $sel_lang->fetch_assoc())
				{
					// Pour savoir les traductions déjà disponible
					$traduction[$res_lang['lang']] = true;

					echo'<li data-id="'.$res_lang['id'].'">';

						echo'<a href="'.make_url($res_lang['url'], array('domaine' => $GLOBALS['scheme'].$GLOBALS['domain_lang'][$res_lang['lang']].$GLOBALS['path'])).'" lang="'.$res_lang['lang'].'">'.$res_lang['title'].'</a>';

						echo ' - '.$res_lang['lang'];

						if($res_lang['state'] != "active") echo' <i class="fa fa-eye-off" title="'.__("Deactivate").'"></i>';

						echo' <i class="fa fa-trash pointer grey"></i>';

					echo'</li>';
				}
			}
			else echo'<li class="empty">'.__("Aucune").'</li>';
			?>
			</ul>


			<!-- Ajouter une connexion -->
			<div class="connecteur">
				<hr class="mbs">
				<div>Connecter une traduction</div>
				<input type="text" id="connecteur" placeholder="Nom de la page" class="w50">
				<button id="connecter" class="small"><?_e("Ajouter")?> <i class="fa fa-fw fa-plus"></i></button>
			</div>


			<!-- Dupliquer pour traduire -->	
			<div class="duplicateur">
				<hr class="mbs">	
				<div class="bold">Dupliquer cette page pour la traduire</div>
				<div>Sélectionner une langue destination :</div>
				<?
				$i = 1;
				foreach($GLOBALS['language'] as $key => $value)
				{
					// Si la langue n'a pas encore de traduction on la propose
					if($value != $lang and !isset($traduction[$value])) 
					{
						?><input type="radio" name="dupliquer" id="lang-<?$value?>" value="<?$value?>"<?=($i==1?' checked':'')?>> <label for="lang-<?$value?>"><?_e($value)?></label> <?
						$i++;
					}
				}
				?>
				<button id="dupliquer" class="small"><?_e("Duplicate")?> <i class="fa fa-fw fa-doc-text"></i></button>
			</div>


		</div>

		<script>
			// Si pas de radio pour dupliquer on masque l'option
			if(!$(".duplicateur input[type='radio']").length)
			{
				$(".duplicateur").hide();
				$(".connecteur").hide();
			}


			$(function()
			{
				// Autocomplete pour l'ajout d'une traduction existante à la page courante
				$("#connecteur").autocomplete({
					minLength: 0,
					source: path+"theme/<?=$GLOBALS['theme']?>/admin/lang.php?mode=links&id=<?=(int)$_REQUEST['id']?>&nonce="+ $("#nonce").val(),
					select: function(event, ui) 
					{ 				
						console.log(ui)	;

						// Ajout à la bdd
						$.ajax({
							type: "POST",
							url: path+"theme/<?=$GLOBALS['theme']?>/admin/lang.php?mode=add",
							data: {
								"id-source": '<?=(int)$_REQUEST['id']?>',
								"lang-source": $("html").attr("lang"),
								"id-dest": ui.item.id,
								"lang-dest": ui.item.lang,
								"nonce": $("#nonce").val()
							},
							success: function(html){ 
								if(!html) 
									$("#list-trad").append('<li data-id="'+ui.item.id+'"><a href="'+ui.item.value+'">'+ui.item.label+'</a> '+ui.item.lang+' <i class="fa fa-trash pointer grey"></i></li>');
								else 
									$("#list-trad").append(html);
							}
						});

						return false;// Coupe l'execution automatique d'ajout du terme
					}
				})
				.focus(function(){
					$(this).data("uiAutocomplete").search($(this).val());// Ouvre les suggestions au focus
				})
				.autocomplete("instance")._renderItem = function(ul, item) {// Mise en page des résultats
			      	return $("<li>").append("<div title='"+item.value+"'>"+item.label+" <span class='grey italic'>"+item.lang+"</span></div>").appendTo(ul);
			    };


			    // Supprime une connexion de traduction
				$("#list-trad").on("click", ".fa-trash", function() 
				{	
					if(confirm("Supprimer la connexion de traduction "+$(this).parent().closest("li").text()+" ?"))
					{
						$.ajax({
							type: "POST",
							url: path+"theme/"+theme+"/admin/lang.php?mode=del",
							data: {
								"id": $(this).closest("li").data("id"),
								"nonce": $("#nonce").val()
							},
							success: function(html){
								$("body").append(html);
							}
						});
					}
				});	
				

			});
		</script>
		<?php 

	break;


	case "links":// Suggère des pages existante

		login('medium');// Vérifie que l'on a le droit d'éditer une page

		// Si on a déjà un bout d'url de saisie (cas des tags) on prend le dernier bout
		if(strstr($_GET["term"], "/")) $_GET["term"] = basename($_GET["term"]);

		$term = $connect->real_escape_string(trim($_GET["term"]));


		// LES CONTENUS
		$sql = "
		SELECT id, title, lang, url
		FROM ".$GLOBALS['table_content']."
		WHERE id!='".(int)$_REQUEST['id']."' AND (title LIKE '%".$term."%' OR url LIKE '%".$term."%')";
		if(!$term) $sql .= " ORDER BY date_update DESC"; else $sql .= " ORDER BY title ASC";
		$sql .= " LIMIT 50";
		$sel = $connect->query($sql);
		while($res = $sel->fetch_assoc()) {
			$data[$res['url']] = array(
				'id' => $res['id'],
				'label' => $res['title'],
				'lang' => $res['lang'],
				'value' => make_url($res['url'], array("absolu" => true))//, array("domaine" => true)
			);
		}


		// LES TAGS
		/*$sql = "SELECT * FROM ".$GLOBALS['tt']." WHERE name LIKE '%".$term."%' GROUP BY encode ORDER BY encode ASC LIMIT 50";
		$sel = $connect->query($sql);
		while($res = $sel->fetch_assoc()) {
			$data[$res['encode'].$res['zone']] = array(
				'id' => 'tag',
				'label' => $res['name'],
				'type' => 'Tag '.$res['zone'],
				'value' => make_url($res['zone'], array($res['encode'], "absolu" => true))//, array("domaine" => true)
			);
		}*/

		header("Content-Type: application/json; charset=utf-8");

		if(@$data)
			echo json_encode($data);

	break;



	case"del":

		login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

		//highlight_string(print_r($_REQUEST['partenaire'], true));

		$connect->query("DELETE FROM ".$tl." WHERE id='".(int)$_REQUEST['id']."' OR trad='".(int)$_REQUEST['id']."'");

		if($connect->error) echo $connect->error;
		else 
		{
			?>
			<script>
				$("#list-trad data-id['<?=(int)$_REQUEST['id']?>']")
				.slideUp("700", function(){
					$(this).remove();
				});
			</script>
			<?
		}

		exit;
		
	break;


	case"add":

		//$lang = get_lang();// Sélectionne  la langue
		//load_translation('api');// Chargement des traductions du système

		login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

		// Création de la table de connexion des traductions si pas existante
		$GLOBALS['connect']->query("
			CREATE TABLE IF NOT EXISTS `".$GLOBALS['table_lang']."` (
				`id` bigint(20) NOT NULL DEFAULT '0',
				`trad` bigint(20) NOT NULL DEFAULT '0',
				`lang` varchar(6) DEFAULT NULL,
				PRIMARY KEY (`id`,`trad`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		/*if($_REQUEST['checked'] == 'true')
			$sql = "INSERT INTO ".$table_meta." SET id='".(int)$_REQUEST['id']."', type='".encode($_REQUEST['type'])."', cle='alaune'";
		else 
			$sql = "DELETE FROM ".$table_meta." WHERE id='".(int)$_REQUEST['id']."' AND type='".encode($_REQUEST['type'])."' AND cle='alaune'";

		$connect->query($sql);*/

		$sql = "INSERT INTO ".$tl." (`id`, `trad`, `lang`) VALUES
		('".(int)$_REQUEST['id-source']."', '".(int)$_REQUEST['id-dest']."', '".encode($_REQUEST['lang-dest'])."'),
		('".(int)$_REQUEST['id-dest']."', '".(int)$_REQUEST['id-source']."', '".encode($_REQUEST['lang-source'])."')";

		$connect->query($sql);

		if($connect->error) echo $connect->error;

		exit;
		
	break;

	
}
?>