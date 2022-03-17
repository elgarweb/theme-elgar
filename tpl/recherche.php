<?
if(!$GLOBALS['domain']) exit;

function highlight($txt, $recherche)
{
	/*$explode = explode(" ", $recherche);// Chaque élément

	$explode = array_filter($explode, function($v){ return strlen($v) > 2; });// Retire les éléments des moins de 2 lettres

	if($recherche) return preg_replace('/'.implode('|', $explode).'/i', '<span class="color-alt">$0</span>', $txt);
	else return $txt;*/

	return $txt;
}
?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>


	<h1 class="tc"><?txt('title')?></h1>


	<div class="w90 center">
	<?
	// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
	if(!@$_SESSION['auth']['edit-article']) $sql_state = "AND state='active'";
	else $sql_state = "";

	// Navigation par page
	$num_pp = 10;

	if(isset($GLOBALS['filter']['page'])) $page = (int)$GLOBALS['filter']['page']; else $page = 1;

	$start = ($page * $num_pp) - $num_pp;


	// Construction de la requete
	$sql ="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;


	$sql.=" WHERE url!='recherche' ".$sql_state." ";

	// RECHERCHE dans les titres //print_r($GLOBALS['filter']);
	if(@$_POST['recherche'] or $GLOBALS['filter'])
	{
		// On regarde si c'est un post (sumbit form) ou un get (filter)
		if(!@$_POST['recherche'] and $GLOBALS['filter']) 
			$_POST['recherche'] = str_replace("-", " " , array_keys($GLOBALS['filter'])[0]);
		else 
			$GLOBALS['filter'][] = @$_POST['recherche'];

		//echo"<br>filter after : ";print_r($GLOBALS['filter']);

		// Si plusieur argument
		$sql .= "AND (";
		$recherches = explode(" ", @$_POST['recherche']);
		foreach($recherches as $cle => $val) 
		{
			$recherche = trim($connect->real_escape_string($val));

			if($cle > 0) $sql.= " AND ";

			$sql.= "(title LIKE '%".$recherche."%' OR content LIKE '%".$recherche."%')";
		}
		$sql .= ")";
	}	

	$sql.=" ORDER BY ".$tc.".date_insert DESC
	LIMIT ".$start.", ".$num_pp;

	//echo $sql;

	$sel_fiche = $connect->query($sql);

	$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche
	?>


	<div class="tc">
		<?=$num_total.' '.__("result").($num_total>1?'s':'')?>
		<?=__("for")?>
		<strong><?=htmlspecialchars(strip_tags(@$_POST['recherche']));?></strong>
	</div>


	<?
	while($res_fiche = $sel_fiche->fetch_assoc())
	{
		$texte = null;

		// Affichage du message pour dire si l'article est invisible ou pas
		if($res_fiche['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
		else $state = "";

		$content_fiche = json_decode($res_fiche['content'], true);

		?>
		<article class="mod mtl">

			<h2 class="up bigger"><a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>" class="tdn"><?=highlight($res_fiche['title'], strip_tags(@$_POST['recherche']))?></a><?=$state?></h2>
			

			<?
			if(isset($content_fiche['description'])) $texte = $content_fiche['description'];
			elseif(isset($content_fiche['texte'])) $texte = $content_fiche['texte'];
			
			if(isset($texte))
				echo highlight(word_cut($texte, '350', '...', '<br><div>'), strip_tags(@$_POST['recherche']));
			?>


			<div class="fr mtm"><a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>" class="bt bold"><?_e("Lire")?></a></div>

		</article>
		<?
	}?>
	</div>

	<div class="tc mtl"><?page($num_total, $page);?></div>

</section>


<script>
	$(function()
	{
		// Remplis l'input recherche avec les mots-clés de la recherche
		<?if(@$_POST['recherche']) {?>$("#rechercher input").val("<?=htmlspecialchars($_POST['recherche'])?>")<?}?>
	});
</script>