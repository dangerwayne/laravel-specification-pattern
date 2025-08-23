<?php

namespace DangerWayne\Specification\Specifications\Composites;

use DangerWayne\Specification\Contracts\SpecificationInterface;
use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class AndSpecification extends AbstractSpecification
{
    public function __construct(
        private SpecificationInterface $left,
        private SpecificationInterface $right
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate)
            && $this->right->isSatisfiedBy($candidate);
    }

    public function toQuery(Builder $query): Builder
    {
        return $this->right->toQuery($this->left->toQuery($query));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'left' => $this->left->getCacheKey(),
            'right' => $this->right->getCacheKey(),
        ];
    }
}
