# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.2.0]
### Added
- Action link in the Plugins list table to settings page.
- Allow to filter the additional config info passed to the config command via `required_ga.additional_config_info`.

## [2.1.0]
### Added
- Support for disabling Analytics for opted-out users.
- Layer for event tracking via HTML data attributes.

## [2.0.1]
### Fixed
- Use correct API URL for translations via Traduttore.

## [2.0.0]
### Added
- Support for translations with Traduttore via translate.required.com.

### Changed
- Start using global site tag (gtag.js) instead of analytics.js (Universal Analytics). See [migration document](https://developers.google.com/analytics/devguides/collection/gtagjs/migration) before using 2.0.0.

## [1.2.0] - 2018-06-12
### Changed
- Remove the `'no-version'` script data support. Instead pass `null` as version to prevent adding a version argument.

## [1.1.0] - 2018-06-11
### Fixed
- Fix fatal error when used in a project setup.

### Changed
- Rename package to `required-google-analytics`.

## [1.0.0] - 2018-06-11
### Added
- Enqueue Google's analytics.js (Universal Analytics) with `async` and DNS prefetching.

[Unreleased]: https://github.com/wearerequired/required-google-analytics/compare/2.2.0...HEAD
[2.2.0]: https://github.com/wearerequired/required-google-analytics/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/wearerequired/required-google-analytics/compare/2.0.0...2.1.0
[2.0.1]: https://github.com/wearerequired/required-google-analytics/compare/2.0.0...2.0.11
[2.0.0]: https://github.com/wearerequired/required-google-analytics/compare/1.2.0...2.0.0
[1.2.0]: https://github.com/wearerequired/required-google-analytics/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/wearerequired/required-google-analytics/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/wearerequired/required-google-analytics/releases/tag/1.0.0
