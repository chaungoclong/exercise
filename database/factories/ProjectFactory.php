<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->numerify('Project###'),
            'slug' => $this->faker->numerify('project_###'),
            'duration' => $this->faker->randomNumber(3),
            'revenue' => $this->faker->randomFloat(1)
        ];
    }
}
