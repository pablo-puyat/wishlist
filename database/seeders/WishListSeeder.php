<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\WishList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        WishList::factory()
            ->count(10)
            ->create()
            ->each(function (WishList $wishList) use ($products) {
                $wishList->products()->attach(
                    $products->random(rand(1, 5))->pluck('id')->toArray()
                );
            });
    }
}
