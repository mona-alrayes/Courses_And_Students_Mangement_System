<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'experience' => $this->faker->numberBetween(1, 30), // Years of experience between 1 and 30
            'specialty' => $this->faker->words(3, true), // Generates a more focused specialty with three words

        ];
    }
}
