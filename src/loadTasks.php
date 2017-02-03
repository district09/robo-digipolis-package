<?php

namespace DigipolisGent\Robo\Task\Package;

trait loadTasks
{
    /**
     * Creates a PackageProject task.
     *
     * @param string $archiveFile
     *   The full path and name of the archive file to create.
     * @param string $dir
     *   The directory to package. Defaults to digipolis.root.project, or to the
     *   current working directory if that's not set.
     *
     * @return \DigipolisGent\Robo\Task\Package\PackageProject
     *   The package project task.
     */
    protected function taskPackageProject($archiveFile, $dir = null)
    {
        return $this->task(PackageProject::class, $archiveFile, $dir);
    }

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
