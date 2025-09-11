# Changelog

All notable changes to `laravel-specifications` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Laravel 12 Support**: Full compatibility with Laravel 12.x
- **PHP 8.4 Support**: Added support for PHP 8.4 in CI/CD pipeline
- **Orchestra Testbench 10.x**: Updated to support Laravel 12 testing
- **PHPUnit 12.x**: Added support for PHPUnit 12.x

### Enhanced
- **Testing Matrix**: Extended CI/CD matrix to include Laravel 12 with PHP 8.3 and 8.4
- **Dependencies**: Updated all Laravel dependencies to support ^12.0 versions

## [0.2.0] - 2025-08-26

### Added
- **Artisan Generator Command**: `php artisan make:specification` for scaffolding specifications
- **Domain Organization**: Support for organizing specifications by domain/module (e.g., `Bookmark/SearchSpecification`)
- **Model Binding**: Generate model-specific specifications with `--model` option
- **Multiple Stub Templates**: 7 specialized stubs for different specification patterns:
  - Basic specification template
  - Model-bound specification template  
  - Composite specification template (with example composition)
  - Cacheable specification template (with caching trait)
  - Builder pattern specification template (using fluent builder)
  - PHPUnit test template
  - Pest test template
- **Test Generation**: Automatic test creation with `--test` and `--pest` options
- **Stub Publishing**: Customizable stubs via `php artisan vendor:publish --tag=specification-stubs`
- **Advanced Command Options**: Support for combining multiple options (`--composite`, `--cacheable`, `--builder`, etc.)
- **Inline Mode**: `--inline` option for flat specification structure without domain folders

### Enhanced
- **Developer Experience**: Laravel-native command experience with comprehensive option support
- **Documentation**: Updated README with complete Artisan command usage examples

### Technical
- 12 comprehensive tests for command functionality
- PHPStan level 8 compliance maintained
- Laravel Pint code formatting applied
- Full backward compatibility with existing features

## [0.1.1] - 2025-08-24

### Changed
- Package details refinement

## [0.1.0] - 2025-08-24

### Added
- Initial release
- Core specification interfaces and abstract classes
- Composite specifications (AND, OR, NOT)
- Common specifications (Where, WhereIn, WhereBetween, WhereNull, WhereHas)
- Fluent builder interface for creating specifications
- Laravel integration via service provider
- Collection and Query Builder macros
- Configuration file for caching and performance settings
- Comprehensive test suite with 95%+ coverage
- Laravel 9, 10, and 11 support
- PHP 8.0, 8.1, 8.2, and 8.3 support
- PHPStan level 8 compliance
- GitHub Actions CI/CD pipeline
- Comprehensive documentation and examples

### Features
- **SpecificationInterface**: Core interface defining specification contract
- **AbstractSpecification**: Base class implementing common functionality
- **Composite Operations**: And, Or, Not specifications for complex logic
- **Fluent Builder**: Intuitive API for building specifications
- **Eloquent Integration**: Seamless integration with Laravel's query builder
- **Collection Support**: Filter in-memory collections using specifications
- **Caching Support**: Optional caching for improved performance
- **Type Safety**: Full PHP 8+ type declarations and strict typing