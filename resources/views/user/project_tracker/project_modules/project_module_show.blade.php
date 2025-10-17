@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

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
                        <h4 class="fs-18 fw-semibold m-0">
                            {{ request()->attributes->get('is_collaboration') ? 'Collaboration ' : '' }} Project Module</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}">Project
                                    Board</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.project-board.modules.index', ['slug' => $projectBoard->slug]) }}">Modules</a>
                            </li>
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

                                <div class="action-container m-0 gap-1">
                                    <a href="javascript::void(0)" data-bs-toggle="collapse" title="Description"
                                        data-bs-target="#projectDescription{{ $projectModule->id }}" aria-expanded="false"
                                        aria-controls="projectDescription{{ $projectModule->id }}" class="info ms-0">
                                        <i class='bx bx-info-circle'></i>
                                    </a>

                                    @if (!request()->attributes->get('is_collaboration'))
                                        <a href="{{ authRoute('user.project-board.modules.edit', ['slug' => $projectBoard->slug, 'module' => $projectModule->slug]) }}"
                                            class="edit">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <form
                                            action="{{ authRoute('user.project-board.modules.delete', ['slug' => $projectBoard->slug, 'module' => $projectModule->slug]) }}"
                                            method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="delete btn-no-style">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="collapse" id="projectDescription{{ $projectModule->id }}">
                                <div class="card-body">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-12 col-md-8">

                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                @php
                                                    $type = $projectModule->projectModuleType;
                                                    $colorTag = $type->colorTag;
                                                @endphp

                                                @if ($type)
                                                    <div
                                                        class="badge badge-{{ strtolower($colorTag?->name ?? '') }} d-flex align-items-center p-2">
                                                        <span class="badge-circle me-1"></span>
                                                        <span>{{ $type->name }}</span>
                                                    </div>
                                                @endif

                                                <a href="{{ request()->attributes->get('is_collaboration')
                                                ? authRoute('user.collab.project-board.show', ['owner' => $projectBoard->user->username, 'slug' => $projectBoard->slug])
                                                : authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}"
                                                    class="project-module-project-link text-reset">
                                                    <i class="bx bx-link-external fs-6 me-1"></i>
                                                    <span><strong>Project -</strong> {{ $projectBoard->title }}</span>
                                                </a>
                                            </div>


                                            <div class="description p-2 rich-editor-content overflow-auto">
                                                {!! Str::markdown($projectModule->description ?? '') !!}
                                            </div>

                                            @php $assignees = $projectModule->assignees; @endphp

                                            @if (!empty($assignees))
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

                                            <h6 class="mt-3 fw-bold">Important Dates</h6>

                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                @if (!empty($projectModule->start_date))
                                                    <div class="label label-info" title="Start Date">
                                                        <strong>Start Date -</strong>
                                                        {{ date('M d, Y', strtotime($projectModule->start_date)) }}
                                                    </div>
                                                @endif

                                                @if (!empty($projectModule->end_date))
                                                    <div class="label label-danger" title="Deadline Date">
                                                        <strong>Deadline Date -</strong>
                                                        {{ date('M d, Y', strtotime($projectModule->end_date)) }}
                                                    </div>
                                                @endif
                                                <div class="label label-primary" title="Created Date">
                                                    <strong>Created At -</strong>
                                                    {{ date('M d, Y h:i a', strtotime($projectModule->created_at)) }}
                                                </div>


                                            </div>

                                        </div>
                                        <div class="col-12 col-md-4">

                                            <div class="image-3-gallery" data-image-preview="true"
                                                data-image-downloadable="true">
                                                @foreach ($imageFiles as $file)
                                                    <div class="img-wrapper">
                                                        <img src="{{ $file->getFileUrl() }}"
                                                            data-title="{{ $file->file_name }}" alt="">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if ($otherFiles->isNotEmpty())
                                                <h6 class="mt-3 fw-bold">Other Files</h6>

                                                <div class="file-list d-flex align-items-center flex-column gap-1">
                                                    @foreach ($otherFiles as $file)
                                                        <div
                                                            class="file-item d-flex align-items-center gap-2 p-2 py-0 border rounded text-reset w-100  py-1">
                                                            <i class="{{ $file->extensionIcon() }} fs-3"></i>
                                                            <span
                                                                class="file-name flex-grow-1 truncate-2 fs-8">{{ $file->file_name }}</span>
                                                            <small
                                                                class="file-size text-muted ms-auto w-fit-content fs-8">({{ $file->size() }})</small>
                                                            <div class="file-actions">

                                                                <a href="{{ $file->getFileUrl() }}" target="_blank">
                                                                    <i class='bx bx-link-external'></i>
                                                                </a>
                                                                <a href="{{ $file->getFileUrl() }}" download> <i
                                                                        class='bx bxs-download'></i></a>

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <x-project.project-module-task-list-component :project-board="$projectBoard" :project-module="$projectModule"
                            :tasks="$tasks" :filter="[]"/>
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
    <script src="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.js') }}"></script>

@endsection
