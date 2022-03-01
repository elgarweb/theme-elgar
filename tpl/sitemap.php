<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php');?>

	<div class="bg-color-grey mod plm pbl">

		<?php h1('title');?>

		<?php txt('texte');?>

		<ul class="">
	 	<?php
	 	$ul = null;
		// Boucle sur les éléments du menu
		foreach($GLOBALS['nav'] as $cle => $val)
		{
			echo'<li>';

				echo'<a href="'.make_url($val['href'], array("domaine" => true)).'">'.$val['text'].'</a>';

				// On regarde s'il y a des sous catégories
				$sql="SELECT ".$tc.".url, ".$tc.".title FROM ".$tc;
				$sql.=" RIGHT JOIN ".$tt."
				ON
				(
					".$tt.".id = ".$tc.".id AND
					".$tt.".zone = 'navigation' AND
					".$tt.".encode = '".basename($val['href'])."'
				)";
				$sql.=" WHERE ".$tc.".lang='".$lang."' AND state='active'";
				$sql.=" ORDER BY ".$tt.".ordre ASC";
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
		</ul>

	</div>

</section>
