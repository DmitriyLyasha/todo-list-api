<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TaskRepository;
use App\DTO\TaskDTO;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks(array $filters): array
    {
        return $this->taskRepository->getTasks($filters)->toArray();
    }

    public function getTaskById(int $id): TaskDTO
    {
        return $this->taskRepository->getTaskById($id);
    }

    public function createTask(array $data): TaskDTO
    {
        return $this->taskRepository->createTask($data);
    }

    public function updateTask($id, array $data): TaskDTO
    {
        $task = $this->taskRepository->getTaskById($id);

        $this->authorizeTaskAccess($task);

        $updatedTask = $this->taskRepository->updateTask($id, $data);

        return $updatedTask;
    }

    public function deleteTask(int $id): void
    {
        $task = $this->taskRepository->getTaskById($id);
        $this->authorizeTaskAccess($task);

        $this->taskRepository->deleteTask($id);
    }

    public function markTaskAsDone($id): TaskDTO
    {
        $task = $this->taskRepository->getTaskById($id);

        $this->authorizeTaskAccess($task);

        $this->checkSubtasksCompleted($task);

        $this->taskRepository->markTaskAsDone($id);

        return $task;
    }

    protected function authorizeTaskAccess(TaskDTO $task): void
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Access denied. You are not allowed to perform this action.');
        }
    }

    protected function checkSubtasksCompleted(TaskDTO $task): void
    {
        if ($task->hasUncompletedSubtasks()) {
            abort(403, 'Task cannot be marked as done while it has uncompleted subtasks.');
        }
    }

    public function buildTaskTree($tasks, $parentId = null): array
    {
        $branch = [];

        foreach ($tasks as $task) {
            if ($task['parent_id'] === $parentId) {
                $children = $this->buildTaskTree($tasks, $task['id']);
                if ($children) {
                    $task['subtasks'] = $children;
                }
                $branch[] = $task;
            }
        }

        return $branch;
    }

    public function getTasksAsTree(array $filters): array
    {
        $tasks = $this->taskRepository->getTasks($filters);

        return $this->buildTaskTree($tasks);
    }

    public function printTaskTree($tasks): void
    {
        foreach ($tasks as $task) {
            $this->printTaskWithIndent($task, 0);
        }
    }

    private function printTaskWithIndent(array $task, int $indent): void
    {
        echo str_repeat('  ', $indent) . "- {$task->title}\n";

        foreach ($task->subtasks as $subtask) {
            $this->printTaskWithIndent($subtask, $indent + 1);
        }
    }
}
