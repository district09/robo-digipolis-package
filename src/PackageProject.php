<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Archive\Pack;

class PackageProject extends Pack
{

    /**
     * The directory to package. Defaults to digipolis.root.project, or to the
     * current working directory if that's not set.
     *
     * @var string
     */
    protected $dir;


    /**
     * Create a new PackageProject task.
     *
     * @param string $archiveFile
     *   The full path and name of the archive file to create.
     * @param string $dir
     *   The directory to package. Defaults to digipolis.root.project, or to the
     *   current working directory if that's not set.
     */
    public function __construct($archiveFile, $dir = null)
    {
        parent::__construct($archiveFile);
        $this->dir = is_null($dir)
            ? $dir
            : realpath($dir);
    }


    /**
     * Get the files and directories to package.
     *
     * @return array
     *   The list of files and directories to package.
     */
    protected function getFiles()
    {
        $dir = $this->dir;
        if (is_null($dir)) {
            $projectRoot = $this->getConfig()->get('digipolis.root.project', null);
            $dir = is_null($projectRoot)
                ? getcwd()
                : $projectRoot;
        }
        return [$dir];
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->add($this->getFiles());
        return parent::run();
    }
}
