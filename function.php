<?
if(!isset($GLOBALS['domain'])) exit;

// Table des traductions
$GLOBALS['table_lang'] = $GLOBALS['tl'] = $GLOBALS['db_prefix'].'lang';

// Fonction affichage blocs img + titre + extrait texte
function block($url_img, $url_title, $title, $description, $date = null, $tags = null)
{
	global $res, $res_fiche, $state, $num_fiche;

    /* Ajout espaces insécables */
    $search = array("« ", " »", " ?");
    $replace = array("«&nbsp;", "&nbsp;»", "&nbsp;?");
    ?>

    <div class="<?= $res['url'] == 'actualites' ? 'bg-grey ' : 'bg-white '; ?>relative brd-top-alt brd brd-rad-bot-right">

		<!-- Affichage état article si désactivé -->
		<div class="color-alt tc bold">
			<?=$state?>
		</div>
	    
		<a href="<?=make_url($url_title, array("domaine" => true));?>"  class="tdn">
			<article class="h100">
				<!-- Image -->
				<?php //Affichage images des 3 premières actus seulement
				if($num_fiche <= 3) { ?>
					<figure>
						<div class="nor" data-bg="<?=(isset(parse_url($url_img)['scheme'])?'':$GLOBALS['home']).$url_img?>" data-lazy="bg" style="width: 100%; height: 225px;">
						</div>
					</figure>
				<?php } ?>
				
				<div class="pam<?= ($num_fiche <= 3) ? ' brd-top' : '' ?>">				
					<?php 
					/* Affichage tags supprimé pour Elgarweb - laisser en commentaire si besoin pour autre mairie */
					/* Tag  (que sur le listing des articles car query + longue)
					if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste') { ?>
						<div class="mbm">
							<?php 
							if(isset($tags) and isset($res_fiche['id']))
							{ 
								$sel_tag = $GLOBALS['connect']->query("SELECT * FROM ".$GLOBALS['tt']."
									WHERE zone = '".$res['url']."' AND id='".$res_fiche['id']."' LIMIT 5");
								while($res_tag = $sel_tag->fetch_assoc()) {
									echo '<span class="bt-tag">'.$res_tag['name']."</span> ";
								}
							}
							?>
						</div>
					<?php } */ 

					// Titre H3 en home / H2 dans les listes
					if($res['tpl'] == 'home') echo '<h3 class="tl pbm">'.str_replace($search, $replace, $title).'</h3>';
					if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste') echo '<h2 class="h3-like tl pbm">'.str_replace($search, $replace, $title).'</h2>';

					//Description
					if(isset($description)) echo '<p>'.word_cut($description, '80', '...').'</p>';

					//Date évènement
					if(isset($date)) '<p class="bold mbm">'.date_lang($date).'</p>';?>

					<!-- Lien vers détail -->
					<p class="absolute bot15 bold tdu mbn">
						<?php _e("Read more")?>						
					</p>
				</div>
			</article>
		</a>
	</div>
    <?php
}
?>