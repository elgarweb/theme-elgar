<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<nav id="sommaire" role="navigation" aria-label="<?php _e("Summary")?>">
		<h2><?php _e("Summary")?></h2>
		<ol class="pbm"></ol>
	</nav>

	<article>

		<?php txt('texte'); ?>

	</article>

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

		var $header = $("header");
    	var header_hight = $header.outerHeight();
		var header_bottom = (header_hight - window_top);

		var $sommaire = $("#sommaire");
		var sommaire_top = $sommaire.offset().top;

		if(header_bottom > 0 && header_hight > window_top){// On scroll => repositionne et agrandi
			//console.log("top")
			$("#sommaire").css({
				"top": header_bottom - 1,
				//"position": "fixed",
				"minHeight": window_height - header_bottom -10
			});
		}
		else if(header_bottom < 0){// Le header n'est plus visible => max height
			//console.log("0")
			$("#sommaire").css({
				"top": 10,
				"minHeight": window_height - 20
			});
		}

		/*console.log("header_hight: "+header_hight)
		console.log("window_top: "+window_top)
		console.log("header_bottom: "+header_bottom)
		console.log("---------")*/

	});

});
</script>