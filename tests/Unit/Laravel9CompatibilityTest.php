<?php

namespace DangerWayne\Specification\Tests\Unit;

use DangerWayne\Specification\Specifications\Common\WhereSpecification;
use DangerWayne\Specification\Tests\Fixtures\User;
use DangerWayne\Specification\Tests\TestCase;

class Laravel9CompatibilityTest extends TestCase
{
    public function test_not_specification_works_with_method_detection(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $notSpec = $activeSpec->not();

        // Test in-memory filtering (should work regardless of Laravel version)
        $activeUser = new User(['status' => 'active']);
        $inactiveUser = new User(['status' => 'inactive']);

        $this->assertFalse($notSpec->isSatisfiedBy($activeUser));
        $this->assertTrue($notSpec->isSatisfiedBy($inactiveUser));
    }

    public function test_not_specification_query_builds_without_error(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $notSpec = $activeSpec->not();

        // Test that query building doesn't throw errors
        $query = User::query();
        $result = $notSpec->toQuery($query);

        $this->assertNotNull($result);
        $this->assertIsString($result->toSql());
    }
}
