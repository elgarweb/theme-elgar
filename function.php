<?php
if(!isset($GLOBALS['domain'])) exit;

// Table des traductions
$GLOBALS['table_lang'] = $GLOBALS['tl'] = $GLOBALS['db_prefix'].'lang';

// Fonction affichage blocs img + titre + extrait texte
function block($url_img, $url_title, $title, $description, $date = null, $date_fin = null, $tags = null)
{
	global $res, $res_fiche, $state, $num_fiche;

    /* Ajout espaces insécables */
    $search = array("« ", " »", " ?");
    $replace = array("«&nbsp;", "&nbsp;»", "&nbsp;?");
    ?>

    <div class="<?= $res['url'] == 'actualites' ? 'bg-color-3 ' : 'bg-white '; ?>relative brd-top-3 brd">

		<!-- Affichage état article si désactivé -->
		<div class="color-alt tc bold">
			<?=$state?>
		</div>
	    
		<a href="<?=make_url($url_title, array("domaine" => true));?>"  class="tdn">
			<article class="h100">
				<!-- Image -->
				<?php //Affichage images des 3 premières actus seulement
				if($num_fiche <= 3 and isset($url_img)) { ?>
					<figure>
						<div class="nor" data-bg="<?=(isset(parse_url($url_img)['scheme'])?'':$GLOBALS['home']).$url_img?>" data-lazy="bg">
						</div>
					</figure>
				<?php } ?>
				
				<div class="pam<?= ($num_fiche <= 3 and isset($url_img)) ? ' brd-top' : '' ?>">				
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
					if($res['tpl'] == 'home') 
						echo '<h3 class="pbm">'.str_replace($search, $replace, $title).'</h3>';
					else//if($res['tpl'] == 'article-liste' or $res['tpl'] == 'annuaire-liste' or $res['tpl'] == 'publication-liste')
						echo '<h2 class="h3-like tl pbm">'.str_replace($search, $replace, $title).'</h2>';

					//Description
					if(isset($description)) echo '<p class="description">'.word_cut($description, '80', '...').'</p>';

					//Date évènement
					if(isset($date)) 
					{
						echo '<p class="date bold mbm">';

							if($GLOBALS['lang'] == 'eu') echo str_replace('-', '/', $date);
							else echo date_lang($date);

							if(isset($date_fin))
							{
								echo' '.__("to");

								if($GLOBALS['lang'] == 'eu') echo str_replace('-', '/', $date_fin);
								else echo ' '.date_lang($date_fin);
							}

						echo '</p>';
					}
					?>

					<!-- Lien vers détail -->
					<p class="plus absolute bot15 bold tdu mbn">
						<?php _e("Read more")?>						
					</p>
				</div>
			</article>
		</a>
	</div>
    <?php
}
?>