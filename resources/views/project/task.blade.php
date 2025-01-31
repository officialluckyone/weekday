@extends('layouts.backend.main',['subtitle' => 'Task Project'])
@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
@endsection

@section('content')
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('project.index') }}">Projects</a>
          </li>
          <li class="breadcrumb-item active">Task Project</li>
        </ol>
    </nav>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th>:</th>
                        <td>{{ $project->name }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>:</td>
                        <td>{!! $project->description !!}</td>
                    </tr>
                    <tr>
                        <th>Start At</th>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($project->begin)->isoFormat('DD-MMMM-YYYY') }}</td>
                    </tr>
                    <tr>
                        <th>Finish At</th>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($project->end)->isoFormat('DD-MMMM-YYYY') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-users table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Start Task</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Number Users</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ (!is_null($item->start))?\Carbon\Carbon::parse($item->start)->isoFormat('DD-MMMM-YYYY'):'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->deadline)->isoFormat('DD-MMMM-YYYY') }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->priority }}</td>
                                    <td>{{ $item->task_user->count() }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('project.task.show',[$project->id,$item->id]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Detail Task {{ $item->name }}" class="text-primary"><i class="menu-icon tf-icons ri-eye-line"></i></a>
                                            @can('Task Update')
                                                @if (auth()->user()->hasRole('Super Admin') || $item->pic_id == auth()->user()->id)
                                                    <a href="{{ route('project.task.edit',[$project->id,$item->id]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                                @endif
                                            @endcan
                                            @can('Task Delete')
                                                @if (auth()->user()->hasRole('Super Admin') || $item->pic_id == auth()->user()->id)
                                                    <form method="post" action="{{ route('project.task.destroy',[$project->id,$item->id]) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Are you sure?', text: 'Delete Task Project, Data Cannot Be Recovered', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="my-3 d-flex justify-content-center">
                @can('Task Create')
                    <a href="{{ route('project.task.create',$project->id) }}" class="btn btn-primary">Add New Task</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="col-12 d-flex justify-content-end">
        <a href="{{ route('project.index') }}" class="btn btn-danger">Back</a>
    </div>
</div>
@endsection


@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        document.addEventListener('DOMContentLoaded', function (e) {
            $(function () {
                var dt_basic_table = $('.datatables-users'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-users')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Pengguna</h5>');
                    }
                }
            })

        })
    </script>
@show
