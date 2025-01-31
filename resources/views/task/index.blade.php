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
          <li class="breadcrumb-item active">Tasks</li>
        </ol>
    </nav>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-body">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-users table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Project</th>
                        <th>Title</th>
                        <th>Start Task</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Task for User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $item)
                        @php
                            $row_color = '';
                            $deadlineDate = \Carbon\Carbon::parse($item->deadline);
                            if ($item->deadline < \Carbon\Carbon::today() && $item->status != 'Done') {
                                $row_color = 'table-danger';
                            } elseif (now()->diffInDays($deadlineDate) <= 2 && $item->status != 'Done') {
                                $row_color = 'table-warning';
                            } elseif ($item->status == 'Done') {
                                $row_color = 'table-success';
                            }
                        @endphp
                        <tr class="{{ $row_color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->project->name }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ (!is_null($item->start))?\Carbon\Carbon::parse($item->start)->isoFormat('DD-MMMM-YYYY'):'Unknown' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->deadline)->isoFormat('DD-MMMM-YYYY') }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->priority }}</td>
                            <td>
                                <ul>
                                    @foreach ($item->task_user as $user)
                                        <li>{{ $user->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('task.show',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Detail Task {{ $item->name }}" class="text-primary"><i class="menu-icon tf-icons ri-eye-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
