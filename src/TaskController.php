<?php

namespace TaskTracker;

use InvalidArgumentException;

class TaskController
{
    public function __construct(private TaskService $taskService = new TaskService())
    {
    }

    public function listTasks(?string $status = null): string
    {
        if (is_null($status)) {
            $tasks = $this->taskService->listTasks();
        } else {
            $taskStatus = TaskStatus::tryFrom($status);
            if (is_null($taskStatus)) {
                return "Invalid status: $status" . PHP_EOL;
            }
            $tasks = $this->taskService->listTasksByStatus($taskStatus);
        }
        return $this->makeTasksOutput($tasks);
    }

    public function addTask(string $description): string
    {
        if (empty($description)) {
            return "Task description cannot be empty." . PHP_EOL;
        }

        $task = $this->taskService->addTask($description);
        return "Task added successfully (ID: {$task->getId()})" . PHP_EOL;
    }

    public function updateTask(int $id, string $description): string
    {
        if (empty($description)) {
            return "Task description cannot be empty." . PHP_EOL;
        }

        $task = $this->taskService->updateTask($id, $description);
        if (is_null($task)) {
            return 'Task not found.' . PHP_EOL;
        }
        return "Task updated successfully (ID: {$task->getId()})" . PHP_EOL;
    }

    public function markTaskInProgress(int $id): string
    {
        $task = $this->taskService->markTaskInProgress($id);
        if (is_null($task)) {
            return 'Task not found.' . PHP_EOL;
        }
        return "Task marked as in-progress (ID: {$task->getId()})" . PHP_EOL;
    }

    public function markTaskDone(int $id): string
    {
        $task = $this->taskService->markTaskDone($id);
        if (is_null($task)) {
            return 'Task not found.' . PHP_EOL;
        }
        return "Task marked as done (ID: {$task->getId()})" . PHP_EOL;
    }

    public function deleteTask(int $id): string
    {
        $task = $this->taskService->deleteTask($id);
        if (is_null($task)) {
            return "Task not found." . PHP_EOL;
        }
        return "Task deleted successfully (ID: {$task->getId()})" . PHP_EOL;
    }

    /**
     * @param Task[] $tasks
     * @return string
     */
    private function makeTasksOutput(array $tasks): string
    {
        if (empty($tasks)) {
            return "No tasks found." . PHP_EOL;
        }

        $output = '';
        foreach ($tasks as $task) {
            $output .= "[" . $task->getId() . "] " . $task->getDescription() . " (" . $task->getStatus() . ")" . PHP_EOL;
        }
        return $output;
    }
}