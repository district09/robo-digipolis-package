<?php

namespace DigipolisGent\Robo\Task\Package\Commands;

trait ThemeClean
{
    use \DigipolisGent\Robo\Task\Package\Traits\ThemeCleanTrait;

    public function digipolisThemeClean($dir = null)
    {
        $this->taskThemeClean($dir)->run();
    }
}
