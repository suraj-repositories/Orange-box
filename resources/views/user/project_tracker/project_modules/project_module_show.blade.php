@extends('user.layout.layout')

@section('title', Route::is('user.project-board.modules.show') ? 'Project Module' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $projectModule->name }}</h4>
                    </div>

                    <div class="text-end">
                       <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}">Project Board</a></li>
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.project-board.modules.index', ['slug' => $projectBoard->slug]) }}">Modules</a></li>
                            <li class="breadcrumb-item active">Show</li>
                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                                <h5 class="mb-0 fw-bold">
                                    {{ $projectModule->name }}
                                </h5>
                                <button class="btn btn-sm bg-light border" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#projectDescription{{ $projectModule->id }}" aria-expanded="false"
                                    aria-controls="projectDescription{{ $projectModule->id }}">
                                    Description
                                </button>
                            </div>

                            <div class="collapse" id="projectDescription{{ $projectModule->id }}">
                                <div class="card-body">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-12 col-md-8">
                                            <div class="description p-2 rich-editor-content overflow-auto">
                                                {!! Str::markdown($projectModule->description) !!}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="text-center">
                                                {{-- <img src="{{ $projectModule->thumbnail_url }}"
                                                    class="img-fluid rounded shadow-sm" alt="Thumbnail"
                                                    onerror="this.onerror=null;this.src='https://placehold.co/400x300';"> --}}
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
                       <x-project.project-module-task-list-component :project-module="$projectModule" :limit='10'/>
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
