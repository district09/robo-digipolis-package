<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Base\ParallelExec;
use Symfony\Component\Process\Process;

class ThemeCompile extends ParallelExec
{

    use Utility\NpmFindExecutable;

    /**
     * The directory of the theme to compile.
     *
     * @var string
     */
    protected $dir;


    /**
     * The command to execute. Defaults to 'compile'.
     *
     * @var string
     */
    protected $command;

    /**
     * Creates a new ThemeCompile task.
     *
     * @param string $dir
     *   The directory of the theme to compile. Defaults to the current
     *   directory.
     * @param string $command
     *   The grunt/gulp command to execute. Defaults to 'compile'.
     */
    public function __construct($dir = null, $command = 'compile')
    {
        $this->dir = is_null($dir) ? getcwd() : realpath($dir);
        $this->command = $command;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Print the output of the compile commands.
        $this->printed();
        if (file_exists($this->dir . '/Gemfile')) {
            $bundle = $this->findExecutable('bundle');
            $this->processes[] = Process::fromShellCommandline(
                $this->receiveCommand($bundle . ' install --deployment --no-cache'),
                $this->dir,
                null,
                null,
                null
            );
        }

        $nvmPrefix = file_exists($this->dir . '/.nvmrc')
            ? '. ~/.nvm/nvm.sh && nvm exec '
            : '';
        if (file_exists($this->dir . '/package.json')) {
            $executable = $nvmPrefix . $this->findExecutable(file_exists($this->dir . '/yarn.lock') ? 'yarn' : 'npm');
            $this->processes[] = Process::fromShellCommandline(
                $this->receiveCommand($executable . ' install'),
                $this->dir,
                null,
                null,
                null
            );
        }

        // Grunt/gulp and bower must wait for the previous processes to finish.
        $result =  parent::run();
        if ($result->getExitCode() !== 0) {
            return $result;
        }
        $this->processes = [];
        if (file_exists($this->dir . '/bower.json')) {
            $bower = $this->findExecutable('bower');
            if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                $bower = $this->findExecutable('node') . ' '
                    . (strpos($bower, 'call ') === 0 ? substr($bower, 5) : $bower);
            }
            $this->processes[] = Process::fromShellCommandline(
                $this->receiveCommand($nvmPrefix . $bower . ' install'),
                $this->dir,
                null,
                null,
                null
            );
        }
        if (file_exists($this->dir . '/Gruntfile.js')) {
            $grunt = $this->findExecutable('grunt');
            $this->processes[] = Process::fromShellCommandline(
                $this->receiveCommand($nvmPrefix . 'grunt ' . $this->command),
                $this->dir,
                null,
                null,
                null
            );
        }
        if (file_exists($this->dir . '/gulpfile.js')) {
            $this->processes[] = Process::fromShellCommandline(
                $this->receiveCommand($nvmPrefix .  'gulp ' . $this->command),
                $this->dir,
                null,
                null,
                null
            );
        }
        return parent::run();
    }
}
