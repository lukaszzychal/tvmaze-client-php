# Quality Tools Configuration

This project uses a comprehensive set of quality tools to ensure code quality, consistency, and reliability.

## 🛠️ Tools Used

### 1. **PHP CS Fixer** - Code Style
- **Version**: ^3.15
- **Config**: `.php-cs-fixer.php`
- **Purpose**: Enforces consistent coding style and formatting

### 2. **PHPStan** - Static Analysis
- **Version**: ^1.10
- **Config**: `phpstan.neon`
- **Level**: 8 (maximum)
- **Purpose**: Finds bugs and type errors

### 3. **Psalm** - Static Analysis
- **Version**: ^5.26
- **Config**: `psalm.xml`
- **Level**: 1 (strict)
- **Purpose**: Advanced static analysis with type checking

### 4. **PHPUnit** - Testing
- **Version**: ^10.0
- **Config**: `phpunit.xml`
- **Purpose**: Unit and integration testing

## 🚀 Available Commands

### Individual Tools
```bash
# Code style checking
composer cs-check

# Code style fixing
composer cs-fix

# PHPStan static analysis
composer phpstan

# Psalm static analysis
composer psalm

# Create Psalm baseline
composer psalm-baseline

# Run tests
composer test

# Run tests with coverage
composer test-coverage
```

### Quality Checks
```bash
# Fast quality check (CS + Tests)
composer quality-fast

# Standard quality check (CS + PHPStan + Psalm + Tests)
composer quality

# Full quality check (CS + PHPStan + Psalm + Tests with Coverage)
composer quality-full
```

## 📋 Quality Levels

### **Level 1: Fast** (`quality-fast`)
- ✅ Code style check
- ✅ Unit tests
- ⏱️ ~30 seconds

### **Level 2: Standard** (`quality`)
- ✅ Code style check
- ✅ PHPStan analysis
- ✅ Unit tests
- ⏱️ ~1.5 minutes

### **Level 2.5: With Psalm** (`quality-with-psalm`)
- ✅ Code style check
- ✅ PHPStan analysis
- ✅ Psalm analysis
- ✅ Unit tests
- ⏱️ ~2 minutes

### **Level 3: Full** (`quality-full`)
- ✅ Code style check
- ✅ PHPStan analysis
- ✅ Psalm analysis
- ✅ Unit tests with coverage
- ⏱️ ~3 minutes

## 🔧 Configuration Details

### PHP CS Fixer
- **Standard**: PSR-12
- **Migration**: PHP 8.2
- **Features**: Array syntax, braces, spacing, imports, etc.

### PHPStan
- **Level**: 8 (maximum strictness)
- **Target**: `src/` directory only
- **Ignores**: Mixed types for API flexibility

### Psalm
- **Level**: 1 (strict)
- **Features**: Type checking, dead code detection
- **Ignores**: Mixed types for API responses

## 🎯 Best Practices

### 1. **Before Committing**
```bash
composer quality-fast
```

### 2. **Before Release**
```bash
composer quality-full
```

### 3. **Continuous Integration**
```bash
composer quality
```

## 🚨 Troubleshooting

### Common Issues

#### PHP CS Fixer Errors
```bash
# Fix automatically
composer cs-fix
```

#### PHPStan Errors
```bash
# Check specific file
vendor/bin/phpstan analyse src/Client/TVMazeClient.php
```

#### Psalm Errors
```bash
# Create baseline for existing issues
composer psalm-baseline

# Check specific file
vendor/bin/psalm src/Client/TVMazeClient.php
```

#### Test Failures
```bash
# Run specific test
vendor/bin/phpunit tests/Unit/TVMazeClientTest.php

# Run with verbose output
vendor/bin/phpunit --verbose
```

## 📊 Coverage Goals

- **Minimum**: 80% code coverage
- **Target**: 90% code coverage
- **Critical**: 100% for core functionality

## 🔄 Workflow Integration

### GitHub Actions
The CI pipeline runs `composer quality` on every push and pull request.

### Pre-commit Hooks
Consider adding pre-commit hooks to run `composer quality-fast` automatically.

## 📚 Resources

- [PHP CS Fixer Documentation](https://cs.symfony.com/)
- [PHPStan Documentation](https://phpstan.org/)
- [Psalm Documentation](https://psalm.dev/)
- [PHPUnit Documentation](https://phpunit.de/)
- [GitHub Discussions](https://github.com/lukaszzychal/tvmaze-client-php/discussions)
- [GitHub Issues](https://github.com/lukaszzychal/tvmaze-client-php/issues)
