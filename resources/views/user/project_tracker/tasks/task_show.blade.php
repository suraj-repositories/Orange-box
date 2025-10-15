@extends('user.layout.layout')

@section('title', Route::is('user.tasks.show') ? 'Task' : '🟢🟢🟢')

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
                        <h4 class="fs-18 fw-semibold m-0">Task</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">

                            <li class="breadcrumb-item"><a href="{{ authRoute('user.tasks.index') }}">Tasks</a>
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
                                    {{ $task->title }}
                                </h5>

                                <div class="action-container m-0 gap-1">
                                    <a href="javascript::void(0)" data-bs-toggle="collapse" title="Description"
                                        data-bs-target="#projectDescription{{ $task->id }}" aria-expanded="false"
                                        aria-controls="projectDescription{{ $task->id }}" class="info ms-0">
                                        <i class='bx bx-info-circle'></i>
                                    </a>
                                    @php
                                        $editRoute =
                                            !empty($projectBoard) && $projectBoard->exists
                                                ? authRoute('user.project-board.modules.tasks.editNested', [
                                                    'slug' => $projectBoard->slug,
                                                    'module' => $task->module->slug,
                                                    'task' => $task->uuid,
                                                ])
                                                : authRoute('user.tasks.edit', ['task' => $task]);
                                    @endphp
                                    <a href="{{ $editRoute }}" class="edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form action="{{ authRoute('user.tasks.delete', ['task' => $task]) }}" method="post">
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

                            <div class="collapse show" id="projectDescription{{ $task->id }}">
                                <div class="card-body">
                                    <div class="row g-3 align-items-start">
                                        <div class="col-12 col-md-8">

                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                <div
                                                    class="badge badge-{{ strtolower($task->priority_color ?? '') }} d-flex align-items-center p-2">
                                                    <span class="badge-circle me-1"></span>
                                                    <span><strong>Priority -</strong> {{ ucfirst($task->priority) }}</span>
                                                </div>
                                                <div
                                                    class="badge badge-{{ strtolower($task->status_color ?? '') }} d-flex align-items-center p-2">
                                                    <span class="badge-circle me-1"></span>
                                                    <span><strong>Status -</strong> {{ ucfirst($task->status) }}</span>
                                                </div>

                                                <a href="{{ authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}"
                                                    class="project-module-project-link text-reset">
                                                    <i class="bx bx-link-external fs-6 me-1"></i>
                                                    <span><strong>Project -</strong> {{ $projectBoard->title }}</span>
                                                </a>
                                                <a href="{{ authRoute('user.project-board.modules.show', ['slug' => $projectBoard->slug, 'module' => $projectModule->slug]) }}"
                                                    class="project-module-project-link text-reset">
                                                    <i class="bx bx-link-external fs-6 me-1"></i>
                                                    <span><strong>Module -</strong> {{ $projectModule->name }}</span>
                                                </a>
                                            </div>

                                            @if ($task->description)
                                                <div class="description p-2 rich-editor-content overflow-auto">
                                                    {!! Str::markdown($task->description ?? '') !!}
                                                </div>
                                            @endif


                                            @php $user = $task->assignedUser; @endphp

                                            @if (!empty($user))
                                                <h6 class="mt-3 fw-bold">Assignee</h6>

                                                <div class="d-flex flex-wrap gap-2">

                                                    <div class="chip pe-3" data-ob-uid="{{ $user->id }}">
                                                        <img src="{{ $user->profilePicture() }}"
                                                            alt="{{ $user->username }}" width="96" height="96">
                                                        {{ $user->username }}
                                                    </div>

                                                </div>
                                            @endif

                                            <h6 class="mt-3 fw-bold">Important Dates</h6>

                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                @if (!empty($task->start_date))
                                                    <div class="label label-info" title="Start Date">
                                                        <strong>Start Date -</strong>
                                                        {{ date('M d, Y', strtotime($task->start_date)) }}
                                                    </div>
                                                @endif

                                                @if (!empty($task->end_date))
                                                    <div class="label label-danger" title="Deadline Date">
                                                        <strong>Deadline Date -</strong>
                                                        {{ date('M d, Y', strtotime($task->end_date)) }}
                                                    </div>
                                                @endif
                                                <div class="label label-primary" title="Created Date">
                                                    <strong>Created At -</strong>
                                                    {{ date('M d, Y h:i a', strtotime($task->created_at)) }}
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
                {{-- <div class="row">
                    <div class="col-md-12">
                        <x-project.project-module-task-list-component :project-board="$projectBoard" :project-module="$projectModule"
                            :limit='10' />
                    </div>
                </div> --}}
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
