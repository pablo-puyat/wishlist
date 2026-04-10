<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an unauthenticated user cannot view products.
     */
    public function test_unauthenticated_user_cannot_view_products(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(401);
    }

    /**
     * Test that an authenticated user can view products.
     */
    public function test_authenticated_user_can_view_products(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'price', 'created_at', 'updated_at'],
                ],
            ]);
    }
}
