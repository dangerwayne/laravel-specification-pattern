<?php

namespace DangerWayne\Specification\Tests\Unit;

use DangerWayne\Specification\Specifications\Common\WhereBetweenSpecification;
use DangerWayne\Specification\Specifications\Common\WhereInSpecification;
use DangerWayne\Specification\Specifications\Common\WhereNullSpecification;
use DangerWayne\Specification\Specifications\Common\WhereSpecification;
use DangerWayne\Specification\Tests\Fixtures\User;
use DangerWayne\Specification\Tests\TestCase;
use Illuminate\Support\Collection;

class EdgeCaseTest extends TestCase
{
    public function test_handles_null_candidate(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');

        $this->assertFalse($spec->isSatisfiedBy(null));
    }

    public function test_handles_empty_collection(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');
        $collection = collect([]);

        $filtered = $collection->whereSpecification($spec);

        $this->assertCount(0, $filtered);
        $this->assertInstanceOf(Collection::class, $filtered);
    }

    public function test_where_in_with_empty_array(): void
    {
        $spec = new WhereInSpecification('status', []);
        $user = new User(['status' => 'active']);

        // Empty array should not match anything
        $this->assertFalse($spec->isSatisfiedBy($user));
    }

    public function test_where_between_with_inverted_range(): void
    {
        $spec = new WhereBetweenSpecification('age', 50, 20); // max < min
        $user = new User(['age' => 30]);

        // Should handle inverted ranges gracefully
        $this->assertFalse($spec->isSatisfiedBy($user));
    }

    public function test_handles_missing_properties(): void
    {
        $spec = new WhereSpecification('non_existent_field', '=', 'value');
        $user = new User(['status' => 'active']);

        $this->assertFalse($spec->isSatisfiedBy($user));
    }

    public function test_handles_null_values_in_comparisons(): void
    {
        $spec = new WhereSpecification('age', '>', 18);
        $user = new User(['age' => null]);

        $this->assertFalse($spec->isSatisfiedBy($user));
    }

    public function test_where_null_with_actual_null(): void
    {
        $spec = new WhereNullSpecification('email_verified_at');
        $userWithNull = new User(['email_verified_at' => null]);
        $userWithValue = new User(['email_verified_at' => now()]);

        $this->assertTrue($spec->isSatisfiedBy($userWithNull));
        $this->assertFalse($spec->isSatisfiedBy($userWithValue));
    }

    public function test_composite_specifications_with_null_candidates(): void
    {
        $spec1 = new WhereSpecification('status', '=', 'active');
        $spec2 = new WhereSpecification('role', '=', 'admin');

        $andSpec = $spec1->and($spec2);
        $orSpec = $spec1->or($spec2);
        $notSpec = $spec1->not();

        $this->assertFalse($andSpec->isSatisfiedBy(null));
        $this->assertFalse($orSpec->isSatisfiedBy(null));
        $this->assertTrue($notSpec->isSatisfiedBy(null)); // NOT of false = true
    }

    public function test_deeply_nested_specifications(): void
    {
        $spec1 = new WhereSpecification('status', '=', 'active');
        $spec2 = new WhereSpecification('role', '=', 'admin');
        $spec3 = new WhereSpecification('age', '>', 18);
        $spec4 = new WhereSpecification('email_verified_at', '!=', null);

        // Create deeply nested specification
        $nested = $spec1->and($spec2)->or($spec3->and($spec4));

        $user = new User([
            'status' => 'active',
            'role' => 'admin',
            'age' => 25,
            'email_verified_at' => now(),
        ]);

        $this->assertTrue($nested->isSatisfiedBy($user));
    }

    public function test_handles_array_candidates(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');

        $array = ['status' => 'active'];
        $this->assertTrue($spec->isSatisfiedBy($array));

        $array = ['status' => 'inactive'];
        $this->assertFalse($spec->isSatisfiedBy($array));
    }

    public function test_handles_stdclass_candidates(): void
    {
        $spec = new WhereSpecification('status', '=', 'active');

        $object = (object) ['status' => 'active'];
        $this->assertTrue($spec->isSatisfiedBy($object));

        $object = (object) ['status' => 'inactive'];
        $this->assertFalse($spec->isSatisfiedBy($object));
    }

    public function test_like_operator_with_special_characters(): void
    {
        $spec = new WhereSpecification('email', 'like', '%test@example.com%');
        $user = new User(['email' => 'user.test@example.com']);

        $this->assertTrue($spec->isSatisfiedBy($user));
    }

    public function test_case_sensitivity_in_like_operator(): void
    {
        $spec = new WhereSpecification('name', 'like', '%john%');
        $userUpper = new User(['name' => 'JOHN DOE']);
        $userLower = new User(['name' => 'john doe']);

        // PHP's stripos is case-insensitive
        $this->assertTrue($spec->isSatisfiedBy($userUpper));
        $this->assertTrue($spec->isSatisfiedBy($userLower));
    }
}
