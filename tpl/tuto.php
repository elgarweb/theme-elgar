<?
/*
@todo:

lors de la création du site créer les pages suivantes pour le bon fonctionnement du site
- recherche
- navigation
- ....

=> voir en fonction des traductions, et si plusieur (3 langues)

*/
?>

<style>

.tpl-tuto h2, .tpl-tuto .h2-like,
.tpl-tuto h3, .tpl-tuto .h3-like {
    text-align: left;
    margin-bottom: 0;
}

.tpl-tuto a {
    display: block;
}

.tpl-tuto i {
    font-size: 2rem;
}
   
</style>

<!-- SOMMAIRE -->
<section class="mw960p center">
    
    <h1>Tutoriel CMS Translucide</h1>

    <p class="h2-like">Sommaire</p>

    <p class="h3-like">Mode édition</p>
    <a href="#connecter">Se connecter en mode édition</a>
    <a href="#quitter">Quitter le mode édition</a>
    <a href="#deconnecter">Se déconnecter</a>

    <p class="h3-like">Contenu</p>
    <a href="#ajouter-page">Ajouter un produit/article/page</a>
    <a href="#saisir">Saisir les données de la page (pour le référencement)</a>
    <a href="#ajouter-contenu">Ajouter du contenu</a>
    <a href="#ajouter-image">Ajouter une image dans une zone de texte</a>
    <a href="#modifier-contenu">Mettre à jour du contenu</a>
    <a href="#supprimer-page">Supprimer un produit/article/page</a>
    <a href="#ajouter-menu">Ajouter un menu de navigation</a>
    <a href="#supprimer-menu">Supprimer un menu de navigation</a>

    <p class="h3-like">Utilisateurs</p>
    <a href="#ajouter-utilisateur">Ajouter un utilisateur</a>
    <a href="#modifier-utilisateur">Modifier un utilisateur</a>
    <a href="#supprimer-utilisateur">Supprimer un utilisateur</a>

</section>

