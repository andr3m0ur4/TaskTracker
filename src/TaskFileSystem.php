<?php

namespace TaskTracker;

class TaskFileSystem
{
    public function recordTasks(array $tasks): void
    {
        $json = json_encode($tasks, JSON_PRETTY_PRINT);
        file_put_contents('tasks.json', $json);
    }

    public function readTasks(): array
    {
        if (!file_exists('tasks.json')) {
            return [];
        }

        $json = file_get_contents('tasks.json');
        $data = json_decode($json, true);

        if (is_null($data) || !is_array($data)) {
            return [];
        }

        return $data;
    }
}
