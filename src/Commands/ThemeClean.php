<?php

namespace DigipolisGent\Robo\Task\Package\Commands;

trait ThemeClean
{
    use \DigipolisGent\Robo\Task\Package\Traits\ThemeCleanTrait;

    public function digipolisThemeClean($dir = null)
    {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
        }
        $this->taskThemeClean($dir)->run();
    }
}
