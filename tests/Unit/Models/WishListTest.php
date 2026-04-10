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
}
