<?php

namespace TaskTracker;

class Command
{
    public function __construct(private TaskService $taskService = new TaskService())
    {
    }

    public function run()
    {
        global $argv, $argc;

        if ($argc < 2) {
            echo "Usage: php task-cli <command> [options]\n";
            exit(1);
        }

        $command = $argv[1] ?? null;
        if (is_null($command)) {
            echo "No command provided.\n";
            exit(1);
        }

        $options = array_slice($argv, 2);
        $this->handleCommand($command, $options);
    }

    public function handleCommand(string $command, array $options)
    {
        switch ($command) {
            case 'add':
                $task = $this->taskService->addTask($options[0] ?? 'No description');
                echo "Task added successfully (ID: " . $task->getId() . ")" . PHP_EOL;
                break;
            case 'list':
                $tasks = $this->taskService->listTasks();
                $this->printTasks($tasks);
                break;
            case 'update':
                $task = $this->taskService->updateTask((int) ($options[0] ?? 0), $options[1] ?? 'No description');
                if (is_null($task)) {
                    echo "Task not found." . PHP_EOL;
                } else {
                    echo "Task updated successfully (ID: " . $task->getId() . ")" . PHP_EOL;
                }
                break;
            case 'mark-in-progress':
                $task = $this->taskService->markTaskInProgress((int) ($options[0] ?? 0));
                if (is_null($task)) {
                    echo "Task not found." . PHP_EOL;
                } else {
                    echo "Task marked as in-progress (ID: " . $task->getId() . ")" . PHP_EOL;
                }
                break;
            case 'mark-done':
                $task = $this->taskService->markTaskDone((int) ($options[0] ?? 0));
                if (is_null($task)) {
                    echo "Task not found." . PHP_EOL;
                } else {
                    echo "Task marked as done (ID: " . $task->getId() . ")" . PHP_EOL;
                }
                break;
            case 'delete':
                $task = $this->taskService->deleteTask((int) ($options[0] ?? 0));
                if (is_null($task)) {
                    echo "Task not found." . PHP_EOL;
                } else {
                    echo "Task deleted successfully (ID: " . $task->getId() . ")" . PHP_EOL;
                }
                break;
            default:
                echo "Unknown command: $command" . PHP_EOL;
                exit(1);
        }
    }

    private function printTasks(array $tasks)
    {
        if (empty($tasks)) {
            echo "No tasks found." . PHP_EOL;
            return;
        }

        foreach ($tasks as $task) {
            echo "[" . $task->getId() . "] " . $task->getDescription() . " (" . $task->getStatus() . ")" . PHP_EOL;
        }
    }
}
