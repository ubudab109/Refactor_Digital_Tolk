<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

/*
    I assume the laravel project is already setup, so here is the example for creating unit test using PHP UNIT
 */

class UserRepositoryTest extends TestCase
{
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
        // Other setup code for the database if needed

        $this->userRepository = new UserRepository();
    }

    protected function tearDown(): void
    {
        // Other teardown code for your database if needed
        Artisan::call('migrate:rollback');

        parent::tearDown();
    }

    public function testCreateOrUpdateWithNewUser()
    {
        $request = [
            'role' => 'customer',
            'name' => 'John Doe',
            'company_id' => '123',
            'department_id' => '456',
            'email' => 'john@example.com',
            'dob_or_orgid' => '1990-01-01',
            'phone' => '123-456-7890',
            'mobile' => '987-654-3210',
            'password' => 'secret123',
            'consumer_type' => 'paid',
            'new_towns' => 'New Town 1',
            'user_towns_projects' => [1, 2, 3], // Example array of town IDs
            'status' => '1',
        ];

        $result = $this->userRepository->createOrUpdate(null, $request);

        $this->assertInstanceOf(User::class, $result);
        $this->assertDatabaseHas('users', ['id' => $result->id]);
        // Add more assertions based on your expectations
    }

    public function testCreateOrUpdateWithExistingUser()
    {
        // Create a user in the database for testing
        $existingUser = factory(User::class)->create();

        $request = [
            'role' => 'customer',
            'name' => 'John Doe',
            'company_id' => '123',
            'department_id' => '456',
            'email' => 'john@example.com',
            'dob_or_orgid' => '1990-01-01',
            'phone' => '123-456-7890',
            'mobile' => '987-654-3210',
            'password' => 'secret123',
            'consumer_type' => 'paid',
            'new_towns' => 'New Town 1',
            'user_towns_projects' => [1, 2, 3], // Example array of town IDs
            'status' => '1',
        ];

        $result = $this->userRepository->createOrUpdate($existingUser->id, $request);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($existingUser->id, $result->id);
        $this->assertDatabaseHas('users', ['id' => $result->id]);
    }
}
