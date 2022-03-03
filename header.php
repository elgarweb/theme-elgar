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

		<article class="flex wrap jcsa brd-bot-alt ptm pbs">

			<!-- Conformité -->
			<?php txt('texte-conformite'); ?>

			<!-- Accessibilité -->
			<div>

				<nav role="navigation" aria-label="<?php _e("Quick access")?>" class="inline"><a href="#main" class="acces-rapide"><?php _e("Skip to content")?></a></nav>
				|
				<input type="checkbox" name="high-contrast" id="high-contrast"<?=(@$_COOKIE['high-contrast']?'checked="checked"':'')?>> <label class="color tdu" for="high-contrast"><?php _e("Enhanced contrast")?></label>

			</div>

			<!-- Changement de site en fonction de la langue -->
			<?
			if($lang=='fr') $switch_lang='eu';
			else $switch_lang='fr';
			?>
			<a href="<?=$GLOBALS['scheme'].$GLOBALS['domain_lang'][$switch_lang].$GLOBALS['path'];?>" lang="<?=$switch_lang?>"><?=$GLOBALS['translation']['home other language'][$switch_lang]?></a>

		</article>

		<article class="flex wrap jcsb aic ptm">

			<div><a href="<?=$GLOBALS['home']?>"><?php media('logo', array('size' => '330x70', 'lazy' => 'true'))?></a></div>
			
			<!-- Formulaire de recherche -->
			<form role="search" id="rechercher" action="/recherche/" method="post">
				
				<div id="input-recherche" class="inbl">
					
					<label for="recherche"><?_e("Search the site")?></label>	

					<div class="flex">

						<input type="search" name="recherche" id="recherche">
						
						<button type="submit" class="bg-color-alt pat pls prs" value="<?php _e("Search")?>" aria-label="<?php _e("Search")?>">
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>

						<script>
							$("#rechercher").on("submit", function(event) {
								event.preventDefault();
								var url = "/recherche/" + $("#rechercher input").val().replace(/\s+/g, '-').toLowerCase();
								document.location.href = url;
							});
						</script>

					</div>

				</div>
				
			</form>

		</article>
	</section>
		
	<!-- Menu principal -->
	<section class="bg-color">

		<nav role="navigation" class="mw1044p center brd-top-alt mtl tc" aria-label="<?php _e("Browsing menu")?>">

			<button type="button" class="burger" aria-expanded="false" aria-controls="header-menu">
				<span class="open">Menu</span>
				<span class="close none"><?php _e("Close")?></span>
			</button>

			<ul id="header-menu" class="flex space-l bold pll prl">
				<?php				
				// Extraction du menu
				foreach($GLOBALS['nav'] as $cle => $val)
				{
					// Menu sélectionné si page en cours // @$res['type'] == "article" and $val['href'] == "actualites"  or ()
					if(get_url() == $val['href'] or @array_keys($GLOBALS['filter'])[0] == basename($val['href']))
						$selected = " selected";
					else
						$selected = "";

					echo"<li class='".$selected."'><a href=\"".make_url($val['href'], array("domaine" => true))."\"".($val['id']?" id='".$val['id']."'":"")."".($val['target']?" target='".$val['target']."'":"")." class='white tdn''".($selected?' title="'.$val['text'].' - '.__("current page").'"':'').">".$val['text']."</a></li>";
				}
				?>
			</ul>

		</nav>


	</section>

</header>
