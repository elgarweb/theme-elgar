<?
if(!isset($GLOBALS['domain'])) exit;

// Table des traductions
$GLOBALS['table_lang'] = $GLOBALS['tl'] = $GLOBALS['db_prefix'].'lang';

// Fonction affichage blocs img + titre + extrait texte
function block($url_img, $url_title, $title, $description, $date = null, $tag = null)
{
	global $res, $articles, $state;

    /* Ajout espaces insécables */
    $search = array("« ", " »", " ?");
    $replace = array("«&nbsp;", "&nbsp;»", "&nbsp;?");

    ?>

    <div class="relative brd-top-alt brd brd-rad-bot-right pbl">

		<!-- Affichage état article si désactivé -->
		<div class="color-alt tc bold">
			<?=$state?>
		</div>
	    
		<a href="<?=make_url($url_title, array("domaine" => true));?>" title="<?=$title?>" class="tdn">

			<article>

				<!-- Image -->
				<?php //Affichage images des 3 premières actus seulement
				if($url_img != '') { ?>
				<figure>

					<div class="nor" data-bg="<?=(isset(parse_url($url_img)['scheme'])?'':$GLOBALS['home']).$url_img?>" data-lazy="bg" style="width: 100%; height: 225px;">
					</div>

				</figure>
				<?php } ?>
				
				<div class="<?= ($res['tpl'] == 'home') ? 'grid3row ' :'grid4row ' ?>pam<?= ($url_img != '') ? ' brd-top' : '' ?>">

					<!-- Tag  (que sur le listing des articles car query + longue)-->
					<?php if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste') { ?>

						<div class="mbm">

							<?php if(isset($tag)){ ?>
							<p class="inbl tc bg-color-alt brd-rad pts pbs plm prm">

								<?= $tag;// encode($tag) => pour le lien si besoin ?>

							</p>

							<?php } ?>

						</div>

					<?php } ?>

					<?php
					/* Titre */
					if($res['tpl'] == 'home')
					{ ?>
						<h3 class="tl mtn mbn"><?= str_replace($search, $replace, $title); ?></h3>
					<?php 
					}

					if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste')
					{ ?>
						<h2 class="h3-like tl"><?= str_replace($search, $replace, $title); ?></h2>
					<?php } ?>

					<!-- Description -->
					<div class="">
						<?php 
						if(isset($description)) echo word_cut($description, '80', '...');
						?>
					</div>

					<!-- Date évènement -->
					<?php
					if(isset($date))
					{ ?>
						<div class="bold">
						
							<?php $date = strftime("%d %B %Y", strtotime($date));
							// Convertir en utf8 si besoin en fonction du serveur
							echo iconv(mb_detect_encoding($date, mb_detect_order(), true), 'UTF-8', $date);
							?>

						</div>
						<?php 
					} ?>

					<!-- Lien vers détail -->
					<div class="absolute bot15 bold">

						<?php _e("Read more")?>
						
					</div>

				</div>

			</article>

		</a>

	</div>

    <?php
}
    ?>