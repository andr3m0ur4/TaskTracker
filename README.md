# Task Tracker

A simple CLI-based Task Tracker application built with PHP for the [task-tracker](https://roadmap.sh/projects/task-tracker) challenge.

It allows you to create, update, delete, and manage tasks directly from your terminal.

## 🚀 Features

- Add new tasks

- Update and delete existing tasks

- Mark tasks as in progress or done

- List tasks by status, todo, in-progress, or done

## 🛠 Requirements

`Docker` -> _(must be installed)_

## 📦 Installation

Clone this repository and build the container:

```bash
git clone https://github.com/andr3m0ur4/TaskTracker.git

cd TaskTracker

./build.sh
./start.sh
```


After running start.sh, you will be inside the container and ready to use the CLI.

## 📌 Usage

### Adding a new task

```bash
task-cli add "Buy groceries"
# Output: Task added successfully (ID: 1)
```

### Updating a task

```bash
task-cli update 1 "Buy groceries and cook dinner"
# Task updated successfully (ID: 1)
```

### Deleting a task

```bash
task-cli delete 1
# Task deleted successfully (ID: 1)
```

### Marking task status

```bash
task-cli mark-in-progress 1
# Task marked as in-progress (ID: 1)

task-cli mark-done 1
# Task marked as done (ID: 1)
```

### Listing all tasks

```bash
task-cli list
```

### Listing tasks by status

```bash
task-cli list todo
task-cli list in-progress
task-cli list done
```

## 📂 Project Structure

```bash
TaskTracker/
├── build.sh
├── start.sh
├── src/        # Application source code
├── task-cli        # CLI executable
├── Dockerfile
└── README.md
```

## 🧑‍💻 Contributing

Contributions are welcome! Feel free to fork the repository and submit pull requests.

## 📄 License

This project is licensed under the MIT License.
