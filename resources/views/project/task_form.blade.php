@extends('layouts.backend.main',['subtitle' => 'Task Project'])

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
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
            <li class="breadcrumb-item">
                <a href="{{ route('project.show',$project->id) }}">Task Project</a>
            </li>
            @if (isset($task))
                <li class="breadcrumb-item active">Edit Task {{ $task->title }}</li>
            @else
                <li class="breadcrumb-item active">Create Task</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">@if (isset($task)) Edit Task @else Create Task @endif</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}" id="form-area" enctype="multipart/form-data">
                    @isset($task) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="title">Title <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ isset($task) ? old('title',$task->title) : old('title') }}"
                                placeholder="Enter title task" />
                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="start_at">Start Task  <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('start_at') is-invalid @enderror datepicker"
                                id="start_at"
                                name="start_at"
                                value="{{ isset($task) ? old('start_at',\Carbon\Carbon::parse($task->start)->isoFormat('DD-MM-YYYY')) : old('start_at') }}"
                                placeholder="Enter Start Task" />
                            @error('start_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="deadline_at">Deadline  <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('deadline_at') is-invalid @enderror datepicker"
                                id="deadline_at"
                                name="deadline_at"
                                value="{{ isset($task) ? old('deadline_at',\Carbon\Carbon::parse($task->deadline)->isoFormat('DD-MM-YYYY')) : old('deadline_at') }}"
                                placeholder="Enter Deadline Task" />
                            @error('deadline_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="published_at">Priority <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('priority') is-invalid @enderror"
                                  type="radio"
                                  name="priority"
                                  id="low"
                                  {{ (isset($task) && $task->priority == 'Low') ? 'checked' : ((!is_null(old('priority')) && old('priority') == 'Low') ? 'checked' : '') }}
                                  value="Low" />
                                <label class="form-check-label" for="low">Low</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('priority') is-invalid @enderror"
                                  type="radio"
                                  name="priority"
                                  id="medium"
                                  {{ (isset($task) && $task->priority == 'Medium') ? 'checked' : ((!is_null(old('priority')) && old('priority') == 'Medium') ? 'checked' : '') }}
                                  value="Medium" />
                                <label class="form-check-label" for="medium">Medium</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('priority') is-invalid @enderror"
                                  type="radio"
                                  name="priority"
                                  id="high"
                                  {{ (isset($task) && $task->priority == 'High') ? 'checked' : ((!is_null(old('priority')) && old('priority') == 'High') ? 'checked' : '') }}
                                  value="High" />
                                <label class="form-check-label" for="high">High</label>
                            </div>
                            @error('priority')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="description">Tasks For Users <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select
                                id="select2Basic"
                                name="users_id[]"
                                class="select2 form-select form-select-lg"
                                data-allow-clear="true"
                                multiple>
                                @foreach ($users as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ (isset($task) && in_array($item->id,$task->task_user->pluck('id')->toArray())) ? 'selected' : ( (!is_null(old('users_id')) && in_array($item->id, old('users_id'))) ? 'selected' : '') }}
                                        >{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('users_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="description">Description</label>
                        <div class="col-sm-10">
                            <div id="snow-toolbar">
                                <span class="ql-formats">
                                  <select class="ql-font"></select>
                                  <select class="ql-size"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-bold"></button>
                                  <button class="ql-italic"></button>
                                  <button class="ql-underline"></button>
                                  <button class="ql-strike"></button>
                                </span>
                                <span class="ql-formats">
                                  <select class="ql-color"></select>
                                  <select class="ql-background"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-script" value="sub"></button>
                                  <button class="ql-script" value="super"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-header" value="1"></button>
                                  <button class="ql-header" value="2"></button>
                                  <button class="ql-blockquote"></button>
                                  <button class="ql-code-block"></button>
                                </span>
                            </div>
                            <div id="editor-container" style="height: 300px;" class="@error('description') is-invalid @enderror">
                                {!! isset($task) ? old('description',$task->description) : old('description') !!}
                            </div>
                            <textarea id="content" name="description" style="display: none;">{!! isset($task) ? old('description',$task->description) : old('description') !!}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('project.show',$project->id) }}" class="btn btn-danger">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){
            var quill = new Quill('#editor-container', {
                bounds: '#editor-container',
                modules: {
                formula: true,
                    toolbar: '#snow-toolbar'
                },
                theme: 'snow'
            });

            // Simpan data Quill ke textarea sebelum submit
            var form = document.querySelector('#form-area');
            form.onsubmit = function() {
                var content = document.querySelector('textarea[name=description]');
                content.value = quill.root.innerHTML;
            };

            $('.datepicker').datepicker({
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                orientation: isRtl ? 'auto right' : 'auto left'
            })

            const select2 = $('.select2');
            if (select2.length) {
                select2.each(function () {
                    var $this = $(this);
                    select2Focus($this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Select Users',
                        dropdownParent: $this.parent()
                    });
                });
            }
        })

    </script>
@endsection
