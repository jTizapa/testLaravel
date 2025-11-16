<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word().'-plan',
            'duration_days' => $this->faker->randomElement([30, 60, 90]),
            'price' => $this->faker->randomFloat(2, 20, 300),
            'active' => true,
        ];
    }
}
