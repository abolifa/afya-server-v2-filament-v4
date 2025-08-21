<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Center;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'center_id' => Center::factory(),
            'patient_id' => Patient::factory(),
            'doctor_id' => User::factory()->state(['doctor' => true])->create()->id,
            'date' => fake()->date(),
            'time' => fake()->time(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'intended' => fake()->boolean(),
            'notes' => fake()->optional()->text(),
            'start_time' => fake()->optional()->time(),
            'end_time' => fake()->optional()->time(),
        ];
    }
}
