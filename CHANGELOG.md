# Changelog

All Notable changes to `digipolisgent/robo-digipolis-package`.

## [Unreleased]

## [0.1.6]

### Changed

* Added support for Symfony 4.

## [0.1.5]

### Changed

* Fixed the unit tests for PHP7.

## [0.1.4]

### Added

* Fixed [#10]: Print the output of the compile commands.

## [0.1.3]

### Changed

* Renamed argument `$command` to `$buildCommand` because Robo doesn't allow for an argument named `$command`.

### Added

* Fixed [#7]: Added yarn support.

## [0.1.2]

### Changed

* Updated NpmFindExecutable to search in the current working directory as well.

## [0.1.1]

### Changed

* Fixed [#4]: Rename cleanMirrorDir to prepareMirrorDir.

## [0.1.0]

### Changed

* Improved output.
* Code style fixes.

## [0.1.0-beta1]

### Changed

* Avoid adding the tar we're creating to the tar.
* Skip dot-folders (. and ..) when creating the tar.
* Fixed undefined variable notices.
* If the temporary directory is within the directory we're mirroring, don't mirror the directory itself.
* Group addModify calls to Archive_Tar to improve performance.
* Better check for broken links when mirroring a directory.
* Fixed the useTmpDir option (logic was reversed).
* Do not try to remove the temporary directory if we didn't create one.
* Fixed undefined variable target.
* Fixed undefined variable file.
* Make the use of a temp dir optional.


## [0.1.0-alpha3]

### Changed

* Updated the README with command documentation.

### Added

* Added commands for each task.
* Created traits for each command and task.

## [0.1.0-alpha2]

### Changed

* Performance improvement: Use a temporary directory to create the archive.

## [0.1.0-alpha1]

### Added

* Initial functionality.

[Unreleased]: https://github.com/digipolisgent/php_package_successfactors-jobs/compare/master...develop
[0.1.6]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.5...0.1.6
[0.1.5]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.4...0.1.5
[0.1.4]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.3...0.1.4
[0.1.3]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.2...0.1.3
[0.1.2]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.1...0.1.2
[0.1.1]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.0...0.1.1
[0.1.0]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.0-beta1...0.1.0
[0.1.0-beta1]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.0-alpha3...0.1.0-beta1
[0.1.0-alpha3]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.0-alpha2...0.1.0-alpha3
[0.1.0-alpha2]: https://github.com/digipolisgent/robo-digipolis-package/compare/0.1.0-alpha1...0.1.0-alpha2
[0.1.0-alpha1]: https://github.com/digipolisgent/robo-digipolis-package/releases/tag/0.1.0-alpha1

[#10]: https://github.com/digipolisgent/robo-digipolis-package/issues/10
[#7]: https://github.com/digipolisgent/robo-digipolis-package/issues/7
[#4]: https://github.com/digipolisgent/robo-digipolis-package/issues/4
