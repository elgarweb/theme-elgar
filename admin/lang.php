<?
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');// Les variables si on ajax
include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');// Les fonctions si on ajax
include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');// Connexion à la db

//@todo pour plus de 2 langues : lors de l'ajout de connexion de langue : scanner les trad existante/connexe de l'id dest, pour listé ses connexions et les ajouters à la nouvelles (cas de 3 trad ou +)


function creat_table_lang(){
	$GLOBALS['connect']->query("
		CREATE TABLE IF NOT EXISTS `".$GLOBALS['table_lang']."` (
			`id` bigint(20) NOT NULL DEFAULT '0',
			`trad` bigint(20) NOT NULL DEFAULT '0',
			`lang` varchar(6) DEFAULT NULL,
			PRIMARY KEY (`id`,`trad`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	");
}


switch(@$_REQUEST['mode'])
{
	default:
		if(strpos(@$res['tpl'], '-liste') === false) 
		{
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

						// Si ouvert => on ferme
						if($("#lang .absolute").is(":visible")) $("#lang .absolute").fadeOut("fast");
						else
						{
							// On ajax le contenu des options d'edition des langues
							$.ajax({
								type: "POST",
								url: path+"theme/"+theme+"/admin/lang.php?mode=view",
								data: {
									"id": "<?=$id?>",
									"type": type,
									"nonce": $("#nonce").val()
								},
								success: function(html){ 
									// Si pas de layer on l'inject // Sinon on remplace le contenu et ouvre
									if(!$("#lang .absolute").length) $("#lang").append(html);
									else $("#lang .absolute").replaceWith(html).fadeIn();
								}
							});
						}
					}
				);
			});

			// Action avant la supp
			before_del.push(function()
			{
				$.ajax({
					type: "POST",
					url: path+"theme/"+theme+"/admin/lang.php?mode=del",
					data: {
						"id": "<?=$id?>",
						"nonce": $("#nonce").val()
					},
					success: function(html){
						$("body").append(html);
					}
				});
			});
		</script>
		<?
		}
	break;


	case"view":

		$lang = get_lang();// Sélectionne  la langue
		load_translation('api');// Chargement des traductions du système
		load_translation('theme');// Chargement des traductions du theme

		login('high', 'edit-'.encode($_REQUEST['type']));// Vérifie que l'on a le droit d'éditer les contenus de ce type

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
			$sql.=' LIMIT 20';//.count($GLOBALS['language'])
			//echo $sql;
			$sel_lang = $connect->query($sql);
			if(!empty($sel_lang->num_rows))// Si des résultat & que la table existe
			{
				while($res_lang = $sel_lang->fetch_assoc())
				{
					// Pour savoir les traductions déjà disponible
					$traduction[$res_lang['lang']] = true;

					echo'<li data-id="'.$res_lang['id'].'">';

						echo'<a href="'.make_url($res_lang['url'], array('domaine' => $GLOBALS['scheme'].$GLOBALS['domain_lang'][$res_lang['lang']].$GLOBALS['path'])).'" lang="'.$res_lang['lang'].'" target="_blank">'.$res_lang['title'].'</a>';

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
				Connecter : 
				<input type="text" id="connecteur" placeholder="Nom de la traduction" class="w60">
				<!-- <button id="connecter" class="small"><?_e("Ajouter")?> <i class="fa fa-fw fa-plus"></i></button> -->
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
						?><input type="radio" name="dupliquer" id="lang-<?=$value?>" value="<?=$value?>"<?=($i==1?' checked':'')?>> <label for="lang-<?=$value?>"><?_e($value)?></label> <?
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


				// Autocomplete pour l'ajout d'une traduction existante à la page courante
				$("#connecteur").autocomplete({
					minLength: 0,
					source: path+"theme/"+theme+"/admin/lang.php?mode=links&id=<?=(int)$_REQUEST['id']?>&nonce="+ $("#nonce").val(),
					select: function(event, ui) 
					{ 				
						console.log(ui)	;

						// Ajout à la bdd
						$.ajax({
							type: "POST",
							url: path+"theme/"+theme+"/admin/lang.php?mode=add",
							data: {
								"id-source": '<?=(int)$_REQUEST['id']?>',
								"lang-source": $("html").attr("lang"),
								"id-dest": ui.item.id,
								"lang-dest": ui.item.lang,
								"nonce": $("#nonce").val()
							},
							success: function(html){ 
								if(!html) {
									// Ajout de la ligne
									$("#list-trad").append('<li data-id="'+ui.item.id+'"><a href="'+ui.item.value+'" target="_blank">'+ui.item.label+'</a> '+ui.item.lang+' <i class="fa fa-trash pointer grey"></i></li>');

									// Supp la ligne vide
									$("#list-trad .empty").remove();
								}
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


			    // Duplique la page en cours
				$("#dupliquer").click(function() 
				{	
					// Animation duplication en cours (loading)
					$("#dupliquer i").removeClass("fa-doc-text").addClass("fa-spin fa-cog");

					// Envoi de la requête de duplication
					$.ajax({
						type: "POST",
						url: path+"theme/"+theme+"/admin/lang.php?mode=duplique",
						data: {
							"type": type,
							"id": "<?=(int)$_REQUEST['id']?>",
							"lang-dest": $(".duplicateur input[name='dupliquer']:checked").val(),
							"lang-source": $("html").attr("lang"),
							"nonce": $("#nonce").val()
						},
						success: function(html){
							$("body").append(html);// Affichage/exécution du retour
						}
					});
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



	case "duplique":

		login('high', 'edit-'.encode($_REQUEST['type']));// Vérifie que l'on a le droit d'éditer les contenus


		// Récupération des données de la fiche en cours
		$sel = $connect->query("SELECT * FROM ".$tc." WHERE id='".(int)$_REQUEST['id']."'");
		$res = $sel->fetch_assoc();
		$fiche = json_decode($res['content'], true);

		$fiche['title'] = $fiche['title'].' '.strtoupper(encode($_REQUEST['lang-dest']));// Changement du titre H1
		//unset($fiche['og-image'], $fiche['visuel']);

		$json_content = json_encode($fiche, JSON_UNESCAPED_UNICODE);


		// Création de la fiche avec les données copier
		$sql = "INSERT ".$table_content." SET ";
		$sql .= "lang = '".encode($_REQUEST['lang-dest'])."', ";
		$sql .= "type = '".$res['type']."', ";
		$sql .= "tpl = '".$res['tpl']."', ";
		$sql .= "url = '".encode($fiche['title'])."', ";
		$sql .= "title = '".addslashes($fiche['title'])."', ";
		$sql .= "description = '".addslashes($res['description'])."', ";
		$sql .= "content = '".addslashes($json_content)."', ";
		$sql .= "user_insert = '".(int)$_SESSION['uid']."', ";
		$sql .= "date_insert = NOW() ";
		
		$connect->query($sql);


		// Si il y a une erreur
		if($connect->error)
			echo htmlspecialchars($sql)."\n<script>error(\"".htmlspecialchars($connect->error)."\");</script>";

		// Sauvegarde réussit
		else 
		{
			$id = $connect->insert_id;

			// Création de la table de connexion des traductions si pas existante
			creat_table_lang();

			// Connexion table traduction
			$sql = "INSERT LOW_PRIORITY INTO ".$tl." (`id`, `trad`, `lang`) VALUES
			('".(int)$_REQUEST['id']."', '".$id."', '".encode($_REQUEST['lang-dest'])."'),
			('".$id."', '".(int)$_REQUEST['id']."', '".encode($_REQUEST['lang-source'])."')";

			$connect->query($sql);

			if($connect->error) echo $connect->error;

			
			// Pose un cookie pour demander l'ouverture de l'admin automatiquement au chargement
			setcookie("autoload_edit", "true", time() + 60*60, $GLOBALS['path'], $GLOBALS['domain']);
			
			?>
			<script>
			$(function()
			{		
				// Redirection vers la page crée
				document.location.href = "<?=make_url(encode($fiche['title']), array("domaine" => $GLOBALS['scheme'].$GLOBALS['domain_lang'][encode($_REQUEST['lang-dest'])].$GLOBALS['path']));?>";
			});
			</script>
			<?
		}

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
				$("#list-trad [data-id='<?=(int)$_REQUEST['id']?>']")
				.slideUp("700", function(){
					$(this).remove();
				});
			</script>
			<?
		}

		exit;
		
	break;



	case"add":

		login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

		// Création de la table de connexion des traductions si pas existante
		creat_table_lang();

		// Ajout de la connexion
		$sql = "INSERT LOW_PRIORITY INTO ".$tl." (`id`, `trad`, `lang`) VALUES
		('".(int)$_REQUEST['id-source']."', '".(int)$_REQUEST['id-dest']."', '".encode($_REQUEST['lang-dest'])."'),
		('".(int)$_REQUEST['id-dest']."', '".(int)$_REQUEST['id-source']."', '".encode($_REQUEST['lang-source'])."')";

		$connect->query($sql);

		if($connect->error) echo $connect->error;

		exit;
		
	break;

	
}
?>