<?php

namespace DangerWayne\Specification\Specifications;

use DangerWayne\Specification\Contracts\SpecificationInterface;
use DangerWayne\Specification\Specifications\Composites\AndSpecification;
use DangerWayne\Specification\Specifications\Composites\NotSpecification;
use DangerWayne\Specification\Specifications\Composites\OrSpecification;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractSpecification implements SpecificationInterface
{
    abstract public function isSatisfiedBy(mixed $candidate): bool;

    abstract public function toQuery(Builder $query): Builder;

    public function and(SpecificationInterface $specification): SpecificationInterface
    {
        return new AndSpecification($this, $specification);
    }

    public function or(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): SpecificationInterface
    {
        return new NotSpecification($this);
    }

    public function getCacheKey(): string
    {
        return md5(static::class.serialize($this->getParameters()));
    }

    /**
     * Override this to provide specification parameters for cache key generation
     */
    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [];
    }
}
