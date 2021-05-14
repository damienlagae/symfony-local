# Setup ————————————————————————————————————————————————————————————————————————
SHELL         = bash
SYMFONY_BIN   = ./symfony
EXEC_PHP      = $(SYMFONY_BIN) php
SYMFONY       = $(SYMFONY_BIN) console
COMPOSER      = $(EXEC_PHP) composer.phar
DOCKER        = docker-compose
PHPUNIT       = $(EXEC_PHP) bin/phpunit
PHPQA		  = $(DOCKER) run --rm phpqa
YARN          = $(DOCKER) run --rm yarn yarn
.DEFAULT_GOAL = help
#.PHONY       = # Not needed for now

## —— The Symfony Makefile 🍺 ——————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

wait: ## Sleep 5 seconds
	sleep 5

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
./composer.phar: ./symfony
	$(EXEC_PHP) -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	$(EXEC_PHP) composer-setup.php
	$(EXEC_PHP) -r "unlink('composer-setup.php');"

get-composer: ./composer.phar ## Download and install composer in the project (file is ignored)

install: get-composer composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install --no-progress --prefer-dist --optimize-autoloader

update: get-composer composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands
	$(SYMFONY)

cc: cc-redis ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

cc-test: cc-redis ## Clear the cache in test. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c --env=test

warmup: ## Warmup the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	sudo chmod -R 777 var/*

purge: ## Purge cache and logs
	sudo rm -rf var/cache/* var/logs/*

## —— Symfony binary 💻 ————————————————————————————————————————————————————————
./symfony:
	curl -sS https://get.symfony.com/cli/installer | bash
	mv ~/.symfony/bin/symfony .

bin-install: ./symfony## Download and install the binary in the project (file is ignored)

cert-install: ./symfony ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

serve: ./symfony ## Serve the application with HTTPS support
	$(SYMFONY_BIN) serve --daemon

unserve: ./symfony ## Stop the web server
	$(SYMFONY_BIN) server:stop

open: serve ## Open the local project in a browser
	$(SYMFONY_BIN) open:local

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: docker-compose.yml ## Start the docker hub (MySQL,redis,phpmyadmin,mailcatcher)
	$(DOCKER) -f docker-compose.yml up -d

down: docker-compose.yml ## Stop the docker hub
	$(DOCKER) down --remove-orphans

## —— Project 🛠———————————————————————————————————————————————————————————————
run: bin-install up wait install serve ## Start docker and start the web server

reload: load-fixtures ## Reload fixtures

abort: down unserve ## Stop docker and the Symfony binary server

log: ## Show symfony log
	$(SYMFONY_BIN) server:log

cc-redis: ## Flush all Redis cache
	$(SYMFONY) redis:flushall -n

schema: ## Build the db, control the schema validity and check the migration status
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:migrations:migrate -q

load-fixtures: schema ## Build the db, control the schema validity, check the migration status and load fixtures
	$(SYMFONY) doctrine:fixtures:load -n

## —— Tests ✅ —————————————————————————————————————————————————————————————————
db-test: cc-test ## Build the test db, control the schema validity, check the migration status and load fixtures
	$(SYMFONY) doctrine:database:drop --if-exists --force --env=test
	$(SYMFONY) doctrine:database:create --if-not-exists --env=test
	$(SYMFONY) doctrine:migrations:migrate --env=test -q
	$(SYMFONY) dictrine:fixtures:load --env=test -n

test: phpunit.xml.dist ##db-test ## Launch main functional and unit tests
	$(EXEC_PHP) ./bin/phpunit -d memory_limit=-1 --stop-on-failure --testdox

test-unit: phpunit.xml.dist ## Launch only unit tests
	$(PHPUNIT) -d memory_limit=-1 --testsuite Unit --stop-on-failure --testdox

test-functional: phpunit.xml.dist db-test ## Launch only functional tests
	$(PHPUNIT) -d memory_limit=-1 --testsuite Functional --stop-on-failure --testdox

test-wip: phpunit.xml.dist db-test ## Launch tests for current dev
	$(PHPUNIT) -d memory_limit=-1 --group=WIP --stop-on-failure --testdox

## —— Coding standards ✨ ——————————————————————————————————————————————————————
cs: codesniffer mess stan ## Launch check style and static analysis

codesniffer: ## Run php_codesniffer only
	$(PHPQA) phpcs -v --standard=PSR2 --ignore=src/Kernel.php,**/Migrations/* ./src

stan: ## Run PHPStan only
	$(PHPQA) phpstan analyze ./src -l 8

mess: ## Run PHP Mess Detector only
	$(PHPQA) phpmd ./src ansi ./codesize.xml

twig: ## Run twig lint
	$(PHPQA) twig-lint lint ./templates
	$(PHPQA) twigcs ./templates

security: ## Launch dependencies security check
	$(PHPQA) local-php-security-checker

requirements: ./symfony ## Launch symfony requirements check
	$(SYMFONY_BIN) check:requirements

## —— Assets 💄 ——————————————————————————————————————————————————————————
yarn.lock: package.json
	$(YARN) install

node_modules: yarn.lock ## Install yarn packages
	@$(YARN)

yarn-update: yarn.lock ## Upgrade yarn packages
	$(YARN) upgrade

assets: node_modules ## Run Webpack Encore to compile development assets
	@$(YARN) dev

build: node_modules ## Run Webpack Encore to compile production assets
	@$(YARN) build

watch: node_modules ## Recompile assets automatically when files change
	@$(YARN) watch

yarn-audit: node_modules ## Run vulnerability audit against the installed node packages
	$(YARN) audit
