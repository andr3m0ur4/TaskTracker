<?php

namespace TaskTracker;

use DateTimeImmutable;

class TaskService
{
    public function __construct(private TaskFileSystem $taskFileSystem = new TaskFileSystem())
    {
    }
    
    public function addTask(string $description): Task
    {
        $task = new Task($description);

        $tasks = $this->listTasks();
        $id = $this->getNextId($tasks);
        $task->setId($id);

        $tasks[] = $task;

        $this->taskFileSystem->recordTasks($tasks);
        return $task;
    }

    /**
     * @return Task[]
     */
    public function listTasks(): array
    {
        $data = $this->taskFileSystem->readTasks();
        if (empty($data)) {
            return [];
        }

        $tasks = [];
        foreach ($data as $item) {
            $task = new Task($item['description']);
            $task->setId($item['id']);
            $task->setStatus($item['status']);
            $task->setCreatedAt(new DateTimeImmutable($item['createdAt']));
            $task->setUpdatedAt(new DateTimeImmutable($item['updatedAt']));
            $tasks[] = $task;
        }

        return $tasks;
    }

    /**
     * @param TaskStatus $status
     * @return Task[]
     */
    public function listTasksByStatus(TaskStatus $status): array
    {
        $tasks = $this->listTasks();
        return array_filter($tasks, fn (Task $task) => $task->getStatus() === $status->value);
    }

    public function updateTask(int $id, string $description): ?Task
    {
        $tasks = $this->listTasks();
        if (empty($tasks)) {
            return null;
        }

        foreach ($tasks as $task) {
            if ($task->getId() === $id) {
                $task->setDescription($description);
                $task->setUpdatedAt(new DateTimeImmutable());

                $this->taskFileSystem->recordTasks($tasks);
                return $task;
            }
        }

        return null;
    }

    public function markTaskInProgress(int $id): ?Task
    {
        return $this->updateStatusTask($id, TaskStatus::IN_PROGRESS);
    }

    public function markTaskDone(int $id): ?Task
    {
        return $this->updateStatusTask($id, TaskStatus::DONE);
    }

    public function updateStatusTask(int $id, TaskStatus $status): ?Task
    {
        $tasks = $this->listTasks();
        if (empty($tasks)) {
            return null;
        }

        foreach ($tasks as $task) {
            if ($task->getId() === $id) {
                $task->setStatus($status->value);
                $task->setUpdatedAt(new DateTimeImmutable());

                $this->taskFileSystem->recordTasks($tasks);
                return $task;
            }
        }

        return null;
    }

    public function deleteTask(int $id): ?Task
    {
        $tasks = $this->listTasks();
        if (empty($tasks)) {
            return null;
        }

        foreach ($tasks as $index => $task) {
            if ($task->getId() === $id) {
                unset($tasks[$index]);
                
                $this->taskFileSystem->recordTasks($tasks);
                return $task;
            }
        }

        return null;
    }

    /**
     * Get the next available task ID
     * 
     * @param Task[] $tasks
     * @return int
     */
    private function getNextId(array $tasks): int
    {
        $maxId = 0;
        foreach ($tasks as $task) {
            if ($task->getId() > $maxId) {
                $maxId = $task->getId();
            }
        }
        return $maxId + 1;
    }
}
