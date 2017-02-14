# Robo Digipolis Package

General Packaging/Compile tasks for Robo Task Runner

[![Latest Stable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-package/v/stable)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)
[![Latest Unstable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-package/v/unstable)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)
[![Total Downloads](https://poser.pugx.org/digipolisgent/robo-digipolis-package/downloads)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)
[![PHP 7 ready](http://php7ready.timesplinter.ch/digipolisgent/robo-digipolis-package/develop/badge.svg)](https://travis-ci.org/digipolisgent/robo-digipolis-package)
[![License](https://poser.pugx.org/digipolisgent/robo-digipolis-package/license)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)

[![Build Status](https://travis-ci.org/digipolisgent/robo-digipolis-package.svg?branch=develop)](https://travis-ci.org/digipolisgent/robo-digipolis-package)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1f156f4e-6537-46ae-a946-dec578631d95/mini.png)](https://insight.sensiolabs.com/projects/1f156f4e-6537-46ae-a946-dec578631d95)
[![Code Climate](https://codeclimate.com/github/digipolisgent/robo-digipolis-package/badges/gpa.svg)](https://codeclimate.com/github/digipolisgent/robo-digipolis-package)
[![Test Coverage](https://codeclimate.com/github/digipolisgent/robo-digipolis-package/badges/coverage.svg)](https://codeclimate.com/github/digipolisgent/robo-digipolis-package/coverage)
[![Dependency Status](https://www.versioneye.com/user/projects/588617eab194d40039c906dd/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/588617eab194d40039c906dd)

## Commands

This package provides default commands wich you can use in your `RoboFile.php` like so:

```php
class RoboFile extends \Robo\Tasks
{
    use \DigipolisGent\Robo\Task\Package\Commands\loadCommands;
}
```

### digipolis:package-project

`vendor/bin/robo digipolis:package-project FILE [DIR] [OPTIONS]`

#### Arguments

##### FILE

The name of the archive file that will be created.

##### DIR

The directory to package. Defaults to the config value `digipolis.root.project` if it is set (see https://github.com/digipolisgent/robo-digipolis-general for more information), or the current working directory otherwise.

#### Options

##### --ignore, -i

Comma separated list of filenames to ignore.

### digipolis:theme-clean

### digipolis:theme-compile
