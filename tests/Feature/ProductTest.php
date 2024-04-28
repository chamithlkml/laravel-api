<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class ProductTest extends TestCase
{
    public function test_post_products()
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('access-token-' . $user->id)->plainTextToken;
        $faker = Faker::create();
        $productData = [
            'name' => $faker->name,
            'description' => $faker->name,
            'price' => $faker->randomFloat(),
            'slug' => $faker->slug
        ];

        $response = $this->withHeaders([
            'Accept' => 'Application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('/api/products', $productData);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => true
        ]);
    }
}