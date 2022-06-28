<?php if(!$GLOBALS['domain']) exit;?>

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

		<article class="mw960p">

			<?php txt('texte'); ?>

		</article>

	</div>

</section>

<script>

// CONSTRUCTION DU SOMMAIRE
i = 1;
open = false;
html = '';

$("#texte h2, #texte h3").each(function(index) 
{
	if($(this).text().length >0)
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
				html += "</ol></li>";
				open = false;
			}
			else// On ouvre une sous-section
			{
				html += "<li><ol>";
				open = true;
			}
		}


		// Ajoute l'élément au sommaire
		html += "<li><a href='#ancre-" + ancre + "'>" + $(this).text() + "</a></li>";


		previous = $(this).prop("tagName");

		++i;
	}
});

// Si sommaire par fermer
if(open) html += "</ol></li>";

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

		if(h1_pass > 0){// On voit le h1
			//console.log("h1")
			$("#sommaire").css({
				//"top": h1_bottom - window_top,
				"position": "initial",
				"maxHeight": window_height - h1_pass -10
			});
		}
		else if(h1_pass < 0){// On a dépassé le h1
			//console.log("0")
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