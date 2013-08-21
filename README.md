PILULE
=======

Pilule est un système de gestion des études pour les étudiants de l'Université Laval, conçu pour être simple et ergonomique. Vous pouvez l'utiliser pour consulter votre cheminement scolaire, votre relevé de notes, votre horaire de cours, votre boîte Exchange et vos frais de scolarité.

Configuration du serveur
----------

- Apache 2
- PHP 5.3+
    - Librairies nécessaires : [Tidy](http://php.net/manual/fr/book.tidy.php), [Iconv](http://php.net/manual/fr/book.iconv.php), [Curl](http://php.net/manual/fr/book.curl.php)
- MySQL 5.5+

Installation
----------------

1. Copier les fichiers dans le répertoire racine de votre site (**/htdocs** ou équivalent)
1. Créer une base de données et y importer le fichier SQL contenu dans le répertoire **/sql**.
1. Modifier le fichier **/app/Config/core.php** pour basculer en mode développement :
   - Ligne 35 : **Configure::write('debug', 2);**
1. Modifier le fichier **/app/Config/database.php** :
   - Ligne 62-71, entrer les informations de connexion à votre base de données

Liens utiles
----------------

[CakePHP](http://book.cakephp.org/2.0/fr/contributing/documentation.html) - Documentation du frameworkPHP

[DOM Parser](http://sourceforge.net/projects/simplehtmldom/)

Notes
--------

Toute collaboration externe est appréciée, que ce soit pour la recherche et la correction de bugs, l'ajout de nouvelles fonctionnalités ou l'amélioration de fonctionnalités existantes. Les noms des éventuels collaborateurs seront affichés sur le site.

Pour tout demande d'ajout/modification au code source, utiliser la fonction **Pull Request** de GitHub.

Mentions légales
------------------------

Le code source de Pilule est offert à titre gracieux pour permettre l'analyse du code et les collaborations externes. La base de données fournie ne contient aucune donnée personnelle. L'utilisation du code source dans sa forme actuelle ne permet l'accès qu'aux données des utilisateurs Capsule dûment identifiés via l'interface.

L'utilisateur qui télécharge et utilise le code source est le seul responsable de son utilisation personnelle du code source et des conséquences qui pourraient en découler. Le service Pilule, ses créateurs et l'Université Laval se dégagent de toute responsabilité concernant l'utilisation et la modification de ce code source par un tiers.

Le stockage permanent des données nominales provenant de Capsule (ex : dossier scolaire de l'étudiant) sur des serveurs externes situés hors du campus de l'Université Laval est restreint aux données exclusives de l'utilisateur et uniquement à des fins de test local et de développement. Autrement dit, il n'est pas permis d'opérer un service concurrent stockant une copie des données des étudiants de façon permanente (ex : mise en cache de l'horaire).

![Cake Power](https://raw.github.com/cakephp/cakephp/master/lib/Cake/Console/Templates/skel/webroot/img/cake.power.gif)