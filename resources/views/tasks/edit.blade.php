@extends('layouts.app')

@section('content')
    <h1>Edit Tugas di Proyek: {{ $project->name }}</h1>
    <form action="{{ route('tasks.update', [$project, $task]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Judul Tugas</label>
            <input type="text" class="form-control" id="title" name="title" required value="{{ old('title', $task->title) }}">
        </div>
        <div class="form-group">
            <label for="description">Deskripsi Tugas</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $task->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date) }}">
        </div>
        <button type="submit" class="btn btn-success">Update Tugas</button>
    </form>
@endsection