<?php if(!$GLOBALS['domain']) exit;?>

<!--
@todo Simon :
- partage par mail
- newsletter (repris d'Adaptaville)
- ajouter lien vers plan du site
-->

<footer role="contentinfo">

	<!-- PARTAGE RÉSEAUX SOCIAUX -->
	<section id="partage" class="mw1044p flex wrap jcc center tc ptl pbl pls prs">

		<?if(isset($res['url'])){?>

			<?php _e('Share this page'); ?>

			<!-- <?php txt('texte-reseaux-sociaux', 'plm'); ?> -->
			<div class="plm">

				<?php				
				$titre_encode = rawurlencode($title);
				$url_encode = urlencode(make_url($res['url'], array("domaine" => true)));
				?>

				<a href="https://www.facebook.com/sharer/sharer.php?u=<?=$url_encode?>" target="_blank">Facebook<i class="fa fa-fw fa-facebook color-alt big pls prm" aria-hidden="true"></i></a>
				
				<a href="mailto:?subject=<?=$titre_encode?>&body=<?=$url_encode?>" target="_blank">Mail<i class="fa fa-fw fa-mail color-alt big pls prm" aria-hidden="true"></i></a>

			</div>
			
		<?}?>

	</section>

	<!-- RENSEIGNEMENTS COMPLEMENTAIRES -->
	<section class="<?=(isset($GLOBALS['content']['texte-renseignements']) ? 'bg-color-alt ptl pbl' : 'editable-hidden'); ?>">

		<article class="mw1044p center pls prs">

			<?php txt('texte-renseignements', 'bold tc'); ?>

		</article>

	</section>

	<!-- CONTACTS -->
	<section id="contacts" class="bg-color ptl pbm">

		<div class="mw1044p center grid space-xl pls prs">

			<article>

				<?php txt('texte-coordonnees')?>

			</article>


			<article>

				<!-- @todo Formulaire newsletter -->
				<div class="pbm">	

					<form id="newsletter" method="post" action="" target="_blank">

						<input type="email" name="email" style="display:none" />

						<!-- <input type="hidden" name="key" value="<?=$GLOBALS['newsletter-key'][$lang]?>"> -->

						<!-- <input type="hidden" name="webform_id" value="<?=$GLOBALS['newsletter-id'][$lang]?>"> -->

						<label for="email_newsletter"><?php _e('Subscribe to the newsletter of your city') ?></label>

						<div class="flex pts">

							<input type="email" name="inf[1]" id="email_newsletter" data-inf-meta="1" data-inf-error="Merci de renseigner une adresse email" required="required" placeholder="<?php _e("Your email")?>" class="w400p pts pbs pls">	
							
							<button type="submit" class="bt bg-color-alt bold pts pbs plm prm">
								<?php _e("Subscribe"); ?>
							</button>

						</div>

					</form>

				</div>

				<!-- Réseaux sociaux -->
				<div class="pts">

					<?php txt('texte-reseaux'); ?>

				</div>

			</article>


			<article>
				<!-- Liens -->
				<div class="pbl">

					<?php txt('footer-liens'); ?>

				</div>
				
				<!-- Logo France Relance -->
				<?php media('logo-sponsor', array('size' => '150', 'lazy' => 'true', 'class' => 'ptm')); ?>
				<!-- <img src="/theme/<?= $GLOBALS['theme']; ?>/img/logo-france-relance.png" alt="Logo France Relance" style="width: 150px;" loading="lazy" class="ptm"> -->

			</article>

		</div>

	</section>

	<!-- Liens -->
	<section class="mw1044p mod flex wrap jcc space center tc ptm pbm pls prs">
		
		<a href="<?=make_url(__('contact'), array("domaine" => true))?>" class="tdu">
			<?= __("Contact"); ?>
		</a>
		 - 
		<a href="<?=make_url(__('sitemap'), array("domaine" => true))?>" class="tdu">
			<?= __("Site map"); ?>
		</a>
		 - 
		<a href="<?=make_url(__('legal-notices'), array("domaine" => true))?>" class="tdu">
			<?= __("Legal notices"); ?>
		</a>

	</section>

</footer>
