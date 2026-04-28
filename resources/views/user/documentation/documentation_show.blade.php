@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
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

                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card mb-0 border rounded-4">

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="icon-box p-2 border rounded-4">
                                            <img class="square-40" src="{{ Storage::url($documentation->logo_sm_light) }}"
                                                alt="">
                                        </div>
                                        <div>
                                            <h5 class="mb-1 fs-6 fw-bold">
                                                <a href="{{ route('docs.show', [$documentation->user, $documentation->url, $release->version, '/']) }}"
                                                    target="_blank" class=" text-dark">{{ $documentation->title }}<i
                                                        class="bi bi-box-arrow-up-right fs-7 ms-1"></i>
                                                </a>
                                            </h5>
                                            <p class="mb-0 fs-6">Created on:
                                                {{ $documentation->created_at?->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ authRoute('user.documentation.social-links.edit', ['documentation' => $documentation]) }}"
                                            class="btn btn-light border">
                                            Social Links
                                        </a>

                                        <div class="dropdown">
                                            <span class="btn border cursor-default">
                                                Release - {{ $release->version }}
                                            </span>
                                            <button type="button"
                                                class="btn btn-light border dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                                <i class="mdi mdi-chevron-down"></i>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @foreach ($documentation->releases as $r)
                                                    <li><a class="dropdown-item"
                                                            href="{{ authRoute('user.documentation.show', ['documentation' => $documentation, 'release' => $r]) }}">{{ $r->version }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row g-3">

                            <div class="col-md-12">
                                <div class="row  g-3">
                                    <div class="col-12">
                                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 lh-cards">
                                            <div class="col">
                                                <a
                                                    href="{{ authRoute('user.documentation.releases', ['documentation' => $documentation]) }}">
                                                    <div class="card mb-0 rounded-4 border">
                                                        <div class="card-body d-flex align-items-center gap-2">
                                                            <i class="bi bi-hdd-stack fs-4"></i>
                                                            <h5 class="mb-0">Releases</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a
                                                    href="{{ authRoute('user.documentation.pages.index', ['documentation' => $documentation, 'release' => $release]) }}">
                                                    <div class="card mb-0 rounded-4 border">
                                                        <div class="card-body d-flex align-items-center gap-2">
                                                            <i class="bi bi-folder fs-4"></i>
                                                            <h5 class="mb-0">File Explorer</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a
                                                    href="{{ authRoute('user.documentation.edit', ['documentation' => $documentation]) }}">
                                                    <div class="card mb-0 rounded-4 border">
                                                        <div class="card-body d-flex align-items-center gap-2">
                                                            <i class='bx bx-edit fs-3'></i>
                                                            <h5 class="mb-0">Edit Doc</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a
                                                    href="{{ authRoute('user.documentation.document.pages.index', ['documentation' => $documentation]) }}">
                                                    <div class="card mb-0 rounded-4 border">
                                                        <div class="card-body d-flex align-items-center gap-2">
                                                            <i class="bi bi-journal-code fs-4"></i>
                                                            <h5 class="mb-0">Edit Pages</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card mb-0 rounded-4 border">
                                            <div
                                                class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <div
                                                        class="icon-box square-60 rounded-4 border d-flex align-items-center justify-content-center">
                                                        <i class='bx bx-refresh fs-1'></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0">Reload Via Links</h5>
                                                        <p class="mb-0">Reload all pages via one click - only use when
                                                            you
                                                            are sure</p>
                                                    </div>
                                                </div>

                                                <div>
                                                    <button class="btn btn-primary rounded-3">Refresh Docs</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <x-user.analytics.most-visisted-pages-card duration="week" :release-id="$release->id"  />
                            </div>

                            <div class="col-12 col-md-6">
                                <x-user.analytics.documentation-visitors-card duration="week" :documentation-id="$documentation->id"
                                    :release-id="$release->id" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-user.analytics.documentation-audience-heatmap-card  :documentation-id="$documentation->id"
                                    :release-id="$release->id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/documentation-show.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/analytics-dashboard.init.js') }}"></script>
@endsection
