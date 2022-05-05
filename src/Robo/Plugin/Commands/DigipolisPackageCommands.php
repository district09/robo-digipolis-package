<?php

namespace DigipolisGent\Robo\Task\Package\Robo\Plugin\Commands;

use DigipolisGent\Robo\Task\General\Common\DigipolisPropertiesAwareInterface;
use Robo\Symfony\ConsoleIO;

class DigipolisPackageCommands extends \Robo\Tasks implements DigipolisPropertiesAwareInterface, \Robo\Contract\ConfigAwareInterface
{
    use \DigipolisGent\Robo\Task\Package\Tasks;
    use \DigipolisGent\Robo\Task\General\Common\DigipolisPropertiesAware;
    use \Consolidation\Config\ConfigAwareTrait;

    /**
     * @command digipolis:theme-compile
     */
    public function digipolisThemeCompile(ConsoleIO $io, $dir = null, $buildCommand = 'compile')
    {
        $this->readProperties();
        return $this->taskThemeCompile($dir, $buildCommand)->run();
    }

    /**
     * @command digipolis:theme-clean
     */
    public function digipolisThemeClean(ConsoleIO $io, $dir = null)
    {
        $this->readProperties();
        return $this->taskThemeClean($dir)->run();
    }

    /**
     * @command digipolis:package-project
     */
    public function digipolisPackageProject(ConsoleIO $io, $archiveFile, $dir = null, $opts = ['ignore|i' => ''])
    {
        $this->readProperties();
        return $this->taskPackageProject($archiveFile, $dir)
            ->ignoreFileNames(array_map('trim', explode(',', $opts['ignore'])))
            ->run();
    }
}
