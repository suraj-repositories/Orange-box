@extends('user.layout.layout')

@section('title', Route::is('user.daily-digest.create') ? 'Daily Digest' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Create Daily Digest</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Daily Digest</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">New Digestion</h5>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col col-12 col-md-6">
                                        <label for="title-input" class="form-label">Title</label>

                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"> <i
                                                    class="bi bi-journal-bookmark-fill"></i> </span>
                                            <input type="text" class="form-control" placeholder="Username"
                                                id="title-input">
                                        </div>
                                    </div>
                                    <div class="col col-12 col-md-6"></div>
                                    <div class="col col-12 col-md-12">
                                        <label for="title-input" class="form-label">Content</label>

                                        <textarea class="form-control ckeditor" name="description" id="editor" cols="30" rows="3"></textarea>
                                    </div>

                                    <div class="col col-md-12 mt-3">
                                        <label for="title-input" class="form-label">Media</label>
                                        <br>
                                        <input type="file" class="hide" id="media-input" multiple>

                                        <div class="d-flex align-items-center justify-content-between  mb-2">
                                            <label class="btn btn-primary d-flex align-items-center w-fit"
                                                for="media-input">
                                                <i class="bi bi-file-earmark-plus fw-semibold me-1"></i>
                                                Add Media
                                            </label>
                                            <div class="btn-group-horizontal grid-list hide-lt-730" role="group"\>
                                                <input type="radio" class="btn-check" name="hbtn-radio" id="card-radio"
                                                    autocomplete="off" checked>
                                                <label class="btn btn-outline-primary" for="card-radio"><i
                                                        class='bx bx-grid-alt'></i></label>
                                                <input type="radio" class="btn-check" name="hbtn-radio" id="list-radio"
                                                    autocomplete="off">
                                                <label class="btn btn-outline-primary" for="list-radio"><i
                                                        class='bx bx-list-ul'></i></label>
                                            </div>

                                        </div>


                                        <div id="card-view-container"
                                            class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-6 g-3 media-upload-preview image-cards">
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

                                        <div id="list-view-container" class="media-upload-preview mt-3 ">
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
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


            </div> <!-- container-fluid -->

        </div> <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col fs-13 text-muted text-center">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script> - Made with <span class="mdi mdi-heart text-danger"></span> by <a
                            href="#!" class="text-reset fw-semibold">Zoyothemes</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

    </div>

    @include('layout.ckeditor5')
    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>
@endsection
