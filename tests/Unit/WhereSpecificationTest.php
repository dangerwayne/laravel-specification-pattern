<?php

namespace DangerWayne\Specification\Tests\Unit;

use DangerWayne\Specification\Specifications\Common\WhereSpecification;
use DangerWayne\Specification\Tests\Fixtures\User;
use DangerWayne\Specification\Tests\TestCase;

class WhereSpecificationTest extends TestCase
{
    public function test_it_satisfies_equal_condition(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');
        $activeUser = new User(['status' => 'active']);
        $inactiveUser = new User(['status' => 'inactive']);

        $this->assertTrue($spec->isSatisfiedBy($activeUser));
        $this->assertFalse($spec->isSatisfiedBy($inactiveUser));
    }

    public function test_it_builds_query_correctly(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');
        $query = User::query();

        $result = $spec->toQuery($query);

        $this->assertStringContainsString('where "status" = ?', $result->toSql());
        $this->assertEquals(['active'], $result->getBindings());
    }

    public function test_composite_specifications_work(): void
    {
        $activeSpec = new WhereSpecification('status', '=', 'active');
        $adminSpec = new WhereSpecification('role', '=', 'admin');

        $compositeSpec = $activeSpec->and($adminSpec);

        $user = new User(['status' => 'active', 'role' => 'admin']);
        $this->assertTrue($compositeSpec->isSatisfiedBy($user));

        $user = new User(['status' => 'active', 'role' => 'user']);
        $this->assertFalse($compositeSpec->isSatisfiedBy($user));
    }

    public function test_handles_greater_than_operator(): void
    {
        $spec = new WhereSpecification('age', '>', 18);
        $youngUser = new User(['age' => 16]);
        $oldUser = new User(['age' => 25]);

        $this->assertFalse($spec->isSatisfiedBy($youngUser));
        $this->assertTrue($spec->isSatisfiedBy($oldUser));
    }

    public function test_handles_like_operator(): void
    {
        $spec = new WhereSpecification('name', 'like', '%john%');
        $johnUser = new User(['name' => 'John Doe']);
        $janeUser = new User(['name' => 'Jane Smith']);

        $this->assertTrue($spec->isSatisfiedBy($johnUser));
        $this->assertFalse($spec->isSatisfiedBy($janeUser));
    }

    public function test_generates_cache_key(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');
        $cacheKey = $spec->getCacheKey();

        $this->assertIsString($cacheKey);
        $this->assertEquals(32, strlen($cacheKey)); // MD5 length

        // Same spec should generate same cache key
        $spec2 = new WhereSpecification('status', '=', 'active');
        $this->assertEquals($cacheKey, $spec2->getCacheKey());

        // Different spec should generate different cache key
        $spec3 = new WhereSpecification('status', '=', 'inactive');
        $this->assertNotEquals($cacheKey, $spec3->getCacheKey());
    }
}
