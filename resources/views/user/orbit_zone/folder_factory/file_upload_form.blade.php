@extends('user.layout.layout')

@section('title', Route::is('user.folder-factory.create') ? 'Upload File' : '🟢🟢🟢')

@section('css')
   <link rel="stylesheet" href="{{ asset('assets/css/file-upload-style.css') }}">
@endsection

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($syntaxStore) ? 'Create' : 'Edit' }} File</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.syntax-store') }}">File Store</a></li>
                            <li class="breadcrumb-item active">{{ empty($syntaxStore) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card file-upload-card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="pick-folder" class="form-label">Select Folder</label>

                                    <select class="form-select" aria-label="Default select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                                </select>
                                </div>

                                <div class="mb-3 image-area" id="uploadFileArea">
                                    <label for="filesUpload" class="form-label">Upload File</label>

                                    <!-- Hidden input to store file data -->
                                    <input type="file" name="file" id="hiddenFileInput" class="hidden">


                                    <div class="dropzone dz-clickable upload_file_input_dropzone" id="branchImageDropzone">
                                        <div class="dz-message needsclick">
                                            <i class="h1 bx bx-cloud-upload"></i>
                                            <h3>Drop files here or click to upload.</h3>
                                            <span class="text-muted fs-13">
                                                (Please select the file you would like to upload.)
                                            </span>
                                        </div>
                                    </div>

                                    <ul class="list-unstyled mb-0" id="dropzone-preview" style="display: none;">
                                        <li class="mt-2" id="dropzone-preview-list">
                                            <div class="border rounded">
                                                <div class="d-flex p-2">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm bg-light rounded">
                                                            <img data-dz-thumbnail class="img-fluid rounded d-block"
                                                                src="{{ config('constants.DEFAULT_UPLOAD_FILE_DROPZONE_IMAGE') }}"
                                                                alt="Dropzone-Image" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="pt-1">
                                                            <h5 class="fs-14 mb-1" data-dz-name>&nbsp;
                                                            </h5>
                                                            <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                            {{-- <strong class="error text-danger" data-dz-errormessage></strong> --}}
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-3">
                                                        <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                                    </div>

                                                </div>

                                            </div>
                                        </li>
                                    </ul>

                                </div>

                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary ms-auto" id="fileUploadBtn">
                                        Upload
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div class="processing_files" id="processing_files"></div>



                        <template id="file-progress-template">
                            <div class="card upload-progress-card">

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="upload-data">
                                            <i class="bi " id="file_icon"></i> <!-- bi-filetype-pdf -->
                                            <div class="title-area">
                                                <div class="title" id="file_name">
                                                    <span class="placeholder col-12 bg-secondary"></span>
                                                </div>
                                                <div class="sub-title">
                                                    <span class="precentage" id="completed_percentage">0% completed</span>
                                                    <span class="divider">|</span>
                                                    <span class="time" id="file_size">45 MB</span>
                                                    <span class="divider">|</span>
                                                    <span class="time" id="time_remaining">00:00 minutes remaining</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <div class="h-100 w-100 align-content-center me-1">
                                            <div class="progress rounded-0">
                                                <div class="progress-bar bg-dark progress-bar-striped progress-bar-animated"
                                                    role="progressbar" style="width: 75%" aria-valuenow="75"
                                                    aria-valuemin="0" aria-valuemax="100" id="progress_bar"></div>
                                            </div>

                                        </div>

                                        <button id="stop-upload" class="btn stop-upload p-0 m-0">
                                            <iconify-icon class="text-danger fs-2"
                                                icon="solar:close-circle-bold-duotone"></iconify-icon>
                                        </button>

                                    </div>
                                </div>
                            </div>

                        </template>

                    </div>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>

    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/chunk-upload-files.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/dropzone-config.js') }}"></script>

@endsection

@section('js')
@endsection
