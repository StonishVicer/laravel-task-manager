# ✅ Laravel Task Manager

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_4-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

Simple but production-ready **task manager** built with **Laravel 12**, **Blade** and **Bootstrap 4**.  
It focuses on clean MVC structure, validation, pagination, and basic filtering with a PostgreSQL-backed persistence layer.

---

## 🚀 Features

- **Tasks CRUD**: Create, edit, update and delete tasks.
- **Status Toggle**: Quickly switch between `pending` and `completed` from the list view.
- **Search & Filter**: Filter tasks by title and status.
- **Pagination**: Tasks listed with server-side pagination.
- **Validation**: Strong server-side validation for all task fields.
- **Bootstrap UI**: Simple, responsive UI using Bootstrap 4 via CDN.

---

## 🧱 Domain Model

**Task**

- `id`
- `title` (string, required, max 255)
- `description` (text, optional)
- `status` (`pending` or `completed`, default `pending`)
- `created_at`, `updated_at`

Status constants are defined in the `Task` model and reused across the controller and views (`STATUS_PENDING`, `STATUS_COMPLETED`).

---

## 📂 Project Structure

```bash
stonishvicer-laravel-task-manager/
├── app/
│   ├── Http/Controllers/
│   │   └── TaskController.php   # All task actions + status toggle
│   ├── Models/
│   │   └── Task.php             # Task model + status helpers
├── database/
│   ├── migrations/
│   │   └── 2026_02_02_152135_create_tasks_table.php
│   └── seeders/DatabaseSeeder.php
├── resources/
│   ├── views/layouts/app.blade.php   # Base layout (Bootstrap navbar)
│   └── views/tasks/
│       ├── index.blade.php          # List + filters + pagination
│       ├── create.blade.php         # Create form
│       ├── edit.blade.php           # Edit form
│       └── _form.blade.php          # Shared form partial
├── routes/
│   └── web.php                      # Resource routes + toggle-status
└── .env.example                     # Sample PostgreSQL config
```

---

## 🔌 Routing

```php
// routes/web.php

// Redirect root to tasks
Route::get('/', fn () => redirect()->route('tasks.index'));

// Resourceful CRUD (index, create, store, edit, update, destroy)
Route::resource('tasks', TaskController::class)->except('show');

// Toggle status (pending <-> completed)
Route::patch('tasks/{task}/toggle-status', [TaskController::class, 'toggleStatus'])
    ->name('tasks.toggle-status');
```

---

## 🧠 Controller Logic (Overview)

- **index**  
  - Accepts `q` (search by title) and `status` (`pending`, `completed`) as query params.  
  - Uses a case-insensitive search over `title`.  
  - Returns paginated list of tasks (`10` per page) preserving the current filters.

- **store / update**  
  - Validates `title`, `description`, `status` using `Task::statuses()`.  
  - On success, redirects back to the index with flash success messages.

- **destroy**  
  - Deletes the task and redirects with a confirmation message.

- **toggleStatus**  
  - Flips between `STATUS_PENDING` and `STATUS_COMPLETED` and saves the task.  
  - Redirects back to the list with a success message.

---

## 🎨 UI & Views

Implemented with **Blade** + **Bootstrap 4** (via CDN).

- `layouts/app.blade.php`  
  - Includes Bootstrap 4 CSS and JS from CDNs.  
  - Top navbar with links to “Tasks” and “Create”.  
  - Global container and section for flash success messages.

- `tasks/index.blade.php`  
  - Search input (`q`) and status select (`all / pending / completed`).  
  - Table with:
    - Title + truncated description.
    - Created date formatted as `Y-m-d`.
    - Status badge (green for completed, yellow for pending).
    - Actions: Edit, Toggle Status, Delete (confirmation modal).
  - Pagination links using `{{ $tasks->links() }}`.

- `tasks/_form.blade.php`  
  - Reused in create/edit.  
  - Fields: `title`, `description`, `status` (options from `Task::statuses()`).  
  - Displays validation errors with `is-invalid` and feedback blocks.

---

## 🧪 Assessment Notes

### 1. Overview of Approach

I approached this task by designing a clean, maintainable Laravel application using standard framework conventions and a clear separation of concerns.  
I implemented a full CRUD flow for tasks with server-side validation, RESTful routing, and a Bootstrap 4–based UI focused on usability and clarity.  
The application is configured to run locally with PostgreSQL and does not rely on Docker or additional tooling, ensuring straightforward setup and portability.  
I prioritized readable code, meaningful commit history, and a predictable project structure so that the application can be easily reviewed, extended, or maintained.

### 2. Assumptions

I assumed a single-user scope with no authentication requirements, as none were specified in the instructions.  
I also assumed that tasks would have a limited, well-defined lifecycle (`pending` and `completed`) and that simplicity and reliability were preferred over advanced abstractions.  
Based on the submission guidelines, I optimized the environment configuration for local execution and reproducibility, favoring file-based caching and standard Laravel defaults where appropriate.

### 3. Setup Instructions

1. **Clone the Repository**

   ```bash
   git clone https://github.com/StonishVicer/laravel-task-manager.git
   cd laravel-task-manager
   ```

2. **Install PHP Dependencies**

   Make sure you have PHP 8.2+ and Composer installed, then run:

   ```bash
   composer install
   ```

3. **Environment Configuration**

   ```bash
   cp .env.example .env
   ```

   Update `.env` with your PostgreSQL settings (or your preferred database):

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=task_manager
   DB_USERNAME=task_user
   DB_PASSWORD=secret
   ```

4. **Generate App Key & Run Migrations**

   ```bash
   php artisan key:generate
   php artisan migrate
   ```

5. **Run the Development Server**

   ```bash
   php artisan serve
   ```

   The app will be available at:

   ```text
   http://127.0.0.1:8000
   ```

   No additional commands are required: all assets are loaded via CDN, so the UI works out of the box once the server is running.

### 4. Bonus Features

As bonus features, I implemented an enhanced user experience for viewing long task descriptions through a large, dedicated **task details modal** in the list view.  
This modal presents the full task information with clear visual hierarchy and accessible actions, including status toggling, editing, and deletion with confirmation, ensuring that longer descriptions remain readable without cluttering the main task list.

---

## 📄 License

This project is open source under the **MIT License**.  

Built with ❤️ using Laravel 12.