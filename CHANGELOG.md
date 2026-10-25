# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.2] - 2026-10-25

### Changed

- Synchronize the bundled local skill automatically and trust Fast Forward Composer
  skill donors through `fast-forward/*`.
  Resolve Changelog contributor skills from stable `0.4.x` releases without a
  redundant skill allowlist.
  Load the project autoloader for PHPDoc checks when DevTools is installed as a
  dependency.

  ([#19](https://github.com/php-fast-forward/http-message/pull/19))

- GitHub Actions(deps): bump php-fast-forward/dev-tools/.github/workflows/wiki-direct.yml from c637e7f9d8ae6059b8655996e56f6d17f874792c to 84e154046e07cafa6ac09a9e12434b1ff5dcb5e0

  Update dependencies: `php-fast-forward/dev-tools/.github/workflows/wiki-direct.yml`.

  ([#20](https://github.com/php-fast-forward/http-message/pull/20), @dependabot[bot])

### Fixed

- Match HTTP authentication schemes case-insensitively without changing credential values. ([#16](https://github.com/php-fast-forward/http-message/pull/16))
- Honor per-representation Accept specificity and zero-quality exclusions, and distinguish absent Accept-Encoding from empty or restrictive fields. ([#16](https://github.com/php-fast-forward/http-message/pull/16))
- Keep JsonResponse payload access and replacement usable after a generic PSR-7 body replacement, with explicit decoding and stream errors. ([#17](https://github.com/php-fast-forward/http-message/pull/17))

## [1.5.1] - 2026-10-21

### Fixed

- Keep header helpers compatible with PHP 8.3 without mbstring or development polyfills, using ASCII HTTP whitespace and token case folding while preserving credential values. ([#14](https://github.com/php-fast-forward/http-message/pull/14))

## [1.5.0] - 2026-10-21

### Added

- Bundle the fast-forward-http-message consumer skill with executable examples, optional installation guidance and Composer discovery metadata. ([#8](https://github.com/php-fast-forward/http-message/pull/8))

### Changed

- Automate changelog checks, version pull requests and approved releases with Fast Forward Changelog, with the project skill available to contributors. ([#6](https://github.com/php-fast-forward/http-message/pull/6))
- Restore the Changelog skill from its GitHub source during Composer installation instead of committing a generated copy and separate installation receipt. ([#6](https://github.com/php-fast-forward/http-message/pull/6))

## [1.4.0] - 2026-03-29

**Full Changelog**: https://github.com/php-fast-forward/http-message/compare/v1.3.0...v1.4.0

## [1.3.0] - 2025-11-17

Release notes are unavailable for this version.

## [1.2.0] - 2025-06-30

**Full Changelog**: https://github.com/php-fast-forward/http-message/compare/v1.1.0...v1.2.0

## [1.1.0] - 2025-06-30

Release notes are unavailable for this version.

## [1.0.0] - 2025-06-30

Release notes are unavailable for this version.

[1.5.2]: https://github.com/php-fast-forward/http-message/compare/v1.5.1...v1.5.2
[1.5.1]: https://github.com/php-fast-forward/http-message/compare/v1.5.0...v1.5.1
[1.5.0]: https://github.com/php-fast-forward/http-message/compare/v1.4.0...v1.5.0
[1.4.0]: https://github.com/php-fast-forward/http-message/compare/v1.3.0...v1.4.0
[1.3.0]: https://github.com/php-fast-forward/http-message/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/php-fast-forward/http-message/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/php-fast-forward/http-message/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/php-fast-forward/http-message/releases/tag/v1.0.0
