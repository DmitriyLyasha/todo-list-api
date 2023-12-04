<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Models\Task;

class TaskRepository
{
    public function getTasks(array $filters)
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

    public function getTaskById($id)
    {
        return Task::findOrFail($id);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask(TaskDTO $taskDTO, array $data)
    {
        $task = Task::find($taskDTO->getId());

        if (!$task) {
            throw new \InvalidArgumentException('Task not found');
        }

        $task->update($data);

        return $task->fresh();
    }

    public function deleteTask(TaskDTO $taskDTO)
    {
        $task = Task::find($taskDTO->getId());

        if (!$task) {
            throw new \InvalidArgumentException('Task not found');
        }

        $task->delete();
    }

    public function markTaskAsDone(TaskDTO $taskDTO)
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
