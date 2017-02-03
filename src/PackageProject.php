<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Archive\Pack;
use Symfony\Component\Finder\Finder;

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
     * File names to ignore.
     *
     * @var array
     */
    protected $ignoreFileNames = [];


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
     * Exclude filenames from the archive.
     *
     * @param array $fileNames
     *   File names to ignore.
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function ignoreFileNames($fileNames)
    {
        $this->ignoreFileNames = $fileNames;

        return $this;
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
        $finder = new Finder();
        $finder->ignoreDotFiles(false);

        // Ignore files defined by the dev.
        foreach ($this->ignoreFileNames as $fileName) {
            $finder->notName($fileName);
        }
        $dirs = [];
        $finderClone = clone $finder;
        $finder->in($dir);
        foreach ($finder as $file) {
            $realPath = $file->getRealPath();
            if (is_dir($realPath)) {
              $subDirFinder = clone $finderClone;
              // This is a directory that contains files that will be added. So
              // don't add the directory or files will be added twice.
              if ($subDirFinder->in($realPath)->files()->count()) {
                continue;
              }
            }

            $relative = substr($realPath, strlen($dir) + 1);
            $dirs[$relative] = $realPath;
        }
        return $dirs;
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
