<?php

namespace DangerWayne\Specification\Tests\Unit;

use DangerWayne\Specification\Specifications\Common\WhereSpecification;
use DangerWayne\Specification\Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class CacheableSpecificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_cache_key_generation_is_unique(): void
    {
        $spec1 = new WhereSpecification('name', '=', 'john');
        $spec2 = new WhereSpecification('name', '=', 'jane');
        $spec3 = new WhereSpecification('age', '=', 'john');

        $this->assertNotEquals($spec1->getCacheKey(), $spec2->getCacheKey());
        $this->assertNotEquals($spec1->getCacheKey(), $spec3->getCacheKey());
        $this->assertNotEquals($spec2->getCacheKey(), $spec3->getCacheKey());
    }

    public function test_cache_key_avoids_collisions(): void
    {
        // Test the previous collision scenario
        $spec1 = new WhereSpecification('name', '=', 'john1');
        $spec2 = new WhereSpecification('nam', '=', 'ejohn1');

        $this->assertNotEquals($spec1->getCacheKey(), $spec2->getCacheKey());
    }

    public function test_cache_key_is_consistent(): void
    {
        $spec1 = new WhereSpecification('status', '=', 'active');
        $spec2 = new WhereSpecification('status', '=', 'active');

        $this->assertEquals($spec1->getCacheKey(), $spec2->getCacheKey());
    }

    public function test_composite_specifications_have_unique_cache_keys(): void
    {
        $spec1 = new WhereSpecification('status', '=', 'active');
        $spec2 = new WhereSpecification('role', '=', 'admin');

        $andSpec = $spec1->and($spec2);
        $orSpec = $spec1->or($spec2);
        $notSpec = $spec1->not();

        $keys = [
            $spec1->getCacheKey(),
            $spec2->getCacheKey(),
            $andSpec->getCacheKey(),
            $orSpec->getCacheKey(),
            $notSpec->getCacheKey(),
        ];

        // All keys should be unique
        $this->assertEquals(count($keys), count(array_unique($keys)));
    }

    public function test_cache_key_handles_complex_parameters(): void
    {
        $spec1 = new WhereSpecification('data', '=', ['nested' => ['value' => 'test']]);
        $spec2 = new WhereSpecification('data', '=', ['nested' => ['value' => 'test2']]);

        $this->assertNotEquals($spec1->getCacheKey(), $spec2->getCacheKey());
    }
}
