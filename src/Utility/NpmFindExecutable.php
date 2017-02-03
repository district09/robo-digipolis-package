<?php

namespace DigipolisGent\Robo\Task\Package\Utility;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\ExecutableFinder;

trait NpmFindExecutable
{

    /**
     * Return the best path to the executable program with the provided name.
     * Favor vendor/bin in the current project. If not found there, use whatever
     * is on the $PATH.
     *
     * @param string $cmd
     *   The executable to find.
     *
     * @return bool|string
     */
    protected function findExecutable($cmd)
    {
        $pathToCmd = $this->searchForExecutable($cmd);
        if ($pathToCmd) {
            return $this->useCallOnWindows($pathToCmd);
        }
        return false;
    }

    /**
     * @param string $cmd
     *
     * @return string
     */
    private function searchForExecutable($cmd)
    {
        $nodeModules = $this->findNodeModules();
        if ($nodeModules) {
            $finder = new Finder();
            $finder->files()->in($nodeModules)->name($cmd);
            foreach ($finder as $executable) {
                return $executable->getRealPath();
            }
        }
        $execFinder = new ExecutableFinder();
        return $execFinder->find($cmd, null, []);
    }

    /**
     * @return array
     */
    protected function findNodeModules()
    {
        if (isset($this->dir)) {
            $candidates[] =  $this->dir . '/node_modules';
        }
        $candidates[] = __DIR__ . '/node_modules';
        $dirs = [];
        foreach ($candidates as $dir) {
            if (is_dir("$dir")) {
                $dirs[] = realpath($dir);
            }
        }
        return $dirs;
    }

    /**
     * Wrap Windows executables in 'call' per 7a88757d
     *
     * @param string $cmd
     *
     * @return string
     */
    protected function useCallOnWindows($cmd)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            if (file_exists("{$cmd}.bat")) {
                $cmd = "{$cmd}.bat";
            }
            return "call $cmd";
        }
        return $cmd;
    }
}
