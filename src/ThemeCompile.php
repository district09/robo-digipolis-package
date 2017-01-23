<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Base\ParallelExec;
use Symfony\Component\Process\Process;

class ThemeCompile extends ParallelExec {

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
            $this->processes[] = new Process($this->receiveCommand('bundle install --deployment --no-cache'), $this->dir);
        }
        if (file_exists($this->dir . '/package.json')) {
            $this->processes[] = new Process($this->receiveCommand('npm install'), $this->dir);
        }
        if (file_exists($this->dir . '/bower.json')) {
            $this->processes[] = new Process($this->receiveCommand('bower install'), $this->dir);
        }
        if (file_exists($this->dir . '/Gruntfile.js')) {
            $this->processes[] = new Process($this->receiveCommand('grunt ' . $this->command, $this->dir));
        }
        if (file_exists($this->dir . '/gulpfile.js')) {
            $this->processes[] = new Process($this->receiveCommand('gulp ' . $this->command, $this->dir));
        }
        return parent::run();
    }
}
