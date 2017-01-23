<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Archive\Pack;

class PackageProject extends Pack {

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
        if (is_null($dir)) {
          $projectRoot = $this->getConfig->get('digipolis.root.project', null);
          $dir = is_null($projectRoot)
            ? getcwd()
            : $projectRoot;
        }
        $this->dir = realpath($dir);
    }


    /**
     * Get the files and directories to package.
     * 
     * @return array
     *   The list of files and directories to package.
     */
    protected function getFiles() {
        return [$this->dir];
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
