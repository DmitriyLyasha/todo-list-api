# Task Management API

This API allows users to manage their tasks, including creating, editing, marking as done, and deleting tasks. Users can also filter and sort tasks based on various criteria.

## Features

- Get a list of tasks with filtering and sorting options
- Create a new task
- Edit an existing task
- Mark a task as done
- Delete a task

## API Endpoints

- `GET /tasks`: Retrieve a list of tasks based on specified filters and sorting parameters.

  **Query Parameters:**
    - `status`: Filter tasks by status (`todo`, `done`).
    - `priority`: Filter tasks by priority (1 to 5).
    - `title` or `description`: Full-text search for tasks based on title or description.
    - `sort`: Sort tasks by multiple fields, e.g., `priority desc, createdAt asc`.

- `POST /tasks`: Create a new task.

  **Request Body:**
  ```json
  {
    "status": "todo",
    "priority": 3,
    "title": "Task Title",
    "description": "Task Description"
  }
  
- `PUT /tasks/{id}`: Edit an existing task.

  **Request Body:**
  ```json
  {
    "status": "done",
    "priority": 5,
    "title": "Updated Task Title",
    "description": "Updated Task Description"
  }

- `PATCH /tasks/{id}/done`: Mark a task as done.

- `DELETE /tasks/{id}`: Delete a task.
-
##  Installation

  - Clone the repository: `git clone https://github.com/DmitriyLyasha/todo-list-api.git`
  - Navigate to the project directory: `cd task-management-api`
  - Install dependencies: `composer install`
  - Set up the database: `php artisan migrate`
  - Seed the database with sample data: `php artisan db:seed --class=TaskSeeder`

## Usage

- Run the development server: php artisan serve
- Access the API at http://localhost:8000

## Documentation

API documentation is available at http://localhost:8000/api/documentation using Swagger UI.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
