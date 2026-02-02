@extends('layouts.app')

@section('content')
<h1 class="h3">Create Task</h1>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            @include('tasks._form', ['task' => new \App\Models\Task()])
            <button class="btn btn-primary" type="submit">Save</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection
