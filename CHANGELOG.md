# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-10-20

### Added

- Bundle the fast-forward-clock consumer skill with PSR-20 references, executable application examples and optional installation guidance. ([#8](https://github.com/php-fast-forward/clock/pull/8))

### Changed

- Synchronize the Config and Container consumer skills from the installed Composer dependencies through an explicit trusted-package list instead of independent GitHub sources. ([#10](https://github.com/php-fast-forward/clock/pull/10))
- Require fast-forward/container ^1.10 so installed Composer dependencies supply the supported Container consumer skill alongside the runtime library. ([#10](https://github.com/php-fast-forward/clock/pull/10))

## [1.0.1] - 2026-10-20

### Added

- Restores the Fast Forward Container consumer skill through the existing development-only Composer skill manager, alongside the configured contributor skills, without changing runtime dependencies. ([#6](https://github.com/php-fast-forward/clock/pull/6))

### Changed

- Automate changelog checks, PR feedback and reviewed releases with Fast Forward Changelog, including the project agent skill. ([#6](https://github.com/php-fast-forward/clock/pull/6))
- Restore the Changelog contributor skill through the development-only Composer skill manager and its reviewed GitHub source instead of versioning generated skill copies. ([#6](https://github.com/php-fast-forward/clock/pull/6))
- Synchronize the Config consumer skill from the verified v1.7.3 release through the development-only Composer skill manager, alongside the Changelog contributor skill. ([#6](https://github.com/php-fast-forward/clock/pull/6))

## [1.0.0] - 2026-04-07

**Full Changelog**: https://github.com/php-fast-forward/clock/commits/v1.0.0

[1.1.0]: https://github.com/php-fast-forward/clock/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/php-fast-forward/clock/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/php-fast-forward/clock/releases/tag/v1.0.0
