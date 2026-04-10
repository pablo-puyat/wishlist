<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  ProductRequest  $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(ProductRequest $request): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::all());
    }
}
