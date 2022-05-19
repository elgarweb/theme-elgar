<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<nav role="navigation" aria-label="<?php _e("Summary")?>">
		<h2><?php _e("Summary")?></h2>
		<ol id="sommaire" class="unstyled pbm"></ol>
	</nav>

	<article>

		<?php txt('texte'); ?>

	</article>

</section>

<script>
//$(function(){

	// CONSTRUCTION DU SOMMAIRE
	i = 1;
	open = false;
	html = '';

	$("#texte h2, #texte h3").each(function(index) 
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
	});

	// Si sommaire par fermer
	if(open) html += "</ol></li>";

	$("#sommaire").append(html);

//});
</script>