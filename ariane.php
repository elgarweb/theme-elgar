<?php if(!$GLOBALS['domain']) exit;?>

<div class="mw960p ariane mod center pbm">

	<nav role="navigation" aria-label="<?php _e("Breadcrumb")?>" class="fl ptm" itemprop="breadcrumb">
		<ul class="inline pln">

			<li class="inline"><a href="/"><?php _e("Home")?></a></li>

			<?php
			// Page navigation/carrefour
			//tag('navigation', array('tag' => 'span', 'separator' => ' > '));// ancienne version
			if($res['tpl']!='navigation')
			{
				// Si intranet on ajoute toutes les pages carrefour dans le choix du fil d'ariane
				if($intranet)
				{
					// @todo pour optim potentielement changer le chargement de cette requête que lors de l'édition, avec un inject avant édition dans les options du editable-select
					$sql='SELECT DISTINCT tpl, url, title FROM '.$tc.' WHERE lang="'.$lang.'" AND tpl LIKE "navigation%" ORDER BY url ASC LIMIT 20';
					//echo '<br>'.$sql.'<br>';
					$sel_nav = $connect->query($sql);
					while($res_nav = $sel_nav->fetch_assoc())
					{
						$navigation[$res_nav['url']] = $res_nav['title'];
					}
				}

				// Option du menu select pour accrocher à des pages carrefour
				$array_navigation = array('option' => json_encode($navigation, true), 'class'=>'meta');

				// Si un élément selectionner on affiche le lien, sinon c'est un span vide pas lisible
				if(isset($content['navigation'])) {
					$array_navigation['tag'] = 'a';
					$array_navigation['href'] = $GLOBALS['path'].encode(@$content['navigation']);
				}

				?><li class="inline"<?=(isset($content['navigation'])?'':' aria-hidden="true"')?>><?php
				select('navigation', $array_navigation);
				?></li><?php
			}

			// Si page fiche ou listing avec tag
			//if(@$res['type']=='article' or ($res['url'] == encode(__("Actualités")) and $tag))
			if(isset($url_back) and (@$res['type']==@$type or $tag or $GLOBALS['filter']))
			{
				// Supprime le nom de la page en cours pour le chemin
				if($tag or $GLOBALS['filter']) $title = preg_replace('/^'.preg_quote($res['title'].' - ', '/').'*/', '', $title);

				?><li class="inline"><a href="/<?=encode($url_back)?>"><?php echo ucfirst(__(encode($url_back)))?></a></li><?php
			}

			// Supprime le nom du site
			$title = preg_replace('/'.preg_quote(' - '.$GLOBALS['sitename']).'*$/', '', $title);

			//if(isset($GLOBALS['tags']) and isset($res['title'])) echo' > ';
			if(isset($title)) echo'<li class="inline" aria-current="page">'.$title.'</li>';
			?>
		</ul>
	</nav>



	<?php if((isset($GLOBALS['tags'])) || (@$res['type']=='article') || (@$res['type']=='event' or $res['type']=='event-tourinsoft')) { ?>
	<script>
		<?php if(isset($GLOBALS['tags'])) {// Si tag de navigation on met en selected dans la navigation principal?>
			$("header nav [href$='<?=array_keys($GLOBALS['tags'])[0]?>']").parent().addClass("selected");
		<?php }?>
		<?php if(@$res['type']=='article') {?>
			$("header nav [href$='actualites'").parent().addClass("selected");
		<?php }?>
		<?php if(@$res['type']=='event' or $res['type']=='event-tourinsoft') {?>
			$("header nav [href$='agenda'").parent().addClass("selected");
		<?php }?>
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
		echo '<ul class="unstyled fr ptm switch-lang">';
		while($res_lang = $sel_lang->fetch_assoc())
		{
			// Si un domaine pour la langue existe
			if(@$GLOBALS['domain_lang'][$res_lang['lang']])
			echo'<li><a href="'.make_url($res_lang['url'], array('domaine' => $GLOBALS['scheme'].@$GLOBALS['domain_lang'][$res_lang['lang']].$GLOBALS['path'])).'" lang="'.$res_lang['lang'].'">'.@$GLOBALS['translation']['language version'][$res_lang['lang']].'</a></li>';
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
			'<p class="printing clear ptm mbn tc"><i class="fa fa-print mrs" aria-hidden="true"></i>'.
			str_replace('*minute*', $minute,
			str_replace('*second*', $second,
				__("The average reading time for this page is *minute* minutes and *second* seconds. For less impact on the environment we recommend that you print it double-sided, black and white, 2 pages per sheet.")
			)).
			'</p>';
	}
	?>

</div>