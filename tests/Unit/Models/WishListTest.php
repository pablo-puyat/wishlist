<?php

namespace Tests\Unit\Models;

use App\Http\Controllers\WishListController;
use App\Http\Requests\AddProductToWishListRequest;
use App\Http\Resources\WishListResource;
use App\Models\Product;
use App\Models\User;
use App\Models\WishList;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;

class WishListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the wishlist has the correct fillable attributes.
     */
    public function test_wishlist_has_correct_fillable_attributes(): void
    {
        $model = new WishList();

        $this->assertEquals([
            'user_id',
            'name',
        ], $model->getFillable());
    }

    /**
     * Test that the wishlist belongs to a user.
     */
    public function test_wishlist_belongs_to_a_user(): void
    {
        $wishlist = new WishList();

        $this->assertInstanceOf(BelongsTo::class, $wishlist->user());
        $this->assertInstanceOf(User::class, $wishlist->user()->getRelated());
    }

    /**
     * Test that the wishlist has many products.
     */
    public function test_wishlist_belongs_to_many_products(): void
    {
        $wishlist = new WishList();

        $this->assertInstanceOf(BelongsToMany::class, $wishlist->products());
        $this->assertInstanceOf(Product::class, $wishlist->products()->getRelated());
    }

    /**
     * Test that the wishlist controller index returns the user's wishlists.
     */
    public function test_wishlist_controller_index_returns_user_wishlists(): void
    {
        $user = User::factory()->create();
        WishList::factory()->count(2)->for($user)->create();
        WishList::factory()->count(1)->create(); // Other user's wishlist

        $request = new Request();
        $request->setUserResolver(fn () => $user);

        $controller = new WishListController();
        $response = $controller->index($request);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertCount(2, $response);
    }

    /**
     * Test that the wishlist controller addProduct attaches a product.
     */
    public function test_wishlist_controller_add_product_attaches_product(): void
    {
        $user = User::factory()->create();
        $wishlist = WishList::factory()->for($user)->create();
        $product = Product::factory()->create();

        $request = AddProductToWishListRequest::create("/api/wishlists/{$wishlist->id}/products", 'POST', [
            'product_id' => $product->id,
        ]);
        $request->setUserResolver(fn () => $user);
        
        // Mock route parameter for authorization
        $request->setRouteResolver(fn () => new class($wishlist) {
            public function __construct(public $wishlist) {}
            public function parameter($name) { return $name === 'wish_list' ? $this->wishlist : null; }
        });

        $controller = new WishListController();
        $response = $controller->addProduct($request, $wishlist);

        $this->assertInstanceOf(WishListResource::class, $response);
        $this->assertTrue($wishlist->products->contains($product));
    }

    /**
     * Test that the wishlist controller removeProduct detaches a product.
     */
    public function test_wishlist_controller_remove_product_detaches_product(): void
    {
        $user = User::factory()->create();
        $wishlist = WishList::factory()->for($user)->create();
        $product = Product::factory()->create();
        $wishlist->products()->attach($product);

        $this->actingAs($user);

        $request = new Request();
        $request->setUserResolver(fn () => $user);

        $controller = new WishListController();
        $response = $controller->removeProduct($request, $wishlist, $product->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertFalse($wishlist->fresh()->products->contains($product));
    }
}
