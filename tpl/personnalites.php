<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php h1('title', 'vague')?>

	<article class="pal ptm">

		<!-- .module pour bien identifier que ce sont les elements à dupliquer et a sauvegardé -->
		<ul id="groupe" class="module unstyled pan">
			<?php
			// nom du module "personnalite" = id du module, et au début des id des txt() media() ...

			// Module groupe de personnalités
			$moduleGroupe = module("groupe");

			// Module personnalité du groupe
			$modulePersonnalite = module("personnalite");

			foreach($moduleGroupe as $keyGroupe => $val)
			{
				?>

				<li>

					<?php txt('groupe-titre-'.$keyGroupe); ?>

					<ul id="personnalite" class="module unstyled grid-3 space-xl jic tc">
						
						<?php 
						foreach($modulePersonnalite as $keyPersonnalite => $value); 
						{ 
							?>

							<li>

								<a <?php href("personnalite-lien-".$keyPersonnalite.'-'.$keyGroupe); ?> class="tdn">
									
									<?php media("personnalite-visuel-".$keyPersonnalite.'-'.$keyGroupe, array('size' => '150x150', 'lazy' => true, 'crop' => 'true', 'class' => 'brd-rad-100 brd-alt'));?>
									
									<?php txt("personnalite-texte-prenom-".$keyPersonnalite.'-'.$keyGroupe, array("tag" => "span", "class" => "block bold pts"));?>
									
									<?php txt("personnalite-texte-nom-".$keyPersonnalite.'-'.$keyGroupe, array("tag" => "span", "class" => "block bold ptt"));?>
									
									<?php txt("personnalite-texte-".$keyPersonnalite.'-'.$keyGroupe, array("tag" => "span", "class" => "block ptm"));?>
									
								</a>
							</li>
							<?php
						}; ?>
					</ul>
				</li>
				<?php
			}; ?>
		</ul>

	</article>

</section>
