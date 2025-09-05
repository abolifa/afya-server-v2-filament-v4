<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('091#######'),
            'password' => static::$password ??= Hash::make('091091'),
            'active' => true,
            'remember_token' => Str::random(10),
            'doctor' => fake()->boolean(),
            'see_activities' => true,
            'see_all_stock' => true,
            'see_all_center' => true,
            'access_patient' => true,
            'access_site' => true,
            'access_archive' => true,
        ];
    }
}
