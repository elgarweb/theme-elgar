<?php
if(!$GLOBALS['domain']) exit;

// @todo voir pour mettre le fil d'ariane dans une ol/li => problème avec système de tag+edition
// Peut-être injecter accueil dans la liste de tag et le supp au moment du save... séparateur "/"
?>
<div class="ariane mod ptm pbm">

	<nav role="navigation" aria-label="<?php _e("Breadcrumb")?>" class="fl">
		<a href="/"><?php _e("Home")?></a>
		/
		<?php
		// @todo
		// Si page "navigation" on récupère le nom du tag/filtre

		// Sinon on propose de saisir le tag correspondant
		tag('navigation', array('tag' => 'span', 'separator' => ' / '));//'itemprop' => 'breadcrumb',
		?>
	</nav>

	<?php
	// @todo Si une traduction de la page courante existe on propose le lien vers la page traduite
	?>
	<a href="" class="fr">Version basque</a>

</div>