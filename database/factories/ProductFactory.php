<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'category' => fake()->randomElement(['MTB', 'Fixie', 'Road']),
            'price' => fake()->numberBetween(1000000, 10000000),
            'stock' => fake()->numberBetween(10, 100),
            'image' => null, // Giả sử không có ảnh
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
