<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use App\Models\WishList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an unauthenticated user cannot view wishlists.
     */
    public function test_unauthenticated_user_cannot_view_wishlists(): void
    {
        $response = $this->getJson('/api/wishlists');

        $response->assertStatus(401);
    }

    /**
     * Test that an authenticated user can view their wishlists.
     */
    public function test_authenticated_user_can_view_their_wishlists(): void
    {
        $user = User::factory()->create();
        WishList::factory()->count(2)->for($user)->create();
        WishList::factory()->count(1)->create(); // Other user's wishlist

        $response = $this->actingAs($user)->getJson('/api/wishlists');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test that an authenticated user can add a product to their wishlist.
     */
    public function test_authenticated_user_can_add_product_to_their_wishlist(): void
    {
        $user = User::factory()->create();
        $wishlist = WishList::factory()->for($user)->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/wishlists/{$wishlist->id}/products", [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $wishlist->id);

        $this->assertDatabaseHas('product_wish_list', [
            'product_id' => $product->id,
            'wish_list_id' => $wishlist->id,
        ]);
    }

    /**
     * Test that a user cannot add a product to another user's wishlist.
     */
    public function test_user_cannot_add_product_to_others_wishlist(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $wishlist = WishList::factory()->for($otherUser)->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/wishlists/{$wishlist->id}/products", [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that an authenticated user can remove a product from their wishlist.
     */
    public function test_authenticated_user_can_remove_product_from_their_wishlist(): void
    {
        $user = User::factory()->create();
        $wishlist = WishList::factory()->for($user)->create();
        $product = Product::factory()->create();
        $wishlist->products()->attach($product);

        $response = $this->actingAs($user)->deleteJson("/api/wishlists/{$wishlist->id}/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('product_wish_list', [
            'product_id' => $product->id,
            'wish_list_id' => $wishlist->id,
        ]);
    }

    /**
     * Test that a user cannot remove a product from another user's wishlist.
     */
    public function test_user_cannot_remove_product_from_others_wishlist(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $wishlist = WishList::factory()->for($otherUser)->create();
        $product = Product::factory()->create();
        $wishlist->products()->attach($product);

        $response = $this->actingAs($user)->deleteJson("/api/wishlists/{$wishlist->id}/products/{$product->id}");

        $response->assertStatus(403);
    }
}
