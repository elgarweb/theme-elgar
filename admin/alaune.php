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

		// Supprime les alaune
		$connect->query("DELETE FROM ".$table_meta." WHERE type='alaune' and cle='".$lang."'");

		if($_REQUEST['checked'] == 'true')
			$connect->query("INSERT INTO ".$table_meta." SET id='".(int)$_REQUEST['id']."', type='alaune', cle='".$lang."'");

		echo $connect->error;

		exit;
		
	break;

	default:
		?>
		<script>
			// Action si on lance le mode d'edition
			edit.push(function()
			{
				// A LA UNE
				// Ajout du bouton a la une
				$("#admin-bar").append("<div class='alaune fr mat mrs switch o50 ho1 t5'><input type='checkbox' id='alaune' class='none'><label for='alaune' title=\"A la une\"><i></i></label></div>");

				// Position du bouton par défaut
				<?
				$sel_alaune = $connect->query("SELECT * FROM ".$table_meta." WHERE id='".$res['id']."' AND type='alaune' AND cle='".$lang."' LIMIT 1");
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
						url: path+"theme/<?=$GLOBALS['theme']?>/admin/alaune.php?mode=save",
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