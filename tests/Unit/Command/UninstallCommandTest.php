<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Tests\Unit\Command;

use DLG\SymfonyLocal\Command\UninstallCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UninstallCommandTest extends TestCase
{
    public function testName()
    {
        $command = new UninstallCommand();

        $this->assertSame('uninstall', $command->getName());
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new UninstallCommand());

        $command = $application->find('uninstall');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['yes']);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}