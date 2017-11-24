# Robo Digipolis Package

General Packaging/Compile tasks for Robo Task Runner

[![Latest Stable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-package/v/stable)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)
[![Latest Unstable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-package/v/unstable)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)
[![Total Downloads](https://poser.pugx.org/digipolisgent/robo-digipolis-package/downloads)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)
[![License](https://poser.pugx.org/digipolisgent/robo-digipolis-package/license)](https://packagist.org/packages/digipolisgent/robo-digipolis-package)

[![Build Status](https://travis-ci.org/digipolisgent/robo-digipolis-package.svg?branch=develop)](https://travis-ci.org/digipolisgent/robo-digipolis-package)
[![Maintainability](https://api.codeclimate.com/v1/badges/7d98babbd043d51bc40f/maintainability)](https://codeclimate.com/github/digipolisgent/robo-digipolis-package/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7d98babbd043d51bc40f/test_coverage)](https://codeclimate.com/github/digipolisgent/robo-digipolis-package/test_coverage)
[![PHP 7 ready](https://php7ready.timesplinter.ch/digipolisgent/robo-digipolis-package/develop/badge.svg)](https://travis-ci.org/digipolisgent/robo-digipolis-package)

## Commands

This package provides default commands wich you can use in your `RoboFile.php`
like so:

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

The directory to package. Defaults to the config value `digipolis.root.project`
if it is set (see <https://github.com/digipolisgent/robo-digipolis-general> for
more information), or the current working directory otherwise.

#### Options

##### --ignore, -i

Comma separated list of filenames to ignore.

### digipolis:theme-clean

`vendor/bin/robo digipolis:theme-clean [DIR]`

#### Arguments

##### DIR

The theme directory to clean. Defaults to the current working directory.

### digipolis:theme-compile

`vendor/bin/robo digipolis:theme-compile [DIR] [COMMAND]`

#### Arguments

##### DIR

The directory of the theme to compile. Defaults to the current working
directory.

##### COMMAND

The grunt/gulp command to execute if grunt or gulp is available.
