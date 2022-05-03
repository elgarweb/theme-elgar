<?
include_once("../../../config.php");// Variables
include_once("../../../api/function.php");// Fonctions
include_once("../../../api/db.php");// Connexion à la db

$lang = get_lang();// Sélectionne  la langue
load_translation('api');// Chargement des traductions du système


if(!isset($_GET['mode'])) $_GET['mode'] = null;

login('high', 'view-stats');// Vérifie qu'on a les droits

?>
<div class="dialog">

	<iframe plausible-embed src="https://plausible.io/share/<?=$GLOBALS['plausible']?>?auth=<?=$GLOBALS['plausible_auth']?>&embed=true&theme=light&background=%23fff" scrolling="no" frameborder="0" loading="lazy" style="width: 1px; min-width: 100%; height: 1600px;"></iframe>
	<script async src="https://plausible.io/js/embed.host.js"></script>

</div>