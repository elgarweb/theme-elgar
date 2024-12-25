<?php if(!$GLOBALS['domain']) exit;?>

<footer role="contentinfo">

	<div class="editable-hidden tc ptm"><i class="fa fa-attention" aria-hidden="true"></i><?php _e("Have you taken the accessibility rules into account when entering your content?")?></div>

	<!-- Aide accessibilité -->
	<?php txt('texte-aide-access', array('tag' => 'p', 'class' => 'mw960p center tc ptl editable-hidden')); ?>

	<?php if(isset($res['url'])){?>
		<!-- PARTAGE RÉSEAUX SOCIAUX -->
		<section id="partage" class="mw960p flex wrap jcc center tc ptl pbs">

			<p><?php _e('Share this page'); ?></p>

			<!-- <?php txt('texte-reseaux-sociaux', 'plm'); ?> -->
			<ul class="plm">

				<?php				
				$titre_encode = rawurlencode($title);
				$url_encode = urlencode(make_url($res['url'], array("domaine" => true)));
				?>

				<li class="inline"><a href="https://www.facebook.com/sharer/sharer.php?u=<?=$url_encode?>" target="_blank"><i class="fa fa-fw fa-facebook big" aria-hidden="true"></i>Facebook</a></li>
				
				<li class="inline"><a href="mailto:?subject=<?=$titre_encode?>&body=<?=$url_encode?>" target="_blank"><i class="fa fa-fw fa-mail big mlm mrs" aria-hidden="true"></i><?php _e("Mail")?></a></li>

			</ul>

		</section>
	<?php }?>

	<!-- RENSEIGNEMENTS COMPLEMENTAIRES -->
	<section id="renseignement" class="<?=(isset($GLOBALS['content']['texte-renseignements']) ? 'bg-color-2 ptl pbt' : 'editable-hidden'); ?>">

		<?php txt('texte-renseignements', array('tag' => 'article', 'class' => 'mw960p center bold tc')); ?>

	</section>

	<!-- CONTACTS -->
	<section id="contacts" class="bg-color ptl pbm">

		<div class="mw960p center flex jcsb">

			<?php txt('texte-coordonnees', array('tag' => 'article', 'class' => 'prm'))?>

			<article class="prm">

				<?php if(isset($GLOBALS['newsletter-key'][$lang])){?>
				<form id="newsletter" method="post" action="https://newsletter.infomaniak.com/external/submit" target="_blank" class="pbm mbs">

					<input type="email" name="email" style="display:none" />

					<input type="hidden" name="key" value="<?=@$GLOBALS['newsletter-key'][$lang]?>">

					<input type="hidden" name="webform_id" value="<?=@$GLOBALS['newsletter-id'][$lang]?>">

					<label for="email_newsletter"><?php _e('Subscribe to the newsletter of your city') ?><span class="block small ptt"><?php _e('Expected format');?> : nomprenom@exemple.com</span></label>

					<div class="flex pts">

						<input type="email" name="inf[1]" id="email_newsletter" autocomplete="email" required="required" placeholder="<?php _e("Your email")?>" class="w200p pts pbs pls">
						
						<button type="submit" class="bg-color-2 bold pas">
							<?php _e("Subscribe"); ?>
						</button>

					</div>

				</form>
				<script>
					// Message d'erreur en cas de mauvaise saisie du mail. Pour l'accessibilité
					var email_newsletter = document.getElementById("email_newsletter");
					email_newsletter.addEventListener("invalid", function() {
						email_newsletter.setCustomValidity("<?php _e("Invalid email")?>. <?php _e("Expected format")?> : dupont@exemple.com")
					}, false);
					email_newsletter.addEventListener("input", function() {
						email_newsletter.setCustomValidity("");
					}, false);
				</script>
				<?php }?>

				<!-- Réseaux sociaux -->
				<?php txt('texte-reseaux'); ?>

			</article>

			<article class="prm">

				<!-- Liens -->
				<?php txt('footer-liens', 'pbm'); ?>
				
				<!-- Logo France Relance -->
				<?php media('logo-sponsor', array('size' => '150', 'lazy' => 'true')); ?>
			
			</article>

		</div>

	</section>

	<!-- Liens -->
	<section id="footer-liens-plus">
		<?php txt('footer-liens-webmaster', array('tag' => 'section', 'class' => 'mw960p center tc ptm pbm ulinline')); ?>
	</section>

