<?php

namespace Database\Factories;

use App\Enums\PriorityEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'task_id' => null, // Ви можете вказати конкретний task_id, або залишити його нульовим або null
            'status' => $this->faker->randomElement(['todo', 'done']),
            'priority' => $this->faker->randomElement(PriorityEnum::toArray()),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'createdAt' => now(),
            'completedAt' => $this->faker->optional()->dateTimeThisMonth,
        ];
    }
}
