<?php

namespace DigipolisGent\Robo\Task\Package\Traits;

use DigipolisGent\Robo\Task\Package\PackageProject;

trait PackageProjectTrait
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
}
