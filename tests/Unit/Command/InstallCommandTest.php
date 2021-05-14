<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Tests\Unit\Command;

use DLG\SymfonyLocal\Command\InstallCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InstallCommandTest extends TestCase
{
    public function testName()
    {
        $command = new InstallCommand();

        $this->assertSame('install', $command->getName());
    }

    /**
     * @dataProvider provideVersionData
     */
    public function testPhpVersion(string $version, int $statusCode)
    {
        $application = new Application();
        $application->add(new InstallCommand());

        $command = $application->find('install');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([$version]);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame($statusCode, $commandTester->getStatusCode());
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new InstallCommand());

        $command = $application->find('install');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([
            InstallCommand::PHP_DEFAULT, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'
        ]);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame(0, $commandTester->getStatusCode());
    }

    public function provideVersionData()
    {
        yield ['7.3', 0];
        yield ['7.4', 0];
        yield ['8.0', 0];
    }
}