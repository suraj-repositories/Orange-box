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
                                                    <p class="pm-sidebar-label">Pages</p>
                                                    <button class="pm-nav-item active" data-view="all">
                                                        <i class="bx bx-file"></i>
                                                        My Files

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
                                                        <i class="bx bx-info-circle"></i>
                                                        Spam
                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-user"></i>
                                                        History
                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-trash"></i>
                                                        Trash
                                                    </button>

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
                                                        <button class="btn btn-light btn-sm">Show More <i
                                                                class="bx bx-down"></i></button>
                                                    </div>

                                                    <x-files.file-list-component :files="$recentFiles" />


                                                </div>
                                            </div>

                                            <div class="row p-3">
                                                <div class="col-12">
                                                    <div
                                                        class="d-flex justify-content-between gap-2 flex-wrap align-items-center p-2">
                                                        <h4 class="mb-0 fw-semibold">My Files</h4>
                                                       <div class="d-flex align-items-center gap-2">

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
