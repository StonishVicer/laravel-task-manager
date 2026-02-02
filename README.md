# ✅ Laravel Task Manager

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
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
- **Bootstrap UI**: Simple, responsive UI using Bootstrap 4.

---

## 🧱 Domain Model

**Task**

- `id`
- `title` (string, required, max 255)
- `description` (text, optional)
- `status` (`pending` or `completed`, default `pending`)
- `created_at`, `updated_at`

Status constants are defined in the `Task` model and reused across the controller and views.

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

- **index**:  
  - Accepts `q` (search by title) and `status` (`pending`, `completed`) as query params.  
  - Uses `ilike` for PostgreSQL-friendly case-insensitive search.  
  - Returns paginated list of tasks (`10` per page).

- **store / update**:  
  - Validates `title`, `description`, `status` using `Task::statuses()`.  
  - Redirects back with flash success messages.

- **destroy**:  
  - Deletes the task and redirects with confirmation.

- **toggleStatus**:  
  - Flips between `STATUS_PENDING` and `STATUS_COMPLETED`.  
  - Redirects back to the list with a success message.

---

## 🎨 UI & Views

Implemented with **Blade** + **Bootstrap 4**:

- `layouts/app.blade.php`  
  - Top navbar with links to “Tasks” and “Create”.
  - Global container and scripts for jQuery + Bootstrap JS.

- `tasks/index.blade.php`  
  - Search input (`q`) and status select (`all / pending / completed`).
  - Table with:
    - Title + truncated description.
    - Created date.
    - Status badge (green for completed, yellow for pending).
    - Actions: Edit, Toggle Status, Delete (modal confirmation).
  - Pagination links at the bottom.

- `tasks/_form.blade.php`  
  - Reused in create/edit.
  - Fields: `title`, `description`, `status`.
  - Displays validation errors with Bootstrap `is-invalid` and feedback.

---

## 🛠️ Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/StonishVicer/laravel-task-manager.git
cd laravel-task-manager
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Setup

Copy the example file and configure it:

```bash
cp .env.example .env
```

Update your `.env` for PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=taskmanager
DB_USERNAME=taskuser
DB_PASSWORD=secret
```

You can also update `APP_NAME`, `APP_URL`, mail, cache, etc.

### 4. Generate App Key & Run Migrations

```bash
php artisan key:generate
php artisan migrate
```

(Optional) Seed a demo user:

```bash
php artisan db:seed
```

### 5. Frontend Assets

```bash
npm install
npm run dev   # or: npm run build for production
```

### 6. Run the Development Server

```bash
php artisan serve
```

Application will be available at:

```text
http://127.0.0.1:8000
```

---

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

---

## 📝 Future Improvements

- Add user authentication and per-user tasks.
- Add due dates and priority field.
- Add REST API endpoints (JSON) for tasks.
- Add bulk actions (complete/delete multiple tasks).

---

## 📄 License

This project is open source under the **MIT License**.  

Built with ❤️ using Laravel 12.
