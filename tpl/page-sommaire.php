<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<nav role="navigation" aria-label="<?php _e("Summary")?>">
		<ul id="sommaire" class="unstyled pln pbm"></ul>
	</nav>

	<article>

		<?php txt('texte'); ?>

	</article>

</section>

<script>
$(function()
{
	// CONSTRUCTION DU SOMMAIRE
	i = 1;
	open = false;
	html = '';

	$("#texte h2, #texte h3").each(function(index) 
	{
		// 1er élément
		if(i == 1) previous = $(this).prop("tagName");


		// Ajoute l'encre dans le contenu
		$(this).attr("id","ancre"+i);


		// Si changement de niveau
		if($(this).prop("tagName") != previous)
		{
			if(open)// On doit fermer
			{
				html += "</ul></li>";
				open = false;
			}
			else// On ouvre une sous-section
			{
				html += "<li><ul>";
				open = true;
			}
		}


		// Ajoute l'élément au sommaire
		html += "<li><a href='#ancre" + i + "'>" + $(this).text() + "</a></li>";


		previous = $(this).prop("tagName");

		++i;
	});

	// Si sommaire par fermer
	if(open) html += "</ul></li>";

	$("#sommaire").append(html);

});
</script>