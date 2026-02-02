@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ New Task</a>
</div>

<form method="GET" action="{{ route('tasks.index') }}" class="card card-body mb-3">
    <div class="form-row">
        <div class="col-md-6 mb-2">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                class="form-control"
                placeholder="Search by title..."
            >
        </div>
        <div class="col-md-3 mb-2">
            <select name="status" class="form-control">
                <option value="">All statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <button class="btn btn-outline-secondary btn-block" type="submit">Filter</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        @if ($tasks->count() === 0)
            <div class="p-3">No tasks found.</div>
        @else
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($tasks as $task)
                    <tr class="{{ $task->status === 'completed' ? 'table-success' : '' }}">
                        <td>
                            <strong>{{ $task->title }}</strong>
                            @if ($task->description)
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit($task->description, 80) }}</div>
                            @endif
                        </td>
                        <td>{{ $task->created_at->format('Y-m-d') }}</td>
                        <td>
                            @if ($task->status === 'completed')
                                <span class="badge badge-success">Completed</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-info" type="submit">
                                    Toggle
                                </button>
                            </form>

                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <!-- Delete triggers modal -->
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-toggle="modal"
                                data-target="#deleteModal"
                                data-action="{{ route('tasks.destroy', $task) }}"
                                data-title="{{ $task->title }}"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<div class="mt-3">
    {{ $tasks->links() }}
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete: <strong id="deleteTaskTitle"></strong>?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var action = button.data('action');
        var title  = button.data('title');

        $('#deleteForm').attr('action', action);
        $('#deleteTaskTitle').text(title);
    });
</script>
@endpush
