<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WishList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WishList>
 */
class WishListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
        ];
    }
}
