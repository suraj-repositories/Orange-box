@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/file-upload-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.css') }}">

    <style>
        .select2-container {
            z-index: 9999;
        }

        .select2-dropdown {
            z-index: 9999;
        }
    </style>
@endsection

@section('content')
    <div class="content-page">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>

                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card overflow-hidden">
                            <div class="card-body p-0">


                                <div class="pm-root">

                                    <div class="pm-shell">

                                        @include('user.orbit_zone.folder_factory.partials.file-manager-sidebar')

                                        <!-- MAIN CONTENT -->
                                        <main class="pm-main">
                                            <div
                                                class="w-100 p-2 d-flex align-items-center justify-content-between flex-wrap gap-1">
                                                <div class="d-flex gap-2">

                                                    <button class="btn file-manger-sidebar-toggle-btn"
                                                        id="fileManagerSidebarToggleBtn">
                                                     <i class="bi bi-layout-sidebar fs-4"></i>
                                                    </button>
                                                    <button class="btn border d-flex gap-1 align-items-center"
                                                        id="create-folder-factory">
                                                        <i class="bx bx-folder"></i> New Folder
                                                    </button>

                                                    <div class="modal fade folder-factory" id="folder-factory-form-modal"
                                                        tabindex="-1" aria-labelledby="folder-factory-form-title"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form id="folder-factory-form" action="#"
                                                                    method="post">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="folder-factory-form-title">Create Folder
                                                                        </h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col col-12 mb-3">
                                                                                <label for="name-input"
                                                                                    class="form-label">Folder Name</label>

                                                                                <div class="input-group">
                                                                                    <span class="input-group-text"
                                                                                        id="basic-addon1">
                                                                                        <i class="bi bi-folder-plus"></i>
                                                                                    </span>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Enter folder name"
                                                                                        id="name-input" name="name"
                                                                                        value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col col-12 mb-3">
                                                                                <p class="d-inline-flex gap-1">
                                                                                    <a class="link center-content"
                                                                                        data-bs-toggle="collapse"
                                                                                        href="#advance-options"
                                                                                        role="button" aria-expanded="false"
                                                                                        aria-controls="advance-options">
                                                                                        <span>Advance options</span><i
                                                                                            class='bx bx-right-arrow-alt pt-1'></i>
                                                                                    </a>
                                                                                </p>
                                                                                <div class="collapse" id="advance-options">
                                                                                    <label for="folder-icon"
                                                                                        class="form-label">Folder
                                                                                        Icon</label>

                                                                                    <div class="svg-grid">

                                                                                        @foreach ($icons as $icon)
                                                                                            <input type="radio"
                                                                                                name="icon"
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

                                                    <a class="btn border"
                                                        href="{{ authRoute('user.folder-factory.files.create') }}">
                                                        <i class="bx bx-upload"></i> Upload
                                                    </a>
                                                </div>


                                                <div class="d-flex gap-1 flex-wrap align-items-center">
                                                    <form method="GET" action="{{ authRoute('user.file-manager') }}">
                                                        <div class="d-flex gap-1 flex-wrap align-items-center">

                                                            <div class="position-relative topbar-search">
                                                                <input type="text" name="search"
                                                                    value="{{ request('search') }}"
                                                                    class="form-control bg-light bg-opacity-75 border ps-4"
                                                                    placeholder="Search by name">
                                                                <i
                                                                    class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>
                                                            </div>

                                                            <button type="button"
                                                                class="border btn d-flex align-items-center p-1 px-2"
                                                                data-bs-toggle="modal" data-bs-target="#filterModal">
                                                                <i class="bi bi-funnel fs-5"></i>
                                                            </button>

                                                            @if (request()->hasAny(['search', 'type', 'modified', 'location', 'sort']))
                                                                <a href="{{ authRoute('user.file-manager') }}"
                                                                    class="btn btn-dark">
                                                                    Reset
                                                                </a>
                                                            @endif

                                                            <div class="modal fade" id="filterModal" tabindex="-1">
                                                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                                                    <div class="modal-content">

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Filter</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>
                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <div class="mb-3">
                                                                                <label class="form-label">File Type</label>
                                                                                <select class="form-select"
                                                                                    name="type">
                                                                                    <option value="">All Types
                                                                                    </option>
                                                                                    <option value="document"
                                                                                        @selected(request('type') == 'document')>
                                                                                        Documents</option>
                                                                                    <option value="image"
                                                                                        @selected(request('type') == 'image')>Images
                                                                                    </option>
                                                                                    <option value="video"
                                                                                        @selected(request('type') == 'video')>Videos
                                                                                    </option>
                                                                                    <option value="audio"
                                                                                        @selected(request('type') == 'audio')>Audio
                                                                                    </option>
                                                                                    <option value="archive"
                                                                                        @selected(request('type') == 'archive')>
                                                                                        Archives</option>
                                                                                    <option value="other"
                                                                                        @selected(request('type') == 'other')>Other
                                                                                    </option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label class="form-label">Modified</label>
                                                                                <select class="form-select"
                                                                                    name="modified">
                                                                                    <option value="">Any Time
                                                                                    </option>
                                                                                    <option value="today"
                                                                                        @selected(request('modified') == 'today')>Today
                                                                                    </option>
                                                                                    <option value="7_days"
                                                                                        @selected(request('modified') == '7_days')>Last 7
                                                                                        Days</option>
                                                                                    <option value="30_days"
                                                                                        @selected(request('modified') == '30_days')>Last 30
                                                                                        Days</option>
                                                                                    <option value="90_days"
                                                                                        @selected(request('modified') == '90_days')>Last
                                                                                        90 Days</option>
                                                                                    <option value="year"
                                                                                        @selected(request('modified') == 'year')>This
                                                                                        Year</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-0">
                                                                                <label class="form-label">Sort By</label>
                                                                                <select class="form-select"
                                                                                    name="sort">
                                                                                    <option value="name"
                                                                                        @selected(request('sort') == 'name')>Name
                                                                                    </option>
                                                                                    <option value="updated_at"
                                                                                        @selected(request('sort') == 'updated_at')>
                                                                                        Modified</option>
                                                                                    <option value="created_at"
                                                                                        @selected(request('sort') == 'created_at')>
                                                                                        Created</option>
                                                                                </select>
                                                                            </div>

                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <a href="{{ authRoute('user.file-manager') }}"
                                                                                class="btn btn-dark">
                                                                                Reset
                                                                            </a>

                                                                            <button type="submit"
                                                                                class="btn btn-primary">
                                                                                Apply
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                            <div
                                                class="border-top border-bottom w-100 p-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-1">

                                                <strong class="total-items">{{ $totalItems }} Items</strong>

                                                <div class="items-selected d-flex gap-2 align-items-center d-none">
                                                    <button
                                                        class="btn border rounded-circle square-30 btn-sm d-flex align-items-center justify-content-center fs-5">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                    <strong>0 Items Selected</strong>
                                                    {{-- <button
                                                        class="btn border btn-sm d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-download"></i>
                                                    </button> --}}
                                                    {{-- <button
                                                        class="btn border btn-sm d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-share"></i>
                                                    </button> --}}
                                                    <button id="deleteAllSelectedBtn"
                                                        class="btn border btn-sm d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    {{-- <button
                                                        class="btn border btn-sm d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button> --}}
                                                </div>


                                                <button id="openDetailsButton"
                                                    class="btn border btn-sm d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-info-circle"></i>
                                                </button>


                                                <div class="offcanvas offcanvas-end file-details-offcanvas" tabindex="-1"
                                                    id="fileDetailsOffcanvas" aria-labelledby="fileDetailsOffcanvasLabel">
                                                    <div class="offcanvas-header border-bottom">
                                                        <h5 class="offcanvas-title" id="fileDetailsOffcanvasLabel">File
                                                            info</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                    </div>
                                                    <div class="offcanvas-body">

                                                        <!-- No selection -->
                                                        <div class="details-none">
                                                            <x-no-data message="Select an item to view more information" />
                                                        </div>

                                                        <!-- Multiple selected -->
                                                        <div class="details-multiple d-none text-center py-4">
                                                            <div class="multiple-card-view">
                                                                <h4 class="selected-count mb-1">0</h4>
                                                                <p class="mb-0">Items Selected</p>
                                                            </div>
                                                        </div>

                                                        <!-- Single selected -->
                                                        <div class="details-single d-none" id="propElement">

                                                            <div class="preview-container text-center mb-4">
                                                                <img class="detail-image img-fluid rounded d-none"
                                                                    alt="">
                                                                <i class="detail-icon bx bx-file display-3"></i>
                                                            </div>

                                                            <h5 class="fw-bold">Properties</h5>

                                                            <ul class="file-properties">

                                                                <li class="d-flex gap-3 align-items-center">
                                                                    <p class="property-name">Name</p>
                                                                    :
                                                                    <p class="detail-name">-</p>
                                                                </li>

                                                                <li class="d-flex gap-3 align-items-center">
                                                                    <p class="property-name">Type</p>
                                                                    :
                                                                    <p class="detail-type">-</p>
                                                                </li>
                                                                <li class="for-file">
                                                                    <div class="d-flex gap-3 align-items-center">
                                                                        <p class="property-name">Size</p>
                                                                        :
                                                                        <p class="detail-size">-</p>
                                                                    </div>
                                                                </li>

                                                                <li class="for-file">
                                                                    <div class="d-flex gap-3 align-items-center">
                                                                        <p class="property-name">Mime Type</p>
                                                                        :
                                                                        <p class="detail-mime-type">-</p>
                                                                    </div>
                                                                </li>

                                                                <li class="for-folder">
                                                                    <div class="d-flex gap-3 align-items-center">
                                                                        <p class="property-name">Item Count</p>
                                                                        :
                                                                        <p class="detail-item-count">-</p>
                                                                    </div>
                                                                </li>

                                                                <li class="d-flex gap-3 align-items-center">
                                                                    <p class="property-name">Modified</p>
                                                                    :
                                                                    <p class="detail-modified">-</p>
                                                                </li>
                                                                <li class="d-flex gap-3 align-items-center">
                                                                    <p class="property-name">Created At</p>
                                                                    :
                                                                    <p class="detail-created">-</p>
                                                                </li>
                                                            </ul>

                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            @if (!(request()->has('search') || request()->filter == 'trash' || $recentItems->isEmpty()))
                                                <div class="row p-3">
                                                    <div class="col-12">
                                                        <div
                                                            class="d-flex justify-content-between gap-2 flex-wrap align-items-center py-2">
                                                            <h4 class="mb-0 fw-semibold">Recent Files</h4>

                                                            @if ($recentItems->count() > 4)
                                                                <button class="btn btn-light btn-sm show-more-btn"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#moreRecentFiles"
                                                                    aria-expanded="false" aria-controls="moreRecentFiles">

                                                                    <span class="show-more-text">Show More</span>
                                                                    <span class="show-less-text d-none">Show Less</span>

                                                                    <i class='bx bx-chevron-down down-icon'></i>
                                                                    <i class='bx bx-chevron-up up-icon d-none'></i>
                                                                </button>
                                                            @endif
                                                        </div>

                                                        {{-- First 4 files --}}
                                                        <x-files.file-list-component :items="$recentItems->take(4)" />

                                                        {{-- Extra files --}}
                                                        @if ($recentItems->count() > 4)
                                                            <div class="collapse mt-3" id="moreRecentFiles">
                                                                <x-files.file-list-component :items="$recentItems->skip(4)" />
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row p-3">
                                                <div class="col-12">
                                                    @if ($isSearch || request()->filter == 'trash')

                                                        <div
                                                            class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                                            <h4 class="mb-0 fw-semibold">
                                                                {{ request()->filter == 'trash' ? 'Deleted Files' : 'Search Result' }}
                                                            </h4>

                                                            <form method="GET">
                                                                @foreach (request()->except('sort') as $key => $value)
                                                                    <input type="hidden" name="{{ $key }}"
                                                                        value="{{ $value }}">
                                                                @endforeach

                                                                <select class="form-select" name="sort"
                                                                    onchange="this.form.submit()" aria-label="Sort files"
                                                                    style="min-width: 220px;">
                                                                    <option value=""
                                                                        {{ request('sort') == '' ? 'selected' : '' }}>
                                                                        Sort By none
                                                                    </option>

                                                                    <option value="name"
                                                                        {{ request('sort') == 'name' ? 'selected' : '' }}>
                                                                        Sort By name
                                                                    </option>

                                                                    <option value="updated_at"
                                                                        {{ request('sort') == 'updated_at' ? 'selected' : '' }}>
                                                                        Sort By modified
                                                                    </option>

                                                                    <option value="created_at"
                                                                        {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                                                                        Sort By created
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <h4 class="mb-3 fw-semibold">
                                                            All Files
                                                        </h4>

                                                        <div
                                                            class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                                            <ul class="nav nav-underline">
                                                                <li class="nav-item">
                                                                    <a class="nav-link {{ request('filter', 'all') == 'all' ? 'active' : '' }}"
                                                                        href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}">
                                                                        All Files
                                                                    </a>
                                                                </li>

                                                                <li class="nav-item">
                                                                    <a class="nav-link {{ request('filter') == 'recent' ? 'active' : '' }}"
                                                                        href="{{ request()->fullUrlWithQuery(['filter' => 'recent']) }}">
                                                                        Recent
                                                                    </a>
                                                                </li>

                                                                <li class="nav-item">
                                                                    <a class="nav-link {{ request('filter') == 'folder' ? 'active' : '' }}"
                                                                        href="{{ request()->fullUrlWithQuery(['filter' => 'folder']) }}">
                                                                        Folder
                                                                    </a>
                                                                </li>

                                                                <li class="nav-item">
                                                                    <a class="nav-link {{ request('filter') == 'favourite' ? 'active' : '' }}"
                                                                        href="{{ request()->fullUrlWithQuery(['filter' => 'favourite']) }}">
                                                                        Favourite
                                                                    </a>
                                                                </li>
                                                            </ul>

                                                            <form method="GET">
                                                                @foreach (request()->except('sort') as $key => $value)
                                                                    <input type="hidden" name="{{ $key }}"
                                                                        value="{{ $value }}">
                                                                @endforeach

                                                                <select class="form-select" name="sort"
                                                                    onchange="this.form.submit()" aria-label="Sort files"
                                                                    style="min-width: 220px;">
                                                                    <option value=""
                                                                        {{ request('sort') == '' ? 'selected' : '' }}>
                                                                        Sort By none
                                                                    </option>

                                                                    <option value="name"
                                                                        {{ request('sort') == 'name' ? 'selected' : '' }}>
                                                                        Sort By name
                                                                    </option>

                                                                    <option value="updated_at"
                                                                        {{ request('sort') == 'updated_at' ? 'selected' : '' }}>
                                                                        Sort By modified
                                                                    </option>

                                                                    <option value="created_at"
                                                                        {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                                                                        Sort By created
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </div>
                                                    @endif


                                                    <x-files.file-list-component :items="$items" />


                                                </div>
                                            </div>
                                        </main>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>

    <div class="modal fade file-name-editor" id="file-rename-modal" tabindex="-1"
        aria-labelledby="folder-factory-form-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="file-rename-form" action="#" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Rename File
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-12 mb-3">
                                <label for="name-input" class="form-label">File Name</label>

                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="bi bi-file"></i>
                                    </span>
                                    <input type="text" class="form-control file-name-input"
                                        placeholder="Enter file name" name="name" value="">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                        <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
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

@endsection

@section('js')
    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/select2.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/folder-factory-list.js') }}"></script>
    <script src="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.js') }}"></script>

@endsection
