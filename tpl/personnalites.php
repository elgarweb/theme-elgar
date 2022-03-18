<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<article>

		<ul class="unstyled pan ptm">
		<?php
		$groupe = 6;
		for($i=1; $i<=$groupe; $i++)
		{
			?>
			<li class="pbl">

				<div class="<?= (isset($GLOBALS['content']['groupe-titre-'.$i])) ? '' : 'editable-hidden pbl' ?>">
					<?php h2('groupe-titre-'.$i, 'tl mtm'); ?>
				</div>

				<div class="<?= (isset($GLOBALS['content']['groupe-sstitre-'.$i])) ? '' : 'editable-hidden pbl' ?>">
					<?php h3('groupe-sstitre-'.$i); ?>
				</div>

				<div class="<?= (isset($GLOBALS['content']['groupe-ss-sstitre-'.$i])) ? '' : 'editable-hidden pbl' ?>">
					<?php txt('groupe-ss-sstitre-'.$i, 'color'); ?>
				</div>

				<ul id="personnalite-<?=$i?>" class="blocks module unstyled grid-3 space-xl jic tc pln">					
				<?php 
				$module = module("personnalite-".$i);
				//print_r($modulePersonnalite);
				foreach ($module as $key => $value) 
				{					
					?>

						<li>
							
							<?php media("personnalite-".$i."-visuel-".$key, array('size' => '150x150', 'lazy' => true, 'crop' => 'true', 'dir' => 'personnalites', 'class' => 'brd-rad-100 brd-alt'));?>

							<?php txt("personnalite-".$i."-prenom-".$key, array("tag" => "span", "class" => "h3-like block bold ptm"));?>
							
							<?php txt("personnalite-".$i."-nom-".$key, array("tag" => "span", "class" => "h3-like block up bold ptt"));?>
							
							<?php txt("personnalite-".$i."-texte-".$key, array("tag" => "span", "class" => "block ptm"));?>
							
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
