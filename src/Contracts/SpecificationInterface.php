<?php

namespace DangerWayne\Specification\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface SpecificationInterface
{
    /**
     * Check if the specification is satisfied by the given candidate
     */
    public function isSatisfiedBy(mixed $candidate): bool;

    /**
     * Apply the specification to an Eloquent query builder
     */
    public function toQuery(Builder $query): Builder;

    /**
     * Combine with another specification using AND logic
     */
    public function and(SpecificationInterface $specification): SpecificationInterface;

    /**
     * Combine with another specification using OR logic
     */
    public function or(SpecificationInterface $specification): SpecificationInterface;

    /**
     * Negate the specification
     */
    public function not(): SpecificationInterface;

    /**
     * Get a unique cache key for this specification
     */
    public function getCacheKey(): string;
}
