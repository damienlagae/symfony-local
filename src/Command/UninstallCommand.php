<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Damien Lagae <damienlagae@gmail.com>
 */
class UninstallCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'uninstall';

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription(
                'Remove configuration files of SymfonyLocal'
            )
            ->setHelp(
                'The <info>uninstall</info> command removes preconfigured files from root folder of the project.'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you want to remove configuration files? (yes/no) [yes]: ');
        if ($helper->ask($input, $io, $question)) {
            $filesystem = new Filesystem();
            foreach (self::FILES as $file) {
                $destinationFile = self::normalizeDestinationFile($file);
                $filesystem->remove($destinationFile);
                $io->success(sprintf('File [%s] have been deleted.', $file));
            }
        }

        return 0;
    }
}
