<?php if(!$GLOBALS['domain']) exit;

$admin_intranet = true;

// @todo test redirection

//echo 'auth intranet:'.@$_SESSION['auth']['intranet'].'<br>';
//print_r(@$_SESSION['auth']);
//echo '<br>intranet:'.@$content['intranet'];

// Page normale ou si page intranet + autorisation intranet
if(@$content['intranet'] != 'true' or (@$content['intranet'] == 'true' and (isset($_SESSION['auth']['intranet']) or isset($_SESSION['auth']['edit-page'])))) 
{?>
<section class="mw960p center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<article>

		<?php txt('texte'); ?>

	</article>

</section>
<?php
}
// Si page intranet et pas autorisation intranet => connexion
elseif(@$content['intranet'] == 'true' and !isset($_SESSION['auth']['intranet']))
{
	// Pour le fil d'ariane
	$title = $content['title'] = __('Log in');

    // Pour bien rediriger vers la page courante
    $_SERVER['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];

	include('theme/'.$GLOBALS['theme'].'/tpl/connexion.php');
}
?>