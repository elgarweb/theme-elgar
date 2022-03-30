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


	<?php h1('title');?>


	<div class="w90 center">
	<?php 

	// Traitement des mots-clés de recherche
	if(!@$_POST['recherche'] and $GLOBALS['filter'] and key($GLOBALS['filter']) != 'page')// GET (filter)
	{
		$_POST['recherche'] = strip_tags(str_replace("-", " " , array_keys($GLOBALS['filter'])[0]));
	}
	else if(@$_POST['recherche'])// POST (sumbit form)
	{	
		// Pour l'url
		$GLOBALS['filter'][] = encode(strip_tags(@$_POST['recherche']));

		// Pour l'affichage et garder les accents avec la nav par page
		$_SESSION['recherche'] = $_POST['recherche'] = strip_tags(@$_POST['recherche']);
	}
	else 
	{
		// Page de recherche sans mots-clés
		$_SESSION['recherche'] = $_POST['recherche'] = '';
	}


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


	if(@$_POST['recherche'] or $GLOBALS['filter'])
	{
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
	//echo"<br>filter after : ";print_r($GLOBALS['filter']);


	$sel_fiche = $connect->query($sql);


	$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche
	?>


	<script>
		<?
		// JS pour que les mots de la recherche s'affichent bien tels que formaté à l'origine (accents)
		//if(@$_SESSION['recherche']) 
		{?>
			// Remplis l'input recherche avec les mots-clés de la recherche
			$("#rechercher input").val("<?=addslashes(@$_SESSION['recherche'])?>");


			// Change le title de la page
			document.title = "<?=addslashes($res['title']).' '.addslashes(@$_SESSION['recherche']). ($page>1?' - '.__('Page').' '.$page:'') .' - '.addslashes($GLOBALS['sitename']);?>";


			// Change le fil d'Ariane
				// Recherche : lien racine
				$("nav[itemprop='breadcrumb'] span[aria-current='page']").replaceWith("<a href=\"/<?=$res['url']?>\"><?=addslashes($res['title'])?></a>");

				// Mots clés de la recherche
				<?if(@$GLOBALS['filter']['page']){?>
					$("nav[itemprop='breadcrumb']").append("<a href=\"/<?=make_url($res['url'], array(@$_SESSION['recherche']));?>\"><?=addslashes(@$_SESSION['recherche'])?></a>");
				<?}
				else{?>
					$("nav[itemprop='breadcrumb']").append("<span aria-current='page'><?=addslashes(@$_SESSION['recherche'])?></span>");
				<?}?>

				// Page
				<?if($page>1){?>
					$("nav[itemprop='breadcrumb']").append("<span aria-current='page'><?=addslashes(__('Page').' '.$page)?></span>");
				<?}?>


			// Change l'url de la page			
			window.history.replaceState({}, document.title, "<?=make_url($res['url'], array_merge($GLOBALS['filter'], array("page" => $page, "domaine" => true)));?>");//history.state	
		<?}?>
	</script>


	<div class="tc">
		<?php 
		echo $num_total.' '.__("result").($num_total>1?'s':'');
		if(@$_SESSION['recherche']) echo ' '.__("for")." <strong>".htmlspecialchars(@$_SESSION['recherche'])."</strong>";
		?>
	</div>


	<?php 
	while($res_fiche = $sel_fiche->fetch_assoc())
	{
		$texte = null;

		// Affichage du message pour dire si l'article est invisible ou pas
		if($res_fiche['state'] != "active") $state = " <span class='deactivate' title=\"".__("Disabled page")."\"><i class='fa fa-eye-off' aria-hidden='true'></i></span>";
		else $state = "";

		$content_fiche = json_decode($res_fiche['content'], true);

		?>
		<article class="mod mtl">

			<h2><a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>" class="tdn"><?php echo highlight($res_fiche['title'], @$_POST['recherche'])?></a><?php echo $state?></h2>
			
			<?php
			if(isset($content_fiche['description'])) $texte = $content_fiche['description'];
			elseif(isset($content_fiche['texte'])) $texte = $content_fiche['texte'];
			
			if(isset($texte))
				echo highlight(word_cut($texte, '350', '...', '<br><i><div>'), @$_POST['recherche']);
			?>

			<div class="fr mtm"><a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>" class="bt bold" aria-label="<?php _e("Lire")?> <?php echo $res_fiche['title']?>"><?php _e("Lire")?></a></div>

		</article>
		<?php
	}?>
	</div>


	<div class="tc mtl"><?php page($num_total, $page, array('aria-label'=>__("Browsing by page")));?></div>


</section>