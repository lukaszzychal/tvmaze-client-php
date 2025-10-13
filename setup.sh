#!/bin/bash

echo "🚀 Setting up TVMaze PHP Client..."

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Install dependencies
echo "📦 Installing dependencies..."
composer install

# Run code style fixer
echo "🎨 Fixing code style..."
composer cs-fix

# Run static analysis
echo "🔍 Running static analysis..."
composer phpstan

# Run tests
echo "🧪 Running tests..."
composer test

echo "✅ Setup complete!"
echo ""
echo "Next steps:"
echo "1. Push to GitHub repository"
echo "2. Submit to Packagist: https://packagist.org/packages/submit"
echo "3. Run contract tests: composer test -- --group=contract"
echo "4. Test the example: php examples/basic-usage.php"
