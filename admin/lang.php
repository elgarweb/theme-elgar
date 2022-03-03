<?
switch(@$_REQUEST['mode'])
{
	default:

		?>
		<script>
			// Action si on lance le mode d'edition
			edit.push(function()
			{
				// Ajout du bouton langue avec la langue en cours
				$("#admin-bar #del").before('<div id="lang" class="fr"><button class="mat small o50 ho1 t5" title="<?_e("Language")?>"><span class="noss"><?_e("Language")?></span> '+$("html").attr("lang")+' <i class="fa fa-fw fa-language"></i></button></div>');

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
		include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');// Les variables si on ajax
		include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');// Les fonctions si on ajax
		include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');// Connexion à la db

		$lang = get_lang();// Sélectionne  la langue
		load_translation('api');// Chargement des traductions du système
		load_translation('theme');// Chargement des traductions du theme

		login('high', 'edit-page');// Vérifie que l'on a le droit d'éditer les contenus

		// - proposer de créer la meme page dans une autre langue (menu select de langue), checkbox pour copier le contenu de la page en cours. si toutes les lang prise on ne propose pas d'ajout
			// -> créer la page + copie le contenu + créer les connexions dans table lang
		// - propose de relier un contenu existant comme traduction (champs avec autocomplete), saisie du titre, ou id

		// @todo : lors de la suppression d'un contenu supp aussi les liaisons

		?>
		<div class="absolute tooltip pas mas mlt small mod">

			<!-- Liste les pages connecter -->
			<div>Traduction :</div>
			<ul>
		 	<?php
			$sql='SELECT '.$tc.'.url, '.$tc.'.lang AS lang, '.$tc.'.title, '.$tc.'.state FROM '.$tc;
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

					echo'<li>';

						echo'<a href="'.make_url($res_lang['url'], array('domaine' => $GLOBALS['scheme'].$GLOBALS['domain_lang'][$res_lang['lang']].$GLOBALS['path'])).'" lang="'.$res_lang['lang'].'">'.$res_lang['title'].'</a>';

						echo ' - '.$res_lang['lang'];

						if($res_lang['state'] != "active") echo' <i class="fa fa-eye-off" title="'.__("Deactivate").'"></i>';

					echo'</li>';
				}
			}
			else echo'<li class="empty">'.__("Aucune").'</li>';
			?>
			</ul>
			<hr class="mbs">


			<!-- Ajouter une connexion -->
			<div class="connecteur">
				<div>Connecter une traduction</div>
				<input type="text" id="connecteur" placeholder="Nom de la page" class="w50">
				<button id="connecter" class="small"><?_e("Ajouter")?> <i class="fa fa-fw fa-plus"></i></button>
			</div>


			<!-- Dupliquer pour traduire -->	
			<div class="duplicateur">
				<hr class="mbs">	
				<div>Dupliquer cette page pour la traduire</div>
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
				
				$("#connecteur").autocomplete({
					minLength: 0,
					source: path+"theme/<?=$GLOBALS['theme']?>/admin/lang.php?mode=links&nonce="+ $("#nonce").val(),
					select: function(event, ui) 
					{ 
						// S'il y a déjà un chemin présent ont ajouté à la suite avec juste la dernière partie | Cas tag
						if($(this).val().indexOf("/") !== -1)
						{
							// Ajoute le dernier terme au contenu courant (moins la saisie de recherche)
							$(this).val(function(index, value) {
								return value.substring(0, value.lastIndexOf('/')) +'/'+ ui.item.value.split("/").pop();
							});
						}
						else 
							$(this).val(ui.item.value);
			
						return false;// Coupe l'execution automatique d'ajout du terme
					}
				})
				.focus(function(){
					$(this).data("uiAutocomplete").search($(this).val());// Ouvre les suggestions au focus
				})
				.autocomplete("instance")._renderItem = function(ul, item) {// Mise en page des résultats
			      	return $("<li>").append("<div title='"+item.value+"'>"+item.label+" <span class='grey italic'>"+item.type+"</span></div>").appendTo(ul);
			    };
				

			});
		</script>
		<?php 

	break;


	case"save":

		include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');// Les variables si on ajax
		include_once($_SERVER['DOCUMENT_ROOT'].'/api/function.php');// Les fonctions si on ajax
		include_once($_SERVER['DOCUMENT_ROOT'].'/api/db.php');// Connexion à la db

		$lang = get_lang();// Sélectionne  la langue
		load_translation('api');// Chargement des traductions du système

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

		echo $connect->error;

		exit;
		
	break;

	
}
?>