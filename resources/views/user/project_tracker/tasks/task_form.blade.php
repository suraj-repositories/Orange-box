@extends('user.layout.layout')

@section('title',
    Route::is('user.project-board.modules.tasks.createNested') || Route::is('user.tasks.create')
    ? 'Create
    Task'
    : (Route::is('user.project-board.edit')
    ? 'Edit Task'
    : '🟢🟢🟢'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-theme.min.css') }}">
@endsection

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($task) ? 'Create' : 'Edit' }} Task</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board') }}">Task</a></li>
                            <li class="breadcrumb-item active">{{ empty($task) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($task) ? 'Create' : 'Edit' }} Task
                                </h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ authRoute('user.project-board.modules.tasks.store', ['slug' => $projectBoard->slug, 'module' => $projectModule->slug]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="title-input" class="form-label">Task title</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="bi bi-journal-bookmark-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Enter title"
                                                    id="title-input" name="title"
                                                    value="{{ old('title', $task->title ?? '') }}">
                                            </div>
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>



                                        <!-- Description -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="editor" class="form-label">Description</label>
                                            <textarea class="form-control ckeditor" name="description" id="editor" cols="30" rows="3">{{ old('description', $task->description ?? '') }}</textarea>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Assign To -->
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="assigned-to-input" class="form-label">Assign To</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text">
                                                    <i class='bx bx-user fs-5'></i>
                                                </span>
                                                <select class="form-select select2-with-image" id="assigned-to-input" name="assigned_to">
                                                    <option value="" selected disabled>-- Choose a Team Member --
                                                    </option>
                                                    @foreach ($projectModule->projectModuleUsers as $moduleUser)
                                                        <option data-image="{{ $moduleUser->user->profilePicture() }}" value="{{ $moduleUser->user->id }}">
                                                            {{ $moduleUser->user->username }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('assigned_to')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <!-- Priority -->
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="priority-input" class="form-label">Priority</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class='bx bx-pyramid fs-5'></i>
                                                </span>
                                                <select class="form-select" id="priority-input" name="priority">
                                                    <option value="" selected disabled>-- Select Priority --</option>
                                                    <option value="low">🟢 Low</option>
                                                    <option value="medium">🟡 Medium</option>
                                                    <option value="high">🟠 High</option>
                                                    <option value="urgent">🔴 Urgent</option>
                                                </select>
                                            </div>
                                            @error('priority')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Deadline -->
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="due-date-input" class="form-label">Deadline</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-calendar-event"></i>
                                                </span>
                                                <input type="datetime-local" class="form-control" id="due-date-input"
                                                    name="due_date" value="{{ old('due_date', $task->due_date ?? '') }}">
                                            </div>
                                            @error('due_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
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
                                            @error('media_files')
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
@endsection

@section('js')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/select2.init.js') }}"></script>
@endsection
