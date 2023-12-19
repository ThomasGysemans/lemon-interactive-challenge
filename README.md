# Lemon Interactive Challenge

Ce repo contient le code du défi posé par [Lemon Interactive](https://www.lemon-interactive.fr/) dans le cadre d'un recrutement en tant que stagiaire (pour une durée de 3 mois). Il s'agit d'un site web, réalisé avec [Symfony](https://symfony.com/).

![Image de la première page du site](/screenshots/maquette-homepage.jpg)

## Auteur

[Thomas GYSEMANS](https://portfolio.sciencesky.fr/), actuellement en étude d'informatique au BUT de Villeneuve d'Ascq.

Le projet a été débuté le 10 décembre 2023.

## Stack technique

- [SCSS](https://sass-lang.com/) via [SassBundle](https://symfony.com/bundles/SassBundle/current/index.html)
- [TypeScript](https://www.typescriptlang.org/) via [AssetMapperTypeScriptBundle](https://github.com/sensiolabs/AssetMapperTypeScriptBundle)
- [PHP](https://www.php.net/) 8.2
- Symfony 6.4 (Latest LTS vesion)
- [AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html) of Symfony

Ce projet se base sur mon template: [symfony-web-essentials](https://github.com/ThomasGysemans/symfony-web-essentials), créé pour l'occasion, car initialiser un projet web avec Symfony, contenant les meilleurs outils du développeur front-end (SCSS et TypeScript, à mon opinion) requièrent une quantité non-négligeable de configurations (beaucoup de commandes `composer` notamment).

## Base de données et entités

Le back-end n'est pas la partie importante de ce challenge, mais j'avais envie de livrer un site complet, donc j'ai utilisé une base de données SQLite ([plus de détails](https://github.com/ThomasGysemans/symfony-web-essentials?tab=readme-ov-file#database)).

Les entités sont les suivantes:

- `Event` (un événement publié sur la plateforme)

|Colonne|Type|
|-------|----|
|title|varchar(255)|
|description|text|
|location|varchar(255)|
|beginDate|DateTime|
|endDate|DateTime|
|subscribers|ManyToMany(inversedBy: 'participations')|
|author|ManyToOne(inversedBy: 'events')|

> **NOTE**: L'event a des "abonnés" ("subscribers") c'est-à-dire des utilisateurs qui s'y sont inscrits.

- `User`

|Colonne|Type|
|-------|----|
|email|varchar(180), unique|
|username|varchar(255), unique|
|password|varchar|
|isVerified|boolean|
|canCreateEvents|boolean|
|participations|ManyToMany(inversedBy: 'subscribers')|
|events|OneToMany(mappedBy: 'author', orphanRemoval: true)|

> **NOTE**: puisque le sujet n'était pas très clair selon moi, j'ai fait en sorte qu'un seul utilisateur peut ajouter/modifier/supprimer des événements.

## Fixtures

Deux fixtures sont disponibles pour auto-générer quelques événements utilisateurs. Pour ces fixtures, j'ai utilisé [FakerPHP](https://fakerphp.github.io/).

Pour charger les fixtures:

```bash
make load_fictures
```

## Makefile

Pour simplifier les commandes, j'ai créé un fichier [Makefile](./Makefile). Puisque le projet requière de _watch_ les fichiers SCSS et TypeScript et de les compiler au moindre changement, il faut utiliser ces commandes-ci pour lancer le serveur:

```bash
php bin/console sass:build -q --watch &
php bin/console sass:typescript -q --watch &
symfony server:start -d
```

Ces commandes sont simplifiées:

```bash
make dev
# ou
make restart
```

De nombreuses autres commandes sont disponibles dans le [Makefile](./Makefile), notamment pour migrer, créer des composants Twig, etc.

## Obstacles rencontrés

Premièrement, concernant le sujet, j'ai eu du mal à comprendre ce qu'est une "organisation régionale". Puisqu'il s'agit d'un défi destiné à prouver les compétences du candidat, je ne me suis pas attardé là-dessus et j'ai imaginé une association qui organise des concerts caritatifs dans les Hauts-de-France.

Dans ce thème-là, j'ai conçu un design à l'esprit modern sur Figma: https://www.figma.com/file/teHyRBIE9yh1OeU6IhFA9g/ProjetLemonInteractive?type=design&node-id=0%3A1&mode=design&t=osOP9szGN4sCx0ll-1

Ensuite, je ne connaissais Symfony que de nom, et par conséquent apprendre Symfony était le premier obstacle à surmonter. Heureusement, j'avais déjà fait du PHP (v7) il y a quelques années, et j'ai de l'expérience avec des frameworks similaires, donc je me suis rapidement adapté.

Le plus gros problème que j'ai pu avoir avec Symfony c'est l'utilisation de [Webpack Encore](https://symfony.com/doc/current/frontend/encore/installation.html) comme vous pouvez le voir ici : https://stackoverflow.com/questions/77632962/i-cannot-create-a-second-webpack-project-on-my-computer-because-it-only-runs-the

Le second problème que j'ai eu avec Symfony c'est pour utiliser plusieurs fichiers SCSS: https://stackoverflow.com/questions/77634279/how-can-i-use-multiple-scss-files-in-symfony

Actuellement, tous les fichiers sont importés dans [app.scss](/assets/styles/app.scss) via la règle `@import`, ce qui n'est pas idéal (haut risque de conflit de nommage des classes).

## Bootstrap

J'ai la ferme conviction que l'utilisation de Bootstrap nuie à l'originalité d'un site web et joue plutôt le rôle de contrainte dans le processus de création d'un site au thème moderne. Les 11 000 lignes de code que Bootstrap utilise augmentent le poids de l'application, et elles ne sont jamais toutes utilisées.

Je dois avouer que pour créer ce site, je n'ai pas été en capacité d'utiliser Bootstrap. J'ai réussi à concevoir le site dans de bons délais malgré les cours et mes obligations personnelles, et je sais bien qu'utiliser Bootstrap m'aurait freiner plus qu'autre chose. J'adore le (S)CSS et j'adore tout faire de 0.