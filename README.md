# Yieldown

Version 1.0

**Yieldown** est un framework PHP léger de génération de site web basé sur 2 concepts :

* **La forme** : Il utilise la puissance de PHP pour construire la partie dynamique du site et ses fonctionnalités, sans trop imposer de contraintes, avec une implémentation type Vues-Contrôleurs

* **Le fond** : Il permet de mettre à jours facilement le contenue du site grâce à sa partie administration et la rédaction de son contenue en _Markdown_, le tout sans aucun SQL

## Installation
### Pré-requis

Serveur PHP-Apache avec :
* _Mod rewite_ activé
* Protection par `htacces` activé (_AllowOverride All_)

### Installer

Cloner le [projet](https://github.com/Nikya/yieldown.git) dans un dossier _web_ du serveur.

Pour des besoins de _génération/suppression_ de fichiers, donner suffisamment de droits au serveur dans les dossiers suivants :
* cache
* databackup

### Tester

Le projet est accompagné d'un site internet de démonstration _"L'histoire du jeans"_.  
Une simple visite de l'URL où est accessible le site permet de le voir.

## A propos de Markdown

Certains textes et contenues de _Yieldown_ peuvent être rédigés selon la syntaxe *Markdown*.

* Aide rapide sur la syntaxe :  [Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet "Markdown Cheatsheet by Adam Pritchard")
* Explications générales : [Wikipedia](https://fr.wikipedia.org/wiki/Markdown "Aide sur Wikipédia")
* Site officiel de l'auteur : [Daring Fireball](http://daringfireball.net/projects/markdown)

## Découvrir le fond : Le gestionnaire de contenue

### Stoker les donnnées

Le moteur gére 3 type de contenue

* **text** : Gérer des textes potentiellement mise en forme en Markdown
* **Collection** : Un ensemble de données organisés au format Json
* **blog** : Gérer un blog et ses articles. Voir la syntaxe d'un article dans `data/blog/_template.md`

Ces donnée sont à positionner dans le dossier `data`.  
La partie administration permet de gérer ces contenues.

### Chargment des données
Coté PHP, ce contenue est à charger dans le site grâce au moteur de _Yieldown_.

#### Exemples

##### Charger un texte :
Charge le contenue du fichier `data/text/home.md` et applique un formatage Markdown.  
`$content` contient ensuite le texte mise en forme en HTML

```php
	$content = Yieldown::loadText('home', true);
```

##### Charger une collection de donnée
Charge le contenue du fichier `data/collection/history.json` et applique un formatage Markdown sur les objets indexés par le clé `event`  
`$history` contient ensuite un tableau d'objet représentant le contenue de la collection

```php
	$history = Yieldown::loadCollection('history',  array('event'));
```

##### Charger le blog
Charge tous les fichiers d'article de blog disponibles dans `data/blog`  
`$blog` contient ensuite un tableau d'objet représentant un article de blog

```php
	$blog = Yieldown::loadBlog();
```

#### Aside
Le dossier `data/aside` sert à contenir des éléments de contenue supplémentaire comme des images.

## Découvrir la forme : Le Framework

### Hiérarchie des fichiers

- **admin** : Contient le gestionnaire d'administration du site
- **aspect** : Contient l'apparence du site : Images et CSS
- **cache** : Contient les pages en cache du site lors de leurs génération
- **controller** : Contient les contrôleurs : L'intelligence des pages
- **data** : Contient les données le Fond du site
- **databackup** : Contient des sauvegardes du dossier `data`
- **index.php** : Unique point d'entré du site, c'est le contrôleur principale qui assemble le reste du site. (A consulter, ce fichier est auto documenté)
- **view** : Contient les vues : La mise en forme des pages
- **yieldownEngine** : Contient le moteur du framework, aucune modification n'y est nécessaire.

### Assemblage d'une page

Voir le fichier [index.php](index.php)

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
Cette page est à protéger par mot de passe, pour cela il faut :

- Modifier le fichier `admin/.htaccess`
	- Décommenter les lignes (supprimer les #)
	- Changer la valeur de `AuthUserFile` par celle du _chemin absolue_ visible en bas de la page d'administration (sans supprimer le `.htpasswd` final)
- Modifier le fichier `admin/.htpasswd`
	- Saisir un utilisateur et un mot de passe selon la syntaxe `user:motDePasse`
		- Où le mot de passe dois être saisie dans sa version cryptée. (Possibilité d’utiliser la fonction PHP [crypt](http://php.net/manual/fr/function.crypt.php))

Par défaut le login est `admin/admin`.

## Divers

* Un CSS special Markdown est diponible pour un formatage pret à l'emploie en utilisant le fichier `aspect\github-markdown.css` et la classe CSS `markdown-body`
* Les dossiers non public du site sont protégés par un fichier `.htacces = Deny from all`
