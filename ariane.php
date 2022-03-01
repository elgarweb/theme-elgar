<?php
if(!$GLOBALS['domain']) exit;

// @todo voir pour mettre le fil d'ariane dans une ol/li => problème avec système de tag+edition
// Peut-être injecter accueil dans la liste de tag et le supp au moment du save...
?>
<div class="ariane mod ptm pbm">

	<nav role="navigation" aria-label="<?php _e("Breadcrumb")?>" class="fl" itemprop="breadcrumb">
		<a href="/"><?php _e("Home")?></a> >

		<?php if(@$res['type']=='article'){?><a href="/<?encode(__("Actualités"))?>"><?php _e("Actualités")?></a> > <?}?>
		<?php if(@$res['type']=='event' or @$res['type']=='event-tourinsoft'){?><a href="/<?encode(__("Agenda"))?>"><?php _e("Agenda")?></a> > <?}?>

		<?php tag('navigation', array('tag' => 'span', 'separator' => ' > '));?>
	</nav>


	<script>
		<?if(isset($GLOBALS['tags'])) {// Si tag de navigation on met en selected dans la navigation principal?>
			$("[href$='<?=array_keys($GLOBALS['tags'])[0]?>']").parent().addClass("selected");
		<?}?>
		<?if(@$res['type']=='article') {?>
			$("[href$='actualites'").parent().addClass("selected");
		<?}?>
		<?if(@$res['type']=='event' or $res['type']=='event-tourinsoft') {?>
			$("[href$='agenda'").parent().addClass("selected");
		<?}?>
	</script>
	
	

	<?php
	// @todo Si une traduction de la page courante existe on propose le lien vers la page traduite
	?>
	<a href="" class="fr">Version basque</a>

</div>