<?php if(!$GLOBALS['domain']) exit;?>

<footer role="contentinfo">

	<div class="editable-hidden tc ptm"><i class="fa fa-attention" aria-hidden="true"></i><?_e("Have you taken the accessibility rules into account when entering your content?")?></div>

	<?if(isset($res['url'])){?>
		<!-- PARTAGE RÉSEAUX SOCIAUX -->
		<section id="partage" class="mw960p flex wrap jcc center tc ptl pbl">

			<?php _e('Share this page'); ?>

			<!-- <?php txt('texte-reseaux-sociaux', 'plm'); ?> -->
			<ul class="plm">

				<?php				
				$titre_encode = rawurlencode($title);
				$url_encode = urlencode(make_url($res['url'], array("domaine" => true)));
				?>

				<li class="inline"><a href="https://www.facebook.com/sharer/sharer.php?u=<?=$url_encode?>" target="_blank"><i class="fa fa-fw fa-facebook big" aria-hidden="true"></i>Facebook</a></li>
				
				<li class="inline"><a href="mailto:?subject=<?=$titre_encode?>&body=<?=$url_encode?>" target="_blank"><i class="fa fa-fw fa-mail big mlm mrs" aria-hidden="true"></i><?_e("Mail")?></a></li>

			</ul>

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

					<label for="email_newsletter"><?php _e('Subscribe to the newsletter of your city') ?><span class="block small ptt"><?php _e('Expected format');?> : dupont@exemple.com</span></label>

					<div class="flex pts">

						<input type="email" name="inf[1]" id="email_newsletter" autocomplete="email" required="required" placeholder="<?php _e("Your email")?>" class="w200p pts pbs pls">
						
						<button type="submit" class="bg-green bold pas">
							<?php _e("Subscribe"); ?>
						</button>

					</div>

				</form>
				<script>
					// Message d'erreur en cas de mauvaise saisie du mail. Pour l'accessibilité
					var email_newsletter = document.getElementById("email_newsletter");
					email_newsletter.addEventListener("invalid", function() {
						email_newsletter.setCustomValidity("<?_e("Invalid email")?>. <?_e("Expected format")?> : dupont@exemple.com")
					}, false);
					email_newsletter.addEventListener("input", function() {
						email_newsletter.setCustomValidity("");
					}, false);
				</script>
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
		if(!$(this).attr("aria-label")) $(this).attr("aria-label", $(this).text() + " - <?_e("New window");?>");
		$(this).addClass("external");
	});
</script>

<? include("theme/".$GLOBALS['theme']."/admin/lang.php"); ?>