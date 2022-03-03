<?
switch(@$_REQUEST['mode'])
{
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

	case"info":
		// bouton dans la barre du haut avec icon trad + lang en cours
		// au clique sur le bouton un layer/popin pour 
		// - proposer de créer la meme page dans une autre langue (menu select de langue), checkbox pour copier le contenu de la page en cours. si toutes les lang prise on ne propose pas d'ajout
			// -> créer la page + copie le contenu + créer les connexions dans table lang
		// - propose de relier un contenu existant comme traduction (champs avec autocomplete), saisie du titre, ou id
		// - liste les pages connecters : affichage du titre, et de la lang, lien

	break;

	default:

		?>
		<style>
			#admin-bar .alaune.switch label
			{ background: #c8c8c8; }

			/* Désactivé */
			#admin-bar .alaune.switch label:before { content: "\e803"; font-size: 0.8em; }/*e806*/

			/* Couleur de fond si activé */
			#admin-bar .alaune.switch input[type=checkbox]:checked ~ label{ background: #fee566; }

			/* Activé  */
			#admin-bar .alaune.switch input[type=checkbox]:checked ~ label:before {	content: "\e803"; }
		</style>
		<script>
			// Action si on lance le mode d'edition
			edit.push(function()
			{
				// A LA UNE
				// Ajout du bouton a la une
				$("#admin-bar").append("<div class='alaune fr mat mrs switch o50 ho1 t5'><input type='checkbox' id='alaune' class='none'><label for='alaune' title=\"A la une\"><i></i></label></div>");

				// Position du bouton par défaut
				<?
				$sel_alaune = $connect->query("SELECT * FROM ".$table_meta." WHERE id='".$res['id']."' AND type='".$res['type']."' AND cle='alaune' LIMIT 1");
				$res_alaune = $sel_alaune->fetch_assoc();
				if(isset($res_alaune['id']))
				{?>
					$("#alaune").prop("checked", true);
				<?}
				?>

				// Action sur le bouton alaune
				$("#alaune").click(function(event)
				{					
					$.ajax({
						type: "POST",
						url: path+"theme/<?=$GLOBALS['theme']?>/alaune.php?mode=save",
						data: {
							"id": id,
							"type": "<?=$res['type']?>",
							"checked": $(this).prop("checked"),
							"nonce": $("#nonce").val()
						},
						success: function(html){ }
					});
				});

			});
		</script>
	<?
	break;
}
?>