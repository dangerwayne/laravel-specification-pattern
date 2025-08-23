<?php

namespace DangerWayne\Specification\Tests\Unit;

use DangerWayne\Specification\Specifications\Common\WhereSpecification;
use DangerWayne\Specification\Specifications\Composites\AndSpecification;
use DangerWayne\Specification\Specifications\Composites\NotSpecification;
use DangerWayne\Specification\Specifications\Composites\OrSpecification;
use DangerWayne\Specification\Tests\Fixtures\User;
use DangerWayne\Specification\Tests\TestCase;

class CompositeSpecificationTest extends TestCase
{
    public function test_and_specification_works(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $adminSpec = new WhereSpecification('role', '=', 'admin');
        $andSpec = new AndSpecification($activeSpec, $adminSpec);

        $activeAdmin = new User(['status' => 'active', 'role' => 'admin']);
        $activeUser = new User(['status' => 'active', 'role' => 'user']);
        $inactiveAdmin = new User(['status' => 'inactive', 'role' => 'admin']);

        $this->assertTrue($andSpec->isSatisfiedBy($activeAdmin));
        $this->assertFalse($andSpec->isSatisfiedBy($activeUser));
        $this->assertFalse($andSpec->isSatisfiedBy($inactiveAdmin));
    }

    public function test_or_specification_works(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $adminSpec = new WhereSpecification('role', '=', 'admin');
        $orSpec = new OrSpecification($activeSpec, $adminSpec);

        $activeUser = new User(['status' => 'active', 'role' => 'user']);
        $inactiveAdmin = new User(['status' => 'inactive', 'role' => 'admin']);
        $inactiveUser = new User(['status' => 'inactive', 'role' => 'user']);

        $this->assertTrue($orSpec->isSatisfiedBy($activeUser));
        $this->assertTrue($orSpec->isSatisfiedBy($inactiveAdmin));
        $this->assertFalse($orSpec->isSatisfiedBy($inactiveUser));
    }

    public function test_not_specification_works(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $notSpec = new NotSpecification($activeSpec);

        $activeUser = new User(['status' => 'active']);
        $inactiveUser = new User(['status' => 'inactive']);

        $this->assertFalse($notSpec->isSatisfiedBy($activeUser));
        $this->assertTrue($notSpec->isSatisfiedBy($inactiveUser));
    }

    public function test_fluent_and_method(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $adminSpec = new WhereSpecification('role', '=', 'admin');
        $compositeSpec = $activeSpec->and($adminSpec);

        $this->assertInstanceOf(AndSpecification::class, $compositeSpec);

        $activeAdmin = new User(['status' => 'active', 'role' => 'admin']);
        $this->assertTrue($compositeSpec->isSatisfiedBy($activeAdmin));
    }

    public function test_fluent_or_method(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $adminSpec = new WhereSpecification('role', '=', 'admin');
        $compositeSpec = $activeSpec->or($adminSpec);

        $this->assertInstanceOf(OrSpecification::class, $compositeSpec);

        $activeUser = new User(['status' => 'active', 'role' => 'user']);
        $this->assertTrue($compositeSpec->isSatisfiedBy($activeUser));
    }

    public function test_fluent_not_method(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $notSpec = $activeSpec->not();

        $this->assertInstanceOf(NotSpecification::class, $notSpec);

        $inactiveUser = new User(['status' => 'inactive']);
        $this->assertTrue($notSpec->isSatisfiedBy($inactiveUser));
    }
}