<!-- TUTO -->
<section class="mw960p center pbl">

    <h2><i class="fa fa-pencil"></i> Mode édition</h2>

    <article id="connecter">

        <h3>Se connecter en mode édition</h3>

        <ul>
            <li>Cliquer sur la clé <i class="fa fa-key"></i> en bas à gauche de l'écran (s'affiche au survol)</li>
           
            <li>Rentrer l'adresse email et le mot de passe transmis par votre webmaster</li>
        </ul>

    </article>

    <article id="quitter">

        <h3>Quitter le mode édition</h3>

        <p>Pour quitter le mode édition afin d'avoir un aperçu des ajouts, modifications, suppressions, cliquer sur <i class="fa fa-cancel"></i> en haut à droite.</p>

    </article>

    <article id="deconnecter">

        <h3>Se déconnecter</h3>

        <p>Pour se déconnecter, afin par exemple de se connecter avec un autre utilisateur, se positionner sur <i class="fa fa-user-circle"></i> puis cliquer sur <i class="fa fa-logout"></i>.</p>

    </article>

    <h2><i class="fa fa-doc-text"></i> Contenu</h2>

    <article id="ajouter-page">

        <h3>Ajouter un produit/article/page</h3>

        <ul>
            <li>Passer en mode édition en cliquant sur <i class="fa fa-plus"></i> en bas à gauche (apparaît au survol). Un popup apparaît.</li>
            <li>Cliquer sur l'onglet du type de contenu souhaité</li>
            <li>Ajouter un titre</li>
            <li>Choisir le modèle de page correspondant dans la liste déroulante</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
            <li>Penser à passer la page/article en mode "visible" en cliquant sur <i class="fa fa-eye-off"></i> quand le contenu est prêt à être publié</li>
        </ul>
            
    </article>

    <article id="saisir">

        <h3>Saisir les données de la page (pour le référencement)</h3>

        <ul>
            <li>Passer en mode édition en cliquant sur <i class="fa fa-pencil"></i> en bas à gauche (apparaît au survol)</li>
            <li>Survoler le titre de la page tout en haut afin de voir apparaître les données de la page</li>
            <li>Saisir le titre de la page : il s'agit du titre qui apparaîtra dans les moteurs de recherche</li>
            <li>Remplir la Description pour les moteurs de recherche : il s'agit des quelques lignes qui apparaîtront sous le titre dans les moteurs de recherche</li>
            <li>Si besoin, cocher "noindex" afin d'empêcher que la page soit indexée par les moteurs de recherche (par exemple pour les mentions légales)</li>
            <li>Si besoin, cocher "nofollow" afin que les liens de cette page ne soient pas suivis par les moteurs de recherche</li>
            <li>Si besoin, modifier l'adresse web formatée qui apparaîtra dans l'url de votre page</li>
            <li>Si besoin, ajouter une image qui apparaîtra lors d'un partage de votre page sur les réseaux sociaux</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
        </ul>
   
    </article>

    <article id="ajouter-contenu">

        <h3>Ajouter du contenu</h3>

        <ul>
            <li>Après la création d'un produit/article/page, le mode édition est automatiquement activé</li>
            <li>Se positionner sur chaque zone éditable et ajouter le contenu souhaité</li>
            <li>Pour ajouter une image, cliquer sur la zone grisée puis sélectionner l'image à télécharger</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
        </ul>
       
    </article>

    <article id="ajouter-image">

        <h3>Ajouter une image dans une zone de texte</h3>

        <ul>
            <li>Après la création d'un produit/article/page, le mode édition est automatiquement activé</li>
            <li>Se positionner à l'endroit souhaité dans la zone de texte</li>
            <li>Cliquer sur <i class="fa fa-picture"></i> de la barre d'outil</li>
            <li>Redimensionner l'image si besoin en cliquant sur l'image puis sur le coin en bas à droite de l'image</li>
            <li>Si besoin, cliquer sur "sous-titre" pour ajouter une légende</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
        </ul>
       
    </article>

    <article id="modifier-contenu">

        <h3>Mettre à jour du contenu</h3>

        <ul>
            <li>Se positionner sur la page/article concerné-e via le menu</li>
            <li>Passer en mode édition en cliquant sur <i class="fa fa-pencil"></i> en bas à gauche (apparaît au survol)</li>
            <li>Ou cliquer sur <i class="fa fa-menu"></i> en haut à gauche puis cliquer sur la page/article concerné-e</li>
            <li>Cliquer sur la zone à modifier, faire les modifications souhaitées (possibilité de mettre en forme le texte, la barre d'outils apparaît au survol du texte concerné)</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
        </ul>
        
    </article>

    <article id="supprimer-page">

        <h3>Supprimer un produit/article/page</h3>

        <ul>
            <li>Pour sortir du mode édition, cliquer sur <i class="fa fa-cancel"></i></li>
            <li>Aller sur la page concernée (cf. <a href="#modifier-contenu" class="inline">Mettre à jour du contenu</a>)</li>
            <li>Cliquer sur le bouton "Supprimer" en haut à droite</li>
        </ul>
        
    </article>

    <article id="ajouter-menu">

        <h3>Ajouter un menu de navigation</h3>

        <ul>
            <li>Se positionner sur le haut de la page et cliquer sur <i class="fa fa-pencil"></i> (apparaît au survol)</li>
            <li>Cliquer sur <i class="fa fa-plus"></i> en face de chaque menu qui doit être ajouté</li>
            <li>Pour modifier l'ordre des menus, se positionner sur la zone grisée au-dessus du menu concerné puis cliquer quand la petite main apparaît</li>
        </ul>

    </article>

    <article id="supprimer-menu">

        <h3>Supprimer un menu de navigation</h3>

        <p>Se positionner sur la zone grisée au-dessus du menu concerné puis cliquer sur la croix <i class="fa fa-cancel"></i></p>
        
    </article>

    <h2><i class="fa fa-users"></i> Utilisateurs</h2>

    <article id="ajouter-utilisateur">

        <h3>Ajouter un utilisateur</h3>

        <ul>
            <li>Cliquer sur <i class="fa fa-user-circle"></i> puis sur <i class="fa fa-user-plus"></i></li>
            <li>Sélectionner les droits à attribuer à cet utilisateur (maintenir la touche CTRL appuyée pour en sélectionner plusieurs)</li>
            <li>Saisir un pseudo, une adresse email</li>
            <li>Cliquer sur <i class="fa fa-mail"></i> pour envoyer le mot de passe par mail</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
        </ul>
        
    </article>

    <article id="modifier-utilisateur">

        <h3>Modifier un utilisateur</h3>

        <ul>
            <li>Cliquer sur <i class="fa fa-user-circle"></i> puis sur <i class="fa fa-users"></i> pour voir la liste de tous les utilisateurs</li>
            <li>Cliquer sur l'utilisateur à modifier</li>
            <li>Pour mettre à jour le pseudo, l'adresse email ou le mot de passe, les modifier simplement dans la zone concernée</li>
            <li>Pour mettre à jour les droits utilisateur (autorisations), sélectionner ou désélectionner les droits concernés (maintenir la touche CTRL appuyée pour en sélectionner plusieurs)</li>
            <li>Cliquer sur le bouton "Enregistrer" en haut à droite</li>
        </ul>
    
    </article>

    <article id="supprimer-utilisateur">

        <h3>Supprimer un utilisateur</h3>

        <ul>
            <li>Cliquer sur <i class="fa fa-user-circle"></i> puis sur <i class="fa fa-users"></i> pour voir la liste de tous les utilisateurs</li>
            <li>Cliquer sur l'utilisateur à supprimer</li>
            <li>Cliquer sur <i class="fa fa-trash"></i> en bas à gauche du menu</li>
            <li>Confirmer en cliquant sur OK</li>
        </ul>
        
    </article>

</section>

