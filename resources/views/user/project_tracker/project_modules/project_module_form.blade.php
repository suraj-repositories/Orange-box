@extends('user.layout.layout')

@section('title',
    Route::is('user.project-board.modules.create')
    ? 'Create Project Module'
    : (Route::is('user.project-board.edit')
    ? 'Edit
    Project Module'
    : '🟢🟢🟢'))

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($module) ? 'Create' : 'Edit' }} Project Module</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board') }}">Project Module</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($module) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($module) ? 'Create' : 'Edit' }} Project Module
                                </h5>
                            </div>

                            <div class="card-body">
                                <form
                                    action="{{ !empty($module) ? authRoute('project-board.modules.save', ['slug' => $projectBoard->slug]) : authRoute('user.project-board.modules.save', ['slug' => $projectBoard->slug]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="name-input" class="form-label">Module Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="bi bi-journal-bookmark-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Enter name"
                                                    id="name-input" name="name"
                                                    value="{{ old('name', $module->name ?? '') }}">
                                            </div>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Type -->
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="type-input" class="form-label">Type</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class='bx bx-pyramid fs-5'></i>
                                                </span>
                                                <select class="form-select" id="type-input" name="type">
                                                    <option value="" selected disabled>-- Select Type --</option>
                                                    <option value="development">Development</option>
                                                    <option value="testing">Testing</option>
                                                    <option value="design">Design</option>
                                                    <option value="research">Research</option>
                                                    <option value="documentation">Documentation</option>
                                                    <option value="deployment">Deployment</option>
                                                    <option value="maintenance">Maintenance</option>
                                                    <option value="analytics">Analytics</option>
                                                    <option value="ui-ux">UI/UX</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="editor" class="form-label">Description</label>
                                            <textarea class="form-control ckeditor" name="description" id="editor" cols="30" rows="3">{{ old('description', $module->description ?? '') }}</textarea>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Team Member -->
                                        <div class="col-12 mb-3">
                                            <label for="description-input" class="form-label">Team Members</label>

                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="circle-40 cursor-pointer pick-user-btn">+</div>
                                            </div>

                                            <x-modals.user-picker-modal />
                                        </div>


                                        <div class="col col-md-12 mb-3">
                                            <label for="title-input" class="form-label">Module Documents</label>
                                            <br>
                                            <input type="file" class="hide" name="media_files[]" id="media-input"
                                                multiple>

                                            <div class="d-flex align-items-center justify-content-between  mb-2">
                                                <label class="circle-40 cursor-pointer" for="media-input">
                                                    +
                                                </label>
                                                <div class="btn-group-horizontal grid-list hide-lt-730 hide"
                                                    id="card-list-tab-toggler" role="group">
                                                    <input type="radio" class="btn-check" name="hbtn-radio"
                                                        id="card-radio" autocomplete="off" checked>
                                                    <label class="btn btn-outline-primary" for="card-radio"><i
                                                            class='bx bx-grid-alt'></i></label>
                                                    <input type="radio" class="btn-check" name="hbtn-radio"
                                                        id="list-radio" autocomplete="off">
                                                    <label class="btn btn-outline-primary" for="list-radio"><i
                                                            class='bx bx-list-ul'></i></label>
                                                </div>

                                            </div>
                                            @error('media-files')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror


                                            <div id="card-view-container"
                                                class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-6 g-3 media-upload-preview image-cards card-view-container">

                                                @if (!empty($dailyDigest))
                                                    @foreach ($media as $file)
                                                        @if ($file['is_image'])
                                                            <div class="col" data-ob-deleteable-card="true">
                                                                <div class="card h-100">
                                                                    <div class="img-container">
                                                                        <img src="{{ $file['file_path'] }}"
                                                                            alt="image">
                                                                        <div class="hover-actions">
                                                                            <a class="show"
                                                                                href="{{ $file['file_path'] }}"
                                                                                target="_blank" data-bs-toggle="tooltip"
                                                                                data-bs-title="View">
                                                                                <i class="bx bx-show-alt"></i>
                                                                            </a>
                                                                            <a href="javascript::void(0)" class="rename"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#{{ $file['file_id'] }}"
                                                                                title="Rename">
                                                                                <i class="bx bx-rename"></i>
                                                                            </a>
                                                                            <button type="button" class="delete"
                                                                                data-bs-title="Delete"
                                                                                data-bs-toggle="tooltip"
                                                                                data-ob-dismiss="editing-delete-card"
                                                                                data-ob-delete-url="{{ route('file.delete', $file['file_id']) }}">
                                                                                <i class="bx bx-trash-alt"></i></button>

                                                                        </div>
                                                                    </div>

                                                                    <div class="card-body">
                                                                        <h5 class="card-title">{{ $file['file_name'] }}
                                                                        </h5>
                                                                        <ul class="list-unstyled mb-0">
                                                                            <li><span class="text-muted">Type:</span>
                                                                                {{ $file['extension'] }}</li>
                                                                            <li><span class="text-muted">Size:</span>
                                                                                {{ $file['size'] }}</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col" data-ob-deleteable-card="true">
                                                                <div class="card h-100">
                                                                    <div class="file-thumb-holder">
                                                                        <div class="file-thumb-box">
                                                                            <i class="{{ $file['file_icon_class'] }}"></i>
                                                                        </div>
                                                                        <div class="hover-actions">
                                                                            <a class="show"
                                                                                href="{{ $file['file_path'] }}"
                                                                                target="_blank" data-bs-toggle="tooltip"
                                                                                data-bs-title="View">
                                                                                <i class="bx bx-show-alt"></i>
                                                                            </a>
                                                                            <a href="javascript::void(0)" class="rename"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#{{ $file['file_id'] }}"
                                                                                title="Rename">
                                                                                <i class="bx bx-rename"></i>

                                                                            </a>

                                                                            <button type="button" class="delete"
                                                                                data-bs-title="Delete"
                                                                                data-bs-toggle="tooltip"
                                                                                data-ob-dismiss="editing-delete-card"
                                                                                data-ob-delete-url="{{ route('file.delete', $file['file_id']) }}">
                                                                                <i class="bx bx-trash-alt"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <h5 class="card-title">{{ $file['file_name'] }}
                                                                        </h5>
                                                                        <ul class="list-unstyled mb-0">
                                                                            <li><span class="text-muted">Type:</span>
                                                                                {{ $file['extension'] }}</li>
                                                                            <li><span class="text-muted">Size:</span>
                                                                                {{ $file['size'] }}</li>
                                                                        </ul>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>

                                            <div id="list-view-container"
                                                class="media-upload-preview mt-3 list-view-container">

                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary" type="submit">
                                                Save Module
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
    <script src="{{ asset('assets/js/pages/user-select.js') }}"></script>
@endsection
