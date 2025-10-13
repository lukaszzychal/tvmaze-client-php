# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2025-10-13

### Added
- Consistent API responses with typed objects
- Improved type safety for search operations

### Changed
- **BREAKING**: `searchShows()` now returns `Show` objects instead of raw arrays
- **BREAKING**: `searchPeople()` now returns `Person` objects instead of raw arrays
- Updated examples and documentation to reflect new API

### Breaking Changes
This is a **MAJOR** version bump due to breaking changes in API responses.

### Migration Guide
Update your code from:
```php
// Old syntax
$name = $result['show']['name'];
$personName = $result['person']['name'];
```

To:
```php
// New syntax  
$name = $result['show']->name;
$personName = $result['person']->name;
```

## [1.0.0] - 2025-10-13

### Added
- Initial release of TVMaze PHP Client
- PSR-18 compliant HTTP client implementation
- Comprehensive TVMaze API coverage:
  - Show search and lookup operations
  - Episode management and retrieval
  - People search and information
  - Schedule operations (TV and web/streaming)
  - Cast and crew information
  - Updates tracking
- Type-safe model classes for all API responses
- Exception handling with custom exception types
- Rate limiting detection and handling
- Embedded resource support (HAL)
- Comprehensive unit tests with mocking
- Contract tests against real TVMaze API
- CI/CD pipeline with GitHub Actions
- Automated contract testing with scheduled runs
- Code quality tools (PHPStan, PHP CS Fixer)
- Full documentation with examples

### Features
- **Search Operations**: Fuzzy search for shows and people
- **Show Management**: Complete show information, episodes, cast, crew
- **Episode Operations**: Episode retrieval by number, date, or show
- **People Operations**: Person information with credits
- **Schedule Operations**: TV and web/streaming schedules
- **Updates**: Track show and people updates
- **External ID Lookup**: Support for TheTVDB, IMDB, and TVRage IDs
- **Embedding**: HAL-compliant embedded resources

### Technical
- PHP 8.1+ requirement for modern type system
- Guzzle HTTP client integration
- PSR-4 autoloading
- MIT license
- Comprehensive test coverage
- Automated quality assurance
