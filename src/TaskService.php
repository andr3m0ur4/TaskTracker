<?php

namespace TaskTracker;

use DateTimeImmutable;

class TaskService
{
    public function addTask(string $description): Task
    {
        $task = new Task($description);

        $tasks = $this->listTasks();
        $id = $this->getNextId($tasks);
        $task->setId($id);

        $tasks[] = $task;

        $json = json_encode($tasks, JSON_PRETTY_PRINT);
        file_put_contents('tasks.json', $json);
        return $task;
    }

    /**
     * @return Task[]
     */
    public function listTasks(): array
    {
        if (!file_exists('tasks.json')) {
            return [];
        }

        $json = file_get_contents('tasks.json');
        $data = json_decode($json, true);

        if (is_null($data) || !is_array($data)) {
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

                $json = json_encode($tasks, JSON_PRETTY_PRINT);
                file_put_contents('tasks.json', $json);
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
                
                $json = json_encode($tasks, JSON_PRETTY_PRINT);
                file_put_contents('tasks.json', $json);
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
