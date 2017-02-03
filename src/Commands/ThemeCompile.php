<?php

namespace DigipolisGent\Robo\Task\Package\Commands;

trait ThemeCompile
{
    use \DigipolisGent\Robo\Task\Package\Traits\ThemeCompileTrait;

    public function digipolisThemeCompile($dir = null, $command = 'compile')
    {
        $this->taskThemeCompile($dir, $command)->run();
    }
}
