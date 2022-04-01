<?php if(!$GLOBALS['domain']) exit;?>

<div class="mw960p ariane mod center ptm pbm">

	<nav role="navigation" aria-label="<?php _e("Breadcrumb")?>" class="fl" itemprop="breadcrumb">

		<a href="/"><?php _e("Home")?></a>


		<?php 
		// Page navigation/carrefour
		//tag('navigation', array('tag' => 'span', 'separator' => ' > '));// ancienne version
		if($res['tpl']!='navigation')
		{
			$array_navigation = array('option' => json_encode($navigation, true), 'class'=>'meta');

			// Si un élément selectionner on affiche le lien, sinon c'est un span vide pas lisible
			if(isset($content['navigation'])) {
				$array_navigation['tag']= 'a';
				$array_navigation['href']= $GLOBALS['path'].encode(@$content['navigation']);
			}

			select('navigation', $array_navigation);
		}
		?>


		<?php 
		// Si page fiche ou listing avec tag
		//if(@$res['type']=='article' or ($res['url'] == encode(__("Actualités")) and $tag))
		if(isset($url_back) and (@$res['type']==@$type or $tag or $GLOBALS['filter']))
		{
			// Supprime le nom de la page en cours pour le chemin
			if($tag or $GLOBALS['filter']) $title = preg_replace('/^'.preg_quote($res['title'].' - ').'*/', '', $title);

			?><a href="/<?=encode($url_back)?>"><?php _e(encode($url_back))?></a><?
		}
		?>


		<?php 
		// Supprime le nom du site
		$title = preg_replace('/'.preg_quote(' - '.$GLOBALS['sitename']).'*$/', '', $title);

		//if(isset($GLOBALS['tags']) and isset($res['title'])) echo' > ';
		if(isset($title)) echo'<span aria-current="page">'.$title.'</span>';
		?>
		
	</nav>



	<?if((isset($GLOBALS['tags'])) || (@$res['type']=='article') || (@$res['type']=='event' or $res['type']=='event-tourinsoft')) { ?>
	<script>
		<?if(isset($GLOBALS['tags'])) {// Si tag de navigation on met en selected dans la navigation principal?>
			$("header nav [href$='<?=array_keys($GLOBALS['tags'])[0]?>']").parent().addClass("selected");
		<?}?>
		<?if(@$res['type']=='article') {?>
			$("header nav [href$='actualites'").parent().addClass("selected");
		<?}?>
		<?if(@$res['type']=='event' or $res['type']=='event-tourinsoft') {?>
			$("header nav [href$='agenda'").parent().addClass("selected");
		<?}?>
	</script>
	<?php } 



 	// Si une traduction de la page courante existe on propose le lien vers la page traduite
	$sql='SELECT '.$tc.'.url, '.$tc.'.lang FROM '.$tc;
	$sql.=' JOIN '.$tl.'
	ON
	(
		'.$tl.'.id = '.$id.' AND
		'.$tl.'.trad = '.$tc.'.id

	)';
	$sql.=' WHERE state="active"';
	$sql.=' ORDER BY '.$tc.'.lang ASC';
	$sql.=' LIMIT '.count($GLOBALS['language']);
	//echo $sql;
	$sel_lang = $connect->query($sql);
	if(!empty($sel_lang->num_rows))// Si des résultat & que la table existe
	{
		echo '<ul class="unstyled fr">';
		while($res_lang = $sel_lang->fetch_assoc())
		{
			echo'<li><a href="'.make_url($res_lang['url'], array('domaine' => $GLOBALS['scheme'].@$GLOBALS['domain_lang'][$res_lang['lang']].$GLOBALS['path'])).'" lang="'.$res_lang['lang'].'">'.$GLOBALS['translation']['other language'][$res_lang['lang']].'</a></li>';
		}
		echo '</ul>';
	}



	// Calcule du temps de lecture du texte de la page pour suggestion d'impression
	// Vitesse de lecture moyenne entre 230 et 280 mots par minute, donc 200 pour plus de confort
	$word = str_word_count(strip_tags(@$GLOBALS['content']['description'].@$GLOBALS['content']['texte']));
	$minute = floor($word / 200);
	$second = floor($word % 200 / (200 / 60));

	// time > 3m24s => 204s
	if(($minute*60+$second)>204){
		echo 
			'<div class="clear pts tc"><i class="fa fa-print mrs" aria-hidden="true"></i>'.
			str_replace('*minute*', $minute,
			str_replace('*second*', $second,
				__("The average reading time for this page is *minute* minutes and *second* seconds. For less impact on the environment we recommend that you print it double-sided, black and white, 2 pages per sheet.")
			)).
			'</div>';
	}
	?>

</div>