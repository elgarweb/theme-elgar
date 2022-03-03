<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php h1('title', 'vague')?>

	<article class="pal ptm">

		<ul class="unstyled pan">
		<?php
		$groupe = 5;
		for($i=1; $i<=$groupe; $i++)
		{
			?>
			<li>

				<?php h2('groupe-titre-'.$i, 'tl'); ?>
				<?php h3('groupe-sstitre-'.$i); ?>

				<ul id="personnalite-<?=$i?>" class="module unstyled grid-3 space-xl jic tc">					
				<?php 
				$module = module("personnalite-".$i);
				//print_r($modulePersonnalite);
				foreach ($module as $key => $value) 
				{					
					?>
					<li>
						<a <?php href("personnalite-".$i."-lien-".$key); ?> class="tdn">
							
							<?php media("personnalite-".$i."-visuel-".$key, array('size' => '150x150', 'lazy' => true, 'crop' => 'true', 'class' => 'brd-rad-100 brd-alt'));?>

							<?php txt("personnalite-".$i."-prenom-".$key, array("tag" => "span", "class" => "block bold pts"));?>
							
							<?php txt("personnalite-".$i."-nom-".$key, array("tag" => "span", "class" => "block bold ptt"));?>
							
							<?php txt("personnalite-".$i."-texte-".$key, array("tag" => "span", "class" => "block ptm"));?>
							
						</a>
					</li>
				<?php
				}
				?>
				</ul>

			</li>
		<?php
		}
		?>
		</ul>

	</article>

</section>
