<?php

namespace TaskTracker;

class Command
{
    public function __construct(private TaskController $taskController = new TaskController())
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
                $output = $this->taskController->addTask($options[0] ?? 'No description');
                break;
            case 'list':
                $output = $this->taskController->listTasks($options[0] ?? null);
                break;
            case 'update':
                $output = $this->taskController->updateTask((int) ($options[0] ?? 0), $options[1] ?? 'No description');
                break;
            case 'mark-in-progress':
                $output = $this->taskController->markTaskInProgress((int) ($options[0] ?? 0));
                break;
            case 'mark-done':
                $output = $this->taskController->markTaskDone((int) ($options[0] ?? 0));
                break;
            case 'delete':
                $output = $this->taskController->deleteTask((int) ($options[0] ?? 0));
                break;
            default:
                echo "Unknown command: $command" . PHP_EOL;
                exit(1);
        }

        echo $output;
    }
}
