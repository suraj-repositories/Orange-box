@extends('user.layout.layout')

@section('title', $title)


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/editorjs-custom.css') }}">
@endsection


@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Documentation Partners</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">

                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.profile.index') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentation</a>
                            </li>

                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.partners.index', ['documentation' => $documentation->uuid]) }}">Partners</a>
                            </li>

                            <li class="breadcrumb-item active">
                                {{ empty($partner) ? 'New' : 'Edit' }}
                            </li>

                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    {{ empty($partner) ? 'Create' : 'Edit' }} Partner
                                </h5>
                            </div>

                            <div class="card-body">

                                <form
                                    action="{{ authRoute('user.documentation.partners.save', ['documentation' => $documentation->uuid]) }}"
                                    method="POST" enctype="multipart/form-data">

                                    @csrf
                                    @isset($partner)
                                        <input type="hidden" name="partner_id" value="{{ $partner->id }}">
                                    @endisset

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Name</label>

                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-building"></i>
                                                </span>

                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Partner name"
                                                    value="{{ old('name', $partner->name ?? '') }}">
                                            </div>

                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>

                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope"></i>
                                                </span>

                                                <input type="text" name="email" class="form-control"
                                                    placeholder="Partner contact email"
                                                    value="{{ old('email', $partner->email ?? '') }}">
                                            </div>

                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>


                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">Website URL</label>

                                            <div class="input-group">

                                                <span class="input-group-text">
                                                    <i class="bi bi-globe"></i>
                                                </span>

                                                <input type="url" name="website_url" class="form-control"
                                                    placeholder="https://example.com"
                                                    value="{{ old('website_url', $partner->website_url ?? '') }}">

                                            </div>

                                            @error('website_url')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>


                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">Location</label>

                                            <div class="input-group">

                                                <span class="input-group-text">
                                                    <i class="bi bi-geo-alt"></i>
                                                </span>

                                                <input type="text" name="location" class="form-control"
                                                    placeholder="City, Country"
                                                    value="{{ old('location', $partner->location ?? '') }}">

                                            </div>

                                            @error('location')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>

                                        <div class="col-12">
                                            <label for="logo_input" class="form-label">Partner
                                                Media</label>
                                            <div class="doc-logo-picker mt-1 partners-media-picker">

                                                <label>
                                                    <span>Logo (Light)</span>
                                                    <input type="file" name="logo_light" accept="image/*"
                                                        id="logo_light_input"
                                                        data-existing="{{ !empty($partner?->logo_light) ? Storage::url($partner->logo_light) : '' }}">
                                                </label>
                                                <label>
                                                    <span>Logo (Dark)</span>
                                                    <input type="file" name="logo_dark" accept="image/*"
                                                        id="logo_dark_input"
                                                        data-existing="{{ !empty($partner?->logo_dark) ? Storage::url($partner->logo_dark) : '' }}">
                                                </label>

                                                <label>

                                                    <span>Banner</span>
                                                    <input type="file" name="banner" accept="image/*"
                                                        data-existing="{{ !empty($partner?->banner) ? Storage::url($partner->banner) : '' }}">
                                                </label>
                                            </div>
                                        </div>


                                        <div class="col-12 my-3">

                                            <label class="form-label">Short Description</label>

                                            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $partner->short_description ?? '') }}</textarea>

                                            @error('short_description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>


                                        <div class="col-12 mb-3">

                                            <label class="form-label">Description</label>

                                            <textarea name="description" class="form-control ckeditor ckeditor-minimal"
                                                data-markdown="{{ old('description', $partner->description ?? '') }}" rows="5">{{ old('description', $partner->description ?? '') }}</textarea>

                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>

                                        <div class="col-12 mt-2">

                                            <button class="btn btn-primary" type="submit">
                                                {{ !empty($partner) ? 'Update' : 'Save' }}
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
    @include('layout.extras.ckeditor5')

    <script src="{{ asset('assets/js/pages/documentation-partners.js') }}"></script>
@endsection
