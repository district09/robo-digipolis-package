<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Base\ParallelExec;
use Symfony\Component\Process\Process;

class ThemeClean extends ParallelExec {

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
            $this->processes[] = new Process($this->receiveCommand('rm -rf vendor/bundle'), $this->dir);
        }
        if (file_exists($this->dir . '/package.json')) {
            $this->processes[] = new Process($this->receiveCommand('for package in `ls node_modules`; do npm uninstall $package; done; rm -rf node_modules/;'), $this->dir);
        }
        if (file_exists($this->dir . '/bower.json')) {
            $this->processes[] = new Process($this->receiveCommand('bower cache clean'), $this->dir);
        }

        return parent::run();
    }
}
