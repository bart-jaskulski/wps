# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
This change log adheres to [Keep a CHANGELOG](http://keepachangelog.com/).

## 2.0.0 - 2023-05-15

### Added
- hook `wps/loaded`, which allows to add silenced error paths
- `$wp_current_filters` and admin `$current_screen` data to error output
- `symfony/var-dumper` for pretty-print of objects in data table

### Changed
- general plugin rewrite
- removed global access to plugin instance
- marked everything from `WP_CONTENT_DIR` as application code to filter it from WordPress' stack trace
- plugin requires at least PHP 7.2
- vendor libraries are now prefixed to avoid version collision
- updated dependencies (whoops 2.15.2, Pimple 3.5.0)

### Fixed
- line highlighting from upstream bug in filp/whoops

## 1.2 - 2018-12-18

### Added
- plain text handler for CLI context

### Changed
- updated dependencies (whoops 2.3.1)

## 1.1 - 2017-10-27

### Added
- WP REST API handler

### Changed
- Updated dependencies (whoops 2.1.12, Pimple 3.2.2)

## 1.0 - 2016-05-08

### Added
- Initial stable release.
