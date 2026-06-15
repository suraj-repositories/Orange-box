@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')


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
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.documentation.index') }}">Documentations</a></li>

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

                                        <aside class="pm-sidebar">
                                            <div class="pm-sidebar-inner">
                                                <div class="pm-brand">
                                                    <span class="pm-brand-icon">
                                                        <svg width="16" height="16" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M8 2L3 4v4c0 3 2.5 5 5 6 2.5-1 5-3 5-6V4L8 2z" />
                                                        </svg>
                                                    </span>
                                                    File Manager
                                                </div>

                                                <div class="pm-sidebar-section">
                                                    <p class="mb-1 fw-bold px-3 fs-6">My Files</p>
                                                    <a href=""
                                                        class="pm-nav-item {{ request('filter') == 'all' ? 'active' : '' }}"
                                                        data-view="all">
                                                        <i class="bx bx-file"></i>
                                                        My Drive

                                                    </a>
                                                    {{-- <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-share"></i>
                                                        Shared
                                                    </button> --}}
                                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'recent']) }}"
                                                        class="pm-nav-item {{ request('filter') == 'recent' ? 'active' : '' }}"
                                                        data-view="privacy">
                                                        <i class="bx bx-user"></i>
                                                        Recent
                                                    </a>
                                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'folder']) }}"
                                                        class="pm-nav-item {{ request('filter') == 'folder' ? 'active' : '' }}"
                                                        data-view="privacy">
                                                        <i class="bx bx-folder"></i>
                                                        Folder
                                                    </a>
                                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'favourite']) }}"
                                                        class="pm-nav-item {{ request('filter') == 'favourite' ? 'active' : '' }}"
                                                        data-view="privacy">
                                                        <i class="bx bx-star"></i>
                                                        Favourite
                                                    </a>

                                                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'trash']) }}"
                                                        class="pm-nav-item {{ request('filter') == 'trash' ? 'active' : '' }}"
                                                        data-view="privacy">
                                                        <i class="bx bx-trash"></i>
                                                        Trash
                                                    </a>

                                                </div>


                                                <div class="pm-sidebar-section px-2">
                                                    <p class="mb-1 fw-bold px-1 fs-6">Your Storage</p>

                                                    <strong>{{ $storageStats['percentage'] }}% Full</strong>

                                                    <div class="progress-stacked storage-progress">

                                                        <div class="progress storage-photos"
                                                            style="width: {{ $photoPercent }}%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                        <div class="progress storage-videos"
                                                            style="width: {{ $videoPercent }}%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                        <div class="progress storage-documents"
                                                            style="width: {{ $documentPercent }}%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                        <div class="progress storage-others"
                                                            style="width: {{ $otherPercent }}%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                    </div>

                                                    <p class="text-muted fs-7 mt-1">
                                                        Used: {{ $storageStats['used'] }}
                                                        of
                                                        {{ $storageStats['limit'] }}
                                                    </p>

                                                    <ul class="storage-list">

                                                        <li class="storage-item">
                                                            <div class="storage-icon photos">
                                                                <i class='bx bx-image'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Photos</h6>
                                                                <span
                                                                    class="storage-count">{{ $fileStats['photos']['count'] }}
                                                                    files</span>
                                                            </div>

                                                            <div class="storage-size">{{ $fileStats['photos']['size'] }}
                                                            </div>
                                                        </li>

                                                        <li class="storage-item">
                                                            <div class="storage-icon videos">
                                                                <i class='bx bx-video'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Videos</h6>
                                                                <span
                                                                    class="storage-count">{{ $fileStats['videos']['count'] }}
                                                                    files</span>
                                                            </div>

                                                            <div class="storage-size">{{ $fileStats['videos']['size'] }}
                                                            </div>
                                                        </li>

                                                        <li class="storage-item">
                                                            <div class="storage-icon documents">
                                                                <i class='bx bx-file'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Documents</h6>
                                                                <span
                                                                    class="storage-count">{{ $fileStats['documents']['count'] }}
                                                                    files</span>
                                                            </div>

                                                            <div class="storage-size">{{ $fileStats['documents']['size'] }}
                                                            </div>
                                                        </li>


                                                        <li class="storage-item">
                                                            <div class="storage-icon others">
                                                                <i class='bx bx-folder'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Others</h6>
                                                                <span
                                                                    class="storage-count">{{ $fileStats['others']['count'] }}
                                                                    files</span>
                                                            </div>

                                                            <div class="storage-size">{{ $fileStats['others']['size'] }}
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>




                                            </div>

                                            <div class="pm-sidebar-footer">
                                                <div
                                                    class="d-flex w-100 align-items-center justify-content-center flex-column">
                                                    <img class="img-fluid"
                                                        src="{{ asset('assets/images/defaults/storage.webp') }}"
                                                        style="height: 100px">
                                                    <p class="text-center mb-1">
                                                        Want to Increase Storage Capacity?
                                                    </p>
                                                    <button class="btn btn-outline-primary btn-sm w-100" tabindex="0"
                                                        type="button">Upgrade</button>
                                                </div>
                                            </div>
                                        </aside>

                                        <!-- MAIN CONTENT -->
                                        <main class="fm-main">
                                            <div
                                                class="w-100 p-2 d-flex align-items-center justify-content-between flex-wrap gap-1">
                                                <div class="d-flex gap-2">
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
                                                                                        role="button"
                                                                                        aria-expanded="false"
                                                                                        aria-controls="advance-options">
                                                                                        <span>Advance options</span><i
                                                                                            class='bx bx-right-arrow-alt pt-1'></i>
                                                                                    </a>
                                                                                </p>
                                                                                <div class="collapse"
                                                                                    id="advance-options">
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
                                                    <div class="position-relative topbar-search ">
                                                        <input type="text"
                                                            class="form-control bg-light bg-opacity-75 border ps-4"
                                                            placeholder="Search by name">
                                                        <i
                                                            class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>
                                                    </div>
                                                    <button class="border btn d-flex align-items-center px-2">
                                                        <i class="bx bx-filter fs-4"></i>
                                                    </button>
                                                </div>

                                            </div>
                                            <div
                                                class="border-top border-bottom w-100 p-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-1">

                                                <strong class="total-items">23 Items</strong>


                                                <button class="btn border d-flex align-items-center px-2">
                                                    <i class="bx bx-info-circle fs-4"></i>
                                                </button>

                                            </div>

                                            <div class="row p-3">
                                                <div class="col-12">
                                                    <div
                                                        class="d-flex justify-content-between gap-2 flex-wrap align-items-center p-2">
                                                        <h4 class="mb-0 fw-semibold">Recent Files</h4>

                                                        @if ($recentItems->count() > 4)
                                                            <button class="btn btn-light btn-sm show-more-btn"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#moreRecentFiles" aria-expanded="false"
                                                                aria-controls="moreRecentFiles">

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

                                            <div class="row p-3">
                                                <div class="col-12">
                                                    <h4 class="mb-0 fw-semibold">All Files</h4>

                                                    <div
                                                        class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
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

                                                        <div class="d-flex align-items-center gap-2 my-2 mb-3">
                                                            <form method="GET">
                                                                @foreach (request()->except('sort') as $key => $value)
                                                                    <input type="hidden" name="{{ $key }}"
                                                                        value="{{ $value }}">
                                                                @endforeach

                                                                <select class="form-select" name="sort"
                                                                    onchange="this.form.submit()" aria-label="Sort files">

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

                                                            <button class="btn btn-light d-flex align-items-center px-2">
                                                                <i class="bx bx-grid-alt fs-4"></i>
                                                            </button>

                                                            <button class="btn btn-light d-flex align-items-center px-2">
                                                                <i class="bx bx-list-ul fs-4"></i>
                                                            </button>
                                                        </div>
                                                    </div>



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

@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/folder-factory-list.js') }}"></script>
@endsection
