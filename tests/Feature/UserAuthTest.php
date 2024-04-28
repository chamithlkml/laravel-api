<?php

namespace Tests\Feature;

use Tests\TestCase;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class UserAuthTest extends TestCase
{

    /**
     * Test POST /api/auth/register
     */
    public function test_the_application_can_register_new_user(): void
    {
        $faker = Faker::create();
        $email = $faker->email;
        $response = $this->post('/api/auth/register', [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $email,
            'password' => $faker->password
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => true,
            'auth_token' => true
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $email
        ]);
    }

    /**
     * Expect Validation Error response
     *
     * @return void
     */
    public function test_application_validates_auth_register(): void
    {
        $faker = Faker::create();
        $response = $this->post('/api/auth/register', [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->email
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'password' => true
            ]
        ]);
    }

    /**
     * Returns auth token
     *
     * @return void
     */
    public function test_application_logins(): void
    {
        $faker = Faker::create();
        $email = $faker->email;
        $password = $faker->password;
        
        $response = $this->post('/api/auth/register', [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $email,
            'password' => $password
        ]);
        $response->assertStatus(200);

        $loginResponse = $this->post('/api/auth/login', [
            'email' => $email,
            'password' => $password
        ]);
        $loginResponse->assertStatus(200);
        $loginResponse->assertJson([
            'id' => true,
            'auth_token' => true
        ]);
    }
}
