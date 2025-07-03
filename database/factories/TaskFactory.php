<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->sentence(3),
            'descricao' => $this->faker->optional()->paragraph(),
            'finalizado' => $this->faker->boolean(30), // 30% das tarefas já finalizadas
            'data_limite' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
