# [ElgarWeb](https://www.elgarweb.fr/) - Ensemble le web

[![jQuery](https://img.shields.io/badge/Licence-MIT-green.svg)]()
[![jQuery](https://img.shields.io/badge/PHP-7.2-lightgrey.svg?colorB=8892bf)](http://php.net/)

## Présentation
Le projet Elgar Web a pour objectif de donner la possibilité aux communes du territoire Pays Basque de disposer plus facilement et à moindre coût d'un site internet accessible aux personnes en situation de handicap et écoconçu.

Le réseau CCA-CIA de la Communauté Pays Basque a fait appel au Collectif d'indépendants [Translucide](https://www.translucide.net/), spécialisé dans l'écoconception web.

L'objectif est de créer un thème libre de droits (sous licence MIT) pour le [CMS Translucide](https://github.com/simonvdfr/Translucide).

## Installation
- Récupérer les fichiers du CMS [CMS Translucides](https://github.com/simonvdfr/Translucide/releases) et les décompresser 
- Décompresser le thème elgarweb dans le dossier `/theme`

	- Vous devez avoir à la racine de votre hébergement ceci :
		```
		api/
		    ajax.admin.php
			ajax.php
			config.init.php
			db.php
			function.php <-- fonctions principales
			install.php
			lucide.css <-- CSS du mode édition
			lucide.edit.js
			lucide.init.js
			translation.php <-- fichier de traduction principal
		theme/
			default/
			elgar/ <-- le thème elgarweb
				admin/
					alaune.php <-- mise en avant d'un événement/actualité
					lang.php <-- gestion du multilingue
					tourinsoft.php <-- événements Tourinsoft
				img/
				tpl/ <-- modèles de pages
					annuaire-liste.php
					annuaire.php
					arrete-liste.php
					arrete.php
					article-liste.php <-- page liste pour agenda et actualités
					article.php <-- page détail pour actualités
					contact.php
					event.php <-- page détail pour agenda
					home.php
					navigation.php <-- page de navigation intermédiaire
					page-sommaire.php <-- page avec sommaire intégré
					page.php <-- page générique (pour mentions légales...)
					personnalites.php
					recherche.php
					sitemap.php
				Licence
				ariane.php
				footer.php
				function.php <-- fonctions du thème
				header.php
				readme.md
				style.css
				style.min.css
				translation.php <-- fichier de traduction du thème
		.gitignore
		.htaccess
		Licence
		favicon.ico
		index.php
		readme.md
		robots.txt	
		```


- Lancer l'installation de Translucide en allant à l'adresse où se trouve `index.php` :
	- Rentrer les informations pour se connecter à la base de données (vous devez l'avoir créée précédemment)
	- Choisir le [thème Elgar](https://github.com/elgarweb/theme-elgar/releases) qui est dans le dossier `/theme`
	- Choisir vos accès administrateurs, qui vous permettent par la suite d'ajouter d'autres utilisateurs et de modifier les contenus du site

### Configuration
#### Configuration de Translucide
Dans le fichier `config.php` qui s'est créé suite à l'installation vous pouvez modifier les variables suivantes :

- Édition barre d'outils $GLOBALS['toolbox'] : vous pouvez retirer les commentaires devant les fonctionnalités que vous voulez utiliser. /!\ Attention l'option de justification de contenu (justifyFull) peut rendre non accessibles vos contenus si c'est utilisé.
```php 
$GLOBALS['toolbox'] = array(
	"h2",
	"h3",
	//"h4",
	//"h5",
	//"h6",
	"bold",
	"italic",
	"underline",
	//"superscript",
	//"fontSize",
	//"color",
	"p",
	"blockquote",
	"highlight",
	"insertUnorderedList",
	//"justifyLeft",
	//"justifyCenter",
	//"justifyRight",
	//"justifyFull",
	//"InsertHorizontalRule",
	//"viewsource",
	//"icon",
	"media",
	"figure",
	//"video",
	"lang",
	//"anchor",
	//"bt",
	"link"
);
```

- Vous pouvez modifier le type de contenu ajoutable avec la variable $GLOBALS['add_content'] :
```php 
$GLOBALS['add_content'] = array(
	"article" => ["fa" => "fa-rss", "tpl" => "article"],
	"event" => ["fa" => "fa-calendar-empty", "tpl" => "event"],
	"event-tourinsoft" => ["fa" => "fa-calendar-empty", "tpl" => "event"],
	"annuaire" => ["fa" => "fa-location", "tpl" => "annuaire"],
	"arrete" => ["fa" => "fa-info-circled", "tpl" => "arrete"],
	"page" => ["fa" => "fa-doc-text", "tpl" => "page"]
);
```

#### Configuration spécifique au thème
##### Fonctions spécifiques et Recherche
Dans le fichier `config.php` :

- Vous devez spécifier le fichier de fonctions spécifiques au thème dans la variable `$GLOBALS['function'] = 'function.php';`

- Vous devez également ajouter les filtres suivants qui permettent de construire des URL (pour le moteur de recherche par exemple) :
```php 
// Filtre url autorisé
$GLOBALS['filter_auth'] = array(
	'page',
	'user',
	'recherche',
	'month', 'year',
	'start', 'end'
);
```

##### Création de pages spécifiques
Pour le thème vous pouvez aussi créer des pages spécifiques. Pour ajouter des contenus [suivez le tutoriel](https://www.elgarweb.fr/tutoriel.html)

Pour le bon fonctionnement du site vous devez créer des pages types dont le permalien soit :
- `recherche` à partir du template `recherche`
- `contact` à partir du template `contact`

En plus si votre site comporte certaines sections comme des actualités vous devez créer des pages qui permettent de les lister :
- `actualites` à partir du template `article-liste`
- `agenda` à partir du template `article-liste`
- `annuaire` à partir du template `annuaire-liste`
- `arretes` à partir du template `arrete-liste`

Il est important de garder ces permaliens car la zone de filtres des tags/catégories se nomme ainsi dans le code.

##### Tourinsoft
Si vous saisissez vos évènements dans Tourinsoft et que vous voulez les rapatrier dans le site il faut spécifier l'URL Tourinsoft qui contient vos évènements dans la variable `$GLOBALS['tourinsoft_flux']` dans le fichier `config.php`.

En plus vous devez créer un cron (tâche planifiée) qui s'exécute après minuit (heure à laquelle Tourinsoft met à jour le flux distant) sur le fichier `/theme/elgar/admin/tourinsoft.php`

#### Configuration du multilingue
Si votre site utilise plusieurs langues vous devez spécifier les langues existantes dans le fichier `config.php` et les URL des domaines des autres langues qui doivent toutes pointer vers le même dossier où est installé le CMS.

Exemple pour un site en français et basque de `config.php` :
```php
// Fixe la langue en fonction de l'extension du nom de domaine
if(strstr($_SERVER['SERVER_NAME'], '.eus')) 
	$lang = $_SESSION['lang'] = "eu";
else
	$lang = $_SESSION['lang'] = "fr";

// Variables sites pour le multilingue
$GLOBALS['language'] = array('fr','eu');

$GLOBALS['theme_translation'] = true;

$GLOBALS['domain_lang']['fr'] = "www.elgarweb.fr";
$GLOBALS['domain_lang']['eu'] = "www.elgarweb.eus";

$GLOBALS['domain'] = $GLOBALS['domain_lang'][$lang];

```

De plus vous devez ajouter les traductions dans le fichier `translation.php` qui contient déjà les textes en anglais, français et basque pour l'interface vue par les visiteurs du site.


## Premier pas pour la gestion du contenu
Pour la prise en main du CMS nous vous invitons à consulter ce [tutoriel](https://www.elgarweb.fr/tutoriel.html)


### Spécificités du thème

#### Menu de navigation
Le site n'utilise pas de menu déroulant mais des pages intermédiaires de navigation pour simplifier le code HTML et aussi rendre la navigation plus stable à la souris.
Pour créer une page intermédiaire de navigation vous devez :
- Créer une page avec le template `navigation`
- Ajouter au menu principal cette page [voir tutoriel de prise en main](https://www.elgarweb.fr/tutoriel.html) et ajouter les pages qui dépendent de ce menu.

#### Plan du site
Le template `sitemap.php` reprend les éléments du menu dans du header et y ajoute les pages connecter à travers le template `navigation.php`.
Également y sont ajoutés à la fin les liens présents dans les `<ul>` du footer contenu dans les champs editable `#footer-liens` et `#footer-liens-webmaster`. Typiquement des liens vers des pages secondaires (actualité, agenda, annuaire, téléchargement).

## Personnalisation du thème
Pour personnaliser le thème vous pouvez modifier le fichier `style.css` dans le dossier `theme/elgar/`.
Attention à bien prendre en compte les règles d'accessibilité.
Vous pouvez modifier les couleurs des class des typos `.color` & `.color-alt` et des fonds `.bg-color`.
Bien prendre en compte les ratios de contraste à au moins 4.5:1 pour limiter les problèmes d'accessibilité. Pour plus de détails voir le [point 3.2 du RGAA](https://www.numerique.gouv.fr/publications/rgaa-accessibilite/methode-rgaa/criteres/#topic3).

Pour vous aider pour maintenir l'accessibilité du site que vous allers construire vous pouvez suivre le [tableau des critères RGAA](https://github.com/elgarweb/theme-elgar/blob/main/rgaa.md) que nous avons mise en place pour voir les critères à impacts à surveiller que vos soyers développeurs et modifier le CSS, mais aussi pour les éditeurs de contenu.


## Création templates


### Les fonctions 
Dans les fichiers du dossier `tpl` du thème vous pouvez utiliser des fonctions qui vous permettent de donner la main au webmaster sur le contenu une fois en mode édition : voir [documentation du CMS](https://github.com/simonvdfr/Translucide#fonctions-pour-rendre-%C3%A9ditables-des-zones), .

D'autres fonctions sont utilisables librement dans le code des templates :
- `_e()` permet d'afficher une traduction qui est dans le tableau présent dans le fichier `translation.php` du dossier du thème. La variante `__()` fait juste un retour de la traduction pour la manipuler en variable par exemple mais n'affiche pas la traduction.
- `make_url()` permet de créer des URL normées avec notamment le forçage du nom de domaine : `make_url(__('agenda'), array("domaine" => true))` par exemple permet de faire un lien vers la page agenda (en utilisant la traduction du mot `agenda`) et y ajoute le nom de domaine complet (https://www.elgarweb.fr/agenda)
- `tag("nom-de-la-zone")` permet de créer une zone éditable pour ajouter des tags/catégories à la page courante. Typiquement pour pouvoir ensuite filtrer dans les pages qui listent des contenus, comme les pages `Actualités` ou `Agenda`.
- `href()` permet de créer des liens éditables : `<a <?php href('nom-du-lien');?>>texte du lien</a>`


## Responsabilité & support

Nous ne sommes pas responsables de l'utilisation du thème. Par défaut le thème est totalement conformé au RGAA. Nous ne sommes pas garants du niveau d'accessibilité de votre site. Nous ne pouvons pas être tenus responsables de quelconque dégradations d'accessibilité dues à vos modifications ou mauvaise saisie des contenus.

Nous vous recommandons de limiter les modifications du thème aux couleurs de typographie et des fonds de couleur dans le fichier `style.css`, tout en analysant les ratios de contraste pour avoir toujours au moins un ratio de 4.5:1 entre la couleur et le fond.
Vous pouvez suivre le [tableau des critères RGAA](https://github.com/elgarweb/theme-elgar/blob/main/rgaa.md) pour voir les points de vigilance à avoir sur les éléments impactés par le CSS.

Aucun SAV ou support n'est compris avec le thème/dépôt Github. Aucune maintenance ou suivi des tickets n'est possible. Des changements, améliorations et/ou corrections peuvent advenir suite à la commande de la Communauté Pays basque.

## Licence

Elgarweb est sous licence MIT, merci de vous référer au fichier `Licence` à la racine du dossier.

L'équipe Translucide qui a participé à ce projet se compose de [Simon Vandaele](https://github.com/simonvdfr) développeur web et auteur du CMS Translucide, [Maud Subiry](http://maudsubiry.fr/) graphiste pour le Webdesign, [Stéphanie Leroux](https://www.koinga.fr/) pour l'intégration HTML & CSS, [Dominique Nicolle](https://www.pix-e.fr/) pour l'accessibilité.