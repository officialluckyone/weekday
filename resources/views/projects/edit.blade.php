@extends('layouts.app')

@section('content')
    <h1>Edit Proyek</h1>
    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Proyek</label>
            <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $project->name) }}">
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $project->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="start_date">Tanggal Mulai</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required value="{{ old('start_date', $project->start_date) }}">
        </div>
        <div class="form-group">
            <label for="end_date">Tanggal Selesai</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ old('end_date', $project->end_date) }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
@endsection