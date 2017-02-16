<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Task\Archive\Pack;
use Symfony\Component\Filesystem\Filesystem;
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
     * Filesystem component.
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;

    /**
     * The temporary directory that will be used for copying the source
     * directory and deleting files that should not be in the package.
     *
     * @var string
     */
    protected $tmpDir;

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
     * @param \Symfony\Component\Filesystem\Filesystem $fs
     *   Filesystem component to manipulate files.
     */
    public function __construct($archiveFile, $dir = null, FileSystem $fs = null)
    {
        parent::__construct($archiveFile);
        $this->dir = is_null($dir)
            ? $dir
            : realpath($dir);
        $this->fs = is_null($fs)
            ? new Filesystem()
            : $fs;
        $this->tmpDir = md5(time());
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
        $this->mirrorDir();
        $this->cleanMirrorDir();
        $mirrorFinder = new Finder();
        $mirrorFinder->ignoreDotFiles(false);
        $add = [];
        foreach ($mirrorFinder->in($this->tmpDir)->depth(1) as $file) {
            $add[substr($file->getRealPath(), strlen(realpath($this->tmpDir)) + 1)] = $file->getRealPath();
        }
        return $add;
    }

    /**
     * Mirror the directory to a temp directory.
     */
    protected function mirrorDir()
    {
        if (file_exists($this->tmpDir)) {
            $this->fs->remove($this->tmpDir);
        }

        $this->fs->mkdir($this->tmpDir);

        $directoryIterator = new \RecursiveDirectoryIterator($this->dir);
        $iterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $item) {
            if (is_link($file) && file_exists($file)) {
                $this->fs->symlink($file->getLinkTarget(), $target);
                continue;
            }
            if ($item->isDir()) {
                $this->fs->mkdir($this->tmpDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                continue;
            }
            $this->fs->copy($item, $this->tmpDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        }
    }

    /**
     * Removes files that should not be packaged from the mirrored directory.
     *
     * @param string $mirror
     *   Path to the mirrorred directory.
     */
    protected function cleanMirrorDir()
    {
        if (empty($this->ignoreFileNames)) {
            return;
        }
        $files = new Finder();
        $files->in($this->tmpDir);
        $files->ignoreDotFiles(false);
        $files->files();

        // Ignore files defined by the dev.
        foreach ($this->ignoreFileNames as $fileName) {
            $files->name($fileName);
        }
        $this->fs->remove($files);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (is_null($this->dir)) {
            $projectRoot = $this->getConfig()->get('digipolis.root.project', null);
            $this->dir = is_null($projectRoot)
                ? getcwd()
                : $projectRoot;
        }
        $this->add($this->getFiles());
        $result = parent::run();
        $this->fs->remove($this->tmpDir);
        return $result;
    }
}
