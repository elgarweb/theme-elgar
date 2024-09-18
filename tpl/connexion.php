<?
if(!$GLOBALS['domain'] or !$GLOBALS['connect']) exit;

// Notice
// Créer la page /connexion
// Dans config : auth_level => intranet

// Désactive le nonce courant pour en créer un nouveau lors de la connexion
// Evite un retour d'erreur sur le nonce qui entraine un sleep
unset($_SESSION['nonce']);

// SI PAS LOGÉ ON AFFICHE LE FORMULAIRE DE LOGIN
if(!login('medium', null, 'true'))// true error 
{
	?>
	<section class="mw960p center">

		<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>


		<?php h1('title', array('class' => 'picto', 'default' => "Connexion")); ?>


		<article class="tc pal">

			<form id="public-login">

				<p class="mbm"><?_e("All fields are mandatory")?></p>
				
				<label for="email" class="block">
					<?php _e("My email");?>
					(<?_e("Expected format" )?> : dupont@exemple.com)
				</label>
				<input type="email" id="email" autocomplete="email" class="w200p" required>
			
				<label for="password" class="block mts"><?php _e("My password");?></label>
				<input type="password" id="password" autocomplete="current-password" class="w200p" required>

				<div class="mts mbt none">
					<input type="checkbox" id="rememberme"> <label for="rememberme"><?_e("Remember me");?></label>
				</div>
									
				<div class="mts">
					<button class="bt mts mbm">
						<?_e("Log in")?>
					</button>			
				</div>

				<input type="hidden" id="mrr" value="">

				<input type="hidden" id="nonce" value="<?=nonce("nonce");?>">

			</form>
			
		</article>


	</section>


	<script>
		// Redircetion en callback après login/inscription
		function redirection(direct)
		{ 	
			// Si direction définit mais pas d'arg à la fonction
			if(typeof direct === 'undefined' && typeof direction !== 'undefined')
				direct = direction;
			// Si rien de définit
			else if(typeof direct === 'undefined' && typeof direction === 'undefined')
				direct = '/';

			document.location.href = document.location.origin + direct;
		}

		// LOGIN
		function login()
		{
			event.preventDefault();

			// Désactive le submit
			$("#public-login button").attr("disabled", true);
			$("#public-login").off("submit");

			if(typeof callback == 'undefined') 
			{
				<?php				
				// Si referer dans le site interne on récupère le permalien
				if(isset($_SERVER['HTTP_REFERER']) and strstr($_SERVER['HTTP_REFERER'], $GLOBALS['home'])) 
					echo 'direction = path + "'.encode(get_url($_SERVER['HTTP_REFERER'])).'";';
				else
					echo'direction = path + permalink;';
				?>

				callback = "redirection";
			}

			$.ajax({ 
				type: "POST",
				url: path+"api/ajax.php?mode=login",
				data: { 
					email: $("#public-login #email").val(),
					password: $("#public-login #password").val(),
					rememberme: $("#public-login #rememberme").prop("checked"),
					nonce: $("#public-login #nonce").val(),
					quiet: 'error',
					callback: callback
				}
			})
			.done(function(html) { 
				// On ferme le formulaire de login
				//$("#public-login").fadeOut("fast");			
				
				// On exécute le retour
				$("body").append(html);

				// Si erreur de login (callback=null), on rétablit le formulaire
				// /!\ => Non utiliser car le nonce est supprimer et créer donc un nonce error
				/*if(!callback) 
				{
					// Re-Active le submit
					$("#public-login button").attr("disabled", false);
					$("#public-login").submit(function(event) {
						login();
					});
				}*/
			});
		}

		$(function()
		{
			// Update les nonces dans la page courante pour éviter de perdre le nonce
			//$("#nonce").val('<?=$_SESSION['nonce']?>');


			// Message d'erreur en cas de mauvaise saisie du mail. Pour l'accessibilité
			var email = document.getElementById("email");
			email.addEventListener("invalid", function() {
				email.setCustomValidity("<?_e("Invalid email")?>. <?_e("Expected format")?> : dupont@exemple.com")
			}, false);
			email.addEventListener("input", function() {
				email.setCustomValidity("");
			}, false);


			// Submit => login
			$("#public-login").submit(function(event) 
			{
				login();
			});
		});
	</script>
	<?
}
// SI DÉJÀ CONNECTÉ afficher un message
else
{
	?>
	<section class="mw960p center">

		<?php include('theme/'.$GLOBALS['theme'].'/ariane.php')?>


		<?php h1('title', array('class' => 'picto', 'default' => "Vous êtes connecté")); ?>


		<article class="tc pal">

			<p><?_e("Vous êtes connecté")?></p>
			
			<button id="logout" class="bt">
				<?_e("Log out")?>
			</button>

		</article>
		
	</section>


	<script>
		// Au clique sur le bouton de déconnexion
		$("#logout").on("click", function(event)
		{
			event.preventDefault();

			$.ajax({
				type: "POST",
				url: path+"api/ajax.php?mode=logout",
				success: function(html){ 
					$("body").html(html);// Retour
					reload();// Recharge la page	
				}
			});
		});
	</script>
	<?php
}
?>