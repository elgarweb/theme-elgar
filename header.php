<?php if(!$GLOBALS['domain']) exit;?>

<header role="banner">

	<section class="mw960p mod center relative">

		<div class="navigation-secondaire pts">
			
			<!-- Accessibilité -->
			<div id="accessibilite" class="fl pbs">
				
				<nav role="navigation" aria-label="<?php _e("Quick access")?>" class="inline">
					<ul class="exclude">
						<li><a href="#main" class="acces-rapide"><?php _e("Skip to content")?></a></li>
						<li><a href="/<?=encode(__("Contact"))?>"><?php _e("Contact")?></a></li>
						<li><a <?php href('lien-conformite')?>><?php txt('texte-conformite', array('tag'=>'span')); ?></a></li>
					</ul>
				</nav>	

				<div id="contrast" class="inbl">
					<input type="checkbox" name="high-contrast" id="high-contrast"<?=(@$_COOKIE['high-contrast']?'checked="checked"':'')?>> <label class="color" for="high-contrast"><?php _e("Enhanced contrast")?></label>
				</div>

			</div>

			<!-- Changement de site en fonction de la langue -->
			<?php
			if($lang=='fr') $switch_lang='eu';
			else $switch_lang='fr';

			if(isset($GLOBALS['domain_lang'][$switch_lang])){?>
			<a href="<?=$GLOBALS['scheme'].$GLOBALS['domain_lang'][$switch_lang].$GLOBALS['path'];?>" lang="<?=$switch_lang?>" class="fr pbs" id="switch-lang"><?=$GLOBALS['translation']['home other language'][$switch_lang]?></a>
			<?php }?>

		</div>


		<div class="clear flex wrap jcsb aic brd-top-2 ptm plt prt">

			<!-- Logo -->
			<div class="pts"><a href="<?=$GLOBALS['home']?>"><?php media('logo', array('size' => '330x90', 'lazy' => 'true'))?></a></div>
			
			<!-- Formulaire de recherche -->
			<form role="search" id="rechercher" action="/recherche" method="post">
				
				<div id="input-recherche" class="inbl">
					
					<label for="recherche"><?_e("Search the site")?></label>	

					<div class="flex">

						<input type="search" name="recherche" id="recherche">
						
						<button type="submit" class="bg-color-2 pat" value="<?php _e("Search")?>" aria-label="<?php _e("Search")?>">
							<i class="fa fa-fw fa-search" aria-hidden="true"></i>
						</button>

						<script>
							/*$("#rechercher").on("submit", function(event) {
								event.preventDefault();
								var url = "/recherche/" + $("#rechercher input").val().replace(/\s+/g, '-').toLowerCase();
								document.location.href = url;
							});*/
						</script>

					</div>

				</div>
				
			</form>

		</div>

	</section>
		


	<!-- Menu principal -->
	<section class="bg-color">

		<nav role="navigation" class="mw960p center brd-top-3 mtl tc" aria-label="<?php _e("Menu")?>">

			<button type="button" class="burger" aria-expanded="false" aria-controls="main-navigation">
				<span class="open"><?php _e("Menu")?></span>
				<!-- <span class="close none"><?php _e("Close")?></span> @supp 03/07/2024 état du menu est déjà donner par le lecteur d'écran -->
			</button>

			<ul id="main-navigation" class="flex wrap space-l">
				<?php		
				$navigation = [''=>__('Upper menu')];

				// Extraction du menu
				foreach($GLOBALS['nav'] as $cle => $val)
				{
					// Pour le fil d'ariane
					$navigation = array_merge($navigation, [$val['href'] => str_replace('<br>',' ', $val['text'])]);

					// Menu sélectionné si page en cours // @$res['type'] == "article" and $val['href'] == "actualites"  or ()
					if(get_url() == $val['href'] or @array_keys($GLOBALS['filter'])[0] == basename($val['href']))
						$selected = ' selected';
					else
						$selected = '';

					echo'<li class="'.$selected.'">
						<a href="'.make_url($val['href'], array('domaine' => true)).'"'.
						($val['id']?' id="'.$val['id'].'"':'').
						($val['target']?' target="'.$val['target'].'"':'').
						($selected?' class="selected" title="'.str_replace('<br>', ' ', $val['text']) .' - '.__("current page").'"':'').
						'>'.$val['text'].'</a>
					</li>';
				}
				?>
			</ul>

		</nav>

	</section>

</header>