<?php

namespace DangerWayne\Specification\Specifications\Common;

use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class WhereBetweenSpecification extends AbstractSpecification
{
    public function __construct(
        private string $field,
        private mixed $min,
        private mixed $max
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        $candidateValue = data_get($candidate, $this->field);

        return $candidateValue >= $this->min && $candidateValue <= $this->max;
    }

    public function toQuery(Builder $query): Builder
    {
        $query->whereBetween($this->field, [$this->min, $this->max]);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'field' => $this->field,
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}
