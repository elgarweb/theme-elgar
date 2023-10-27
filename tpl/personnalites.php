<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p center">
	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>
	<?php h1('title', 'picto'); ?>

	<?php txt('description', ''); ?>

	<div>
		<?php
		$groupe = 6;
		for($i=1; $i<=$groupe; $i++) { ?>
			<div class="pan ptm">
				<?php
					h2('groupe-titre-'.$i, 'tl mtm');
					h3('groupe-sstitre-'.$i);
					txt('groupe-ss-sstitre-'.$i, array('class'=>'color','tag'=>'h4'));
				?>
				<div>
					<ul id="personnalite-<?=$i?>" class="blocks module end unstyled flex wrap space-xl jic tc pln">					
						<?php 
						$module = module("personnalite-".$i);
						foreach ($module as $key => $value) { ?>
							<li>
								<?php media("personnalite-".$i."-visuel-".$key, array('size' => '150x150', 'lazy' => true, 'crop' => 'true', 'dir' => 'personnalites', 'class' => 'brd-rad-100 brd-alt'));?>
								<?php txt("personnalite-".$i."-prenom-".$key, array("class" => "h3-like block ptm mtn mbn"));?>
								<?php txt("personnalite-".$i."-nom-".$key, array("class" => "h3-like block up ptt mtn"));?>
								<?php txt("personnalite-".$i."-texte-".$key, array("class" => "block asc"));?>
							</li>
						<?php }	?>
					</ul>
				</div>
			</div>
		<?php } ?>
	</div>

	<?php txt('texte'); ?>

</section>
<script>
	const allTitles = document.querySelectorAll('h2, h3, h4');
	allTitles.forEach(function(theTitle) {
		contentTitle = theTitle.innerHTML
		if(contentTitle.length === 0) {
			theTitle.setAttribute("aria-hidden", true);
			theTitle.classList.add("editable-hidden");
		}
	});	
</script>