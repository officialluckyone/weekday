@extends('layouts.app')

@section('content')
    <h1>Daftar Proyek</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Tambah Proyek Baru</a>
    @if($projects->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Proyek</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->start_date }}</td>
                        <td>{{ $project->end_date }}</td>
                        <td>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus proyek ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $projects->links() }}
    @else
        <p>Tidak ada proyek ditemukan.</p>
    @endif
@endsection