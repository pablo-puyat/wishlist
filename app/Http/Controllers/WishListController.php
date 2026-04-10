<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductToWishListRequest;
use App\Http\Resources\WishListResource;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class WishListController extends Controller
{
    /**
     * Display a listing of the authenticated user's wishlists.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $wishLists = $request->user()
            ->wishLists()
            ->with('products')
            ->get();

        return WishListResource::collection($wishLists);
    }

    /**
     * Add a product to the specified wishlist.
     */
    public function addProduct(AddProductToWishListRequest $request, WishList $wishList): WishListResource
    {
        $wishList->products()->syncWithoutDetaching([$request->input('product_id')]);

        return new WishListResource($wishList->load('products'));
    }

    /**
     * Remove a product from the specified wishlist.
     */
    public function removeProduct(Request $request, WishList $wishList, int $productId): Response
    {
        $this->authorize('update', $wishList);

        $wishList->products()->detach($productId);

        return response()->noContent();
    }
}
