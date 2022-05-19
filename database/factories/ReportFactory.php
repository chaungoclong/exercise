<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Report;
use App\Models\Project;
use App\Models\Position;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $workingTypes = [
            Report::WORKING_TYPE_OFFSITE,
            Report::WORKING_TYPE_ONSITE,
            Report::WORKING_TYPE_REMOTE,
            Report::WORKING_TYPE_OFF,
        ];

        $statuses = [
            Report::STATUS_PENDING,
            Report::STATUS_APPROVED
        ];

        return [
            'project_id' => Project::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'position_id' => Position::inRandomOrder()->first()->id,
            'working_type' => Arr::random($workingTypes),
            'working_time' => 8,
            'note' => Str::random(),
            'date' => $this->faker->dateTimeThisMonth(),
            'status' => Arr::random($statuses)
        ];
    }
}
