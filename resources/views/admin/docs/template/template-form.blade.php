@extends('admin.layout.layout')

@section('title', empty($template) ? 'Create Template' : 'Edit Template')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Templates</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('admin.docs.templates.index') }}">Templates</a></li>

                            <li class="breadcrumb-item active">{{ empty($template) ? 'New' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />


                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($template) ? 'Create' : 'Edit' }} Template
                                </h5>
                            </div>

                            <div class="card-body">
                                <form id="templateForm"
                                    action="{{ empty($template)
                                        ? authRoute('admin.docs.templates.store')
                                        : authRoute('admin.docs.templates.update', ['template' => $template]) }}"
                                    method="POST" enctype="multipart/form-data">

                                    @csrf
                                    @if (!empty($template))
                                        @method('PUT')
                                    @endif

                                    <div class="row">

                                        <!-- Title -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Title</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-journal-bookmark-fill"></i>
                                                </span>
                                                <input type="text" name="title" class="form-control"
                                                    placeholder="Enter template title"
                                                    value="{{ old('title', $template->title ?? '') }}">
                                            </div>
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Key -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Key</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-key-fill"></i>
                                                </span>
                                                <input type="text" name="key" class="form-control"
                                                    placeholder="Unique key (slug)"
                                                    value="{{ old('key', $template->key ?? '') }}">
                                            </div>
                                            @error('key')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label">Preview Url</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-link"></i>
                                                </span>
                                                <input type="text" name="preview_url" class="form-control"
                                                    placeholder="https://example.com"
                                                    value="{{ old('preview_url', $template->preview_url ?? '') }}">
                                            </div>
                                            @error('preview_url')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" rows="4" class="form-control" placeholder="Enter description">{{ old('description', $template->description ?? '') }}</textarea>

                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Price -->
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Original Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" name="original_price"
                                                    class="form-control" placeholder="Enter price"
                                                    value="{{ old('original_price', $template->original_price ?? '') }}">
                                            </div>
                                            @error('original_price')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Discount Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" name="price" class="form-control"
                                                    placeholder="Enter price"
                                                    value="{{ old('price', $template->price ?? '') }}">
                                            </div>
                                            @error('price')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Preview Image -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Thumbnail Image</label>
                                            <input type="file" name="preview_image" class="form-control"
                                                accept="image/*">

                                            @if (!empty($template?->preview_image))
                                                <div class="multi-image-container mt-3">
                                                    <div class="image-box">
                                                        <img src="{{ Storage::url($template->preview_image) }}">
                                                    </div>
                                                </div>
                                            @endif

                                            @error('preview_image')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <div class="col-12 mb-3">
                                            <label class="form-label">Other Images</label>

                                            <input type="file" name="images[]" class="form-control" multiple
                                                accept="image/*" id="multiImagePicker">

                                            @error('images')
                                                <small class="text-danger d-block">{{ $message }}</small>
                                            @enderror

                                            @foreach ($errors->get('images.*') as $messages)
                                                @foreach ($messages as $message)
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @endforeach
                                            @endforeach

                                            <div class="multi-image-container mt-3" id="multiImageContainer">
                                                @if ($template?->files?->isNotEmpty())
                                                    @foreach ($template->files as $file)
                                                        <div class="image-box">
                                                            <img src="{{ $file->getFileUrl() }}" alt="Image">

                                                            <button type="button" class="remove-btn"
                                                                data-deletion-url="{{ route('file.delete', $file) }}">
                                                                &times;
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Submit -->
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary" type="submit">
                                                {{ !empty($template) ? 'Update' : 'Save' }}
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
    <script src="{{ asset('assets/js/pages/admin/docs-templates.js') }}"></script>
@endsection
