<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * Get the price in dollars, but store it in cents.
     *
     * @return Attribute<float, int>
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value / 100,
            set: fn (float $value) => (int) round($value * 100),
        );
    }

    /**
     * Get the wishlists that contain this product.
     *
     * @return BelongsToMany<WishList, Product>
     */
    public function wishLists(): BelongsToMany
    {
        return $this->belongsToMany(WishList::class);
    }
}
