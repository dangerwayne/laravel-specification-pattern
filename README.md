# Laravel Specification Pattern

A powerful implementation of the Specification Pattern for Laravel applications.

[![Tests](https://github.com/dangerwayne/laravel-specification-pattern/actions/workflows/tests.yml/badge.svg)](https://github.com/dangerwayne/laravel-specification-pattern/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/dangerwayne/laravel-specification-pattern.svg?style=flat-square)](https://packagist.org/packages/dangerwayne/laravel-specification-pattern)
[![Total Downloads](https://img.shields.io/packagist/dt/dangerwayne/laravel-specification-pattern.svg?style=flat-square)](https://packagist.org/packages/dangerwayne/laravel-specification-pattern)

## Features

- **Eloquent Integration**: Seamlessly works with Laravel's Eloquent ORM
- **Collection Support**: Filter in-memory collections using the same specifications
- **Fluent Builder**: Intuitive API for building complex specifications
- **Composite Operations**: Combine specifications with AND, OR, NOT operations
- **Caching Support**: Built-in caching for improved performance
- **Laravel 9, 10, 11**: Full compatibility with modern Laravel versions
- **Type Safe**: Full PHP 8.0+ type declarations and PHPStan level 8 compliance

## Installation

```bash
composer require dangerwayne/laravel-specification-pattern
```

The package will automatically register its service provider.

## Basic Usage

### Using Pre-built Specifications

```php
use DangerWayne\Specification\Specifications\Common\WhereSpecification;

$activeUsers = User::query()
    ->whereSpecification(new WhereSpecification('status', 'active'))
    ->get();
```

### Creating Custom Specifications

```php
use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class PremiumUserSpecification extends AbstractSpecification
{
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->subscription === 'premium';
    }
    
    public function toQuery(Builder $query): Builder
    {
        return $query->where('subscription', 'premium');
    }
}
```

### Using the Fluent Builder

```php
use DangerWayne\Specification\Facades\Specification;

$spec = Specification::create()
    ->where('status', 'active')
    ->where('age', '>=', 18)
    ->whereNotNull('email_verified_at')
    ->build();

$users = User::whereSpecification($spec)->get();
```

### Combining Specifications

```php
$activeSpec = new WhereSpecification('status', '=', 'active');
$premiumSpec = new PremiumUserSpecification();

// AND combination
$activePremiumSpec = $activeSpec->and($premiumSpec);

// OR combination
$activeOrPremiumSpec = $activeSpec->or($premiumSpec);

// NOT combination
$notActiveSpec = $activeSpec->not();
```

### Working with Collections

```php
$users = collect([
    new User(['status' => 'active', 'age' => 25]),
    new User(['status' => 'inactive', 'age' => 30]),
    new User(['status' => 'active', 'age' => 17]),
]);

$spec = Specification::create()
    ->where('status', 'active')
    ->where('age', '>=', 18)
    ->build();

$filteredUsers = $users->whereSpecification($spec);
```

## Available Specifications

The package includes several pre-built specifications:

### WhereSpecification
```php
new WhereSpecification('status', '=', 'active');
new WhereSpecification('age', '>', 18);
new WhereSpecification('name', 'like', '%john%');
```

### WhereInSpecification
```php
new WhereInSpecification('status', ['active', 'pending']);
```

### WhereBetweenSpecification
```php
new WhereBetweenSpecification('age', 18, 65);
```

### WhereNullSpecification
```php
new WhereNullSpecification('email_verified_at');
```

### WhereHasSpecification
```php
new WhereHasSpecification('posts', new WhereSpecification('published', true));
```

## Fluent Builder Methods

```php
Specification::create()
    ->where('field', 'operator', 'value')    // Basic where clause
    ->where('field', 'value')                // Defaults to '=' operator
    ->whereIn('field', [1, 2, 3])           // WHERE IN clause
    ->whereBetween('field', 1, 10)          // BETWEEN clause
    ->whereNull('field')                     // IS NULL clause
    ->whereNotNull('field')                  // IS NOT NULL clause
    ->whereHas('relation', $specification)   // Has relationship
    ->or()                                   // Next condition uses OR
    ->build();                              // Build the specification
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=specification-config
```

```php
return [
    'cache' => [
        'enabled' => env('SPECIFICATION_CACHE_ENABLED', false),
        'ttl' => env('SPECIFICATION_CACHE_TTL', 3600),
        'prefix' => env('SPECIFICATION_CACHE_PREFIX', 'spec_'),
    ],
    'performance' => [
        'lazy_collections' => env('SPECIFICATION_USE_LAZY', true),
        'chunk_size' => env('SPECIFICATION_CHUNK_SIZE', 1000),
    ],
];
```

## Advanced Usage

### Custom Specifications with Parameters

```php
class AgeRangeSpecification extends AbstractSpecification
{
    public function __construct(
        private int $minAge,
        private int $maxAge
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->age >= $this->minAge 
            && $candidate->age <= $this->maxAge;
    }
    
    public function toQuery(Builder $query): Builder
    {
        return $query->whereBetween('age', [$this->minAge, $this->maxAge]);
    }

    protected function getParameters(): array
    {
        return [
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
        ];
    }
}
```

### Complex Specifications

```php
$specification = Specification::create()
    ->where('status', 'active')
    ->where(function ($builder) {
        return $builder
            ->where('role', 'admin')
            ->or()
            ->where('role', 'moderator');
    })
    ->whereNotNull('email_verified_at')
    ->build();
```

## Testing

```bash
composer test
```

## Code Quality

```bash
composer analyse    # PHPStan analysis
composer format     # Code formatting with Pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Your Name](https://github.com/yourusername)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.