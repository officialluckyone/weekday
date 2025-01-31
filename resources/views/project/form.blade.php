@extends('layouts.backend.main',['subtitle' => 'Projects'])

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
            @if (isset($project))
                <li class="breadcrumb-item active">Edit Project {{ $project->name }}</li>
            @else
                <li class="breadcrumb-item active">Create Project</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">@if (isset($project)) Edit Project @else Create Project @endif</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}" id="form-area" enctype="multipart/form-data">
                    @isset($project) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ isset($project) ? old('name',$project->name) : old('name') }}"
                                placeholder="Enter Name Project" />
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="start_at">Start Project  <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('start_at') is-invalid @enderror datepicker"
                                id="start_at"
                                name="start_at"
                                value="{{ isset($project) ? old('start_at',\Carbon\Carbon::parse($project->begin)->isoFormat('DD-MM-YYYY')) : old('start_at') }}"
                                placeholder="Enter Start Project Date" />
                            @error('start_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="end_at">End Project  <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('end_at') is-invalid @enderror datepicker"
                                id="end_at"
                                name="end_at"
                                value="{{ isset($project) ? old('end_at',\Carbon\Carbon::parse($project->end)->isoFormat('DD-MM-YYYY')) : old('end_at') }}"
                                placeholder="Enter Start Project Date" />
                            @error('end_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="published_at">Status <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="ongoing"
                                  {{ (isset($project) && $project->status == 'Ongoing') ? 'checked' : ((!is_null(old('status')) && old('status') == 'Ongoing') ? 'checked' : '') }}
                                  value="Ongoing" />
                                <label class="form-check-label" for="ongoing">Ongoing</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="decline"
                                  {{ (isset($project) && $project->status == 'Decline') ? 'checked' : ((!is_null(old('status')) && old('status') == 'Decline') ? 'checked' : '') }}
                                  value="Decline" />
                                <label class="form-check-label" for="decline">Decline</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="done"
                                  {{ (isset($project) && $project->status == 'Done') ? 'checked' : ((!is_null(old('status')) && old('status') == 'Done') ? 'checked' : '') }}
                                  value="Done" />
                                <label class="form-check-label" for="done">Done</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="nothing"
                                  {{ (isset($project) && $project->status == 'Nothing') ? 'checked' : ((!is_null(old('status')) && old('status') == 'Nothing') ? 'checked' : '') }}
                                  value="Nothing" />
                                <label class="form-check-label" for="nothing">Nothing</label>
                            </div>
                            @error('status')
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
                                {!! isset($project) ? old('description',$project->description) : old('description') !!}
                            </div>
                            <textarea id="content" name="description" style="display: none;">{!! isset($project) ? old('description',$project->description) : old('description') !!}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <a href="{{ route('project.index') }}" class="btn btn-danger">Back</a>
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
        })

    </script>
@endsection
