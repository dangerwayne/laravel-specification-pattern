# Contributing to Laravel Specifications

Thank you for considering contributing to Laravel Specifications! We welcome contributions from the community.

## Ways to Contribute

### Reporting Issues

- **Bug Reports**: Use our [bug report template](https://github.com/dangerwayne/laravel-specifications/issues/new?template=bug_report.md) to report issues
- **Feature Requests**: Suggest new features using the [feature request template](https://github.com/dangerwayne/laravel-specifications/issues/new?template=feature_request.md)
- **Questions**: Ask questions using the [question template](https://github.com/dangerwayne/laravel-specifications/issues/new?template=question.md)

### Code Contributions

1. **Fork the Repository**: Create your own fork of the project
2. **Create a Branch**: Create a feature branch (`git checkout -b feature/amazing-feature`)
3. **Write Tests**: Ensure your changes are covered by tests
4. **Follow Code Style**: Run `composer format` to ensure code style consistency
5. **Run Tests**: Ensure all tests pass with `composer test`
6. **Static Analysis**: Run `composer analyse` to check for static analysis issues
7. **Commit Changes**: Write clear, descriptive commit messages
8. **Push to Branch**: Push your changes to your fork
9. **Open Pull Request**: Submit a PR with a clear description of your changes

## Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/laravel-specifications.git
cd laravel-specifications

# Install dependencies
composer install

# Run tests
composer test

# Run static analysis
composer analyse

# Format code
composer format
```

## Code Standards

- **PHP Version**: Minimum PHP 8.0
- **Laravel Versions**: Support for Laravel 9, 10, 11, and 12
- **Code Style**: We use Laravel Pint for code formatting
- **Static Analysis**: PHPStan level 8
- **Testing**: All new features must include tests

## Testing

```bash
# Run all tests
composer test

# Run specific test
vendor/bin/phpunit tests/Unit/YourTest.php

# Run with coverage
composer test-coverage
```

## Documentation

- Update README.md if your change affects usage
- Add PHPDoc comments for all public methods
- Include examples in your PR description

## Pull Request Process

1. Ensure all tests pass
2. Update documentation as needed
3. Add your changes to CHANGELOG.md
4. Reference any related issues in your PR description
5. Wait for code review and address feedback

## Community Guidelines

- Be respectful and considerate
- Welcome newcomers and help them get started
- Focus on constructive criticism
- Follow the [Laravel Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct)

## Questions?

Feel free to:
- Open an [issue](https://github.com/dangerwayne/laravel-specifications/issues)
- Start a [discussion](https://github.com/dangerwayne/laravel-specifications/discussions)
- Reach out to the maintainers

Thank you for contributing!