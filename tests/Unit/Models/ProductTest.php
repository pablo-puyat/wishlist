<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the product has the correct fillable attributes.
     */
    public function test_product_has_correct_fillable_attributes(): void
    {
        $model = new Product;

        $this->assertEquals([
            'name',
            'description',
            'price',
        ], $model->getFillable());
    }

    /**
     * Test that the product price attribute converts between dollars and cents.
     */
    public function test_product_price_converts_between_dollars_and_cents(): void
    {
        $product = new Product;

        // Test setter (dollars to cents)
        $product->price = 19.99;
        $this->assertEquals(1999, $product->getAttributes()['price']);

        // Test getter (cents to dollars)
        $product->setRawAttributes(['price' => 2995]);
        $this->assertEquals(29.95, $product->price);
    }
}
