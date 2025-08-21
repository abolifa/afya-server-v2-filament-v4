<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->boolean(70) ? $this->faker->unique()->safeEmail() : null,
            'phone' => $this->faker->unique()->numerify('09#########'),
            'address' => $this->faker->optional()->address(),
        ];
    }
}
