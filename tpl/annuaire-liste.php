<?php 
if(!$GLOBALS['domain']) exit;

$url_back = encode($res['url']);
?>

<section class="mw960p mod center mbm">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	
	<?php
	h1('title', 'picto mbn');

	// Liste les tags pour filtrer la page
	$i = 1;
	$sel_tag_list = $connect->query("SELECT distinct encode, name FROM ".$table_tag." WHERE zone='".$res['url']."' AND lang='".$lang."' GROUP BY encode, name ORDER BY encode ASC");
	//echo $connect->error;

	if($sel_tag_list->num_rows > 0) {
	?>
		<nav role="navigation" aria-label="<?php _e("Filter by")?>" class="flex wrap space jcc tc ptl pbm">
			<ul class="unstyled pln"><?php 
				while($res_tag_list = $sel_tag_list->fetch_assoc()) {
					echo'<li class="inline prs"><a href="'.make_url($res['url'], array($res_tag_list['encode'], 'domaine' => true)).'" class="bt-tag">'.$res_tag_list['name'].'</a></li>';
					$i++;
				}
				?>
			</ul>
		</nav>
	<?php
	}

	txt('description', array('class'=>'ptm mbn'));
	?>
	
</section>


<section class="mw960p center">

	<div>
		
		<?php 
		// Si on n'a pas les droits d'édition des articles on affiche uniquement ceux actifs
		if(!@$_SESSION['auth']['edit-annuaire']) $sql_state = "AND state='active'";
		else $sql_state = "";

		// Navigation par page
		$num_pp = 10;

		if(isset($GLOBALS['filter']['page'])) $page = (int)$GLOBALS['filter']['page']; else $page = 1;

		$start = ($page * $num_pp) - $num_pp;

		// Construction de la requete
		$sql="SELECT SQL_CALC_FOUND_ROWS ".$tc.".id, ".$tc.".* FROM ".$tc;

		// Si filtre tag
		if(isset($tag))
		$sql.=" RIGHT JOIN ".$tt."
		ON
		(
			".$tt.".id = ".$tc.".id AND
			".$tt.".zone = '".$res['url']."' AND
			".$tt.".lang = '".$lang."' AND
			".$tt.".encode = '".$tag."'
		)";

		$sql.=" WHERE ".$tc.".lang='".$lang."' ".$sql_state." AND";

		if($res['url'] == encode(__('Directory'))) 
			$sql.=" ".$tc.".type='annuaire'";
		else
			$sql.=" ".$tc.".type='commerce'";

		$sql.=" LIMIT ".$start.", ".$num_pp;

		//echo $sql;
		$sel_fiche = $connect->query($sql);

		$num_total = $connect->query("SELECT FOUND_ROWS()")->fetch_row()[0];// Nombre total de fiche

		while($res_fiche = $sel_fiche->fetch_assoc())
		{
			// Affichage du message pour dire si l'article est invisible ou pas
			if($res_fiche['state'] != "active") $state = " <span class='deactivate pat'>".__("Article d&eacute;sactiv&eacute;")."</span>";
			else $state = "";

			$content_fiche = json_decode($res_fiche['content'], true);
			?>

			<div class="brd mbm">

				<article class="relative flex aic">

					<!-- Image -->
					<figure>

						<div class="nor" data-bg="<?=(isset(parse_url(@$content_fiche['visuel'])['scheme'])?'':$GLOBALS['home']).@$content_fiche['visuel']; ?>" data-lazy="bg" style="width: 100%; height: 225px;">
						</div>

					</figure>

					<div class="ptm pbl plm prm brd-left">
						
						<!-- Tags -->
						<!-- <div class="mbm">
			
							<?php 
							/* Affichage tags supprimé pour Elgarweb - laisser en commentaire si besoin pour autre mairie */
							if(isset($tags) and isset($res_fiche['id']))
							global $tags;
							{ 
								$sel_tag = $GLOBALS['connect']->query("SELECT * FROM ".$GLOBALS['tt']."
									WHERE zone = '".$res['url']."' AND id='".$res_fiche['id']."' LIMIT 5");
								while($res_tag = $sel_tag->fetch_assoc()) {
									echo '<span class="bt-tag">'.$res_tag['name']."</span> ";
								}
							} 
							?>

						</div> -->

						<!-- Titre -->
						<h2 class="h3-like tl">
							<?=$res_fiche['title']?>
						</h2>

						<!-- Coordonnées -->
						<ul class="unstyled pln">
						<?php 
							if(isset($content_fiche['url-site-web']))
								echo '<li class="bold pbn"><i class="fa fa-fw fa-globe" aria-hidden="true"></i> <a href="'.$content_fiche['url-site-web'].'" target="_blank">'.__('Website').'</a></li>';

							if(isset($content_fiche['telephone']))
								echo '<li class="pbn"><details class="pts" aria-live="polite"><summary class="tel color pointer tdu bold  inbl" data-encode="'.$content_fiche['telephone'].'"><i class="fa fa-fw fa-phone" aria-hidden="true"></i>'.__('Telephone').'</summary><p class="inline pls bold"></p></details></li>';

							if(isset($content_fiche['mail-contact']))
								echo '<li class="pbn"><details class="pts" aria-live="polite"><summary class="tel color pointer tdu bold inbl" data-encode="'.$content_fiche['mail-contact'].'"><i class="fa fa-fw fa-mail-alt" aria-hidden="true"></i>'.__('Email').'</summary><p class="inline pls bold"></p></details></li>';

							if(isset($content_fiche['adresse']))
								echo '<li class="bold pbn pts"><i class="fa fa-fw fa-location" aria-hidden="true"></i>'.__('Address').'<p class="plt">'.$content_fiche['adresse'].'</p></li>';
							?>
							</ul>
						<!-- Lien vers détail -->
						<div class="absolute bot15 right15 plus">

							<a href="<?=make_url($res_fiche['url'], array("domaine" => true));?>" aria-label="<?php echo __("See the sheet")." ".$res_fiche['title']?>"><?php _e("See the sheet")?></a>
							
						</div>

					</div>

				</article>				

			</div>


		<?php	
		}
		?>

	</div>

	<div class="tc ptm mtl pbl">

		<?php
		page($num_total, $page);
		?>

	</div>

</section>

<script>
$(function()
{
	// Décode
	$(".tel, .mailto").on("click", function(event) { 
		//event.preventDefault();
		//document.location.href = $(event.target).attr("class") + ":" + atob($(event.target).data("encode"));
		$(event.target).next('p').html(atob($(event.target).data("encode")));
	});
});
</script>