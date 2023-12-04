<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TaskRepository;
use App\DTO\TaskDTO;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks(array $filters)
    {
        return $this->taskRepository->getTasks($filters);
    }

    public function getTaskById($id)
    {
        return $this->taskRepository->getTaskById($id);
    }

    public function createTask(array $data)
    {
        return $this->taskRepository->createTask($data);
    }

    public function updateTask($id, array $data)
    {
        $task = $this->taskRepository->getTaskById($id);

        $this->authorizeTaskAccess($task);

        $updatedTask = $this->taskRepository->updateTask($id, $data);

        return $updatedTask;
    }

    public function deleteTask($id)
    {
        $task = $this->taskRepository->getTaskById($id);
        $this->authorizeTaskAccess($task);

        $this->taskRepository->deleteTask($id);
    }

    public function markTaskAsDone($id)
    {
        $task = $this->taskRepository->getTaskById($id);

        $this->authorizeTaskAccess($task);

        $this->checkSubtasksCompleted($task);

        $this->taskRepository->markTaskAsDone($id);
    }

    protected function authorizeTaskAccess(TaskDTO $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Access denied. You are not allowed to perform this action.');
        }
    }

    protected function checkSubtasksCompleted(TaskDTO $task)
    {
        if ($task->hasUncompletedSubtasks()) {
            abort(403, 'Task cannot be marked as done while it has uncompleted subtasks.');
        }
    }

    public function buildTaskTree($tasks, $parentId = null)
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

    public function getTasksAsTree(array $filters)
    {
        $tasks = $this->taskRepository->getTasks($filters);

        return $this->buildTaskTree($tasks);
    }

    public function printTaskTree($tasks)
    {
        foreach ($tasks as $task) {
            $this->printTaskWithIndent($task, 0);
        }
    }

    private function printTaskWithIndent($task, $indent)
    {
        echo str_repeat('  ', $indent) . "- {$task->title}\n";

        foreach ($task->subtasks as $subtask) {
            $this->printTaskWithIndent($subtask, $indent + 1);
        }
    }
}
