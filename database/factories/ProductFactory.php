<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'category_id'    => null,
            'sku'            => strtoupper(fake()->unique()->lexify('SKU-??????')),
            'oem_reference'  => null,
            'name'           => ucfirst($name),
            'slug'           => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description'    => fake()->paragraph(),
            'condition'      => 'used_good',
            'price'          => fake()->randomFloat(2, 10, 500),
            'currency'       => 'EUR',
            'stock_quantity' => fake()->numberBetween(1, 20),
            'status'         => 'active',
            'main_image_path'=> null,
            'location'       => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['status' => 'inactive']);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock_quantity' => 0]);
    }
}
