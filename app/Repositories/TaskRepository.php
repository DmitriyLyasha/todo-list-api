<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getTasks(array $filters): Collection
    {
        $query = Task::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%$searchTerm%")
                    ->orWhere('description', 'LIKE', "%$searchTerm%");
            });
        }

        if (isset($filters['sort'])) {
            [$sortBy, $sortOrder] = explode(' ', $filters['sort']);

            if (in_array($sortBy, ['createdAt', 'completedAt', 'priority'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        return $query->get();
    }

    public function getTaskById(int $id): TaskDTO
    {
        $taskModel = Task::findOrFail($id);

        return TaskDTO::fromArray($taskModel->toArray());
    }

    public function createTask(array $data): TaskDTO
    {
        $createdTask = Task::create($data);

        return TaskDTO::fromArray($createdTask->toArray());
    }

    public function updateTask(TaskDTO $taskDTO, array $data): TaskDTO
    {
        $task = Task::find($taskDTO->getId());

        if (!$task) {
            throw new \InvalidArgumentException('Task not found');
        }

        $task->update($data);

        // Перетворення моделі на DTO
        return TaskDTO::fromArray($task->fresh()->toArray());
    }

    public function deleteTask(TaskDTO $taskDTO): void
    {
        $task = Task::find($taskDTO->getId());

        if (!$task) {
            throw new \InvalidArgumentException('Task not found');
        }

        $task->delete();
    }

    public function markTaskAsDone(TaskDTO $taskDTO): Task
    {
        $task = Task::find($taskDTO->getId());

        if (!$task) {
            throw new \InvalidArgumentException('Task not found');
        }

        if ($task->hasUncompletedSubtasks()) {
            throw new \InvalidArgumentException('Task cannot be marked as done while it has uncompleted subtasks.');
        }

        $task->update(['status' => 'done', 'completed_at' => now()]);

        return $task->fresh();
    }
}
