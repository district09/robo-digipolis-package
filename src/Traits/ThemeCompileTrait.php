<?php

namespace DigipolisGent\Robo\Task\Package\Traits;

trait ThemeCompileTrait
{
    /**
     * Creates a ThemeCompile task.
     *
     * @param string $dir
     *   The directory of the theme to compile. Defaults to the current
     *   directory.
     * @param string $command
     *   The grunt/gulp command to execute. Defaults to 'compile'.
     *
     * @return \DigipolisGent\Robo\Task\Package\ThemeCompile
     *   The theme compile task.
     */
    protected function taskThemeCompile($dir = null, $command = 'compile')
    {
        return $this->task(ThemeCompile::class, $dir, $command);
    }
}
