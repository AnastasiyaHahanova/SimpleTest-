PHP_EXEC=exec -u test-user php8 php -d memory_limit=1G -dxdebug.mode=off
PHP_EXEC_DEBUG=exec -u test-user php8 php -d memory_limit=1G

CONSOLE=$(PHP_EXEC) bin/console
CONSOLE_DEBUG=$(PHP_EXEC_DEBUG) bin/console

DOCKER_COMPOSE_COMMAND=docker compose

up:
	@$(DOCKER_COMPOSE_COMMAND) up -d --remove-orphans

down:
	@$(DOCKER_COMPOSE_COMMAND) down --remove-orphans

cache-clear:
	@$(DOCKER_COMPOSE_COMMAND) $(CONSOLE) cache:clear

cache-rm:
	@$(DOCKER_COMPOSE_COMMAND) exec -u root php8 rm -rf var/cache

composer-install:
	@$(DOCKER_COMPOSE_COMMAND) run --rm -u test-user php8 composer install --optimize-autoloader --apcu-autoloader

composer-update:
	@$(DOCKER_COMPOSE_COMMAND) run --rm -u test-user php8 composer update --optimize-autoloader --apcu-autoloader

migrations-up:
	@$(DOCKER_COMPOSE_COMMAND) $(CONSOLE) doctrine:migrations:migrate --no-interaction --query-time

upload-csv:
	$(DOCKER_COMPOSE_COMMAND) $(CONSOLE) load:questions:from:csv /app/resource/questions.csv

depends:
	make composer-install
	make migrations-up
	make upload-csv

restart:
	make down
	make up
