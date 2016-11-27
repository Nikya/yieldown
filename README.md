# Yieldown

## Générateur de site web avec PHP + Markdown + NoSql

#### Version 1.1

##### _Yieldown_ est un framework PHP léger de génération de site web basé sur 2 concepts :

* **_La forme_** : Il utilise la puissance de **PHP** pour construire la partie dynamique du site et ses fonctionnalités, sans imposer de limites, basé sur une implémentation type Vues-Contrôleurs.

* **_Le fond_** : Il permet de mettre à jours facilement le contenue du site grâce à sa partie administration et la rédaction de son contenue en **_Markdown_**, le tout sans aucun SQL

## Sommaire
<!-- TOC depthFrom:1 depthTo:6 withLinks:1 updateOnSave:0 orderedList:0 -->

- [Installation](#installation)
	- [Pré-requis](#pr-requis)
	- [Installer](#installer)
	- [Tester](#tester)
- [A propos de Markdown](#a-propos-de-markdown)
- [Découvrir _le fond_ : Le gestionnaire de contenue](#dcouvrir-le-fond-le-gestionnaire-de-contenue)
	- [Stoker les donnnées](#stoker-les-donnnes)
	- [Chargement des données](#chargement-des-donnes)
		- [Fonctions de lectures des données](#fonctions-de-lectures-des-donnes)
			- [Charger un texte : ``loadText``](#charger-un-texte-loadtext)
				- [Paramètres](#paramtres)
				- [Retour](#retour)
				- [Exemple](#exemple)
			- [Charger une collection de donnée : ``loadCollection``](#charger-une-collection-de-donne-loadcollection)
				- [Paramètres](#paramtres)
				- [Retour](#retour)
				- [Exemple](#exemple)
			- [Charger le blog : ``loadBlog``](#charger-le-blog-loadblog)
				- [Paramètres](#paramtres)
				- [Retour](#retour)
				- [Exemple](#exemple)
		- [Aside](#aside)
- [Découvrir _la forme_ : Le Framework](#dcouvrir-la-forme-le-framework)
	- [Hiérarchie des fichiers](#hirarchie-des-fichiers)
	- [Génération d'une page et mise en cache](#gnration-dune-page-et-mise-en-cache)
- [Administration](#administration)
	- [Login](#login)
- [Divers](#divers)

<!-- /TOC -->


## Installation
### Pré-requis

Serveur PHP-Apache avec :
* _Mod rewite_ activé
* Protection par `htacces` activé (_AllowOverride All_)

### Installer

Cloner le [projet](https://github.com/Nikya/yieldown.git) dans un dossier _web_ du serveur.

Pour des besoins de _génération/suppression_ de fichiers, donner suffisamment de droits au serveur dans les dossiers suivants :
* ``cache``
* ``databackup``

### Tester

Le projet est accompagné d'un site internet de démonstration _"L'histoire du jeans"_.  
Une simple visite de l'URL où est déployé le site permet de le voir pour comprendre la philosophie de _Yieldown_.

## A propos de Markdown

Certains textes et contenues de _Yieldown_ peuvent être rédigés selon la syntaxe somple de *Markdown*.

* Aide rapide sur la syntaxe :  [Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet "Markdown Cheatsheet by Adam Pritchard")
* Explications générales : [Wikipedia](https://fr.wikipedia.org/wiki/Markdown "Aide sur Wikipédia")
* Site officiel de l'auteur : [Daring Fireball](http://daringfireball.net/projects/markdown)

## Découvrir _le fond_ : Le gestionnaire de contenue

### Stoker les donnnées

Le moteur gére 3 type de contenue

* **``text``** : Gérer des textes potentiellement mise en forme en Markdown
* **``collection``** : Un ensemble de données organisés au format Json
* **``blog``** : Gérer un blog et ses articles.

Ces donnée sont à positionner dans le dossier `data` et leurs sous-dossier respectifs.  
La partie administration permet de gérer ce contenue.

### Chargement des données
Coté PHP, ce contenue est à charger dans les controleurs par un appel au moteur de _Yieldown_.

#### Fonctions de lectures des données

##### Charger un texte : ``loadText``
Charge le contenue d'un fichier de _type texte,_.

###### Paramètres
* ``fileId`` : est l'identifiant du fichier (son nom sans extension), recherché uniquement dans le dossier `data/text/`. Extension de fichier implicite ``*.md``.
* ``toFormat`` : Permet de demander ou non d'appliquer le formatage _Markdown_ sur le contenue lue.

###### Retour
Retourne une chaine de caratère contenant le contenue du fichier mise en forme ou non au format HTML.

###### Exemple
Charger et formater le texte de la page d'accueil.

```php
	$homeText = Yieldown::loadText('home', true);
```

##### Charger une collection de donnée : ``loadCollection``
Charge le contenue d'un fichier de type _Json object_.

###### Paramètres
* ``fileId`` : est l'identifiant du fichier (son nom sans extension), recherché uniquement dans le dossier `data/collection/`. Extension de fichier implicite ``*.json``.
* ``keyToFormatList`` : Permet de demander ou non d'appliquer le formatage _Markdown_ sur certaines _clés_ de la collection lue.

###### Retour
Retourne un tableau d'objet contenant la collection lue avec certaines clés mise en forme ou non au format HTML.

###### Exemple
Charger la collection _historique_ et formate les clés _event_.

```php
	$history = Yieldown::loadCollection('history',  array('event'));
```

##### Charger le blog : ``loadBlog``
Charge tout le contenue du blog dans son intégralité.

###### Paramètres
* Aucun :
	* Charge tout les fichier _articles_ trouvés uniquement dans le dossier `data/blog/`. Extension de fichier implicite ``*.md``.
	* Applique systématiquement le formatage _Markdown_ sur le corps des l'articles lues.
	* La articles doivent être struturés selon une syntaxe suplémentaire. Voir la syntaxe d'un article dans le modèle auto-documenté : ``data/blog/_template.md``

###### Retour
Contient un tableau de tous les articles.

###### Exemple
```php
	$blog = Yieldown::loadBlog();
```

#### Aside
Le dossier `data/aside` contient des éléments suplémentaires comme des images, documents ou autre utilisés dans les textes ou articles.

## Découvrir _la forme_ : Le Framework

### Hiérarchie des fichiers

- **admin** : Contient le gestionnaire d'administration du site
- **aspect** : Contient l'apparence du site : Images et CSS
- **cache** : Contient les pages en cache du site lors de leurs génération
- **controller** : Contient les contrôleurs : L'intelligence des pages
- **data** : Contient les données : _le Fond_ du site
- **databackup** : Contient des sauvegardes du dossier `data`
- **index.php** : **Unique point d'entré** du site, c'est le _contrôleur principale_ qui assemble le reste du site. (A consulter, ce fichier est auto documenté)
- **view** : Contient les vues : La mise en forme des pages
- **yieldownEngine** : Contient le moteur du framework, aucune modification n'y est nécessaire.

### Génération d'une page et mise en cache

Voir le fichier auto-documenté [index.php](index.php)

Chaque appel de page, dois définir :
- Un contrôleur
- Une vue
- Une sous-vue

Selon la valeur du paramètre `p`, les éléments précédents peuvent changer selon une branche du `switch` du fichier `index.php`,

Le système `mod-rewrite` convertit le nom de la page appelée en paramètre `p`.

**Exemple :**

	monTest.html -> index.php?p=monTest

Ensuite le contrôleur définie est exécuté, puis les variables et données qu'il a générés sont transmises à la _vue_ et la _sous-vue_,

C'est le trio `contrôleur + vue + sous-vue` qui détermine si une page est déjà en cache et dois être réutilisée ou régénérée.

## Administration

La page web est auto-documentée.

L'administration du site est disponibles au travers de l'URL `.../admin`.  
Elle permet d'_ajouter/supprimer_ des fichiers dans le dossier `data`.  
Une fois les modifications effectuées, il suffit de vider le cache avec le bouton `Regénérer`.

### Login
**Cette page est à protéger** par mot de passe, pour cela il faut :

- Modifier le fichier `admin/.htaccess`
	- Décommenter les lignes (supprimer les #)
	- Changer la valeur de `AuthUserFile` par celle du _chemin absolue_ visible en bas de la page d'administration (sans supprimer le `.htpasswd` final)
- Modifier le fichier `admin/.htpasswd`
	- Saisir un utilisateur et un mot de passe selon la syntaxe `user:motDePasse`
		- Où le mot de passe dois être saisie dans sa version cryptée. (Possibilité d’utiliser la fonction PHP [crypt](http://php.net/manual/fr/function.crypt.php))

Par défaut le login est `admin/admin`.

## Divers

* Un CSS special Markdown est diponible pour un formatage prêt à l'emploi en utilisant le fichier `aspect\github-markdown.css` et la classe CSS `markdown-body`
* Les dossiers non public du site sont protégés par un fichier `.htacces = Deny from all`
