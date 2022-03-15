<?php if(!$GLOBALS['domain']) exit;?>

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

	<section class="mw960p mod center relative">

		<div class="flex wrap center space-l jcc tc brd-bot-alt ptm pbs">

			<!-- Conformité -->
			<?php txt('texte-conformite', 'mrm'); ?>

			<!-- Accessibilité -->
			<div class="prm">

				<nav role="navigation" aria-label="<?php _e("Quick access")?>" class="inline"><a href="#main" class="acces-rapide"><?php _e("Skip to content")?></a></nav>
				|
				<input type="checkbox" name="high-contrast" id="high-contrast"<?=(@$_COOKIE['high-contrast']?'checked="checked"':'')?>> <label class="color tdu" for="high-contrast"><?php _e("Enhanced contrast")?></label>
				|
				<a href="/<?=encode(__("Contact"))?>"><?php _e("Contact")?></a>
				
			</div>

			<!-- Changement de site en fonction de la langue -->
			<?
			if($lang=='fr') $switch_lang='eu';
			else $switch_lang='fr';
			?>
			<a href="<?=$GLOBALS['scheme'].$GLOBALS['domain_lang'][$switch_lang].$GLOBALS['path'];?>" lang="<?=$switch_lang?>"><?=$GLOBALS['translation']['home other language'][$switch_lang]?></a>

		</div>


		<div class="flex wrap jcsb aic ptm">

			<!-- Logo -->
			<div><a href="<?=$GLOBALS['home']?>"><?php media('logo', array('size' => '330x70', 'lazy' => 'true'))?></a></div>
			
			<!-- Formulaire de recherche -->
			<form role="search" id="rechercher" action="/recherche/" method="post">
				
				<div id="input-recherche" class="inbl">
					
					<label for="recherche"><?_e("Search the site")?></label>	

					<div class="flex">

						<input type="search" name="recherche" id="recherche">
						
						<button type="submit" class="bg-green pat" value="<?php _e("Search")?>" aria-label="<?php _e("Search")?>">
							<i class="fa fa-fw fa-search" aria-hidden="true"></i>
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

		</div>

	</section>
		


	<!-- Menu principal -->
	<section class="bg-color">

		<nav role="navigation" class="mw960p center brd-top-alt mtl tc" aria-label="<?php _e("Browsing menu")?>">

			<button type="button" class="burger" aria-expanded="false" aria-controls="header-menu">
				<span class="open">Menu</span>
				<span class="close none"><?php _e("Close")?></span>
			</button>

			<ul id="header-menu" class="flex wrap space bold plm prm">
				<?php				
				// Extraction du menu
				foreach($GLOBALS['nav'] as $cle => $val)
				{
					// Menu sélectionné si page en cours // @$res['type'] == "article" and $val['href'] == "actualites"  or ()
					if(get_url() == $val['href'] or @array_keys($GLOBALS['filter'])[0] == basename($val['href']))
						$selected = true;
					else
						$selected = false;

					echo'<li class="'.$selected.'">
						<a href="'.make_url($val['href'], array('domaine' => true)).'"'.
						($val['id']?' id="'.$val['id'].'"':'').
						($val['target']?' target="'.$val['target'].'"':'').
						($selected?' class="selected" title="'.$val['text'].' - '.__("current page").'"':'').
						'>'.$val['text'].'</a>
					</li>';
				}
				?>
			</ul>

		</nav>

	</section>



	<!-- ZONE ALERTE -->
	<?php
	$alert_view = false;
	
	if(
		isset($GLOBALS['content']['alerte-texte']) and
		date('Y-m-d') >= @$GLOBALS['content']['alert-date-debut'] and
		date('Y-m-d') <= @$GLOBALS['content']['alert-date-fin']
	)
		$alert_view = true;

	if(!$alert_view){?>
		<button class="editable-hidden tc w100" onclick="$('#alert').slideToggle();">Éditer l'alerte <i class="fa fa-attention grey" aria-hidden="true"></i></button>
	<?php }?>

	<section id="alert" class="bg-grey<?=(!$alert_view?' none':'');?>">

		<div class="editable-hidden pts tc">
			<label for="alert-date-debut">Date de début d'affichage de l'alerte</label> <?input('alert-date-debut', array('type' => 'date'))?>
		 	<label for="alert-date-fin">Date de fin</label> <?input('alert-date-fin', array('type' => 'date'))?>
		</div>

		<article class="mw960p mod flex wrap space-l aic jcc center pam">
						
			<?php media('alerte-img', array('size' => '300', 'lazy' => true)); ?>
						
			<?php txt('alerte-texte', 'mw600p bold bigger'); ?>
			
		</article>

	</section>


</header>