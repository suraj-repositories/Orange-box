@extends('user.layout.layout')

@section('title', Route::is('user.project-board.show') ? 'Project Board' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Project Board</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board') }}">Project Board</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                                <h5 class="mb-0 fw-bold">
                                    {{ $projectBoard->title }}
                                </h5>
                                <button class="btn btn-sm bg-light border" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#projectDescription{{ $projectBoard->id }}" aria-expanded="false"
                                    aria-controls="projectDescription{{ $projectBoard->id }}">
                                    Description
                                </button>
                            </div>

                            <div class="collapse" id="projectDescription{{ $projectBoard->id }}">
                                <div class="card-body">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-12 col-md-8">
                                            <div class="description p-2 rich-editor-content overflow-auto">
                                                {!! Str::markdown($projectBoard->description) !!}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="text-center">
                                                <img src="{{ $projectBoard->thumbnail_url }}"
                                                    class="img-fluid rounded shadow-sm" alt="Thumbnail"
                                                    onerror="this.onerror=null;this.src='https://placehold.co/400x300';">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <x-project.project-module-list-component :project-board="$projectBoard" :limit='10'/>
                  </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections">
                                        <i data-feather="crosshair" class="widgets-icons"></i>
                                    </div>
                                    <h5 class="card-title mb-0">Resent Tasks</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Task Name</th>
                                                <th>Created Date</th>
                                                <th>Number of Task</th>
                                                <th>Deadline</th>
                                                <th>Project</th>
                                                <th>Assignee</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input m-0 align-middle"
                                                    aria-label="Select task" checked>
                                            </td>
                                            <td>
                                                <a href="#" class="text-reset">Review codebase for
                                                    security vulnerabilities</a>
                                            </td>
                                            <td class="text-nowrap text-reset">
                                                <i data-feather="calendar" style="height: 18px; width: 18px;"
                                                    class="me-1"></i>
                                                May 15, 2023
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="#" class="text-reset">
                                                    <i data-feather="check" style="height: 18px; width: 18px;"
                                                        class="me-1"></i>
                                                    5/8
                                                </a>
                                            </td>
                                            <td class="text-nowrap text-reset">
                                                <i data-feather="calendar" style="height: 18px; width: 18px;"
                                                    class="me-1"></i>
                                                December 08, 2023
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="#" class="text-reset">
                                                    <i data-feather="folder" style="height: 18px; width: 18px;"
                                                        class="me-1"></i>
                                                    6
                                                </a>
                                            </td>
                                            <td>
                                                <img src="/assets/images/users/user-10.jpg"
                                                    class="avatar avatar-sm img-fluid rounded-2" />
                                            </td>
                                        </tr>

                                    </table>
                                </div>
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
