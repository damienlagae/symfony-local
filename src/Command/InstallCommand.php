<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Command;

use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Damien Lagae <damienlagae@gmail.com>
 */
final class InstallCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'install';

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Create configuration files of SymfonyLocal')
            ->setHelp('The <info>install</info> command create preconfigured files to root folder of the project.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $questionHelper = $this->getHelper('question');

        $filesystem = new Filesystem();
        $gitignore = [];

        $choiceVersion = new ChoiceQuestion(
            sprintf('Which php version are you using ? [Default: %s]', self::PHP_DEFAULT),
            self::PHP_VERSIONS,
            self::PHP_DEFAULT
        );
        $version = $questionHelper->ask($input, $output, $choiceVersion);

        // Copy files
        foreach (self::FILES as $file) {
            $source = self::normalizeSourceFile($file, $version);
            $destination = self::normalizeDestinationFile($file);

            if ($filesystem->exists($source)) {
                if ($filesystem->exists($destination)) {
                    $questionOverride = new ConfirmationQuestion(
                        sprintf('File [%s] exists. Do you want override file? (yes/no) [yes]: ', $file)
                    );

                    // Make a backup if file exist & can be override
                    if ($questionHelper->ask($input, $output, $questionOverride)) {
                        $filesystem->copy($source, $destination);
                        $io->success(sprintf('File [%s] has been created.', $file));
                    } else {
                        $io->warning(sprintf('File [%s] has not been created.', $file));
                    }
                } else {
                    $filesystem->copy($source, $destination);
                    $io->success(sprintf('File [%s] has been created.', $file));
                }
            } else {
                $io->error('Source files not found !');
                return 500;
            }
        }

        // Create directories
        foreach (self::DIRECTORIES as $directory) {
            $gitkeep = $directory.DIRECTORY_SEPARATOR.'.gitkeep';
            $filesystem->mkdir(self::normalizeDestinationFile($directory));
            $filesystem->touch(self::normalizeDestinationFile($gitkeep));
            $io->success(sprintf("Directory [%s] and file [%s] has been created.", $directory, $gitkeep));
            $gitignore[] = $directory;
            $gitignore[] = '!'.$gitkeep;
        }


        // Show gitignore insert
        $io->section('Add the follow line to your .gitignore file:');
        $io->text('###> damienlagae/symfony-local ###');
        foreach ($gitignore as $ignore) {
            $io->text(sprintf('%s', $ignore));
        }
        $io->text('###< damienlagae/symfony-local ###');

        return 0;
    }
}
