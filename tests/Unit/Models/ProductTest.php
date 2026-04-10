<?php

namespace Tests\Unit\Models;

use App\Http\Controllers\ProductController;
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
        $model = new Product();

        $this->assertEquals([
            'name',
            'description',
            'price',
        ], $model->getFillable());
    }

    /**
     * Test that the product attributes are cast to the correct types.
     */
    public function test_product_has_correct_casts(): void
    {
        $model = new Product();

        // Laravel 11+ uses casts() method, but we can check the resolved casts
        $this->assertEquals('decimal:2', $model->getCasts()['price'] ?? null);
    }

    /**
     * Test that the product controller index returns all products.
     */
    public function test_product_controller_index_returns_all_products(): void
    {
        Product::factory()->count(3)->create();

        $controller = new ProductController();
        $response = $controller->index();

        $this->assertCount(3, $response);
    }
}
