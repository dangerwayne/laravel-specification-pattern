<?php

namespace DangerWayne\Specification\Tests\Unit;

use DangerWayne\Specification\Specifications\Builders\SpecificationBuilder;
use DangerWayne\Specification\Tests\Fixtures\User;
use DangerWayne\Specification\Tests\TestCase;

class SpecificationBuilderTest extends TestCase
{
    public function test_can_create_builder(): void
    {
        $builder = SpecificationBuilder::create();
        $this->assertInstanceOf(SpecificationBuilder::class, $builder);
    }

    public function test_can_build_simple_where_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->where('status', '=', 'active')
            ->build();

        $activeUser = new User(['status' => 'active']);
        $inactiveUser = new User(['status' => 'inactive']);

        $this->assertTrue($spec->isSatisfiedBy($activeUser));
        $this->assertFalse($spec->isSatisfiedBy($inactiveUser));
    }

    public function test_can_build_where_with_two_arguments(): void
    {
        $spec = SpecificationBuilder::create()
            ->where('status', 'active')
            ->build();

        $activeUser = new User(['status' => 'active']);
        $this->assertTrue($spec->isSatisfiedBy($activeUser));
    }

    public function test_can_build_compound_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->where('status', 'active')
            ->where('role', 'admin')
            ->build();

        $activeAdmin = new User(['status' => 'active', 'role' => 'admin']);
        $activeUser = new User(['status' => 'active', 'role' => 'user']);

        $this->assertTrue($spec->isSatisfiedBy($activeAdmin));
        $this->assertFalse($spec->isSatisfiedBy($activeUser));
    }

    public function test_can_build_or_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->where('status', 'active')
            ->or()
            ->where('role', 'admin')
            ->build();

        $activeUser = new User(['status' => 'active', 'role' => 'user']);
        $inactiveAdmin = new User(['status' => 'inactive', 'role' => 'admin']);
        $inactiveUser = new User(['status' => 'inactive', 'role' => 'user']);

        $this->assertTrue($spec->isSatisfiedBy($activeUser));
        $this->assertTrue($spec->isSatisfiedBy($inactiveAdmin));
        $this->assertFalse($spec->isSatisfiedBy($inactiveUser));
    }

    public function test_can_build_where_in_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->whereIn('status', ['active', 'pending'])
            ->build();

        $activeUser = new User(['status' => 'active']);
        $pendingUser = new User(['status' => 'pending']);
        $inactiveUser = new User(['status' => 'inactive']);

        $this->assertTrue($spec->isSatisfiedBy($activeUser));
        $this->assertTrue($spec->isSatisfiedBy($pendingUser));
        $this->assertFalse($spec->isSatisfiedBy($inactiveUser));
    }

    public function test_can_build_where_between_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->whereBetween('age', 18, 65)
            ->build();

        $youngUser = new User(['age' => 25]);
        $oldUser = new User(['age' => 16]);

        $this->assertTrue($spec->isSatisfiedBy($youngUser));
        $this->assertFalse($spec->isSatisfiedBy($oldUser));
    }

    public function test_can_build_where_null_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->whereNull('email_verified_at')
            ->build();

        $unverifiedUser = new User(['email_verified_at' => null]);
        $verifiedUser = new User(['email_verified_at' => now()]);

        $this->assertTrue($spec->isSatisfiedBy($unverifiedUser));
        $this->assertFalse($spec->isSatisfiedBy($verifiedUser));
    }

    public function test_can_build_where_not_null_specification(): void
    {
        $spec = SpecificationBuilder::create()
            ->whereNotNull('email_verified_at')
            ->build();

        $verifiedUser = new User(['email_verified_at' => now()]);
        $unverifiedUser = new User(['email_verified_at' => null]);

        $this->assertTrue($spec->isSatisfiedBy($verifiedUser));
        $this->assertFalse($spec->isSatisfiedBy($unverifiedUser));
    }

    public function test_throws_exception_when_building_empty_specification(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot build empty specification');

        SpecificationBuilder::create()->build();
    }
}
