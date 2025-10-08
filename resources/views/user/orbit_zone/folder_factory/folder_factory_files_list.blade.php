@extends('user.layout.layout')

@section('title', Route::is('user.folder-factory.files.index') ? 'Folder Factory' : '🟢🟢🟢')


@section('content')
    <div class="content-page">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0 ms-1">{{ $folderFactory->name }}</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.folder-factory') }}">Folder Factory</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $folderFactory->name }}</li>

                        </ol>
                    </div>
                </div>

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom-0 py-2 border-bottom-primary-3">
                                <div class="d-flex flex-wrap w-100 align-items-center">
                                    <img src="{{ $folderFactory->getIconUrl() }}" alt=""
                                        class="me-2 img-badge-30 border-0 loadable">
                                    <h4 class="fs-18 fw-semibold m-0"> {{ $folderFactory->name }} </h4>
                                    <div class="ms-auto fw-semibold">
                                        <a href="{{ authRoute('user.folder-factory.files.create', ['folder' => $folderFactory->slug]) }}"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-upload"></i>
                                            <div> Upload</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="accordion plain-accordion" id="accordionExample">
                                    @forelse ($folderFactory->files as $file)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                                <div class="accordion-button fw-medium collapsed" type="button">

                                                    <div class="file-toggle d-flex align-items-center overflow-hidden w-100"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $loop->iteration }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $loop->iteration }}">
                                                        <div class="icon me-2">
                                                            @if (str_starts_with($file->mime_type, 'image/'))
                                                                <img class="img-badge-40" src="{{ $file->getFileUrl() }}"
                                                                    alt="">
                                                            @else
                                                                <i class="bi list-icon {{ $file->extensionIcon() }}"></i>
                                                            @endif
                                                        </div>
                                                        <div class="name text-truncate w-100 me-2"
                                                            id="id{{ $loop->iteration }}">
                                                            {{ $file->file_name }}
                                                        </div>
                                                    </div>
                                                    <div class="type me-2 ms-auto min-w-100 text-center">
                                                        {{ $file->extension() ?? '-' }}</div>
                                                    <div class="size me-2 w-fit-content min-w-100 text-center">
                                                        {{ $file->size() ?? '0 Bytes' }}</div>
                                                    <small
                                                        class="date me-2 w-fit-content min-w-100 text-center">{{ $file->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </h2>
                                            <div id="collapse-{{ $loop->iteration }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $loop->iteration }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <!-- Details for small screens -->
                                                    <div class="d-md-none">
                                                        <h6 class="mb-2">Details:</h6>
                                                        <ul class="list-unstyled mb-3">
                                                            <li><strong>Type :</strong> {{ $file->extension() ?? '-' }}
                                                            </li>
                                                            <li><strong>Size :</strong> {{ $file->size() ?? '0 Bytes' }}
                                                            </li>
                                                            <li><strong>Creation date :</strong>
                                                                {{ $file->updated_at->diffForHumans() }}
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Actions -->
                                                    <h6 class="mb-2">Actions</h6>
                                                    <div class="action d-flex gap-2 flex-wrap">
                                                        <a class="btn btn-outline-primary btn-sm d-flex align-items-center"
                                                            href="{{ $file->getFileUrl() }}" target="_blank">
                                                            <i class='bx bx-show'></i>&nbsp;View
                                                        </a>
                                                        <a class="btn btn-outline-success btn-sm"
                                                            href="{{ route('file.download', $file->id) }}">
                                                            <i class='bx bx-download'></i> Download
                                                        </a>
                                                        <button class="btn btn-outline-warning btn-sm rename-file-btn" data-ob-file-id="{{ $file->id }}">
                                                            <i class='bx bx-rename'></i> Rename
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm delete-file-button"
                                                            data-file-id="{{ $file->id }}">
                                                            <i class='bx bx-trash-alt'></i> Delete
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <x-no-data />
                                    @endforelse
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

    <script src="{{ asset('assets/js/pages/folder-factory-files.js') }}"></script>
@endsection
