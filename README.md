# Symfony + local development
Provide local development tools for Symfony project

## Included tools
* GrumPHP
* PHP-QA (phpmd, phpcs, phpstan, phpunit, ...)
* Docker-compose (database, cache, mail, queue)
* Symfony CLI
* Makefile

## Requirements
PHP needs to be a minimum version of PHP 7.2.  
Symfony Framework needs to be a minimum version of Symfony Framework 4.0 or 5.0.

## Installation
To install `symfony-local`, [install Composer](https://getcomposer.org/download/), execute the following command:
```
composer require --dev damienlagae/symfony-local
```
and create (or update) configuration files:
```
./vendor/bin/symfony-local install
```

## Configuration
You can, and perhaps you should, check and customize all configured tasks in `grumphp.yml` file in project root folder.
You can find more GrumPHP configuration [here](https://github.com/phpro/grumphp/blob/master/doc/commands.md#installation).

## Uninstall
If you want to uninstall this library remove configuration files first:
```
./vendor/bin/symfony-local uninstall
```
then remove package:
```
composer remove damienlagae/symfony-local
```

## Help and docs

We use GitHub issues only to discuss bugs and new features.

## Contributing

Thank you for considering contributing to this project! Please review and abide the [contribution guide](docs/CONTRIBUTING.md).

## Code of Conduct

In order to ensure that this community is welcoming to all, please review and abide by the [Code of Conduct](docs/CODE_OF_CONDUCT.md).

## License

This project is open-sourced software licensed under the [AGPL-3.0 License](LICENSE).