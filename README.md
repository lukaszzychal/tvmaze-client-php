# TVMaze PHP Client

[![CI](https://github.com/lukaszzychal/tvmaze-client-php/workflows/CI/badge.svg)](https://github.com/lukaszzychal/tvmaze-client-php/actions)
[![Contract Tests](https://github.com/lukaszzychal/tvmaze-client-php/workflows/Scheduled%20Contract%20Tests/badge.svg)](https://github.com/lukaszzychal/tvmaze-client-php/actions)
[![Code Coverage](https://codecov.io/gh/lukaszzychal/tvmaze-client-php/branch/main/graph/badge.svg)](https://codecov.io/gh/lukaszzychal/tvmaze-client-php)
[![Latest Stable Version](https://img.shields.io/packagist/v/lukaszzychal/tvmaze-client-php.svg)](https://packagist.org/packages/lukaszzychal/tvmaze-client-php)
[![Total Downloads](https://img.shields.io/packagist/dt/lukaszzychal/tvmaze-client-php.svg)](https://packagist.org/packages/lukaszzychal/tvmaze-client-php)
[![License](https://img.shields.io/packagist/l/lukaszzychal/tvmaze-client-php.svg)](https://packagist.org/packages/lukaszzychal/tvmaze-client-php)
[![PHP Version](https://img.shields.io/packagist/php-v/lukaszzychal/tvmaze-client-php.svg)](https://packagist.org/packages/lukaszzychal/tvmaze-client-php)

A modern, PSR-18 compliant PHP client for the [TVMaze API](https://www.tvmaze.com/api). This library provides a clean, type-safe interface to access TV show information, episodes, cast, crew, schedules, and more.

## Features

- ✅ **PSR-18 Compliant**: Implements the HTTP Client standard
- ✅ **Type Safety**: Full PHP 8.1+ type declarations
- ✅ **Comprehensive Coverage**: All TVMaze API endpoints supported
- ✅ **Exception Handling**: Proper error handling with custom exceptions
- ✅ **Rate Limiting**: Built-in rate limit detection and handling
- ✅ **Embedding Support**: Support for embedded resources (HAL)
- ✅ **Extensive Testing**: Unit tests + contract tests against real API
- ✅ **CI/CD Pipeline**: Automated testing and quality checks
- ✅ **Code Quality**: PHPStan, PHP CS Fixer, and comprehensive linting

## Installation

```bash
composer require lukaszzychal/tvmaze-client-php:^2.0
```

## Requirements

- PHP 8.1 or higher
- Guzzle HTTP 7.5 or higher

## Quick Start

```php
<?php

use TVMaze\Client\TVMazeClient;

// Create a client instance
$client = TVMazeClient::create();

// Search for shows
$shows = $client->searchShows('breaking bad');
foreach ($shows as $result) {
    echo $result['show']->name . ' (Score: ' . $result['score'] . ')' . PHP_EOL;
}

// Get a specific show
$show = $client->getShow(169); // Breaking Bad
echo $show->name . PHP_EOL;
echo $show->summary . PHP_EOL;

// Get show episodes
$episodes = $client->getShowEpisodes(169);
foreach ($episodes as $episode) {
    echo "S{$episode->season}E{$episode->number}: {$episode->name}" . PHP_EOL;
}

// Search for people
$people = $client->searchPeople('bryan cranston');
foreach ($people as $result) {
    echo $result['person']->name . PHP_EOL;
}
```

## API Reference

### Search Operations

#### Search Shows
```php
// Search for shows with fuzzy matching
$results = $client->searchShows('breaking bad');
// Returns array of [score => float, show => Show]

// Single show search (returns one result or null)
$show = $client->singleShowSearch('breaking bad');
// Returns Show object or null

// Lookup by external ID
$show = $client->lookupShow('thetvdb', '81189');
$show = $client->lookupShow('imdb', 'tt0903747');
$show = $client->lookupShow('tvrage', '12345');
```

#### Search People
```php
$people = $client->searchPeople('bryan cranston');
// Returns array of [score => float, person => Person]
```

### Show Operations

#### Get Show Information
```php
// Basic show info
$show = $client->getShow(169);

// With embedded resources
$show = $client->getShow(169, ['cast', 'episodes', 'nextepisode']);
```

#### Show Episodes
```php
// Get all episodes
$episodes = $client->getShowEpisodes(169);

// Include specials
$episodes = $client->getShowEpisodes(169, true);

// Get specific episode by season/number
$episode = $client->getEpisodeByNumber(169, 1, 1);

// Get episodes by date
$episodes = $client->getEpisodesByDate(169, '2010-01-20');
```

#### Show Cast & Crew
```php
$cast = $client->getShowCast(169);
$crew = $client->getShowCrew(169);
```

### People Operations

```php
// Get person information
$person = $client->getPerson(489); // Bryan Cranston

// With embedded credits
$person = $client->getPerson(489, ['castcredits', 'crewcredits']);
```

### Schedule Operations

```php
// Get today's schedule for US
$schedule = $client->getSchedule('US');

// Get schedule for specific date
$schedule = $client->getSchedule('US', '2023-12-25');

// Get web/streaming schedule
$webSchedule = $client->getWebSchedule('US');

// Get global web schedule only
$globalWebSchedule = $client->getWebSchedule(''); // Empty string for global only
```

### Updates

```php
// Get show updates
$showUpdates = $client->getShowUpdates('day'); // day, week, month, or null for all
$peopleUpdates = $client->getPeopleUpdates('week');
```

## Models

The client uses strongly-typed model classes:

- `TVMaze\Model\Show` - TV show information
- `TVMaze\Model\Episode` - Episode information  
- `TVMaze\Model\Person` - Person information
- `TVMaze\Model\Network` - Network information
- `TVMaze\Model\Country` - Country information
- `TVMaze\Model\Image` - Image information
- And more...

## Exception Handling

The client provides specific exception types for different error scenarios:

```php
use TVMaze\Exception\TVMazeException;
use TVMaze\Exception\ClientException;
use TVMaze\Exception\ServerException;
use TVMaze\Exception\RateLimitException;

try {
    $show = $client->getShow(999999);
} catch (RateLimitException $e) {
    // Handle rate limiting (429 errors)
    sleep(1);
    // Retry the request
} catch (ClientException $e) {
    // Handle client errors (4xx)
    echo "Client error: " . $e->getMessage();
} catch (ServerException $e) {
    // Handle server errors (5xx)
    echo "Server error: " . $e->getMessage();
} catch (TVMazeException $e) {
    // Handle other API errors
    echo "API error: " . $e->getMessage();
}
```

## Rate Limiting

The TVMaze API has a rate limit of at least 20 calls every 10 seconds per IP. The client automatically detects rate limit errors (HTTP 429) and throws `RateLimitException`. Implement retry logic as needed:

```php
use TVMaze\Exception\RateLimitException;

function makeRequestWithRetry(callable $request, int $maxRetries = 3): mixed
{
    $retries = 0;
    
    while ($retries < $maxRetries) {
        try {
            return $request();
        } catch (RateLimitException $e) {
            $retries++;
            if ($retries >= $maxRetries) {
                throw $e;
            }
            sleep(pow(2, $retries)); // Exponential backoff
        }
    }
}

// Usage
$show = makeRequestWithRetry(fn() => $client->getShow(169));
```

## Project Structure

```
tvmaze-client-php/
├── 📁 src/                    # Source code
│   ├── Client/               # Main HTTP client
│   ├── Model/                # Data models (Show, Episode, Person, etc.)
│   └── Exception/            # Custom exceptions
├── 📁 tests/                 # Test suites
│   ├── Unit/                 # Unit tests with mocked responses
│   └── Contract/             # Contract tests against real API
├── 📁 examples/              # Usage examples
├── 📁 .github/               # GitHub Actions & templates
├── 📄 composer.json          # Package configuration
└── 📄 README.md              # This documentation
```

### Architecture

- **PSR-4 Autoloading**: `TVMaze\` namespace
- **PSR-18 HTTP Client**: Standard-compliant HTTP interface
- **Type Safety**: PHP 8.2+ with full type declarations
- **Error Handling**: Custom exceptions for different API errors
- **Testing**: Unit tests + contract tests for API compatibility

## Development

### Running Tests

```bash
# Install dependencies
composer install

# Run unit tests
composer test

# Run contract tests (requires internet connection)
composer test -- --group=contract

# Run with coverage
composer test-coverage
```

### Code Quality

```bash
# Run all quality checks
composer quality

# Run individual tools
composer cs-check    # Check coding standards
composer cs-fix      # Fix coding standards
composer phpstan     # Static analysis
```

### Contract Testing

Contract tests verify compatibility with the real TVMaze API:

- **Manual**: Run `composer test -- --group=contract`
- **Scheduled**: Automated daily via GitHub Actions
- **CI**: Runs on main branch pushes

Contract test failures automatically create GitHub issues when the API changes.

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes and add tests
4. Run quality checks: `composer quality`
5. Commit your changes: `git commit -m 'Add amazing feature'`
6. Push to the branch: `git push origin feature/amazing-feature`
7. Open a Pull Request

## License Compliance

### TVMaze API License (CC BY-SA 4.0)

The TVMaze API is licensed under [Creative Commons Attribution-ShareAlike 4.0](https://creativecommons.org/licenses/by-sa/4.0/). This means you must provide attribution when using their data.

### Automatic Attribution

This client provides built-in methods to ensure license compliance:

```php
use TVMaze\Client\TVMazeClient;

$client = TVMazeClient::create('MyApp/1.0');

// For web applications
echo $client->getAttributionHtml();
// Output: <p class="tvmaze-attribution">Data provided by <a href="https://www.tvmaze.com" target="_blank" rel="noopener noreferrer">TVMaze</a></p>

// For console applications
echo $client->getAttributionText();
// Output: Data provided by TVMaze (https://www.tvmaze.com)

// For documentation (Markdown)
echo $client->getAttributionMarkdown();
// Output: Data provided by [TVMaze](https://www.tvmaze.com)

// Detailed attribution with license info
echo $client->getDetailedAttributionHtml();
```

### Model-specific Attribution

You can also get attribution from individual models:

```php
$show = $client->getShow(1);
echo $show->getAttributionHtml();    // HTML attribution
echo $show->getAttributionText();    // Plain text
echo $show->getAttributionMarkdown(); // Markdown

$episode = $client->getShowEpisodes(1)[0];
echo $episode->getAttributionHtml(); // Same methods available
```

### Examples

See [examples/attribution-usage.php](examples/attribution-usage.php) for comprehensive attribution usage examples.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## TVMaze API

This client is built for the [TVMaze API](https://www.tvmaze.com/api), which provides:

- **Free**: No API key required
- **Fast**: Cached responses
- **Comprehensive**: Shows, episodes, cast, crew, schedules
- **RESTful**: Clean JSON API
- **HAL Compliant**: Hypermedia API with embedded resources

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes.

## Support

- **Documentation**: [TVMaze API Docs](https://www.tvmaze.com/api)
- **Issues**: [GitHub Issues](https://github.com/lukaszzychal/tvmaze-client-php/issues)
- **TVMaze Forums**: [TVMaze Community](https://forums.tvmaze.com/)
