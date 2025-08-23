<?php

namespace DangerWayne\Specification\Specifications\Common;

use DangerWayne\Specification\Specifications\AbstractSpecification;
use Illuminate\Database\Eloquent\Builder;

class WhereNullSpecification extends AbstractSpecification
{
    public function __construct(
        private string $field
    ) {}

    public function isSatisfiedBy(mixed $candidate): bool
    {
        $candidateValue = data_get($candidate, $this->field);

        return $candidateValue === null;
    }

    public function toQuery(Builder $query): Builder
    {
        $query->whereNull($this->field);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getParameters(): array
    {
        return [
            'field' => $this->field,
        ];
    }
}
