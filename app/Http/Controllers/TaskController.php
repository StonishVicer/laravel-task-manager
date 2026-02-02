<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Bonus: search + filter por status
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $query = Task::query()->latest();

        if ($search !== '') {
            $query->where('title', 'ilike', "%{$search}%"); // Postgres-friendly
        }

        if (in_array($status, Task::statuses(), true)) {
            $query->where('status', $status);
        }

        $tasks = $query->paginate(10)->withQueryString();

        return view('tasks.index', compact('tasks', 'search', 'status'));
    }

    public function create()
    {
        $statuses = Task::statuses();
        return view('tasks.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(Task::statuses())],
        ]);

        Task::create($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $statuses = Task::statuses();
        return view('tasks.edit', compact('task', 'statuses'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(Task::statuses())],
        ]);

        $task->update($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    // Requerimiento: marcar Completed/Pending fácilmente desde el listado
    public function toggleStatus(Task $task)
    {
        $task->status = $task->status === Task::STATUS_COMPLETED
            ? Task::STATUS_PENDING
            : Task::STATUS_COMPLETED;

        $task->save();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task status updated.');
    }
}
