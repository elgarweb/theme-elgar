# [ElgarWeb](https://www.elgarweb.fr/) - Ensemble le web

[![jQuery](https://img.shields.io/badge/Licence-MIT-green.svg)]()
[![jQuery](https://img.shields.io/badge/PHP-7.2-lightgrey.svg?colorB=8892bf)](http://php.net/)

Le projet Elgar Web a pour objectif de donner la possibilité aux communes du territoire Pays Basque de disposer plus facilement et à moindre coût d'un site internet accessible aux personnes en situation de handicap et écoconçu.
La Mission accessibilité de la Communauté Pays Basque a fait appel au Collectif d'indépendants [Translucide](https://www.translucide.net/), spécialisé dans l'écoconception web.
L'objectif est de créer un thème libre de droits (sous licence MIT) pour le [CMS Translucide](https://github.com/simonvdfr/Translucide).

## Installation
- Récupérer les [CMS Translucides](https://github.com/simonvdfr/Translucide/archive/refs/tags/v1.5.zip), décompresser les fichiers 
- Décompresser le thème elgarweb dans le dossier `theme`
	- Vous devez avoir à la racine de votre hébergement ceci :
		```
		api/
		theme/
			default/
			elgar/ <-- le thème elgarweb
				admin/
				img/
				tpl/
				Licence
				ariane.php
				footer.php
				function.php
				header.php
				readme.md
				style.css
				style.min.css
				translation.php
		.gitignore
		.htaccess
		Licence
		favicon.ico
		index.php
		readme.md
		robots.txt	
		```


- Lancer l'installation de translucide
	- Rentrez les informations pour se connecter à votre base de données (vous devez l'avoir créé précédemment)
	- Choisissez bien le [thème Elgar](@todo lien vers la version stable) qui est dans votre dossier `theme`
	- Choisissez vos accès administrateurs, qui vous permettent par la suite d'ajouter d'autre utilisateur et de modifier les contenus du site

### Configuration
#### Configuration de Translucide
Dans le fichier `config.php` qui c'est créer suite à l'installation vous pouvez modifier les variables suivantes :

Éditions barre d'outils $GLOBALS['toolbox'] : vous pouvez retirer les commentaires devant les fonctionnalités que vous voulez utiliser. /!\ Attention l'option de justification de contenu peut rendre non accessibles vos contenus si c'est utilisé.
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

Vous pouvez modifier le type de contenu ajoutable avec la variable $GLOBALS['add_content'] :
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
Pour le thème vous devez créer des pages spécifique. Pour ajouter des contenus [suivez le tutoriel](@todo)

Pour le bon fonctionnement du site vous devez créer des pages types dont le permalien soit :
- `recherche` avec le template `recherche`
- `contact` avec le template `contact`

En plus si votre site comporte certaines sections comme des actualités vous devez créer des pages qui permettent de les lister :
- `actualites` avec le template `article-liste`
- `agenda` avec le template `article-liste`
- `annuaire` avec le template `annuaire-liste`
- `arretes` avec le template `arrete-liste`
il est important de garder ces permaliens car la zone de filtres des tags/catégories se nomme ainsi dans le code.

##### Tourinsoft
Si vous saisissez vos évènements dans Tourinsoft et que vous voulez les rapatrier dans le site il faut spécifier dans le fichier `config.php` dans la variable `$GLOBALS['tourinsoft_flux']` l'URL Tourinsoft qui contient vos évènements.
En plus vous devez créer un cron (tache planifier) qui s'exécute après minuit (heure à laquelle tourinsoft met à jour le flux distant) sur le fichier `/theme/elgar/admin/tourinsoft.php`

#### Configuration du multilingue
Si votre site utilise plusieurs langues vous devez spécifier les langues existant dans le fichier de `config.php` et les URL des domaines des autres langues qui doivent tous pointer vers le même dossier ou est installé le CMS.

Exemple pour un site en français et basque de config.php : ```@todo```

De plus vous devez ajouter les traductions dans le fichier `translation.php` qui contient déjà les textes en anglais, français et basque pour l'interface vu par les visiteurs du site.


## Premier pas pour la gestion du contenu
Pour la prise en main du CMS nous vous invitons à regarder ce [tutoriel](@todo)


### Spécificité du thème

#### Menu de navigation
Le site n'utilise pas de menu déroulant mais des pages intermédiaires de navigation pour simplifier le code HTML et aussi rendre la navigation plus stable à la souris.
Pour créer une page intermédiaire de navigation vous devez :
- Créer une page avec le template `navigation`
- Ajouter au menu principal cette page [voir tutoriel de prise en main](@todo)
- Dans les pages que vous voulez voir apparaître dans cette page intermédiaire vous devez en mode édition sélectionner ce dernier dans le menu déroulant qui se situe dans le chemin de navigation (fil d'Ariane) pour la rattacher.


## Personnalisation du thème
Pour personnaliser le thème vous pouvez modifier le fichier `style.css` dans le dossier `theme/elgar/`.
Attention à bien prendre en compte les règles d'accessibilité.
Vous pouvez modifier les couleurs des class des typos `.color` & `.color-alt` et des fonds `.bg-color`.
Bien prendre en compte les ratios de contraste pour limiter les problèmes d'accessibilité. @todo donner le ratio RGAA


## Création templates

### Les fonctions 
Comme vous pouvez le voir dans la [documentation du CMS](https://github.com/simonvdfr/Translucide#fonctions-pour-rendre-%C3%A9ditables-des-zones) dans les fichiers dans le dossier `tpl` du thème vous pouvez utiliser des fonctions qui vous permettent de donner la main au webmaster sur le contenu une fois en mode édition.

D'autres fonction sont utilisables librement dans le code des templates :
- `_e()` Permets d'afficher une traduction qui est dans le tableau présent dans le fichier `translation.php` dans le dossier du thème. La variante `__()` fait juste un retour de la traduction pour la manipuler en variable par exemple, elle ne fait pas un affichage.
- `make_url()` Permets de créer des URL normées avec notamment le forçage du nom de domaine : `make_url(__('agenda'), array("domaine" => true))` par exemple permet de faire un lien vers la page agenda (en utilisant la traduction du mot `agenda`) et y ajoute le nom de domaine complet (https://www.elgarweb.fr/agenda)
- `tag("nom-de-la-zone")` Permets de créer une zone éditable pour ajouter des tags/catégorie à la page courante. Typiquement pour filtrer après dans les pages qui listent des contenus comme la page actualité ou agenda.
- `href()` Permets de créer des liens éditables `<a <?php href();?>>lien editable vers un contenu</a>`


## Responsabilité & support

Nous ne sommes pas responsable de l'utilisation du thème. Par défaut le thème est totalement conformé au RGAA. Nous ne sommes pas garant du niveau d'accessibilité de votre site. Nous ne pouvons pas être tenus responsables de quelconque dégradation d'accessibilité due à vos modifications ou mauvaise saisie des contenus.
Nous vous recommandons de limiter les modifications thème aux couleurs de typographie et des fonds dans le fichier `style.css`, tout en analysant les ratios de contraste pour avoir au moins toujours un ratio de 4.5:1, au moins, entre la couleur et le fond.
Aucun SAV ou support n'est compris avec le thème/dépôt github. Aucune maintenance ou suivi des tickets n'est possible. Des changements, amélioration et/ou correction peuvent advenir suite à la commande de la Communauté Pays basque.

## Licence

Elgarweb est sous licence MIT, merci de vous référer au fichier `Licence` à la racine du dossier.

L'équipe Translucide qui a participé à ce projet se compose de [Simon Vandaele](https://github.com/simonvdfr) développeur web et auteur du CMS Translucide, [Maud Subiry](http://maudsubiry.fr/) pour le Webdesign, [stéphanie Leroux](https://www.koinga.fr/) intégratrice HTML & CSS, [Dominique Nicolle](https://www.pix-e.fr/) Accessibilité