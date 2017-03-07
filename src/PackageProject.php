<?php

namespace DigipolisGent\Robo\Task\Package;

use Robo\Result;
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
     * Whether or not to use a temporary directory.
     *
     * @var bool
     */
    protected $useTmpDir = false;


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
     * Whether or not to use a temporary directory. Files that should not be
     * packaged will be deleted when creating the package. When we use a
     * temporary directory, we copy all the files to that directory and remove
     * the files that should not be packaged from that temporary directory, so
     * that your original directory stays the same. If not the files will be
     * deleted from the original directory.
     *
     * @param bool $use
     *   Whether or not to use a temporary directory.
     *
     * @return $this
     */
    public function useTmpDir($use = null)
    {
        $this->useTmpDir = is_null($use)
            ? true
            : $use;
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
        $this->printTaskInfo('Retreiving files to package.');
        $mirrorFinder = new Finder();
        $mirrorFinder->ignoreDotFiles(false);
        $add = [];
        $mirrorFinder
            ->in($this->tmpDir)
            ->depth(0);
        foreach ($mirrorFinder as $file) {
            $add[substr($file->getRealPath(), strlen(realpath($this->tmpDir)) + 1)] = $file->getRealPath();
        }
        return $add;
    }

    /**
     * Mirror the directory to a temp directory.
     */
    protected function mirrorDir()
    {
        if (!$this->useTmpDir) {
            $this->tmpDir = $this->dir;
            return;
        }
        $this->tmpDir = md5(time());
        if (file_exists($this->tmpDir)) {
            $this->fs->remove($this->tmpDir);
        }
        $this->printTaskInfo(sprintf(
            'Creating temporary directory %s.',
            $this->tmpDir
        ));
        $this->fs->mkdir($this->tmpDir);
        $tmpRealPath = realpath($this->tmpDir);

        $directoryIterator = new \RecursiveDirectoryIterator($this->dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $recursiveIterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);
        $filterIterator = new \CallbackFilterIterator(
            $recursiveIterator,
            function ($current) use ($tmpRealPath)
            {
                return strpos($current->getRealPath(), $tmpRealPath) !== 0;
            }
        );
        $this->printTaskInfo(sprintf(
            'Mirroring directory %s to temporary directory %s.',
            $this->dir,
            $tmpRealPath
        ));
        foreach ($filterIterator as $item) {
            if (strpos($item->getRealPath(), $tmpRealPath) === 0) {
              continue;
            }
            if (is_link($item)) {
                if ($item->getRealPath() !== false) {
                    $this->fs->symlink($item->getLinkTarget(), $this->tmpDir . DIRECTORY_SEPARATOR . $filterIterator->getSubPathName());
                }
                continue;
            }
            if ($item->isDir()) {
                $this->fs->mkdir($this->tmpDir . DIRECTORY_SEPARATOR . $filterIterator->getSubPathName());
                continue;
            }
            $this->fs->copy($item, $this->tmpDir . DIRECTORY_SEPARATOR . $filterIterator->getSubPathName());
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
        $this->printTaskInfo(sprintf('Cleaning directory %s.', $this->tmpDir));
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
        if ($this->useTmpDir) {
            $this->printTaskInfo(
                sprintf('Removing temporary directory %s.', $this->tmpDir)
            );
            $this->fs->remove($this->tmpDir);
        }
        return $result;
    }
}
