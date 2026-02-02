@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ New Task</a>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="card card-body mb-3">
        <div class="form-row">
            <div class="col-md-6 mb-2">
                <input type="text" name="q" value="{{ $search }}" class="form-control"
                    placeholder="Search by title...">
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

    @if ($totalTasks > 0)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="font-weight-bold">Progress</div>
                    <div class="text-muted small">
                        {{ $completedTasks }} / {{ $totalTasks }} tasks completed ({{ $completionPercent }}%)
                    </div>
                </div>

                <div class="progress" style="height: 18px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $completionPercent }}%;"
                        aria-valuenow="{{ $completionPercent }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $completionPercent }}%
                    </div>
                </div>

                @if ($allCompleted)
                    <div class="alert alert-success mt-3 mb-0">
                        <strong>All tasks completed.</strong>
                    </div>
                @endif
            </div>
        </div>
    @endif


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
                                    <a href="#" class="font-weight-bold text-dark" data-toggle="modal"
                                        data-target="#taskDetailsModal" data-id="{{ $task->id }}"
                                        data-title="{{ $task->title }}"
                                        data-description="{{ $task->description ? e($task->description) : '' }}"
                                        data-status="{{ $task->status }}"
                                        data-created="{{ $task->created_at->format('Y-m-d H:i') }}"
                                        data-updated="{{ $task->updated_at->format('Y-m-d H:i') }}"
                                        data-edit-url="{{ route('tasks.edit', $task) }}"
                                        data-toggle-url="{{ route('tasks.toggle-status', $task) }}"
                                        data-delete-url="{{ route('tasks.destroy', $task) }}">
                                        {{ $task->title }}
                                    </a>

                                    @if ($task->description)
                                        <div class="text-muted small">
                                            {{ \Illuminate\Support\Str::limit($task->description, 80) }}
                                        </div>
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
                                    <form action="{{ route('tasks.toggle-status', $task) }}" method="POST"
                                        class="d-inline">
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
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                        data-target="#deleteModal" data-action="{{ route('tasks.destroy', $task) }}"
                                        data-title="{{ $task->title }}">
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
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
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

    <!-- Task Details Modal (Large) -->
    <div class="modal fade" id="taskDetailsModal" tabindex="-1" role="dialog" aria-labelledby="taskDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="taskDetailsModalLabel" style="font-size: 1.25rem; font-weight: 700;">
                            Task Details
                        </h5>
                        <div class="small text-muted" id="taskMeta"></div>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="pr-3">
                            <h4 id="taskTitle" class="mb-1" style="font-size: 1.5rem; font-weight: 700;"></h4>
                            <div id="taskStatusBadge"></div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-uppercase text-muted" style="letter-spacing: .04em;">Description</h6>

                    <div id="taskDescription" class="border rounded p-3"
                        style="min-height: 120px; max-height: 320px; overflow-y: auto; white-space: pre-wrap;">
                    </div>

                    <div class="mt-3 small text-muted" id="taskTimestamps"></div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <form id="taskToggleForm" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-info">
                                Toggle Status
                            </button>
                        </form>

                        <a id="taskEditLink" href="#" class="btn btn-outline-primary ml-2">
                            Edit
                        </a>
                    </div>

                    <form id="taskDeleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="taskDeleteBtn" class="btn btn-danger" data-toggle="modal"
                            data-target="#deleteModal" data-action="" data-title="">
                            Delete
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var action = button.data('action');
            var title = button.data('title');

            $('#deleteForm').attr('action', action);
            $('#deleteTaskTitle').text(title);
        });

        $('#taskDetailsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            var id = button.data('id');
            var title = button.data('title');
            var description = button.data('description') || '';
            var status = button.data('status');
            var created = button.data('created');
            var updated = button.data('updated');

            var editUrl = button.data('edit-url');
            var toggleUrl = button.data('toggle-url');
            var deleteUrl = button.data('delete-url');

            $('#taskTitle').text(title);

            if (status === 'completed') {
                $('#taskStatusBadge').html('<span class="badge badge-success">Completed</span>');
            } else {
                $('#taskStatusBadge').html('<span class="badge badge-warning">Pending</span>');
            }

            if (description.trim() === '') {
                $('#taskDescription').html('<span class="text-muted">No description provided.</span>');
            } else {
                $('#taskDescription').text(description);
            }

            $('#taskMeta').text('Task #' + id);
            $('#taskTimestamps').text('Created: ' + created + ' • Last updated: ' + updated);

            $('#taskEditLink').attr('href', editUrl);
            $('#taskToggleForm').attr('action', toggleUrl);


            $('#taskDeleteBtn')
                .attr('data-action', deleteUrl)
                .attr('data-title', title);
        });

        // Ensure delete confirmation modal is shown above task details modal
        $('#taskDeleteBtn').on('click', function() {
            // Close the task details modal first
            $('#taskDetailsModal').modal('hide');
        });

        // Optional: when delete modal closes, clean up any leftover backdrops
        $('#deleteModal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endpush
