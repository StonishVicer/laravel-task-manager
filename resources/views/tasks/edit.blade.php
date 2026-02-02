@extends('layouts.app')

@section('content')
<h1 class="h3">Edit Task</h1>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.update', $task) }}">
            @csrf
            @method('PUT')
            @include('tasks._form', ['task' => $task])
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div>
</div>
@endsection
