<?
if(!isset($GLOBALS['domain'])) exit;

// Table des traductions
$GLOBALS['table_lang'] = $GLOBALS['tl'] = $GLOBALS['db_prefix'].'lang';

// Fonction affichage blocs img + titre + extrait texte
function block($url_img, $url_title, $title, $description, $date = null, $tag = null)
{
	global $res, $articles;

    /* Ajout espaces insécables */
    $search = array("« ", " »", " ?");
    $replace = array("«&nbsp;", "&nbsp;»", "&nbsp;?");

    ?>

    <div class="relative brd-top-alt brd brd-rad-bot-right pbl">

	    <article>

	        <!-- Image -->
			<figure>

				<div class="nor" data-bg="<?=(isset(parse_url($url_img)['scheme'])?'':$GLOBALS['home']).$url_img?>" data-lazy="bg" style="width: 100%; height: 225px;">
				</div>

			</figure>
			
			<div class="<?= ($res['tpl'] == 'home') ? 'grid3row ' :'grid4row ' ?>pam brd-top">

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
				{
					echo
					'<h3 class="tl mtn mbn">
						<a href="'.make_url($url_title, array("domaine" => true)).'" class="tdn">'.str_replace($search, $replace, $title).
						'</a>
					</h3>';
				}


				if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste')
				{
					echo
				'<h2 class="h3-like tl">
						<a href="'.make_url($url_title, array("domaine" => true)).'" class="tdn">'.str_replace($search, $replace, $title).
					'</a>
					</h2>';
				} 
					?>

				<!-- Description -->
				<div class="">
					<?php 
					if(isset($description)) echo word_cut($description, '80', '...');
					?>
				</div>

				<!-- Date évènement -->
				<?php
				if(isset($date))
				{
					echo 
					'<div class="bold">';
					
						$date = strftime("%d %B %Y", strtotime($date));
						// Convertir en utf8 si besoin en fonction du serveur
						echo iconv(mb_detect_encoding($date, mb_detect_order(), true), 'UTF-8', $date);
						
					echo '</div>';
				} 

				?>

				<!-- Lien vers détail -->
				<div class="absolute bot15 bold">

					<a href="<?=make_url($url_title, array("domaine" => true));?>"><span class=""><?php _e("Read more")?></span></a>
					
				</div>

			</div>

	    </article>

	</div>

    <?php
}
    ?>