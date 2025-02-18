<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_name' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit_price' => $this->faker->numberBetween(1, 5000),
            'package_volume' => $this->faker->numberBetween(1000, 1000000),
        ];
    }
}
