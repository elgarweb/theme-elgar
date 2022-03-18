<?php if(!$GLOBALS['domain']) exit;?>

<!--
@todo Simon :
- partage par mail
- newsletter (repris d'Adaptaville)
- ajouter lien vers plan du site
-->

<footer role="contentinfo">

	<!-- PARTAGE RÉSEAUX SOCIAUX -->
	<section id="partage" class="mw960p flex wrap jcc center tc ptl pbl">

		<?if(isset($res['url'])){?>

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
			
		<?}?>

	</section>

	<!-- RENSEIGNEMENTS COMPLEMENTAIRES -->
	<section class="<?=(isset($GLOBALS['content']['texte-renseignements']) ? 'bg-green ptl pbl' : 'editable-hidden'); ?>">

		<article class="mw960p center">

			<?php txt('texte-renseignements', 'bold tc'); ?>

		</article>

	</section>

	<!-- CONTACTS -->
	<section id="contacts" class="bg-color ptl pbm">

		<div class="mw960p center flex jcsb">

			<article class="pbm">

				<?php txt('texte-coordonnees')?>

			</article>


			<article class="pbm">

				<?if(isset($GLOBALS['newsletter-key'][$lang])){?>
				<div class="pbm">	

					<form id="newsletter" method="post" action="https://newsletter.infomaniak.com/external/submit" target="_blank">

						<input type="email" name="email" style="display:none" />

						<input type="hidden" name="key" value="<?=@$GLOBALS['newsletter-key'][$lang]?>">

						<input type="hidden" name="webform_id" value="<?=@$GLOBALS['newsletter-id'][$lang]?>">

						<label for="email_newsletter"><?php _e('Subscribe to the newsletter of your city') ?></label>

						<div class="flex pts">

							<input type="email" name="inf[1]" id="email_newsletter" data-inf-meta="1" data-inf-error="Merci de renseigner une adresse email" required="required" placeholder="<?php _e("Your email")?>" class="w200p pts pbs pls">	
							
							<button type="submit" class="bg-green bold pas">
								<?php _e("Subscribe"); ?>
							</button>

						</div>

					</form>

				</div>
				<?}?>


				<!-- Réseaux sociaux -->
				<div class="pts">

					<?php txt('texte-reseaux'); ?>

				</div>

			</article>


			<article class="pbm">
				<!-- Liens -->
				<div class="pbl">

					<?php txt('footer-liens'); ?>

				</div>
				
				<!-- Logo France Relance -->
				<?php media('logo-sponsor', array('size' => '150', 'lazy' => 'true')); ?>
			
			</article>

		</div>

	</section>

	<!-- Liens -->
	<section class="mw960p center tc ptm pbm">

		<?php txt('footer-liens-webmaster'); ?>

	</section>

</footer>

<? include("theme/".$GLOBALS['theme']."/admin/lang.php"); ?>