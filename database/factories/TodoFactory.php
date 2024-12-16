<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'details' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
