@extends('layouts.backend.main',['subtitle' => 'Update Profile'])

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card mb-6">
    <!-- Account -->
    <div class="card-body">
        <span class="text-danger">* Wajib diisi</span>
        <form method="post" action="{{ $action }}">
            @csrf
            <div class="row mt-1 g-5">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                        class="form-control @error('name') is-invalid @enderror"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ $user->name }}"
                        autofocus />
                        <label for="name">Name <span class="text-danger">*</span></label>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                    <input
                        class="form-control @error('email') is-invalid @enderror"
                        type="text"
                        id="email"
                        name="email"
                        value="{{ $user->email }}"/>
                        <label for="email">Email <span class="text-danger">*</span></label>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Save</button>
            </div>
        </form>
    </div>
    <!-- /Account -->
</div>
@endsection
