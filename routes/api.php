<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', ProductController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlists', [WishListController::class, 'index']);
    Route::post('/wishlists/{wish_list}/products', [WishListController::class, 'addProduct']);
    Route::delete('/wishlists/{wish_list}/products/{product}', [WishListController::class, 'removeProduct']);
});
