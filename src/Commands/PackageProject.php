<?php

namespace DigipolisGent\Robo\Task\Package\Commands;

trait PackageProject
{

    use \DigipolisGent\Robo\Task\Package\Traits\PackageProjectTrait;

    public function digipolisPackageProject($archiveFile, $dir = null, $opts = ['ignore|i' => '', 'in-place' => false])
    {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
        }
        $this->taskPackageProject($archiveFile, $dir)
            ->ignoreFileNames(array_map('trim', explode(',', $opts['ignore'])))
            ->useTmpDir(!$opts['in-place'])
            ->run();
    }
}
