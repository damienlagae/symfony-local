<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Tests\Functional;

use DLG\SymfonyLocal\Command\InstallCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InstallTest extends TestCase
{
    /**
     * @var Application
     */
    private $application;

    public function setUp(): void
    {
        $this->application = new Application();
        $this->application->add(new InstallCommand());
    }

    public function testCreateFiles()
    {
        $command = $this->application->find('install');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            ' [OK] File [.github/workflows/symfony.yml] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [.php-version] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [codesize.xml] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [docker-compose.yml] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [grumphp.yml] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [Makefile] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [php.ini] has been created. ',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [phpunit.xml.dist] has been created. ',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [yarn.sh] has been created. ',
            $commandTester->getDisplay()
        );
    }

    public function testCreateDirectory()
    {
        $command = $this->application->find('install');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            '[OK] Directory [.docker/database/data] and file [.docker/database/data/.gitkeep] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            '[OK] Directory [.docker/cache/data] and file [.docker/cache/data/.gitkeep] has been created.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            '[OK] Directory [.docker/queue/data] and file [.docker/queue/data/.gitkeep] has been created.',
            $commandTester->getDisplay()
        );
    }

    public function testShowGitignoreSegment()
    {
        $command = $this->application->find('install');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            " ###> damienlagae/symfony-local ###\n".
            " .docker/database/data\n".
            " !.docker/database/data/.gitkeep\n".
            " .docker/cache/data\n".
            " !.docker/cache/data/.gitkeep\n".
            " .docker/queue/data\n".
            " !.docker/queue/data/.gitkeep\n".
            " ###< damienlagae/symfony-local ###\n",
            $commandTester->getDisplay()
        );
    }
}