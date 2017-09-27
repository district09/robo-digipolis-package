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
        if (file_exists($this->dir . '/Gemfile')) {
            $bundle = $this->findExecutable('bundle');
            $this->processes[] = new Process(
                $this->receiveCommand($bundle . ' install --deployment --no-cache'),
                $this->dir,
                null,
                null,
                null
            );
        }
        if (file_exists($this->dir . '/package.json')) {
            $executable = $this->findExecutable(file_exists($this->dir . '/package.lock') ? 'yarn' : 'npm');
            $this->processes[] = new Process(
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
            $this->processes[] = new Process(
                $this->receiveCommand($bower . ' install'),
                $this->dir,
                null,
                null,
                null
            );
        }
        if (file_exists($this->dir . '/Gruntfile.js')) {
            $grunt = $this->findExecutable('grunt');
            $this->processes[] = new Process(
                $this->receiveCommand($grunt . ' ' . $this->command),
                $this->dir,
                null,
                null,
                null
            );
        }
        if (file_exists($this->dir . '/gulpfile.js')) {
            $gulp = $this->findExecutable('gulp') ? : $this->findExecutable('gulp.js');
            $this->processes[] = new Process(
                $this->receiveCommand($gulp . ' ' . $this->command),
                $this->dir,
                null,
                null,
                null
            );
        }
        return parent::run();
    }
}
