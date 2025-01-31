@extends('layouts.auth.main')

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
      <div class="authentication-inner py-6">
        <!-- Login -->
        <div class="card p-md-7 p-1">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
                <a href="{{ route('dashboard') }}" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-heading fw-semibold">Weekday</span>
                </a>
            </div>
            <!-- /Logo -->

            <div class="card-body mt-1">
                <h4 class="mb-1">Welcome to Weekday! 👋</h4>
                <p class="mb-5">Please log in with your account and start managing your project</p>

                <form id="formAuthentication" class="mb-5" action="{{ route('login.store') }}" method="post">
                    @csrf
                    <div class="form-floating form-floating-outline mb-5">
                        <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        autofocus />
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <div class="form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <label for="password">Password</label>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                        </div>
                        </div>
                    </div>
                    {{-- <div class="mb-5 d-flex justify-content-between mt-5">
                        <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="remember-me" />
                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                        </div>
                        <a href="auth-forgot-password-basic.html" class="float-end mb-1 mt-2">
                        <span>Forgot Password?</span>
                        </a>
                    </div> --}}
                    <div class="mb-5">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Login -->
        <img
          alt="mask"
          src="{{ asset('assets/img/illustrations/auth-basic-login-mask-light.png') }}"
          class="authentication-image d-none d-lg-block"
          data-app-light-img="illustrations/auth-basic-login-mask-light.png"
          data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
      </div>
    </div>
  </div>
@endsection
