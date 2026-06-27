@extends('layout/auth_layout')
@section('title', Route::is('password.reset') ? 'Reset Password' : '')

@section('content')
    <div class="account-page bg-light">
        <div class="container-fluid p-0">
            <div class="row align-items-center g-0">
                <div class="col-xl-5">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                                <div class="mb-4 p-0">
                                    <a class='auth-logo' href='/'>
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-dark"
                                            class="mx-auto" />
                                    </a>
                                </div>

                                <div class="pt-0">
                                    <form action="{{ route('password.update') }}" method="POST"
                                        class="authentication-form">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="{{ $email ?? old('email') }}" placeholder="Enter your email">
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="New Password">
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password Confirmation</label>
                                            <input type="password" id="password" class="form-control"
                                                name="password_confirmation" placeholder="Confirm Password">

                                        </div>
                                        <div class="mb-1 text-center d-grid">
                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <button class="btn btn-primary mt-2" type="submit">Reset Password</button>
                                        </div>

                                        <p class="mb-0 text-center mt-2">Go Back to - <a href="{{ route('login') }}"
                                                class="text-reset fw-bold ms-1">Sign
                                                In</a></p>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="account-page-bg p-md-5 bg-light border p-4">
                        <div class="text-center">
                            <h3 class="text-dark mb-3 pera-title">
                                Set a new password to regain access to your account.
                            </h3>
                            <div class="auth-image">
                                <img src="{{ asset('assets/images/landing/reset-password.webp') }}" class="mx-auto img-fluid" alt="images">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
