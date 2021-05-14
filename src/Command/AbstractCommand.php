<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Command;

use Symfony\Component\Console\Command\Command;

/**
 * @author Damien Lagae <damienlagae@gmail.com>
 */
class AbstractCommand extends Command
{
    /**
     * @const array FILES
     */
    protected const FILES = [
        '.github/workflows/symfony.yml',
        '.php-version',
        'codesize.xml',
        'docker-compose.yml',
        'grumphp.yml',
        'Makefile',
        'php.ini',
        'phpunit.xml.dist',
        'yarn.sh'
    ];

    /**
     * @const array DIRECTORIES
     */
    protected const DIRECTORIES = [
        '.docker/database/data',
        '.docker/cache/data',
        '.docker/queue/data'
    ];

    /**
     * @const array PHP_VERSIONS
     */
    protected const PHP_VERSIONS = [
        '7.3'
    ];

    /**
     * @const string PHP_DEFAULT
     */
    public const PHP_DEFAULT = '7.3';

    /**
     * @param string $file
     * @param string $version
     *
     * @return string
     */
    protected static function normalizeSourceFile(string $file, string $version): string
    {
        return sprintf(str_replace('/', DIRECTORY_SEPARATOR, '%s/../Files/%s/%s'), __DIR__, $version, $file);
    }

    /**
     * @param string $file
     *
     * @return string
     */
    protected static function normalizeDestinationFile(string $file): string
    {
        return sprintf(str_replace('/', DIRECTORY_SEPARATOR, '%s/%s'), getcwd(), $file);
    }
}
