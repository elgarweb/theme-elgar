<?php
if(!$GLOBALS['domain']) exit;

// @todo voir pour mettre le fil d'ariane dans une ol/li => problème avec système de tag+edition
// Peut-être injecter accueil dans la liste de tag et le supp au moment du save...
?>
<div class="mw960p ariane mod center ptl pbl">

	<nav role="navigation" aria-label="<?php _e("Breadcrumb")?>" class="fl" itemprop="breadcrumb">

		<a href="/"><?php _e("Home")?></a>

		<?php 
		if(@$res['type']=='article'){?><a href="/<?=encode(__("Actualités"))?>"><?php _e("Actualités")?></a><?}
		elseif(@$res['type']=='event' or @$res['type']=='event-tourinsoft'){?><a href="/<?=encode(__("Agenda"))?>"><?php _e("Agenda")?></a><?}
		elseif(@$res['type']=='annuaire'){?><a href="/<?=encode(__("Annuaire"))?>"><?php _e("Annuaire")?></a><?}
		?>

		<?php tag('navigation', array('tag' => 'span', 'separator' => ' > '));?>

		<?php 
		//if(isset($GLOBALS['tags']) and isset($res['title'])) echo' > ';
		if(isset($res['title'])) echo'<span aria-current="page">'.$res['title'].'</span>';
		?>
		
	</nav>


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
	


	<ul class="unstyled fr">
	 	<?php
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
			while($res_lang = $sel_lang->fetch_assoc())
			{
				echo'<li><a href="'.make_url($res_lang['url'], array('domaine' => $GLOBALS['scheme'].@$GLOBALS['domain_lang'][$res_lang['lang']].$GLOBALS['path'])).'" lang="'.$res_lang['lang'].'">'.$GLOBALS['translation']['other language'][$res_lang['lang']].'</a></li>';
			}
		}
		?>
	</ul>

</div>