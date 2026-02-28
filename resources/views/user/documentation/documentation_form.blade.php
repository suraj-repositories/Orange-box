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
                                <form
                                    action="{{ empty($documentation) ? authRoute('user.documentation.store') : authRoute('user.documentation.update', ['documentation' => $documentation->uuid]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">

                                        <!-- Title -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="title-input" class="form-label">Title</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="bi bi-journal-bookmark-fill"></i></span>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter documentation title" id="title-input" name="title"
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
                                                <span class="input-group-text" id="basic-addon3">{{ $urlPrefix }}</span>
                                                <input type="text" class="form-control" accept="image/*" id="url-input"
                                                    name="url" value="{{ old('url', $documentation->url ?? '') }}">
                                            </div>
                                            @error('url')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col col-12 col-md-12 mb-3">

                                            <label class="form-label d-flex justify-content-between align-items-center">
                                                <span>Logo Images</span>

                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="logoCustomizeToggle"
                                                        {{ !empty($documentation?->logo_dark) ||
                                                        !empty($documentation?->logo_sm_dark) ||
                                                        $errors->has('logo_dark') ||
                                                        $errors->has('logo_sm_dark')
                                                            ? 'checked'
                                                            : '' }}>
                                                    <label class="form-check-label small" for="logoCustomizeToggle">
                                                        Customize Dark Mode
                                                    </label>
                                                </div>
                                            </label>

                                            <div class="doc-logo-picker mt-3">

                                                <label class="{{ $errors->has('logo_light') ? 'has-error' : '' }}">
                                                    <span>Logo</span>
                                                    <input type="file" name="logo_light" accept="image/*"
                                                        data-existing="{{ !empty($documentation?->logo_light) ? Storage::url($documentation->logo_light) : '' }}">

                                                    @error('logo_light')
                                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                    @enderror
                                                </label>


                                                <label class="{{ $errors->has('logo_sm_light') ? 'has-error' : '' }}">
                                                    <span>Logo Small</span>
                                                    <input type="file" name="logo_sm_light" accept="image/*"
                                                        data-existing="{{ !empty($documentation?->logo_sm_light) ? Storage::url($documentation->logo_sm_light) : '' }}">

                                                    @error('logo_sm_light')
                                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                    @enderror
                                                </label>


                                                <label
                                                    class="dark-logo
                                                        {{ !empty($documentation?->logo_dark) || $errors->has('logo_dark') ? '' : 'd-none' }}
                                                        {{ $errors->has('logo_dark') ? 'has-error' : '' }}">

                                                    <span>Logo Dark</span>
                                                    <input type="file" name="logo_dark" accept="image/*"
                                                        data-existing="{{ !empty($documentation?->logo_dark) ? Storage::url($documentation->logo_dark) : '' }}">

                                                    @error('logo_dark')
                                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                    @enderror
                                                </label>


                                                <label
                                                    class="dark-logo
                                                    {{ !empty($documentation?->logo_sm_dark) || $errors->has('logo_sm_dark') ? '' : 'd-none' }}
                                                    {{ $errors->has('logo_sm_dark') ? 'has-error' : '' }}">

                                                    <span>Logo Small Dark</span>
                                                    <input type="file" name="logo_sm_dark" accept="image/*"
                                                        data-existing="{{ !empty($documentation?->logo_sm_dark) ? Storage::url($documentation->logo_sm_dark) : '' }}">

                                                    @error('logo_sm_dark')
                                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                    @enderror
                                                </label>

                                            </div>
                                        </div>

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

    <script>
        const toggle = document.getElementById("logoCustomizeToggle");
        const darkLogos = document.querySelectorAll(".doc-logo-picker .dark-logo");

        toggle.addEventListener("change", function() {
            darkLogos.forEach(el => {
                el.classList.toggle("d-none", !this.checked);
            });
        });

        document.querySelectorAll(".doc-logo-picker label").forEach(label => {

            const input = label.querySelector("input[type='file']");
            const existingImage = input.dataset.existing;

            const pickerBox = document.createElement("div");
            pickerBox.classList.add("picker-box");

            input.insertAdjacentElement('afterend', pickerBox);

            function renderPreview(src) {
                pickerBox.innerHTML = "";

                const img = document.createElement("img");
                img.src = src;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.classList.add("remove-btn");
                removeBtn.innerHTML = "×";

                removeBtn.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    input.value = "";
                    pickerBox.innerHTML = "<span>Click to upload</span>";
                };

                pickerBox.appendChild(img);
                pickerBox.appendChild(removeBtn);
            }

            if (existingImage) {
                renderPreview(existingImage);
            } else {
                pickerBox.innerHTML = "<span>Click to upload</span>";
            }

            input.addEventListener("change", function() {
                if (!this.files || !this.files[0]) return;

                const reader = new FileReader();
                reader.onload = e => renderPreview(e.target.result);
                reader.readAsDataURL(this.files[0]);
                label.classList.remove('has-error');
            });
        });
    </script>
@endsection