</footer>

<script>
	noAccess = false;

	// Ajout du aria-label "nouvelle fenêtre" au lien sortant
	$("a[target='_blank']").each(function() 
	{
		//if(!$(this).attr("aria-label"))		
			$(this).attr("aria-label", $(this).text() + " - <?php _e("New window");?>");
		
		if($(this).children().prop("tagName") != "IMG")
			$(this).addClass("external");// Pour l'icône d'ouverture dans un nouvel onglet
	});

	// Ajout du aria-label pour dire que la destination du lien est non accessible
	// Ajout d'un lien avec la phrase d'aide en bas de page
	$("a.no-access").each(function() 
	{
		$(this)
			.attr("aria-label", $(this).text() + ($(this).hasClass("external")?" - <?php _e("New window");?>":"") + " - <?php _e("Link destination not accessible");?>")
			.attr("aria-describedby","texte-aide-access");

		noAccess = true;
	});

	// Si contenu non accessible on affiche le message d'aide
	if(noAccess) $("#texte-aide-access").show();

	// Si mobile on n'affiche pas le bouton d'accès au login admin
	if(ismobile()) {
		bt_login = false;
		$(".bt.fixed.edit").remove();
	}

	<?php if(isset($GLOBALS['plausible_auth']) or ($intranet and $admin_intranet)){?>
	$(function()
	{
		edit.push(function() 
		{
			<?php
			// Statistique Plausible
			if(isset($GLOBALS['plausible_auth']) and @$GLOBALS['plausible_auth']){?>
				// Bouton admin Statistique
				if(get_cookie('auth').indexOf('view-stats') !== -1)
					$("#admin-bar").append("<button id='statistique' class='fl mat small t5 popin'><i class='fa fa-chart-bar big vatt'></i> <span class='no-small-screen'>Statistique</span></button>");

				// OUVERTUR DE LA DIALOG ADMIN
				$("#admin-bar button.popin").on("click",
					function(event) {
						that = this;

						$.ajax({
							url: path+"theme/"+theme+"/admin/"+ that.id +".php?nonce="+$("#nonce").val(),
							success: function(html)
							{				
								$("body").append(html);

								$(".dialog").dialog({
									autoOpen: false,
									modal: true,
									width: "90%",//"850" "auto"
									position: { my: "center top", at: "center bottom+10px", of: $("#admin-bar") },
									show: function() {$(this).fadeIn(300);},
									close: function() { $(".dialog").remove(); }
								});

								$(".dialog").dialog("open");
							}
						});
					}
				);
			<?php }

			// Intranet
			if($intranet and $admin_intranet){?>
				// Bouton contenu Intranet
				$("#admin-bar").append("<div class='intranet fr mat mrs switch o50 ho1 t5'><input type='checkbox' id='intranet-bar' class='none'><label for='intranet-bar' title=\"Contenu Intranet\"><i></i></label></div>");

				// Checkbox pour la savegarde
				$("body > .content").append("<input type='checkbox' id='intranet' class='editable-input none'>");

				// Position du bouton par défaut
				<?if(@$content['intranet'] == 'true'){?>
					$("#intranet, #intranet-bar").prop("checked", true);
				<?}?>

				// Action sur le bouton intranet
				$("#intranet-bar").click(function(event){
					console.log($(this).prop("checked"))
					console.log($("#intranet").prop("checked"))
					
					// Change le statut de la checkbox dans le contenu editable pour la sauvegarde
					$("#intranet").prop("checked", $(this).prop("checked"));

					tosave();
				});
			<?php }?>	
		});
	});
	<?php }?>
</script>

<?php include("theme/".$GLOBALS['theme']."/admin/lang.php"); ?>