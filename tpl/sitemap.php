<?php if(!$GLOBALS['domain']) exit;
//@todo: ajouter à la fin les élément de navigation dans le footer (contact, plan...)
?>

<section class="mw960p center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php');?>

	<div class="bg-grey mod plm pbl">

		<?php h1('title');?>

		<?php txt('texte');?>

		<ul>
		 	<?php
		 	$ul = null;
		 	$num_pp = 50;
			// Boucle sur les éléments du menu
			foreach($GLOBALS['nav'] as $cle => $val)
			{
				echo'<li>';

					echo'<a href="'.make_url($val['href'], array("domaine" => true)).'">'.str_replace('<br>',' ', $val['text']).'</a>';

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
							echo'<ul>';
							$ul = true;
						}

						echo'<li><a href="'.make_url($res_nav['url'], array("domaine" => true)).'">'.$res_nav['title'].'</a></li>';
					}

					// Ferme le sous ul
					if($ul) { 
						echo'</ul>';
						$ul = null;
					}

				echo'</li>';
			}
			?>
			<li><a href="/<?=encode(__("Contact"))?>"><?php _e("Contact")?></a></li>
		</ul>

	</div>

</section>