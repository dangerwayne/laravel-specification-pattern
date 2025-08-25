# Changelog

All notable changes to `laravel-specifications` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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