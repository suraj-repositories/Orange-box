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
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.documentation.index') }}">Documentation</a></li>

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
                                <form action="{{ empty($documentation) ? authRoute('user.documentation.store') : authRoute('user.documentation.update', ['documentation' => $documentation->uuid]) }}"
                                    method="POST" enctype="multipart/form-data" >
                                    @csrf

                                    <div class="row">

                                        <!-- Title -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="title-input" class="form-label">Title</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="bi bi-journal-bookmark-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Enter documentation title"
                                                    id="title-input" name="title"
                                                    value="{{ old('title', $documentation->title ?? '') }}">
                                            </div>
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="url-input" class="form-label">Documetation Url</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></i></span>
                                                 <span class="input-group-text" id="basic-addon3">{{ $urlPrefix  }}</span>
                                                <input type="text" class="form-control" accept="image/*"
                                                    id="url-input" name="url" value="{{ old('url', $documentation->url ?? '') }}">
                                            </div>
                                            @error('url')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Logo -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="logo-input" class="form-label">Logo Image</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-image"></i></i></span>
                                                <input type="file" class="form-control" accept="image/*"
                                                    id="logo-input" name="logo">
                                            </div>
                                            @if (!empty($documentation->logo))
                                                <img src="{{ asset('storage/' . $documentation->logo) }}"
                                                    alt="logo" class="img-logo mt-2" width="100">
                                            @endif
                                            @error('logo')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <!-- Submit Button -->
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary" type="submit">
                                                {{ !empty($documentation) ? 'Update' : 'Save' }}
                                            </button>
                                        </div>

                                    </div>
                                </form>

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
    <script src="{{ asset('assets/js/pages/documentation.js') }}"></script>
@endsection
