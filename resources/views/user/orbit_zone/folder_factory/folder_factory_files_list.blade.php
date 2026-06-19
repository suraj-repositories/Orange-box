@extends('user.layout.layout')

@section('title', Route::is('user.folder-factory.files.index') ? 'Folder Factory' : '🟢🟢🟢')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/file-upload-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-theme.min.css') }}">
@endsection
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


                                    <div class="d-flex align-items-center ms-auto fw-semibold gap-2 flex-wrap">
                                        <button class="btn d-flex gap-1 align-items-center btn-sm btn-light border"
                                            id="create-folder-factory">
                                            <i class="bx bx-folder"></i> New Folder
                                        </button>

                                        <div class="modal fade folder-factory" id="folder-factory-form-modal" tabindex="-1"
                                            aria-labelledby="folder-factory-form-title" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form id="folder-factory-form" action="#" method="post">
                                                        @csrf
                                                        <input type="hidden" name="folder_id"
                                                            value="{{ $folderFactory->id }}">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="folder-factory-form-title">
                                                                Create
                                                                Folder
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col col-12 mb-3">
                                                                    <label for="name-input" class="form-label">Folder
                                                                        Name</label>

                                                                    <div class="input-group">
                                                                        <span class="input-group-text" id="basic-addon1">
                                                                            <i class="bi bi-folder-plus"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Enter folder name" id="name-input"
                                                                            name="name" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col col-12 mb-3">
                                                                    <p class="d-inline-flex gap-1">
                                                                        <a class="link center-content"
                                                                            data-bs-toggle="collapse"
                                                                            href="#advance-options" role="button"
                                                                            aria-expanded="false"
                                                                            aria-controls="advance-options">
                                                                            <span>Advance options</span><i
                                                                                class='bx bx-right-arrow-alt pt-1'></i>
                                                                        </a>
                                                                    </p>
                                                                    <div class="collapse" id="advance-options">
                                                                        <label for="folder-icon" class="form-label">Folder
                                                                            Icon</label>

                                                                        <div class="svg-grid">

                                                                            @foreach ($icons as $icon)
                                                                                <input type="radio" name="icon"
                                                                                    value="{{ $icon->id }}"
                                                                                    id="folder-icon-{{ $icon->id }}">
                                                                                <label class="svg-card"
                                                                                    for="folder-icon-{{ $icon->id }}">
                                                                                    <img src="{{ $icon->getUrl() }}"
                                                                                        alt="{{ $icon->name }}">
                                                                                </label>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="save-btn">Create</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
                                    @forelse ($items as $item)

                                        <div class="accordion-item">

                                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                                <div class="accordion-button fw-medium collapsed" type="button">

                                                    <div class="d-flex align-items-center gap-2 w-100">
                                                        <div class="file-toggle d-flex align-items-center overflow-hidden w-100"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse-{{ $loop->iteration }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapse-{{ $loop->iteration }}">

                                                            <div class="icon me-2">
                                                                @if ($item->item_type === 'file')
                                                                    @if ($item->mime_type && str_starts_with($item->mime_type, 'image/'))
                                                                        <img class="img-badge-40"
                                                                            src="{{ $item->file_url }}"
                                                                            alt="{{ $item->item_name }}">
                                                                    @else
                                                                        <i
                                                                            class="bi list-icon {{ $item->extension_icon }}"></i>
                                                                    @endif
                                                                @else
                                                                    <img src="{{ $item->icon_url }}"
                                                                        class="img-badge-40 border-0"
                                                                        alt="{{ $item->item_name }}">
                                                                @endif
                                                            </div>

                                                            <div class="name text-truncate w-100 me-2">
                                                                {{ $item->item_name }}
                                                            </div>


                                                        </div>

                                                        <button class="btn-no-style favourite_toggle"
                                                            data-is-favourite="{{ $item->is_favourite }}"
                                                            data-item-type="{{ $item->item_type }}"
                                                            data-item-id="{{ $item->id }}">
                                                            <i
                                                                class="bx {{ $item->is_favourite ? 'bxs-star' : 'bx-star' }} fs-4"></i>
                                                        </button>
                                                    </div>

                                                    <div class="type me-2 ms-auto min-w-100 text-center">
                                                        @if ($item->item_type === 'file')
                                                            {{ $item->extension ?? '-' }}
                                                        @else
                                                            Folder
                                                        @endif
                                                    </div>

                                                    <div class="size me-2 min-w-100 text-center">
                                                        @if ($item->item_type === 'file')
                                                            {{ $item->formatted_file_size ?? '0 Bytes' }}
                                                        @else
                                                            {{ $item->item_count ?? 0 }} Items
                                                        @endif
                                                    </div>

                                                    <small class="date me-2 min-w-100 text-center">
                                                        {{ date('d M Y', strtotime($item->updated_at)) }}
                                                    </small>

                                                </div>
                                            </h2>

                                            <div id="collapse-{{ $loop->iteration }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $loop->iteration }}"
                                                data-bs-parent="#accordionExample">

                                                <div class="accordion-body">

                                                    {{-- Mobile Details --}}
                                                    <div class="d-md-none">
                                                        <h6 class="mb-2">Details:</h6>

                                                        <ul class="list-unstyled mb-3">

                                                            <li>
                                                                <strong>Type :</strong>

                                                                @if ($item->item_type === 'file')
                                                                    {{ $item->extension ?? '-' }}
                                                                @else
                                                                    Folder
                                                                @endif
                                                            </li>

                                                            <li>
                                                                <strong>
                                                                    {{ $item->item_type === 'file' ? 'Size' : 'Items' }} :
                                                                </strong>

                                                                @if ($item->item_type === 'file')
                                                                    {{ $item->formatted_file_size ?? '0 Bytes' }}
                                                                @else
                                                                    {{ $item->item_count ?? 0 }}
                                                                @endif
                                                            </li>

                                                            <li>
                                                                <strong>Created :</strong>
                                                                {{ date('d M Y', strtotime($item->created_at)) }}
                                                            </li>

                                                            <li>
                                                                <strong>Modified :</strong>
                                                                {{ date('d M Y', strtotime($item->updated_at)) }}
                                                            </li>

                                                        </ul>
                                                    </div>

                                                    {{-- Actions --}}
                                                    <h6 class="mb-2">Actions</h6>

                                                    <div class="action d-flex gap-2 flex-wrap">

                                                        @if ($item->item_type === 'folder')
                                                            <a class="btn btn-outline-primary btn-sm"
                                                                href="{{ authRoute('user.folder-factory.files.index', ['folderId' => $item->id]) }}">
                                                                <i class="bx bx-folder-open"></i>
                                                                Open Folder
                                                            </a>

                                                            <button
                                                                class="btn btn-outline-warning btn-sm edit-form-factory-btn"
                                                                data-ob-folder-factory-id="{{ $item->id }}"
                                                                data-ob-folder-factory-name="{{ $item->item_name }}"
                                                                data-ob-folder-factory-icon="">
                                                                <i class="bx bx-edit"></i>
                                                                Rename
                                                            </button>

                                                            <button class="btn btn-outline-info btn-sm file-reallocation"
                                                                data-action-title="Copy Folder"
                                                                data-submit-url="{{ authRoute('user.folders.copy', ['folder' => $item->id]) }}">
                                                                <i class="bx bx-copy"></i>
                                                                Copy Folder
                                                            </button>

                                                            <button
                                                                class="btn btn-outline-secondary btn-sm file-reallocation"
                                                                data-action-title="Move Folder"
                                                                data-submit-url="{{ authRoute('user.folders.move', ['folder' => $item->id]) }}">
                                                                <i class="bx bx-log-in"></i>
                                                                Move Folder
                                                            </button>

                                                            <form
                                                                action="{{ authRoute('user.folder-factory.delete', ['folderFactory' => $item->id]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-sm">
                                                                    <i class="bx bx-trash"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a class="btn btn-outline-primary btn-sm d-flex align-items-center"
                                                                href="{{ $item->file_url }}" target="_blank">
                                                                <i class="bx bx-show"></i>&nbsp;View
                                                            </a>

                                                            <a class="btn btn-outline-success btn-sm"
                                                                href="{{ route('file.download', $item->id) }}">
                                                                <i class="bx bx-download"></i>
                                                                Download
                                                            </a>

                                                            <button class="btn btn-outline-info btn-sm file-reallocation"
                                                                data-action-title="Copy File"
                                                                data-submit-url="{{ authRoute('user.files.copy', ['file' => $item->id]) }}">
                                                                <i class="bx bx-copy"></i>
                                                                Copy File
                                                            </button>

                                                            <button
                                                                class="btn btn-outline-secondary btn-sm file-reallocation"
                                                                data-action-title="Move File"
                                                                data-submit-url="{{ authRoute('user.files.move', ['file' => $item->id]) }}">
                                                                <i class="bx bx-log-in"></i>
                                                                Move File
                                                            </button>

                                                            <button class="btn btn-outline-warning btn-sm rename-file-btn"
                                                                data-ob-file-id="{{ $item->id }}"
                                                                data-ob-file-name="{{ $item->item_name }}">
                                                                <i class="bx bx-rename"></i>
                                                                Rename
                                                            </button>

                                                            <button class="btn btn-outline-dark btn-sm favourite_toggle"
                                                                data-is-favourite="{{ $item->is_favourite }}"
                                                                data-item-type="{{ $item->item_type }}"
                                                                data-item-id="{{ $item->id }}">
                                                                <i
                                                                    class="bx {{ $item->is_favourite ? 'bxs-star' : 'bx-star' }}"></i>
                                                                Favourite
                                                            </button>

                                                            <form action="{{ route('file.delete', $item->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-sm">
                                                                    <i class="bx bx-trash"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @empty
                                        <x-no-data />
                                    @endforelse

                                    {{ $items->links() }}
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

    <div class="modal fade file-realocation-modal" id="file-realocation-modal" tabindex="-1"
        aria-labelledby="folder-factory-form-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="file-realocation-form" action="#" method="post" data-submit-type="ajax">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-12 mb-3">

                                <label for="pick-folder" class="form-label">Select Folder</label>

                                <div class="d-flex">
                                    <select class="select2-with-image" name="folder_id" id="folder-picker">
                                        @foreach ($folderFactories as $folder)
                                            <option value="{{ $folder->id }}" data-image="{{ $folder->getIconUrl() }}"
                                                data-image-class="rounded-0" data-slug="{{ $folder->slug }}"
                                                {{ request()->get('folder') == $folder->slug ? 'selected' : '' }}>
                                                {{ $folder->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="#" id="redirect-link" class="d-none"></a>
                                    <button id="OpenSelectedFolderBtn" type="button"
                                        class="btn bg-light border ms-1 p-0 center-content px-2 text-primary "
                                        title="Open Folder"><i class='bx bx-folder-open fs-4'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                        <button type="submit" class="btn btn-primary submit-btn"
                            data-loading-text="Proceeding...">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/pages/folder-factory-files.js') }}"></script>
@endsection


@section('js')
    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/select2.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/folder-factory-list.js') }}"></script>
@endsection
