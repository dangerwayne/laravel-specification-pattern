<?php

namespace DangerWayne\Specification\Specifications\Common;

use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class WhereSpecification extends AbstractSpecification
{
    public function __construct(
        private string $field,
        private string $operator,
        private mixed $value
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        $candidateValue = data_get($candidate, $this->field);

        return match ($this->operator) {
            '=' => $candidateValue == $this->value,
            '!=' => $candidateValue != $this->value,
            '>' => $candidateValue > $this->value,
            '>=' => $candidateValue >= $this->value,
            '<' => $candidateValue < $this->value,
            '<=' => $candidateValue <= $this->value,
            'like' => str_contains(strtolower($candidateValue), strtolower(trim($this->value, '%'))),
            default => false,
        };
    }

    public function toQuery(Builder $query): Builder
    {
        $query->where($this->field, $this->operator, $this->value);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'field' => $this->field,
            'operator' => $this->operator,
            'value' => $this->value,
        ];
    }
}
