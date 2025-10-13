# Packagist Setup Guide

This document outlines the steps to publish this TVMaze PHP Client to Packagist.

## Prerequisites

1. **GitHub Repository**: Push this code to a GitHub repository
2. **Packagist Account**: Create an account at https://packagist.org/
3. **GitHub Integration**: Connect your Packagist account with GitHub

## Steps to Publish

### 1. Push to GitHub

```bash
# Initialize git repository (if not already done)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit: TVMaze PHP Client"

# Add remote origin (replace with your GitHub repository URL)
git remote add origin https://github.com/lukaszzychal/tvmaze-client-php.git

# Push to GitHub
git push -u origin main
```

### 2. Submit to Packagist

1. Go to https://packagist.org/packages/submit
2. Enter your GitHub repository URL: `https://github.com/lukaszzychal/tvmaze-client-php`
3. Click "Check" to validate the package
4. Click "Submit" to publish

### 3. Configure Auto-Update (Recommended)

1. Go to your package page on Packagist
2. Click "Settings"
3. Enable "Auto-update"
4. Connect your GitHub account if not already connected

This will automatically update the package on Packagist whenever you push a new release tag.

### 4. Create First Release

```bash
# Create a version tag
git tag v1.0.0

# Push the tag
git push origin v1.0.0
```

### 5. Verify Installation

Once published, users can install your package:

```bash
composer require lukaszzychal/tvmaze-client-php
```

## Package Information

- **Package Name**: `lukaszzychal/tvmaze-client-php`
- **Description**: PSR-18 PHP client for the TVMaze API
- **License**: MIT
- **Minimum PHP Version**: 8.1
- **Dependencies**: Guzzle HTTP 7.5+

## Maintenance

### Updating the Package

1. Make your changes
2. Update version in `composer.json` (if needed)
3. Update `CHANGELOG.md`
4. Commit and push changes
5. Create a new release tag
6. Packagist will auto-update (if configured)

### Monitoring

- **GitHub Actions**: Monitor CI/CD pipeline status
- **Contract Tests**: Automated daily contract tests against TVMaze API
- **Issues**: Monitor GitHub issues for bugs and feature requests
- **Packagist**: Monitor download statistics and package health

## Support

For issues related to:
- **Package Publishing**: Check Packagist documentation
- **Code Issues**: Create GitHub issues
- **TVMaze API**: Visit TVMaze forums

## Success Metrics

After publishing, you can track:
- Download statistics on Packagist
- GitHub stars and forks
- Issue reports and feature requests
- Contract test stability
- CI/CD pipeline health
