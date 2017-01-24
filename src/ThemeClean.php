<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Base\ParallelExec;
use Symfony\Component\Process\Process;

class ThemeClean extends ParallelExec {
    use Utility\FindExecutable;

    /**
     * The directory of the theme to clean.
     *
     * @var string
     */
    protected $dir;


    /**
     * Creates a new ThemeClean task.
     *
     * @param string $dir
     *   The directory of the theme to clean, defaults to the current directory.
     */
    public function __construct($dir = null)
    {
        $this->dir = is_null($dir) ? getcwd() : realpath($dir);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (file_exists($this->dir . '/Gemfile')) {
            $this->processes[] = new Process($this->receiveCommand('rm -rf vendor/bundle'), $this->dir, null, null, null);
            $this->processes[] = new Process($this->receiveCommand('rm -rf .bundle'), $this->dir, null, null, null);
        }

        if (file_exists($this->dir . '/package.json')) {
            $this->processes[] = new Process($this->receiveCommand('rm -rf node_modules'), $this->dir, null, null, null);
        }

        if (file_exists($this->dir . '/bower.json')) {
            $bower = $this->findExecutable('bower');
            $this->processes[] = new Process($this->receiveCommand($bower . ' cache clean'), $this->dir, null, null, null);
        }

        if (file_exists($this->dir . '/Gruntfile.js') || file_exists($this->dir . '/gulpfile.js')) {
            $this->processes[] = new Process($this->receiveCommand('rm -rf .sass-cache'), $this->dir, null, null, null);
        }

        return parent::run();
    }
}
