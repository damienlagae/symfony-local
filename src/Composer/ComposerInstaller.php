<?php

declare(strict_types=1);

namespace DLG\SymfonyLocal\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * @author Damien Lagae <damienlagae@gmail.com>
 */
class ComposerInstaller
{
    /**
     * This method makes sure that SymfonyLocal registers itself during installation.
     */
    public static function postInstall(Event $event): void
    {
        $filesystem = new Filesystem();

        $composerBinDir = $event->getComposer()->getConfig()->get('bin-dir');
        $executable = dirname(__DIR__, 2).self::ensureValidSlashes('/bin/symfony-local');
        $composerExecutable = $composerBinDir.'/symfony-local';
        $filesystem->copy(
            self::ensureValidSlashes($executable),
            self::ensureValidSlashes($composerExecutable)
        );

        $commandline = [$composerExecutable, 'install'];

        $process = new Process($commandline);
        $process->run();
        if (!$process->isSuccessful()) {
            $event->getIO()->write(
                '<fg=red>SymfonyLocal can not self-install. Did you specify the correct git-dir?</fg=red>'
            );
            $event->getIO()->write('<fg=red>'.$process->getErrorOutput().'</fg=red>');

            return;
        }

        $event->getIO()->write(sprintf('<fg=yellow>%s</fg=yellow>', $process->getOutput()));
    }

    private static function ensureValidSlashes(string $path): string
    {
        // Unix systems know best ...
        if (DIRECTORY_SEPARATOR === '/') {
            return $path;
        }

        // Convert / slash to \ on windows:
        $path = str_replace('/', '\\', $path);

        return $path;
    }
}
