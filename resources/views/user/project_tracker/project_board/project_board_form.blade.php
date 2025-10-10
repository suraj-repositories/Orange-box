@extends('user.layout.layout')

@section('title',
    Route::is('user.project-board.create')
    ? 'Create Project'
    : (Route::is('user.project-board.edit')
    ? 'Edit
    Project Board'
    : '🟢🟢🟢'))

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($projectBoard) ? 'Create' : 'Edit' }} Project Board</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board') }}">Project Board</a></li>
                            <li class="breadcrumb-item active">{{ empty($projectBoard) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($projectBoard) ? 'Create' : 'Edit' }} Project Board
                                </h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ !empty($projectBoard) ? authRoute('user.project-board.update', ['projectBoard' => $projectBoard]) : authRoute('user.project-board.store') }}"
                                    method="POST" enctype="multipart/form-data" >
                                    @csrf

                                    <div class="row">

                                        <!-- Title -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="title-input" class="form-label">Title</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="bi bi-journal-bookmark-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Enter project title"
                                                    id="title-input" name="title"
                                                    value="{{ old('title', $projectBoard->title ?? '') }}">
                                            </div>
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Thumbnail -->
                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="thumbnail-input" class="form-label">Thumbnail Image</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-image"></i></i></span>
                                                <input type="file" class="form-control" accept="image/*"
                                                    id="thumbnail-input" name="thumbnail">
                                            </div>
                                            @if (!empty($projectBoard->thumbnail))
                                                <img src="{{ asset('storage/' . $projectBoard->thumbnail) }}"
                                                    alt="Thumbnail" class="img-thumbnail mt-2" width="100">
                                            @endif
                                            @error('thumbnail')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Color Tag -->
                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="color-tag-input" class="form-label">Color Tag</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-palette"></i></span>
                                                <select class="form-select" name="color_tag">
                                                    <option selected disabled>--select a color tag--</option>
                                                    @foreach ($tagColors as $tag)
                                                        <option value="{{ $tag->code }}"
                                                            {{ old('color_tag', $projectBoard->color_tag ?? '') == $tag->code ? 'selected' : '' }}>
                                                            {{ $tag->emoji }} {{ $tag->label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('color_tag')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="editor" class="form-label">Description</label>
                                            <textarea class="form-control ckeditor" name="description" id="editor" cols="30" rows="3">{{ old('description', $projectBoard->description ?? '') }}</textarea>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Start Date -->
                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="start-date-input" class="form-label">Start Date</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                                <input type="date" class="form-control" id="start-date-input"
                                                    name="start_date"
                                                    value="{{ old('start_date', $projectBoard->start_date ?? '') }}">
                                            </div>
                                            @error('start_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- End Date -->
                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="end-date-input" class="form-label">End Date</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                                <input type="date" class="form-control" id="end-date-input"
                                                    name="end_date"
                                                    value="{{ old('end_date', $projectBoard->end_date ?? '') }}">
                                            </div>
                                            @error('end_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary" type="submit">
                                                {{ !empty($projectBoard) ? 'Update Project' : 'Create Project' }}
                                            </button>
                                        </div>

                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>


            </div> <!-- container-fluid -->

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

    @include('layout.extras.ckeditor5')
    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/add-media.js') }}"></script>
    <script src="{{ asset('assets/js/pages/project-board.js') }}"></script>
@endsection
