@extends('layout/auth_layout')
@section('title', Route::is('login') ? 'Login' : '🟢🟢🟢')

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
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-dark" class="mx-auto" />
                                    </a>
                                </div>

                                <div class="pt-0">
                                    <form action="{{ route('login') }}" method="POST" class="my-4">
                                        @csrf
                                        <div>
                                            @include('layout.alert')
                                        </div>
                                        <div class="form-group mb-3 mt-3">
                                            <label for="emailaddress" class="form-label">Email address</label>
                                            <input class="form-control" type="email" name="email" id="emailaddress"
                                                value="{{ old('email') ?? 'user@gmail.com' }}"
                                                placeholder="Enter your email">
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input class="form-control" type="password" name="password" id="password"
                                                placeholder="Enter your password" value="12345678">
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group d-flex mb-3">
                                            <div class="col-sm-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkbox-signin"
                                                        checked>
                                                    <label class="form-check-label" for="checkbox-signin">Remember
                                                        me</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                <a class='text-muted fs-14' href='auth-recoverpw.html'>Forgot password?</a>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit"> Log In </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="saprator my-4"><span>or sign in with</span></div>

                                    <div class="text-center text-muted mb-4">
                                        <p class="mb-0">Don't have an account ?<a class='text-primary ms-2 fw-medium'
                                                href='{{ route('register') }}'>Sign up</a></p>
                                    </div>

                                  @include('auth.partials.social-media-login-buttons')

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="account-page-bg p-md-5 p-4">
                        <div class="text-center">
                            <h3 class="text-dark mb-3 pera-title">Quick, Effective, and Productive With Tapeli Admin
                                Dashboard</h3>
                            <div class="auth-image">
                                <img src="assets/images/authentication.svg" class="mx-auto img-fluid" alt="images">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
