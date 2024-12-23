@extends('user.layout.layout')

@section('title', Route::is('user.daily-digest.show') ? 'Daily Digest' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">
                {{-- <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">View Daily Digest</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Components</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Daily Digest</a></li>
                        <li class="breadcrumb-item active">$G$WY</li>
                    </ol>
                </div>
            </div> --}}

                <!-- start row -->

                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3 daily-digest-show-card">

                            <div class="card-body">

                                <div class="align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{-- <div class="rounded-circle avatar-xxl img-thumbnail float-start d-flex align-items-center">
                                            <div class="emoji">🤣</div>
                                        </div> --}}
                                        <img src="http://ideas.free.nf/storage/profile/qbZVED4EfOwqn5vMAu92GszM8VmSrXmhGV3EBS92.png"
                                            class="rounded-circle avatar-xxl img-thumbnail float-start" alt="image profile">

                                        <div class="overflow-hidden ms-4">
                                            <h4 class="m-0 text-dark fs-20">{{ $dailyDigest->title }}</h4>
                                            <p class="my-1 text-muted fs-16">{{ $dailyDigest->sub_title }}</p>
                                            <span class="fs-15">
                                                <i class="mdi mdi-thumb-up me-1 align-middle"></i>
                                                10
                                                <i class="mdi mdi-thumb-down-outline me-1 ms-1 align-middle"></i>
                                                1
                                                <i class="mdi ms-3 mdi-message me-1 align-middle">   </i>
                                                50
                                                <i class="mdi mdi-calendar-blank-outline ms-2 me-1 align-middle">   </i>

                                                <span>Since - <span
                                                        class="badge bg-primary-subtle text-primary px-2 py-1 fs-13 fw-normal">10 Dec 2024</span>
                                                   </span></span>
                                        </div>
                                    </div>
                                </div>

                                <div id="description-area" class="rich-editor-content">
                                    @if($dailyDigest->description)
                                        <hr>
                                        {!! $dailyDigest->description !!}
                                    @endif
                                </div>



                            </div>
                        </div>


                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-6 g-3 media-upload-preview image-cards card-view-container">

                            <div class="col" data-media-files-index="0">
                                <div class="card h-100">
                                    <div class="img-container">
                                        <img src="#" alt="image">
                                        <div class="hover-actions">
                                            <a class="show" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="View">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                            <a class="rename" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Rename">
                                                <i class="bx bx-rename"></i>
                                            </a>
                                            <a class="delete" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Delete" data-ob-dismiss="delete-card">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title">WhatsApp Image</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><span class="text-muted">Type:</span> JPEG</li>
                                            <li><span class="text-muted">Size:</span> 647.29 KB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col" data-media-files-index="1">
                                <div class="card h-100">
                                    <div class="img-container">
                                        <img src="#" alt="Image">
                                        <div class="hover-actions">
                                            <a class="show" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="View">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                            <a class="rename" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Rename">
                                                <i class="bx bx-rename"></i>
                                            </a>
                                            <a class="delete" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Delete" data-ob-dismiss="delete-card">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title">loading-placeholder-300.svg</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><span class="text-muted">Type:</span> SVG</li>
                                            <li><span class="text-muted">Size:</span> 2.14 KB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col" data-media-files-index="2">
                                <div class="card h-100">
                                    <div class="file-thumb-holder">
                                        <div class="file-thumb-box">
                                            <i class="bi bi-file-earmark-zip"></i>
                                        </div>
                                        <div class="hover-actions">
                                            <a class="show" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="View">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                            <a class="rename" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Rename">
                                                <i class="bx bx-rename"></i>
                                            </a>
                                            <a class="delete" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Delete" data-ob-dismiss="delete-card">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">boxicons-2.1.4.zip</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><span class="text-muted">Type:</span> ZIP</li>
                                            <li><span class="text-muted">Size:</span> 1.86 MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col" data-media-files-index="3">
                                <div class="card h-100">
                                    <div class="file-thumb-holder">
                                        <div class="file-thumb-box">
                                            <i class="bi bi-filetype-mp3"></i>
                                        </div>
                                        <div class="hover-actions">
                                            <a class="show" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="View">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                            <a class="rename" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Rename">
                                                <i class="bx bx-rename"></i>
                                            </a>
                                            <a class="delete" href="javascript:void(0)" data-bs-toggle="tooltip"
                                                data-bs-title="Delete" data-ob-dismiss="delete-card">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">old_Duplicate-Mere Mehboob.mp3</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><span class="text-muted">Type:</span> MP3</li>
                                            <li><span class="text-muted">Size:</span> 5.26 MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>


    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>
@endsection
