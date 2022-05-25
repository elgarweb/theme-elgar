<?php if(!$GLOBALS['domain']) exit;?>

<section class="mw960p mod center">

	<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>

	<?php h1('title', 'picto'); ?>

	<article>
		<?php
		for($i=1; $i <= 5; $i++) { ?>
			<div class="pan ptm">

				<?h2('titre-'.$i, array('class' => 'tl mtm', 'dir' => 'telechargement'));?>

				<ul id="telechargement-<?=$i?>" class="module unstyled grid-4 tc pan">					
					<?php 
					$module = module("telechargement-".$i);
					foreach($module as $key => $value) {?>
						<li>
							<?media('telechargement-'.$i.'-visuel-'.$key, array('size' => '185x256', 'lazy' => true, 'dir' => 'telechargement'));?>

							<?txt('telechargement-'.$i.'-titre-'.$key, array('class' => 'mtm', 'dir' => 'telechargement'));?>
						</li>
					<?php }	?>
				</ul>
			</div>
		<?php } ?>
	</article>

</section>

<script>
	const allTitles = document.querySelectorAll('h2, h3');
	allTitles.forEach(function(theTitle) {
		contentTitle = theTitle.innerHTML
		if(contentTitle.length === 0) theTitle.setAttribute("aria-hidden", true);
	});	
</script>