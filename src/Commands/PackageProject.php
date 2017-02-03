<?php

namespace DigipolisGent\Robo\Task\Package\Commands;

trait PackageProject
{

    use \DigipolisGent\Robo\Task\Package\Traits\PackageProjectTrait;

    public function digipolisPackageProject($archiveFile, $dir = null, $opts = ['ignore|i' => ''])
    {
        $this->taskPackageProject($archiveFile, $dir)
            ->ignoreFileNames(array_map('trim', explode(',', $opts['ignore'])))
            ->run();
    }
}
