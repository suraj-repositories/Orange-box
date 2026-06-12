@extends('user.layout.layout')
@php
    if (empty($documentation) && Route::is('user.documentation.create')) {
        $pageTitle = 'Create Documentation';
    } elseif (!empty($documentation) && Route::is('user.documentation.edit')) {
        $pageTitle = 'Edit Documentation';
    } else {
        $pageTitle = '🟢🟢🟢';
    }
@endphp

@section('title', $pageTitle)

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Documentation</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.documentation.index') }}">Documentation</a></li>

                            <li class="breadcrumb-item active">{{ empty($documentation) ? 'New' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($documentation) ? 'Create' : 'Edit' }} Documentation
                                </h5>
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

@section('js')

@endsection
