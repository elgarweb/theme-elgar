<?
if(!isset($GLOBALS['domain'])) exit;

/* Fonction affichage blocs img + titre + extrait texte */

function block($url_img, $url_title, $title, $text, $date = null)
{
	global $res, $articles;

    /* Ajout espaces insécables */
    $search = array("« ", " »", " ?");
    $replace = array("«&nbsp;", "&nbsp;»", "&nbsp;?");

    ?>

    <div id="block" class="mw320p relative brd-top-alt brd brd-rad-bot-right pbl">

	    <article>

	        <!-- Image -->
	        <figure>

	            <div class="cover" data-bg="<?= $GLOBALS['home'].$url_img ?>" data-lazy="bg" style="width: 100%; height: 225px;">
	            </div>

	        </figure>

			<div class="grid3row pam brd-top">
				<?php
				/* Titre */
				if($res['tpl'] == 'home')
				{
					echo
					'<h3 class="tl mtn">
						<a href="'.make_url($url_title, array("domaine" => true)).'" class="tdn">'.str_replace($search, $replace, $title).
						'</a>
					</h3>';
				}


				if($res['tpl'] == 'article-liste')
				{
					echo
				'<h2 class="h3-like tl">
						<a href="'.make_url($url_title, array("domaine" => true)).'" class="tdn">'.str_replace($search, $replace, $title).
					'</a>
					</h2>';
				} 
					?>

				<!-- Extrait texte -->
				<div class="ptm">
					<?php 
					if(isset($text)) echo word_cut($text, '100', '...');
					?>
				</div>

				<!-- Date évènement -->
				<?php
				if(isset($date))
				{
					echo 
					'<div class="bold pts">';
					
						$date = strftime("%d %B %Y", strtotime($date));
						// Convertir en utf8 si besoin en fonction du serveur
						echo iconv(mb_detect_encoding($date, mb_detect_order(), true), 'UTF-8', $date);
						
					echo '</div>';
				} 

				?>

				<div class="absolute bot15 bold">

					<a href="<?=make_url($url_title, array("domaine" => true));?>"><span class=""><?php _e("Read more")?></span></a>
					
				</div>

			</div>

	    </article>

	</div>

    <?php
}
    ?>