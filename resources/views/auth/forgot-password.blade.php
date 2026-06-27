@extends('layout/auth_layout')
@section('title', Route::is('password.request') ? 'Forgot Password' : '🟢🟢🟢')

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
                                    <form action="{{ route('password.email') }}" method="POST" class="my-4">
                                        @csrf
                                        <div>
                                            @include('layout.alert')
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="{{ old('email') }}" placeholder="Enter your email">
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mt-2" type="submit">Get Reset
                                                        Link</button>


                                                </div>

                                                <p class="mb-0 text-center mt-2">Go Back to - <a href="{{ route('login') }}"
                                                        class="text-reset fw-bold ms-1">Sign
                                                        In</a></p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="account-page-bg p-md-5 p-4 bg-light border">
                        <div class="text-center">
                            <div class="auth-image">
                                <img src="{{ asset('assets/images/landing/reset-password.webp') }}" class="mx-auto img-fluid" alt="images">
                            </div>
                            <h3 class="text-dark mb-3 pera-title">Forgot your password? <strong>No worries</strong>—we'll help you get back into your account.</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
