<?php

namespace DigipolisGent\Robo\Task\Package\Commands;

trait ThemeCompile
{
    use \DigipolisGent\Robo\Task\Package\Traits\ThemeCompileTrait;

    public function digipolisThemeCompile($dir = null, $buildCommand = 'compile')
    {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
        }
        $this->taskThemeCompile($dir, $buildCommand)->run();
    }
}
