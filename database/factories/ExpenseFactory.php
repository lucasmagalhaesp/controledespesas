<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "description"   => fake()->text(100),
            "date"          => fake()->date("Y-m-d"),
            "user_id"       => User::first()->id,
            "value"         => fake()->randomFloat(2, 0, 5000)
        ];
    }
}
