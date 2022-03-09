<?
if(!isset($GLOBALS['domain'])) exit;

// Table des traductions
$GLOBALS['table_lang'] = $GLOBALS['tl'] = $GLOBALS['db_prefix'].'lang';

// Fonction affichage blocs img + titre + extrait texte
function block($url_img, $url_title, $title, $description, $date = null, $tags = null)
{
	global $res, $res_fiche, $state;

    /* Ajout espaces insécables */
    $search = array("« ", " »", " ?");
    $replace = array("«&nbsp;", "&nbsp;»", "&nbsp;?");

    ?>

    <div id="block" class="relative brd-top-alt brd brd-rad-bot-right">

		<!-- Affichage état article si désactivé -->
		<div class="color-alt tc bold">
			<?=$state?>
		</div>
	    
		<a href="<?=make_url($url_title, array("domaine" => true));?>" title="<?=$title?>" class="tdn">

			<article class="pbm">

				<!-- Image -->
				<?php //Affichage images des 3 premières actus seulement
				if($url_img != '') { ?>
				<figure>

					<div class="nor" data-bg="<?=(isset(parse_url($url_img)['scheme'])?'':$GLOBALS['home']).$url_img?>" data-lazy="bg" style="width: 100%; height: 225px;">
					</div>

				</figure>
				<?php } ?>
				
				<div class="pam<?= ($url_img != '') ? ' brd-top' : '' ?>">

					<!-- Tag  (que sur le listing des articles car query + longue)-->
					<?php if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste') { ?>

						<div class="mbm">

							<?php if(isset($tags) and isset($res_fiche['id']))
							{ 
								$sel_tag = $GLOBALS['connect']->query("SELECT * FROM ".$GLOBALS['tt']."
									WHERE zone = '".$res['url']."' AND id='".$res_fiche['id']."' LIMIT 5");
								while($res_tag = $sel_tag->fetch_assoc()) {
									echo '<span class="inbl tc bg-green brd-rad pts pbs plm prm mbs">'.$res_tag['name']."</span> ";
								}
							} 
							?>

						</div>

					<?php } ?>

					<?php
					/* Titre */
					if($res['tpl'] == 'home')
					{ ?>
						<h3 class="tl pbm"><?= str_replace($search, $replace, $title); ?></h3>
					<?php 
					}

					if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste')
					{ ?>
						<h2 class="h3-like tl pbm"><?= str_replace($search, $replace, $title); ?></h2>
					<?php } ?>

					<!-- Description -->
					<div class="pbm">
						<?php 
						if(isset($description)) echo word_cut($description, '80', '...');
						?>
					</div>

					<!-- Date évènement -->
					<?php
					if(isset($date))
					{ ?>
						<div class="bold mbm">
						
							<?php echo date_lang($date);?>

						</div>
						<?php 
					} ?>

					<!-- Lien vers détail -->
					<div class="absolute bot15 bold tdu">

						<?php _e("Read more")?>
						
					</div>

				</div>

			</article>

		</a>

	</div>

    <?php
}
?>