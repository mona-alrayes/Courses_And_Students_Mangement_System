<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(3), // Generates a more descriptive course title
            'description' => $this->faker->unique()->paragraphs(3, true), // Provides a richer course description
            'start_date' => $this->faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'), // Start date between last year and next year
        ];
    }
}
