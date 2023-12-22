.PHONY: controller entity migration migrate load_fixtures build dev stop restart component fixture

PHP = php bin/console

# This target creates a PHP Controller in /src/Controller
# Use `make controller ctrl=NAME` to create a controller named "NAME"
# Use `make controller` to use the CLI of PHP for this action
ctrl :=
controller: bin/console
	${PHP} make:controller $(ctrl)

# This target creates an entity
entity: bin/console
	${PHP} make:entity

# Generate a PHP script to migrate the entities to an SQL database.
migration: bin/console
	${PHP} make:migration

# This target migrates the entities to the database
# Use `make migration` first.
migrate: bin/console
	${PHP} doctrine:migrations:migrate

# This target loads the fixtures
load_fixtures: bin/console
	${PHP} doctrine:fixtures:load

build:
	${PHP} cache:clear --env=prod --no-debug
	${PHP} sass:build -v
	${PHP} typescript:build -v
	${PHP} asset-map:compile

dev:
	${PHP} sass:build -q --watch &
	${PHP} typescript:build -q --watch &
	symfony server:start -d

preview:
	@php -S localhost:9090 -t public

stop:
	symfony server:stop

restart:
	@$(MAKE) stop
	@$(MAKE) dev

component:
	${PHP} make:twig-component

# Use `make fixture fixt=HELLO` to create a fixture named "HELLO"
fixt :=
fixture:
	${PHP} make:fixtures $(fixt)