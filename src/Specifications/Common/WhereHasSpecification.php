<?php

namespace DangerWayne\Specification\Specifications\Common;

use DangerWayne\Specification\Contracts\SpecificationInterface;
use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class WhereHasSpecification extends AbstractSpecification
{
    public function __construct(
        private string $relation,
        private SpecificationInterface $specification
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        $relationValue = data_get($candidate, $this->relation);

        if ($relationValue === null) {
            return false;
        }

        // Handle collections
        if (is_iterable($relationValue)) {
            foreach ($relationValue as $item) {
                if ($this->specification->isSatisfiedBy($item)) {
                    return true;
                }
            }

            return false;
        }

        // Handle single relation
        return $this->specification->isSatisfiedBy($relationValue);
    }

    public function toQuery(Builder $query): Builder
    {
        return $query->whereHas($this->relation, function ($query) {
            $this->specification->toQuery($query);
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'relation' => $this->relation,
            'specification' => $this->specification->getCacheKey(),
        ];
    }
}
