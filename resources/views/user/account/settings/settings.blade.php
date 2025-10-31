@extends('user.layout.layout')

@section('title', Route::is('user.settings.index') ? 'Settings' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Settings</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Account</a></li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Settings</h5>
                            </div>

                            <div class="card-body">



                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>


@endsection
