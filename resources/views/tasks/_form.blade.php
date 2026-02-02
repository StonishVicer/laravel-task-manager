<div class="form-group">
    <label for="title">Task Title</label>
    <input
        type="text"
        id="title"
        name="title"
        value="{{ old('title', $task->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror"
        required
    >
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="description">Task Description</label>
    <textarea
        id="description"
        name="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="4"
    >{{ old('description', $task->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="status">Task Status</label>
    <select
        id="status"
        name="status"
        class="form-control @error('status') is-invalid @enderror"
        required
    >
        @foreach ($statuses as $s)
            <option value="{{ $s }}" {{ old('status', $task->status ?? 'pending') === $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
            </option>
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
