<?php


namespace DLG\SymfonyLocal\Tests\Functional;


use DLG\SymfonyLocal\Command\UninstallCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UninstallTest
 *
 * @author Damien Lagae <damienlagae@gmail.com>
 */
class UninstallTest extends TestCase
{
    /**
     * @var Application
     */
    private $application;

    public function setUp(): void
    {
        $this->application = new Application();
        $this->application->add(new UninstallCommand());
    }

    public function testRemoveFiles()
    {
        $command = $this->application->find('uninstall');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            ' [OK] File [.github/workflows/symfony.yml] have been deleted.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [.php-version] have been deleted.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [codesize.xml] have been deleted.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [docker-compose.yml] have been deleted.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [grumphp.yml] have been deleted.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [Makefile] have been deleted.',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [php.ini] have been deleted. ',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [phpunit.xml.dist] have been deleted. ',
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            ' [OK] File [yarn.sh] have been deleted. ',
            $commandTester->getDisplay()
        );
    }
}