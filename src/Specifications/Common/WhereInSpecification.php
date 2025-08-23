<?php

namespace DangerWayne\Specification\Specifications\Common;

use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class WhereInSpecification extends AbstractSpecification
{
    /**
     * @param  array<mixed>  $values
     */
    public function __construct(
        private string $field,
        private array $values
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        $candidateValue = data_get($candidate, $this->field);

        return in_array($candidateValue, $this->values, true);
    }

    public function toQuery(Builder $query): Builder
    {
        $query->whereIn($this->field, $this->values);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'field' => $this->field,
            'values' => $this->values,
        ];
    }
}
