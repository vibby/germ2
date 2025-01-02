DOCKER_COMPOSE  = docker compose

EXEC_PHP        = $(DOCKER_COMPOSE) exec php
SYMFONY         = $(EXEC_PHP) bin/console

PHPQA           = docker run --init -it --rm -v "./api:/project" -v "./api/tmp-phpqa:/tmp" -w /project jakzal/phpqa:alpine

# docker

start:
	$(DOCKER_COMPOSE) up -d

stop:
	$(DOCKER_COMPOSE) down

## PHP

php-sh:
	${EXEC_PHP} sh

php-cc:
	${SYMFONY} cache:clear

php-keypair:
	${EXEC_PHP} sh -c '\
		set -e && apt-get install openssl && \
		php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction && \
		setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt && \
		setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt\
	'

## PHPQA

phpqa-phpstan:
	$(EXEC_PHP) vendor/bin/phpstan analyse src --level 10

phpqa-php-cs-fixer:
	$(PHPQA) php-cs-fixer fix src --rules=@Symfony,@PSR12 --allow-risky=yes

phpqa-phpmd:
	$(PHPQA) phpmd src text phpmd.xml

phpqa-phpcpd:
	$(PHPQA) phpcpd src

php-phpunit:
	${EXEC_PHP} sh -c 'vendor/bin/phpunit'

phpqa: phpqa-php-cs-fixer phpqa-phpcpd phpqa-phpstan


## databare

db-create:
	$(SYMFONY) doctrine:database:drop --force
	$(SYMFONY) doctrine:database:create

db-migration: ## Create a new migration
	$(SYMFONY) doctrine:migration:diff

db-migrate: ## head to latest migration
	$(SYMFONY) doctrine:migration:migrate --no-interaction

db-revert: ## revert last migration
	$(SYMFONY) doctrine:migration:migrate prev --no-interaction

db-validate: ## validate entity and db status
	$(SYMFONY) doctrine:schema:validate

db-fixtures: ## load fixtures
	$(SYMFONY) doctrine:fixtures:load --no-interaction

db-reset: db-create db-migrate db-fixtures ## reset database
