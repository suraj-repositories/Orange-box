@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('css')
    <style>

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
                                                    <button class="pm-nav-item active" data-view="all">
                                                        <i class="bx bx-file"></i>
                                                        My Drive

                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-share"></i>
                                                        Shared
                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-user"></i>
                                                        Recent
                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-star"></i>
                                                        Favourite
                                                    </button>

                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-trash"></i>
                                                        Trash
                                                    </button>

                                                </div>


                                                <div class="pm-sidebar-section px-2">
                                                    <p class="mb-1 fw-bold px-1 fs-6">Your Storage</p>

                                                    <strong>48% Full</strong>
                                                    <div class="progress-stacked storage-progress">
                                                        <div class="progress storage-photos" style="width:15%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                        <div class="progress storage-videos" style="width:30%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                        <div class="progress storage-documents" style="width:20%">
                                                            <div class="progress-bar"></div>
                                                        </div>

                                                        <div class="progress storage-others" style="width:35%">
                                                            <div class="progress-bar"></div>
                                                        </div>
                                                    </div>

                                                    <p class="text-muted fs-7 mt-1">Used: 17.9 GB of 20 GB</p>

                                                    <ul class="storage-list">

                                                        <li class="storage-item">
                                                            <div class="storage-icon photos">
                                                                <i class='bx bx-image'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Photos</h6>
                                                                <span class="storage-count">580 files</span>
                                                            </div>

                                                            <div class="storage-size">6.6 GB</div>
                                                        </li>

                                                        <li class="storage-item">
                                                            <div class="storage-icon videos">
                                                                <i class='bx bx-video'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Videos</h6>
                                                                <span class="storage-count">32 files</span>
                                                            </div>

                                                            <div class="storage-size">3.2 GB</div>
                                                        </li>

                                                        <li class="storage-item">
                                                            <div class="storage-icon documents">
                                                                <i class='bx bx-file'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Documents</h6>
                                                                <span class="storage-count">312 files</span>
                                                            </div>

                                                            <div class="storage-size">3.7 GB</div>
                                                        </li>


                                                        <li class="storage-item">
                                                            <div class="storage-icon others">
                                                                <i class='bx bx-folder'></i>
                                                            </div>

                                                            <div class="storage-content">
                                                                <h6 class="storage-title">Others</h6>
                                                                <span class="storage-count">948 files</span>
                                                            </div>

                                                            <div class="storage-size">2.1 GB</div>
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
                                                <button class="btn border">
                                                    <i class="bx bx-upload"></i> Upload
                                                </button>


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

                                                        @if ($recentFiles->count() > 4)
                                                            <button class="btn btn-light btn-sm show-more-btn" type="button"
                                                                data-bs-toggle="collapse"
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
                                                    <x-files.file-list-component :files="$recentFiles->take(4)" />

                                                    {{-- Extra files --}}
                                                    @if ($recentFiles->count() > 4)
                                                        <div class="collapse mt-3" id="moreRecentFiles">
                                                            <x-files.file-list-component :files="$recentFiles->skip(4)" />
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
                                                                <a class="nav-link active" aria-current="page"
                                                                    href="#">All Files</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#">Recent</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#">Folder</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#">Favourite</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#">Shared</a>
                                                            </li>

                                                        </ul>

                                                        <div class="d-flex align-items-center gap-2 my-2 mb-3">
                                                            <select class="form-select"
                                                                aria-label="Default select example">
                                                                <option selected>Sort By none</option>
                                                                <option value="1">Sort By name</option>
                                                                <option value="2">Sort By size</option>
                                                                <option value="3">Sort By modified</option>
                                                                <option value="3">Sort By created</option>
                                                            </select>

                                                            <button class="btn btn-light d-flex align-items-center px-2">
                                                                <i class="bx bx-grid-alt fs-4"></i>
                                                            </button>

                                                            <button class="btn btn-light d-flex align-items-center px-2">
                                                                <i class="bx bx-list-ul fs-4"></i>
                                                            </button>
                                                        </div>
                                                    </div>



                                                    <x-files.file-list-component :files="$files" />


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

@endsection
