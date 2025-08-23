<?php

namespace DangerWayne\Specification\Specifications\Builders;

use DangerWayne\Specification\Contracts\SpecificationInterface;
use DangerWayne\Specification\Specifications\Common\WhereBetweenSpecification;
use DangerWayne\Specification\Specifications\Common\WhereHasSpecification;
use DangerWayne\Specification\Specifications\Common\WhereInSpecification;
use DangerWayne\Specification\Specifications\Common\WhereNullSpecification;
use DangerWayne\Specification\Specifications\Common\WhereSpecification;

class SpecificationBuilder
{
    private ?SpecificationInterface $specification = null;

    private ?string $nextOperator = null;

    public static function create(): self
    {
        return new self;
    }

    public function where(string $field, mixed $operator, mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $spec = new WhereSpecification($field, $operator, $value);

        return $this->addSpecification($spec);
    }

    /**
     * @param  array<mixed>  $values
     */
    public function whereIn(string $field, array $values): self
    {
        return $this->addSpecification(new WhereInSpecification($field, $values));
    }

    public function whereBetween(string $field, mixed $min, mixed $max): self
    {
        return $this->addSpecification(new WhereBetweenSpecification($field, $min, $max));
    }

    public function whereNull(string $field): self
    {
        return $this->addSpecification(new WhereNullSpecification($field));
    }

    public function whereNotNull(string $field): self
    {
        return $this->addSpecification((new WhereNullSpecification($field))->not());
    }

    public function whereHas(string $relation, SpecificationInterface $specification): self
    {
        return $this->addSpecification(new WhereHasSpecification($relation, $specification));
    }

    public function or(): self
    {
        $this->nextOperator = 'or';

        return $this;
    }

    public function build(): SpecificationInterface
    {
        if (! $this->specification) {
            throw new \LogicException('Cannot build empty specification');
        }

        return $this->specification;
    }

    private function addSpecification(SpecificationInterface $spec): self
    {
        if (! $this->specification) {
            $this->specification = $spec;
        } else {
            $this->specification = isset($this->nextOperator) && $this->nextOperator === 'or'
                ? $this->specification->or($spec)
                : $this->specification->and($spec);
            unset($this->nextOperator);
        }

        return $this;
    }
}
