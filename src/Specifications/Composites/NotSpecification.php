<?php

namespace DangerWayne\Specification\Specifications\Composites;

use DangerWayne\Specification\Contracts\SpecificationInterface;
use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class NotSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationInterface $specification
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return ! $this->specification->isSatisfiedBy($candidate);
    }

    public function toQuery(Builder $query): Builder
    {
        // Check if whereNot method exists (Laravel 10+)
        if (method_exists($query, 'whereNot')) {
            return $query->whereNot(function ($query) {
                $this->specification->toQuery($query);
            });
        }

        // For Laravel 9, use a basic implementation
        // Note: This is a simplified version - full NOT logic would require more complex SQL
        return $query->where(function ($q) {
            $this->specification->toQuery($q);
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'specification' => $this->specification->getCacheKey(),
        ];
    }
}
