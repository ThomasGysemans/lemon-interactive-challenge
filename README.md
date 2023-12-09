# Symfony Web Essentials (for Symfony 6.4)

This repo holds the basic tools for creating a web application using Symfony.

It includes:

- [AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html)
- [SCSS](https://symfony.com/bundles/SassBundle/current/index.html)
- [TypeScript](https://github.com/sensiolabs/AssetMapperTypeScriptBundle)
- [Bootstrap](https://getbootstrap.com/) SCSS

The following commands were used to create this template (in this order):

```bash
composer create-project symfony/skeleton:"6.4.*" symfony-web-essentials
cd symfony-web-essentials
composer require webapp
composer require symfony/asset-mapper symfony/asset symfony/twig-pack
php bin/console importmap:require bootstrap
composer require symfonycasts/sass-bundle
composer require sensiolabs/typescript-bundle
composer require twbs/bootstrap
```

## Entrypoints

This template comes with a unique Controller: [HomeController](/src/Controller/HomeController.php) (with route="/").

The TypeScript entrypoint is [app.ts](/assets/typescript/app.ts).

The SCSS main file is [app.scss](/assets/styles/app.scss).

## Why AssetMapper instead of Webpack?

A trauma: https://stackoverflow.com/questions/77632962/i-cannot-create-a-second-webpack-project-on-my-computer-because-it-only-runs-the

## Database

The database were also modified so as to use `SQLite`:

In `/.env`:

```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
```

The first line was uncommented, and the last line was commented.

## Makefile

A few commands are available in the [Makefile](./Makefile) whose purpose is to shorten commands:

- Run the development server with SASS and TypeScript being watched:

```bash
make dev
```

- Build the app (for production release):

```bash
make build
```

- Stop the server:

```bash
make stop
```

- Restart the server:

```bash
make restart
```

- Create a Controller:

```bash
make controller ctrl=NAME
# ou 
make controller
```

- Create an Entity:

```bash
make entity
```

- Create migrations:

```bash
make migration
```

- Migrate:

```bash
make migrate
```

- Load fixtures:

```bash
make load_fixtures
```