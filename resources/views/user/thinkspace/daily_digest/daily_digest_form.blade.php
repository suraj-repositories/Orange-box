@extends('user.layout.layout')

@section('title',
    Route::is('user.daily-digest.create')
    ? 'Daily Digest'
    : (Route::is('user.daily-digest.edit')
    ? 'Edit
    Daily Digest'
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
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($dailyDigest) ? 'Create' : 'Edit' }} Daily Digest</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.daily-digest') }}">Daily Digest</a></li>
                            <li class="breadcrumb-item active">{{ empty($dailyDigest) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($dailyDigest) ? 'Create' : 'Edit' }} Digestion</h5>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <form
                                    action="{{ !empty($dailyDigest) ? authRoute('user.daily-digest.update', ['dailyDigest' => $dailyDigest]) : authRoute('user.daily-digest.store') }}"
                                    method="POST" enctype="multipart/form-data" id="add-digest-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col col-12 col-md-6  mb-3">
                                            <label for="title-input" class="form-label">Title</label>

                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1"> <i
                                                        class="bi bi-journal-bookmark-fill"></i> </span>
                                                <input type="text" class="form-control" placeholder="Enter title"
                                                    id="title-input" name="title"
                                                    value="{{ !empty($dailyDigest) ? $dailyDigest->title : '' }}">
                                            </div>
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="sub_title-input" class="form-label">Sub title</label>

                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="bi bi-list-nested"></i> </span>
                                                <input type="text" class="form-control" placeholder="Enter sub title"
                                                    id="sub_title-input" name="sub_title"
                                                    value="{{ !empty($dailyDigest) ? $dailyDigest->sub_title : '' }}">
                                            </div>
                                            @error('sub_title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col col-12 col-md-6 mb-3">
                                            <label for="sub_title-input" class="form-label">
                                                Visibility
                                            </label>
                                            <a tabindex="0" class="text-dark" role="button" data-bs-html="true"
                                                data-bs-toggle="popover" data-bs-trigger="focus"
                                                data-bs-custom-class="custom-popover" data-bs-title="Visibility Level"
                                                data-bs-content="
                                               <b>Private</b> - Visible to author only
                                                <br> <b>Protected</b> - Visible for followers
                                                <br> <b>Unlisted</b> - Visitable by only share link
                                                 <br> <b>Public</b> - Visible To Everyone
                                                ">
                                                <i class="bi bi-info-circle"></i>
                                            </a>

                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="bx bx-show-alt"></i> </span>
                                                <select class="form-select select2-with-image" name="visibility">
                                                    @foreach (config('icons.visibility') as $visibility => $icon)
                                                        <option value="{{ $visibility }}"
                                                            data-image="{{ asset($icon) }}"
                                                            {{ !empty($dailyDigest) && $dailyDigest->visibility == $visibility ? 'selected' : '' }}>
                                                            {{ ucfirst($visibility ?? '') }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            @error('visibility')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col col-12 col-md-12">
                                            <label for="title-input" class="form-label">Description</label>

                                            <textarea class="form-control ckeditor" name="description" id="editor" cols="30" rows="3"
                                                data-markdown="{{ !empty($dailyDigest) ? $dailyDigest->description : '' }}">
                                                {{ !empty($dailyDigest) ? trim($dailyDigest->description) : '' }}
                                            </textarea>

                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col col-md-12 mt-3">
                                            <label for="title-input" class="form-label">Media</label>
                                            <br>
                                            <input type="file" class="hide" name="media_files[]" id="media-input"
                                                multiple>

                                            <div class="d-flex align-items-center justify-content-between  mb-2">
                                                <label class="btn btn-primary d-flex align-items-center w-fit"
                                                    for="media-input">
                                                    <i class="bi bi-file-earmark-plus fw-semibold me-1"></i>
                                                    Add Media
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

                                                {{--
                                                <div class="col">
                                                    <div class="card h-100">

                                                        <div class="file-thumb-holder">
                                                            <div class="file-thumb-box">
                                                                <i class="bx bxs-file"></i>
                                                            </div>

                                                            <div class="hover-actions">
                                                                <a class="show" href="javascript:void()"
                                                                    data-bs-toggle="tooltip" data-bs-title="View">
                                                                    <i class='bx bx-show-alt'></i>
                                                                </a>
                                                                <a class="rename" href="javascript:void()"
                                                                    data-bs-toggle="tooltip" data-bs-title="Rename">
                                                                    <i class='bx bx-rename'></i>
                                                                </a>
                                                                <a class="delete" href="javascript:void()"
                                                                    data-bs-toggle="tooltip" data-bs-title="Delete">
                                                                    <i class='bx bx-trash-alt'></i>
                                                                </a>

                                                            </div>
                                                        </div>

                                                        <div class="card-body">
                                                            <h5 class="card-title text-truncate">
                                                                EAadhaar_2728208020303320241102094945_07112024145514 (1)</h5>
                                                            <ul class="list-unstyled mb-0">
                                                                <li><span class="text-muted">Type:</span> Text</li>
                                                                <li><span class="text-muted">Size:</span> 512KB</li>
                                                            </ul>



                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="card h-100">
                                                        <div class="img-container">
                                                            <img src="https://letsenhance.io/static/73136da51c245e80edc6ccfe44888a99/1015f/MainBefore.jpg"
                                                                alt="Image" />
                                                            <div class="hover-actions">
                                                                <a class="show" href="javascript:void()"
                                                                    data-bs-toggle="tooltip" data-bs-title="View">
                                                                    <i class='bx bx-show-alt'></i>
                                                                </a>
                                                                <a class="rename" href="javascript:void()"
                                                                    data-bs-toggle="tooltip" data-bs-title="Rename">
                                                                    <i class='bx bx-rename'></i>
                                                                </a>
                                                                <a class="delete" href="javascript:void()"
                                                                    data-bs-toggle="tooltip" data-bs-title="Delete">
                                                                    <i class='bx bx-trash-alt'></i>
                                                                </a>

                                                            </div>
                                                        </div>

                                                        <div class="card-body">
                                                            <h5 class="card-title">Card title</h5>
                                                            <ul class="list-unstyled mb-0">
                                                                <li><span class="text-muted">Type:</span> Text</li>
                                                                <li><span class="text-muted">Size:</span> 512KB</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            --}}
                                            </div>

                                            <div id="list-view-container"
                                                class="media-upload-preview mt-3 list-view-container">
                                                {{--
                                                <div class="card border-0 rounded-0">
                                                    <div class="horizontal-viewer">
                                                        <div class="icon"><i class="bi bi-file-earmark-code"></i></div>
                                                        <div class="name text-truncate">
                                                            EAadhaar_2728208020303320241102094945_07112024145514.jpg</div>
                                                        <div class="type">JPEG</div>
                                                        <div class="size">200KB</div>
                                                        <div class="action">

                                                            <a class="show" href="javascript:void()"
                                                                data-bs-toggle="tooltip" data-bs-title="View"><i
                                                                    class='bx bx-show-alt'></i></a>
                                                            <a class="rename" href="javascript:void()"
                                                                data-bs-toggle="tooltip" data-bs-title="Rename"><i
                                                                    class='bx bx-rename'></i></a>
                                                            <a class="delete" href="javascript:void()"
                                                                data-bs-toggle="tooltip" data-bs-title="Delete"><i
                                                                    class='bx bx-trash-alt'></i></a>


                                                        </div>
                                                    </div>
                                                </div>
                                            --}}
                                            </div>


                                        </div>

                                        <div class="mt-2">
                                            <button class="btn btn-primary mt-2" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                                @if (!empty($dailyDigest))
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
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>


@endsection

@section('js')
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/select2.init.js') }}"></script>
@endsection
