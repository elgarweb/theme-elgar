<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php');?>

	<div class="bg-color-3 relative mod pbl">

		<?php h1('title');?>

		<?php txt('texte');?>

		<?php //highlight_string(print_r($GLOBALS['nav'], true));?>

		<ul id="sitemap">
		 	<?php
		 	$ul = $sitemap = $index = null;
		 	$num_pp = 50;
		 	 null;
			// Boucle sur les éléments du menu
			foreach($GLOBALS['nav'] as $cle => $val)
			{
				// Check s'il y a la page d'accueil dans le menu
				if($val['href'] == 'index') $index = true;

				$sitemap.='<li>';

					$sitemap.='<a href="'.make_url($val['href'], array("domaine" => true)).'">'.str_replace('<br>',' ', $val['text']).'</a>';

					// On regarde s'il y a des sous catégories // Ancienne méthode avec les tags
					/*$sql="SELECT ".$tc.".url, ".$tc.".title FROM ".$tc;
					$sql.=" RIGHT JOIN ".$tt."
					ON
					(
						".$tt.".id = ".$tc.".id AND
						".$tt.".zone = 'navigation' AND
						".$tt.".encode = '".basename($val['href'])."'
					)";
					$sql.=" WHERE ".$tc.".lang='".$lang."' AND state='active'";
					$sql.=" ORDER BY ".$tt.".ordre ASC";*/

					// Version avec select dans le fil d'ariane
					$sql ="SELECT ".$tc.".url, ".$tc.".title FROM ".$tc;
					$sql.=" JOIN ".$tm."
					ON
					(
						".$tm.".id = ".$tc.".id AND
						".$tm.".type='navigation' AND
						".$tm.".cle='".basename($val['href'])."'
					)";
					$sql.=" WHERE ".$tc.".lang='".$lang."' AND state='active'";
					$sql.=" ORDER BY ".$tc.".date_insert DESC";
					$sql.=" LIMIT ".$num_pp;

					//echo $sql;
					$sel_nav = $connect->query($sql);
					while($res_nav = $sel_nav->fetch_assoc())
					{
						// Si pas de ul ouvert
						if(!$ul) { 
							$sitemap.='<ul>';
							$ul = true;
						}

						$sitemap.='<li><a href="'.make_url($res_nav['url'], array("domaine" => true)).'">'.$res_nav['title'].'</a></li>';
					}

					// Ferme le sous ul
					if($ul) { 
						$sitemap.='</ul>';
						$ul = null;
					}

				$sitemap.='</li>';
			}

			if($index != true) $sitemap = '<li><a href="'.make_url('index', array("domaine" => true)).'">'.__("Home").'</a></li>'.$sitemap;

			echo $sitemap;
			?>
		</ul>

	</div>

</section>
<script>
$(function()
{
	// Tableau avec les éléments déjà present
	sitemap = {};
	$("#sitemap a").each(function() 
	{
		sitemap[$(this).attr("href")] = $(this).html();
	});

	// Liens supplementaire dans le footer
	$("#footer-liens a").each(function() 
	{
		// Si le lien n'est pas déjà présent
		if(!sitemap[window.location.origin + $(this).attr("href")]) 
		{
			$("#sitemap").append('<li><a href="'+$(this).attr("href")+'">'+$(this).html()+'</a></li>');

			sitemap[window.location.origin + $(this).attr("href")] = $(this).html();
		}
	});

	// Liens webmaster tout en bas dans le footer
	$("#footer-liens-webmaster a").each(function() 
	{
		if(!sitemap[window.location.origin + $(this).attr("href")]) 
		{
			$("#sitemap").append('<li><a href="'+$(this).attr("href")+'">'+$(this).html()+'</a></li>');
			
			sitemap[window.location.origin + $(this).attr("href")] = $(this).html();
		}
	});

	//console.log(sitemap)
});
</script>