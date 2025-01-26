@extends('layouts.app')

@section('content')
    <h1>{{ $project->name }}</h1>
    <p><strong>Deskripsi:</strong> {{ $project->description }}</p>
    <p><strong>Tanggal Mulai:</strong> {{ $project->start_date }}</p>
    <p><strong>Tanggal Selesai:</strong> {{ $project->end_date }}</p>

    <h3>Tugas</h3>
    <a href="{{ route('tasks.create', $project) }}" class="btn btn-primary mb-3">Tambah Tugas</a>
    @if($tasks->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', [$project, $task]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('tasks.destroy', [$project, $task]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus tugas ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada tugas ditemukan.</p>
    @endif

    <h3>Kemajuan Proyek</h3>
    <canvas id="progressChart" width="400" height="200"></canvas>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('progressChart').getContext('2d');
        const completed = {{ $project->tasks->where('status', 'completed')->count() }};
        const inProgress = {{ $project->tasks->where('status', 'in_progress')->count() }};
        const pending = {{ $project->tasks->where('status', 'pending')->count() }};
        const total = {{ $project->tasks->count() }};

        const data = {
            labels: ['Completed', 'In Progress', 'Pending'],
            datasets: [{
                data: [completed, inProgress, pending],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)', // Completed - Hijau
                    'rgba(23, 162, 184, 0.7)', // In Progress - Biru
                    'rgba(220, 53, 69, 0.7)'   // Pending - Merah
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
            },
        };

        new Chart(ctx, config);
    </script>
@endsection