@extends('layout/auth_layout')
@section('title', Route::is('lock-screen.index') ? 'Locked' : '🟢🟢🟢')

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
                                    <form action="{{ route('unlock') }}" method="POST"  id="unlock-form" class="my-4">
                                        @csrf
                                        <div>
                                            @include('layout.alert')
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="pin" class="form-label"><i class='bx bx-pin fs-5 position-relative top-4-px'></i> PIN</label>
                                            <input class="form-control" type="password" name="pin" id="pin"
                                                placeholder="Enter PIN" value="123456">
                                            @error('pin')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit"> Unlock </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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


