<?php if(!$GLOBALS['domain']) exit;?>

<!--
@todo Simon :
- bouton choix langue
- formulaire de recherche

@todo Stéphanie :
- soulignement nav selected : pb si une seule ligne
-->

<style>
	/* 
	Bug style="width: 1024px;" se met en mode édition ??? 
	A supprimer quand corrigé
	*/
	.lucide #texte-conformite {
		width: 33% !important;
	}
</style>

<header role="banner">

	<section class="mw1044p mod center relative pls prs">

		<article class="flex wrap jcsa brd-bot ptm pbs">

			<!-- Conformité -->
			<?php txt('texte-conformite'); ?>

			<!-- Accessibilité -->
			<div>

				<nav role="navigation" aria-label="<?php _e("Quick access")?>" class="inline"><a href="#main" class="acces-rapide"><?php _e("Skip to content")?></a></nav>
				|
				<input type="checkbox" name="high-contrast" id="high-contrast"<?=(@$_COOKIE['high-contrast']?'checked="checked"':'')?>> <label for="high-contrast"><?php _e("Enhanced contrast")?></label>

			</div>

			<!-- Choix langue -->
			<a href="<?=$GLOBALS['scheme'].$GLOBALS['domain_lang']['eu'].$GLOBALS['path'];?>"><?php _e("Accueil basque"); ?></a>

		</article>

		<article class="flex wrap jcsb aic ptm">

			<div><a href="<?=$GLOBALS['home']?>"><?php media('logo', array('size' => '330x70', 'lazy' => 'true'))?></a></div>
			
			<!-- Formulaire de recherche -->
			<form role="search" id="rechercher" action="/rechercher" method="post">
				
				<div id="input-recherche" class="inbl">
					
					<label for="recherche"><?txt('label-recherche')?></label>	

					<div class="flex">

						<input type="search" name="recherche" id="recherche" required=""/>
						
						<a href="javascript:$('#rechercher').submit();void(0)" class="bg-color bt pat pls prs">
							<i class="fa fa-search pan"></i>
						</a>

					</div>

				</div>
				
			</form>

		</article>
	</section>
		
		<!-- Menu principal -->
		<section class="bg-color-alt">

		<nav role="navigation" class="mw1044p center brd-top mtl tc" aria-label="<?php _e("Browsing menu")?>">

			<button type="button" class="burger" aria-expanded="false" aria-controls="header-menu">
				<span class="open">Menu</span>
				<span class="close none"><?php _e("Close")?></span>
			</button>
			
			<ul id="header-menu" class="flex space-l aic bold pll prl">
				<?php
				// Extraction du menu
				foreach($GLOBALS['nav'] as $cle => $val)
				{
					// Menu sélectionné si page en cours ou article (actu)
					if(get_url() == $val['href'] or (@$res['type'] == "article" and $val['href'] == "actualites"))
						$selected = " selected";
					else
						$selected = "";

					echo"<li class='relative pbm ".$selected."'><a href=\"".make_url($val['href'], array("domaine" => true))."\"".($val['id']?" id='".$val['id']."'":"")."".($val['target']?" target='".$val['target']."'":"")." class='color-nav'".($selected?' title="'.$val['text'].' - '.__("current page").'"':'').">".$val['text']."</a></li>";
				}
				?>
			</ul>

		</nav>


	</section>

</header>
