<?php if(!$GLOBALS['domain']) exit;?>

<footer role="contentinfo">


	<div class="editable-hidden tc ptm"><i class="fa fa-attention"></i><?_e("Have you taken the accessibility rules into account when entering your content?")?></div>



	<?if(isset($res['url'])){?>
		<!-- PARTAGE RÉSEAUX SOCIAUX -->
		<section id="partage" class="mw960p flex wrap jcc center tc ptl pbl">

			<?php _e('Share this page'); ?>

			<!-- <?php txt('texte-reseaux-sociaux', 'plm'); ?> -->
			<div class="plm">

				<?php				
				$titre_encode = rawurlencode($title);
				$url_encode = urlencode(make_url($res['url'], array("domaine" => true)));
				?>

				<a href="https://www.facebook.com/sharer/sharer.php?u=<?=$url_encode?>" target="_blank">Facebook<i class="fa fa-fw fa-facebook big pls prm" aria-hidden="true"></i></a>
				
				<a href="mailto:?subject=<?=$titre_encode?>&body=<?=$url_encode?>" target="_blank">Mail<i class="fa fa-fw fa-mail big pls prm" aria-hidden="true"></i></a>

			</div>

		</section>
	<?}?>



	<!-- RENSEIGNEMENTS COMPLEMENTAIRES -->
	<section class="<?=(isset($GLOBALS['content']['texte-renseignements']) ? 'bg-green ptl pbt' : 'editable-hidden'); ?>">

		<?php txt('texte-renseignements', array('tag' => 'article', 'class' => 'mw960p center bold tc')); ?>

	</section>



	<!-- CONTACTS -->
	<section id="contacts" class="bg-color ptl pbm">

		<div class="mw960p center flex jcsb">


			<?php txt('texte-coordonnees', array('tag' => 'article'))?>


			<article>

				<?if(isset($GLOBALS['newsletter-key'][$lang])){?>
				<form id="newsletter" method="post" action="https://newsletter.infomaniak.com/external/submit" target="_blank" class="pbm">

					<input type="email" name="email" style="display:none" />

					<input type="hidden" name="key" value="<?=@$GLOBALS['newsletter-key'][$lang]?>">

					<input type="hidden" name="webform_id" value="<?=@$GLOBALS['newsletter-id'][$lang]?>">

					<label for="email_newsletter"><?php _e('Subscribe to the newsletter of your city') ?><span class="block small ptt"><?php _e('Expected format: nom@domaine.com');?></span></label>

					<div class="flex pts">

						<input type="email" name="inf[1]" id="email_newsletter" autocomplete="email" required="required" placeholder="<?php _e("Your email")?>" class="w200p pts pbs pls">
						
						<button type="submit" class="bg-green bold pas">
							<?php _e("Subscribe"); ?>
						</button>

					</div>

				</form>
				<?}?>

				<!-- Réseaux sociaux -->
				<?php txt('texte-reseaux', 'pts'); ?>

			</article>


			<article>

				<!-- Liens -->
				<?php txt('footer-liens', 'pbm'); ?>
				
				<!-- Logo France Relance -->
				<?php media('logo-sponsor', array('size' => '150', 'lazy' => 'true')); ?>
			
			</article>

		</div>

	</section>


	<!-- Liens -->
	<?php txt('footer-liens-webmaster', array('tag' => 'section', 'class' => 'mw960p center tc ptm')); ?>


</footer>

<script>
	// Ajout du title "nouvelle fenêtre" au lien sortant
	$("a[target='_blank']").each(function() {
		if(!$(this).attr("title")) $(this).attr("title", $(this).text() + " - <?_e("New window");?>");
	});
</script>

<? include("theme/".$GLOBALS['theme']."/admin/lang.php"); ?>