@extends('layouts.backend.main',['subtitle' => 'Detail Task Project'])
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
          @if (isset($action))
            <li class="breadcrumb-item">
                <a href="{{ route('task.index') }}">Tasks</a>
            </li>
            <li class="breadcrumb-item active">Detail Task Project</li>
          @else
            <li class="breadcrumb-item">
                <a href="{{ route('project.index') }}">Projects</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('project.show',$project->id) }}">Task Project</a>
            </li>
            <li class="breadcrumb-item active">Detail Task Project</li>
          @endif
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
            <h4 class="card-header">
                Detail Project
            </h4>
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
            <h4 class="card-header">
                Detail Task
            </h4>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Title</th>
                        <td>:</td>
                        <td>{{ $task->title }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>:</td>
                        <td>{!! $task->description !!}</td>
                    </tr>
                    <tr>
                        <th>Start Task</th>
                        <td>:</td>
                        <td>{{ (!is_null($task->start))?\Carbon\Carbon::parse($task->start)->isoFormat('DD-MMMM-YYYY'):'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>Deadline</th>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($task->deadline)->isoFormat('DD-MMMM-YYYY') }}</td>
                    </tr>
                    <tr>
                        <th>Priority</th>
                        <td>:</td>
                        <td>{{ $task->priority }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td>{{ $task->status }}</td>
                    </tr>
                    <tr>
                        <th>Tasks For Users</th>
                        <td>:</td>
                        <td>
                            <ul>
                                @foreach ($task->task_user as $user)
                                    <li><span>{{ $user->name }}</span></li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @if (isset($action))
        <div class="col-12">
            <div class="card">
                <h4 class="card-header">Progress Task</h4>
                <div class="card-body">
                    <form action="{{ $action }}" method="post">
                        @csrf
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="published_at">Status</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline mt-4">
                                    <input
                                      class="form-check-input @error('status') is-invalid @enderror"
                                      type="radio"
                                      name="status"
                                      id="todo"
                                      {{ (isset($task) && $task->status == 'To-Do') ? 'checked' : ((!is_null(old('status')) && old('status') == 'To-Do') ? 'checked' : '') }}
                                      value="To-Do" />
                                    <label class="form-check-label" for="todo">To-Do</label>
                                </div>
                                <div class="form-check form-check-inline mt-4">
                                    <input
                                      class="form-check-input @error('status') is-invalid @enderror"
                                      type="radio"
                                      name="status"
                                      id="inprogress"
                                      {{ (isset($task) && $task->status == 'In Progress') ? 'checked' : ((!is_null(old('status')) && old('status') == 'In Progress') ? 'checked' : '') }}
                                      value="In Progress" />
                                    <label class="form-check-label" for="inprogress">In Progress</label>
                                </div>
                                <div class="form-check form-check-inline mt-4">
                                    <input
                                      class="form-check-input @error('status') is-invalid @enderror"
                                      type="radio"
                                      name="status"
                                      id="done"
                                      {{ (isset($task) && $task->status == 'Done') ? 'checked' : ((!is_null(old('status')) && old('status') == 'Done') ? 'checked' : '') }}
                                      value="Done" />
                                    <label class="form-check-label" for="done">Done</label>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <div class="col-12 d-flex justify-content-end">
        <a href="{{ $link_back }}" class="btn btn-danger">Back</a>
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
