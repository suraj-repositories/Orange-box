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
                        <div class="card mb-0 overflow-hidden border rounded-4">

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

                                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 row-cols-xxl-4 mt-2 g-3">

                                    <!-- Setup Sponsor -->
                                    <div class="col">
                                        <a href="{{ authRoute('user.documentation.sponsors.index', ['documentation' => $documentation, 'v' => $release->version]) }}"
                                            class="text-decoration-none">
                                            <div class="card mb-0 rounded-4 border h-100 hover-shadow">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="bi bi-cash-coin fs-4"></i>
                                                        <h5 class="mb-0 mt-2">Setup Sponsor</h5>
                                                        <small class="text-muted">Configure sponsorship</small>
                                                    </div>
                                                    <i class="bi bi-chevron-right fs-4"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col">
                                        <a href="{{ authRoute('user.documentation.releases', ['documentation' => $documentation]) }}"
                                            class="text-decoration-none">
                                            <div class="card mb-0 rounded-4 border h-100 hover-shadow">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="bi bi-megaphone fs-4"></i>
                                                        <h5 class="mb-0 mt-2">Releases Page</h5>
                                                        <small class="text-muted">Manage releases</small>
                                                    </div>
                                                    <i class="bi bi-chevron-right fs-4"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Partners -->
                                    <div class="col">
                                        <a href="{{ authRoute('user.documentation.partners.index', ['documentation' => $documentation]) }}"
                                            class="text-decoration-none">
                                            <div class="card mb-0 rounded-4 border h-100 hover-shadow">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class='bx bx-donate-heart fs-4'></i>
                                                        <h5 class="mb-0 mt-2">Partners</h5>
                                                        <small class="text-muted">Manage parteners</small>
                                                    </div>
                                                    <i class="bi bi-chevron-right fs-4"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Customize -->
                                    <div class="col">
                                        <a href="#" class="text-decoration-none">
                                            <div class="card mb-0 rounded-4 border h-100 hover-shadow">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="bi bi-box fs-4"></i>
                                                        <h5 class="mb-0 mt-2">Customize</h5>
                                                        <small class="text-muted">Customize docs</small>
                                                    </div>
                                                    <i class="bi bi-chevron-right fs-4"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ authRoute('user.documentation.document.pages.index', ['documentation' => $documentation]) }}"
                                            class="text-decoration-none">
                                            <div class="card mb-0 rounded-4 border h-100 hover-shadow">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="bi bi-box fs-4"></i>
                                                        <h5 class="mb-0 mt-2">Pages</h5>
                                                        <small class="text-muted">Custom Pages</small>
                                                    </div>
                                                    <i class="bi bi-chevron-right fs-4"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card mb-0 rounded-4 border hot-pages-card">
                                    <div class="card-body">

                                        <!-- Header -->
                                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                            <div
                                                class="icon-box p-2 square-50 d-flex align-items-center justify-content-center border rounded-4">
                                                <i class="bi bi-cup-hot fs-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1 fs-6 fw-bold">
                                                    <a href="/" class="text-dark">Hot Pages</a>
                                                </h5>
                                                <small>Other important static pages</small>
                                            </div>
                                        </div>

                                        <!-- Pages List -->
                                        <ul class="list-group list-group-flush">

                                            <!-- FAQ -->
                                            <li
                                                class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-semibold">FAQ</div>
                                                    <span class="badge bg-warning-subtle text-warning">Unimplemented</span>
                                                </div>

                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light border square-30 center-content"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end p-2">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ authRoute('user.documentation.faqs.index', ['documentation' => $documentation, 'release' => $release]) }}">Edit</a>
                                                        </li>
                                                        <li>
                                                            <div
                                                                class="dropdown-item d-flex justify-content-between align-items-center">
                                                                <span>Disable Page</span>
                                                                <div class="form-check form-switch m-0">
                                                                    <input class="form-check-input change-status-checkbox"
                                                                        type="checkbox">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>

                                            @php
                                                $pages = [
                                                    'privacy' => [
                                                        'title' => 'Privacy Policy',
                                                        'route' => 'privacy-policy.edit',
                                                    ],
                                                    'terms' => [
                                                        'title' => 'Terms & Condition',
                                                        'route' => 'terms.edit',
                                                    ],
                                                    'guide' => [
                                                        'title' => 'Community Guide',
                                                        'route' => 'community-guide.edit',
                                                    ],
                                                    'code_of_conduct' => [
                                                        'title' => 'Code Of Conduct',
                                                        'route' => 'code-of-conduct.edit',
                                                    ],
                                                ];
                                            @endphp

                                            @foreach ($pages as $type => $page)
                                                @php
                                                    $doc = $documentationDocuments->get($type);
                                                @endphp

                                                <li
                                                    class="list-group-item px-0 d-flex justify-content-between align-items-center document-list-item">

                                                    <div>
                                                        <div class="fw-semibold">{{ $page['title'] }}</div>

                                                        @if (!$doc)
                                                            <span
                                                                class="badge bg-warning-subtle text-warning page-status-badge">Unimplemented</span>
                                                        @elseif($doc->status === 'active')
                                                            <span
                                                                class="badge bg-success-subtle text-success page-status-badge">Active</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger-subtle text-danger page-status-badge">Inactive</span>
                                                        @endif
                                                    </div>

                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-sm btn-light border square-30 center-content"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end p-2"
                                                            style="min-width: 200px">

                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ authRoute('user.documentation.' . $page['route'], ['documentation' => $documentation, 'release' => $release]) }}">
                                                                    Edit
                                                                </a>
                                                            </li>

                                                            @if ($doc)
                                                                <li>
                                                                    <div
                                                                        class="dropdown-item d-flex justify-content-between align-items-center">

                                                                        <span>Page Status</span>

                                                                        <div class="form-check form-switch m-0">
                                                                            <input
                                                                                class="form-check-input change-status-checkbox"
                                                                                type="checkbox"
                                                                                data-document-id="{{ $doc->id }}"
                                                                                {{ $doc->status === 'active' ? 'checked' : '' }}>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @endif

                                                        </ul>
                                                    </div>

                                                </li>
                                            @endforeach

                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row  g-3">
                                    <div class="col-12">
                                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
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
                                                            <i class="bi bi-pencil-square fs-4"></i>
                                                            <h5 class="mb-0">Edit Doc</h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a href="">
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
@endsection
