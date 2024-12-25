<?php if(!$GLOBALS['domain']) exit;

$admin_intranet = true;

// Page normale ou si page intranet + autorisation intranet
if(@$content['intranet'] != 'true' or (@$content['intranet'] == 'true' and (isset($_SESSION['auth']['intranet']) or isset($_SESSION['auth']['edit-page'])))) 
{?>
	<section class="mod center">

		<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

		<?php h1('title', 'mw960p center picto'); ?>

		<div class="mw1600p center flex-sommaire mtl">

			<div class="col-sommaire">
				<nav id="sommaire" role="navigation" aria-label="<?php _e("Summary")?>">
					<h2><?php _e("Summary")?></h2>
					<ol class="pbm"></ol>
				</nav>
			</div>


			<?php if($res['tpl'] == 'page-sommaire-grille'){?>
				

				<article class="mw960p minw320p">

					<?php txt('description'); ?>

					<div id="texte">

						<ul id="groupe" class="module unstyled pan">
						<?php
						$groupe = module("groupe");
						//highlight_string(print_r($groupe, true));
						foreach($groupe as $i => $val)
						{ ?>
							<li>

								<?php
								h2('groupe-titre-'.$i);
								txt('groupe-texte-'.$i);
								?>

								<ul id="grille-<?=$i?>" class="module unstyled pan end grid-4 space-xl">
								<?php 
								$grille = module('grille-'.$i);
								//highlight_string(print_r($grille, true));
								foreach($grille as $k => $val) { ?>
									<li>
										<?php media('grille-'.$i.'-visuel-'.$k, array('size' => '150x150', 'lazy' => true));?>
										<?php txt('grille-'.$i.'-texte-'.$k, array("class" => ""));?>
									</li>
								<?php }	?>
								</ul>		

							</li>
						<?php } ?>
						</ul>

					</div>

				</article>


			<?php }else{?>


				<article class="mw960p">

					<?php txt('texte', array('lazy' => true)); ?>

				</article>


			<?php }?>


		</div>

	</section>

	<script>

	// CONSTRUCTION DU SOMMAIRE
	i = 1;
	open = false;
	html = '';

	$("#texte h2, #texte h3").each(function(index) 
	{
		if($(this).text().length > 0)
		{
			// nom de l'ancre
			var ancre = $(this).text().toLowerCase().replace(/[^a-z0-9]+/g,'-');


			// 1er élément
			if(i == 1) previous = $(this).prop("tagName");


			// Ajoute l'encre dans le contenu
			$(this).attr("id","ancre-" + ancre);


			// Si changement de niveau
			if($(this).prop("tagName") != previous)
			{
				if(open)// On doit fermer
				{
					html += "</li></ol>";
					open = false;
				}
				else// On ouvre une sous-section
				{
					html += "<ol>";
					open = true;
				}
			}
			else html += "</li>";


			// Ajoute l'élément au sommaire
			html += "<li><a href='#ancre-" + ancre + "'>" + $(this).text() + "</a>";


			previous = $(this).prop("tagName");

			++i;
		}
		else $(this).removeAttr("id");
	});

	// Si sommaire par fermer
	if(open) html += "</li></ol>";

	$("#sommaire ol").append(html);


	$(function(){

		$window = $(window);

		// Au scroll
		$window.on("scroll resize load", function ()
		{
			window_height = $window.outerHeight();//height
			window_top = $window.scrollTop();

			var $h1 = $("h1");
			var h1_height = $h1.outerHeight(true);
			var h1_top = $h1.offset().top;
			var h1_bottom = h1_height + h1_top;
			var h1_pass = h1_bottom - window_top;

			if(h1_pass > 0)// On voit le h1
			{
				//console.log("h1")

				$("#sommaire").css({
					//"top": h1_bottom - window_top,
					"position": "initial",
					"maxHeight": window_height - h1_pass -10
				});
			}
			else if(h1_pass < 0)// On a dépassé le h1
			{	
				//console.log("0")

				// Si footer visible on réduit le sommaire pour éviter les superpositions avec le footer
				if($("footer").offset().top <= (window_top + window_height))				
					window_height = window_height - ((window_top + window_height) - $("footer").offset().top);

				$("#sommaire").css({
					"top": 10,
					"position": "fixed",
					"maxHeight": window_height - 20
				});
			}

			/*console.log("h1_bottom: "+h1_bottom)
			console.log("window_top: "+window_top)
			console.log("h1_pass: "+h1_pass)
			console.log("---------")*/

		});

	});
	</script>

<?php
}
// Si page intranet et pas autorisation intranet => connexion
elseif(@$content['intranet'] == 'true' and !isset($_SESSION['auth']['intranet']))
{
	// Pour le fil d'ariane
	$title = $content['title'] = __('Log in');

    // Pour bien rediriger vers la page courante
    $_SERVER['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];

	include('theme/'.$GLOBALS['theme'].'/tpl/connexion.php');
}
?>