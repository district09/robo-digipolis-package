<?php

namespace DigipolisGent\Robo\Task\Package\Traits;

trait ThemeCleanTrait
{
    /**
     * Creates a ThemeClean task.
     *
     * @param string $dir
     *   The directory of the theme to clean, defaults to the current directory.
     *
     * @return \DigipolisGent\Robo\Task\Package\ThemeClean
     *   The theme clean task.
     */
    protected function taskThemeClean($dir = null)
    {
        return $this->task(ThemeClean::class, $dir);
    }
}
