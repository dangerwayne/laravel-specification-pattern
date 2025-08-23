<?php

namespace Examples;

use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class ActiveUserSpecification extends AbstractSpecification
{
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->status === 'active'
            && $candidate->email_verified_at !== null;
    }

    public function toQuery(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->whereNotNull('email_verified_at');
    }
}

class PremiumUserSpecification extends AbstractSpecification
{
    public function __construct(private int $minAge = 18) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate->role === 'premium'
            && $candidate->age >= $this->minAge;
    }

    public function toQuery(Builder $query): Builder
    {
        return $query->where('role', 'premium')
            ->where('age', '>=', $this->minAge);
    }

    protected function getParameters(): array
    {
        return ['minAge' => $this->minAge];
    }
}

class UserInLocationSpecification extends AbstractSpecification
{
    public function __construct(
        private string $country,
        private ?string $city = null
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        $matchesCountry = $candidate->country === $this->country;

        if ($this->city !== null) {
            return $matchesCountry && $candidate->city === $this->city;
        }

        return $matchesCountry;
    }

    public function toQuery(Builder $query): Builder
    {
        $query = $query->where('country', $this->country);

        if ($this->city !== null) {
            $query->where('city', $this->city);
        }

        return $query;
    }

    protected function getParameters(): array
    {
        return [
            'country' => $this->country,
            'city' => $this->city,
        ];
    }
}

class RecentlyActiveUserSpecification extends AbstractSpecification
{
    public function __construct(private int $daysAgo = 30) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        if ($candidate->last_login_at === null) {
            return false;
        }

        $cutoffDate = now()->subDays($this->daysAgo);

        return $candidate->last_login_at >= $cutoffDate;
    }

    public function toQuery(Builder $query): Builder
    {
        return $query->where('last_login_at', '>=', now()->subDays($this->daysAgo));
    }

    protected function getParameters(): array
    {
        return ['daysAgo' => $this->daysAgo];
    }
}

// Example usage:
/*
use DangerWayne\Specification\Facades\Specification;

// Simple usage
$activeUsers = User::whereSpecification(new ActiveUserSpecification())->get();

// Complex specifications
$spec = (new ActiveUserSpecification())
    ->and(new PremiumUserSpecification(21))
    ->and(new UserInLocationSpecification('US', 'New York'));

$users = User::whereSpecification($spec)->get();

// Using the fluent builder
$fluentSpec = Specification::create()
    ->where('status', 'active')
    ->whereNotNull('email_verified_at')
    ->where('age', '>=', 21)
    ->where('country', 'US')
    ->build();

$users = User::whereSpecification($fluentSpec)->get();

// Collection filtering
$users = collect([
    new User(['status' => 'active', 'email_verified_at' => now(), 'age' => 25]),
    new User(['status' => 'inactive', 'email_verified_at' => null, 'age' => 30]),
]);

$activeSpec = new ActiveUserSpecification();
$filteredUsers = $users->whereSpecification($activeSpec);
*/
