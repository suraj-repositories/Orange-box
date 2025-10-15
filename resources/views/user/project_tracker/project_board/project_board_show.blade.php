@extends('user.layout.layout')

@section('title', Route::is('user.project-board.show') ? 'Project Board' : '🟢🟢🟢')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.css') }}">
@endsection
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

                <x-alert-component />

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm mb-3">
                            <div
                                class="card-header d-flex align-items-center justify-content-between flex-wrap border-double-5 border-{{ strtolower($projectBoard->colorTag?->name ?? '') }}">
                                <h5 class="mb-0 fw-bold">
                                    {{ $projectBoard->title }}
                                </h5>
                                <div class="action-container m-0 gap-1">
                                    <a href="javascript::void(0)" class="text-reset" data-bs-toggle="collapse"
                                        data-bs-target="#projectDescription{{ $projectBoard->id }}" aria-expanded="false"
                                        aria-controls="projectDescription{{ $projectBoard->id }}">
                                        <i class='bx bx-info-circle'></i>
                                    </a>

                                    <a href="{{ authRoute('user.project-board.edit', ['slug' => $projectBoard->slug]) }}"
                                        class="edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form
                                        action="{{ authRoute('user.project-board.delete', ['slug' => $projectBoard->slug]) }}"
                                        method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="delete btn-no-style">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                    {{-- <div class="more">
                                        <i class='bx bx-dots-vertical-rounded' ></i>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="collapse" id="projectDescription{{ $projectBoard->id }}">
                                <div class="card-body border-double-5 border-{{ strtolower($projectBoard->colorTag?->name ?? '') }}">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-12 col-md-8">
                                            @php
                                                $colorTag = $projectBoard->colorTag;
                                            @endphp

                                            @if ($colorTag)
                                                <div class="d-flex mb-2">
                                                    <div
                                                        class="badge badge-{{ strtolower($colorTag->name ?? '') }} d-flex align-items-center p-2">
                                                        <span class="badge-circle me-1"></span>
                                                        <span>{{ $colorTag->name }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="description p-2 rich-editor-content overflow-auto">
                                                {!! Str::markdown($projectBoard->description ?? "") !!}
                                            </div>


                                            @php $assignees = $projectBoard->users; @endphp

                                            @if ($assignees && $assignees->isNotEmpty())
                                                <h6 class="mt-3 fw-bold">Team Members</h6>

                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($assignees as $user)
                                                        <div class="chip pe-3" data-ob-uid="{{ $user->id }}">
                                                            <img src="{{ $user->profilePicture() }}"
                                                                alt="{{ $user->username }}" width="96" height="96">
                                                            {{ $user->username }}
                                                        </div>
                                                    @endforeach

                                                </div>
                                            @endif

                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="text-center" data-image-preview="true" data-image-downloadable="true">
                                                <img src="{{ $projectBoard->thumbnail_url }}"
                                                    class="img-fluid rounded shadow-sm" alt="Thumbnail"
                                                    onerror="this.onerror=null;this.src='https://placehold.co/400x300';">


                                                <div class="row g-2 mt-2">
                                                    @if (!empty($projectBoard->start_date))
                                                        <div class="col-{{ empty($projectBoard->end_date) ? '12' : '6' }}">
                                                            <div class="label label-info w-100" title="Start Date">
                                                                @if (empty($projectBoard->end_date))
                                                                    <strong>Start Date -</strong>
                                                                @endif
                                                                {{ date('M d, Y', strtotime($projectBoard->start_date)) }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if (!empty($projectBoard->end_date))
                                                        <div
                                                            class="col-{{ empty($projectBoard->start_date) ? '12' : '6' }}">
                                                            <div class="label label-danger w-100" title="Deadline Date">
                                                                @if (empty($projectBoard->start_date))
                                                                    <strong>Deadline Date -</strong>
                                                                @endif
                                                                {{ date('M d, Y', strtotime($projectBoard->end_date)) }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-12">
                                                        <div class="label label-primary w-100" title="Created Date">
                                                            <strong>Created At -</strong>
                                                            {{ date('M d, Y h:i a', strtotime($projectBoard->created_at)) }}
                                                        </div>
                                                    </div>

                                                </div>
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
                        <x-project.project-module-list-component :project-board="$projectBoard" :limit='10' />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <x-project.project-module-task-list-component :project-board="$projectBoard" :limit='10' />
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

@section('js')
    <script src="{{ asset('assets/js/pages/task_list.js') }}"></script>

    <script src="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.js') }}"></script>
@endsection
