@extends('user.layout.layout')

@section('title',
    Route::is('user.project-board.modules.create') || Route::is('user.modules.create')
    ? 'Create Project Module'
    : (Route::is('user.project-board.modules.edit') || Route::is('user.modules.edit')
    ? 'Edit
    Project Module'
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
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($projectModule) ? 'Create' : 'Edit' }} Project Module</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board') }}">Project Board</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ !empty($projectBoard) ? authRoute('user.project-board.modules.index', ['slug' => $projectBoard->slug]) : '' }}">
                                    Project Module
                                </a>

                            </li>
                            <li class="breadcrumb-item active">{{ empty($projectModule) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($projectModule) ? 'Create' : 'Edit' }} Project Module
                                </h5>
                            </div>

                            <div class="card-body">

                                @php
                                    if (!empty($projectModule)) {
                                        $action = !empty($projectBoard)
                                            ? authRoute('user.project-board.modules.update', [
                                                'slug' => $projectBoard->slug,
                                                'module' => $projectModule->slug,
                                            ])
                                            : authRoute('user.modules.update', ['module' => $projectModule->slug]);
                                    } else {
                                        $action = !empty($projectBoard)
                                            ? authRoute('user.project-board.modules.save', [
                                                'slug' => $projectBoard->slug,
                                            ])
                                            : authRoute('user.modules.save');
                                    }
                                @endphp

                                <form action="{{ $action }}" method="POST" enctype="multipart/form-data">


                                    @csrf
                                    <div class="row">

                                        @if (!empty($projectBoards))
                                            <div class="col col-12 col-md-12 mb-3">

                                                <label for="pick-project" class="form-label">Select Project</label>

                                                <div class="d-flex">
                                                    <select class="form-select select2-with-image" id="pick-project" name="project_board">
                                                        <option value="" selected disabled>Select Project</option>
                                                        @foreach ($projectBoards as $board)
                                                            <option value="{{ $board->id }}"
                                                                data-image="{{ $board->thumbnail_url }}"
                                                                {{ (!empty($projectModule) && $projectModule->projectBoard->id == $board->id) ? 'selected' : '' }}
                                                                >
                                                                {{ $board->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <a href="#" id="redirect-link" class="d-none"></a>
                                                    <button type="button" id="OpenSelectedProjectBtn"
                                                        class="btn bg-light border ms-1 p-0 center-content px-2 text-primary "
                                                        title="Open Project"><i class='bx bx-link fs-4'></i></button>

                                                </div>
                                                @error('project_board')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="name-input" class="form-label">Module Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="bi bi-journal-bookmark-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Enter name"
                                                    id="name-input" name="name"
                                                    value="{{ old('name', $projectModule->name ?? '') }}">
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
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type->id }}" {{ old('type', $projectModule->project_module_type_id ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('type')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="col col-12 col-md-12 mb-3">
                                            <label for="editor" class="form-label">Description</label>
                                            <textarea class="form-control ckeditor" name="description" id="editor" cols="30" rows="3" data-markdown="{{ !empty($projectModule) ? $projectModule->description : '' }}">{{ old('description', $projectModule->description ?? '') }}</textarea>
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
                                                    value="{{ old('start_date', $projectModule->start_date ?? '') }}">
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
                                                    value="{{ old('end_date', $projectModule->end_date ?? '') }}">
                                            </div>
                                            @error('end_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Team Member -->
                                        <div class="col-12 mb-3">
                                            <label for="description-input" class="form-label">Team Members</label>

                                            <div class="d-flex flex-wrap gap-2">
                                                @php $users = empty($projectModule) ? session('users') : $projectModule->assignees; @endphp

                                                @if ($users)
                                                    @foreach ($users as $user)
                                                        <div class="chip" data-ob-uid="{{ $user->id }}">
                                                            <img src="{{ $user->profilePicture() }}" alt="{{ $user->username }}"
                                                                width="96" height="96">
                                                            {{ $user->username }}
                                                            <input type="hidden" name="user[]"
                                                                value="{{ $user->id }}">
                                                            <span class="closebtn"
                                                                onclick="this.parentElement.remove()">&times;</span>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <div class="circle-40 cursor-pointer pick-user-btn">+</div>
                                            </div>

                                            @error('user[]')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

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
                                            @error('media_files')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror


                                            <div id="card-view-container"
                                                class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-6 g-3 media-upload-preview image-cards card-view-container">

                                                @if (!empty($projectModule))
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

                                @if (!empty($projectModule))
                                    @foreach ($media as $file)
                                        <x-modals.rename-modal :modalId="$file['file_id']" :prevResourceName="$file['file_name']" :formActionUrl="route('file.rename', $file['file_id'])" />
                                    @endforeach
                                @endif

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

@section('js')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/select2.init.js') }}"></script>
@endsection
