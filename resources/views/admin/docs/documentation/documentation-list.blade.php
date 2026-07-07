@extends('admin.layout.layout')
@section('title', Route::is('admin.docs.index') ? $title : '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0"> {{ $title }}</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Documentations</li>
                        </ol>
                    </div>
                </div>


                <x-alert-component />
                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections">
                                        <i class="bi bi-file-richtext fs-5"></i>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $title }}</h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">

                                        <a data-bs-toggle="collapse" href="#filterCollapse" role="button"
                                            aria-expanded="false" aria-controls="filterCollapse"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-filter fs-5"></i>
                                            <div>Filters</div>
                                        </a>

                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">

                                <div class="collapse {{ $hasFilter ? 'show' : '' }}" id="filterCollapse">
                                    <form method="GET" action="" class="p-3">
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <label class="form-label">Search</label>
                                                <input type="text" name="search" class="form-control"
                                                    value="{{ request('search') }}" placeholder="Search">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Created From</label>
                                                <input type="date" name="from_date" class="form-control"
                                                    value="{{ request('from_date') }}">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Created To</label>
                                                <input type="date" name="to_date" class="form-control"
                                                    value="{{ request('to_date') }}">
                                            </div>

                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-dark w-100">
                                                    Filter
                                                </button>

                                                @if ($hasFilter)
                                                    <a href="{{ route('admin.users.index') }}"
                                                        class="btn border ms-1 w-100">
                                                        Reset
                                                    </a>
                                                @endif
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>User</th>
                                                <th>Logo</th>
                                                <th>Title</th>
                                                <th>Link</th>
                                                <th>Status</th>
                                                <th>Version</th>
                                                <th>Last Updated</th>
                                            </tr>
                                        </thead>
                                        @forelse ($documentations as $doc)
                                            <tr>
                                                <td>
                                                    {{ $documentations->firstItem() + $loop->iteration - 1 }}
                                                </td>

                                                <td>
                                                    <div class="text-dark truncate-2 d-flex align-items-center">
                                                        <img src="{{ $doc?->user?->profilePicture() ?? '' }}" alt=""
                                                            class="avatar-sm rounded-circle me-1">
                                                        <span>
                                                            <div>{{ $doc?->user?->fullname() ?? '' }}</div>
                                                            <div>
                                                                <strong>{{ $doc?->user?->username ?? '' }}</strong>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a class="text-dark" href="#">
                                                        @if (!empty($doc->logo_light))
                                                            <img src="{{ Storage::url($doc->logo_light) }}"
                                                                class="avatar avatar-sm img-fluid rounded-2 me-1 avatar-xl-logo"
                                                                aria-label="{{ $doc->title }}">
                                                        @elseif (!empty($doc->logo_sm_light))
                                                            <img src="{{ Storage::url($doc->logo_sm_light) }}"
                                                                class="avatar avatar-sm img-fluid rounded-2 me-1"
                                                                aria-label="{{ $doc->title }}">
                                                        @endif
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $doc->title }}

                                                </td>
                                                <td>
                                                    <small> <a href="{{ $doc->full_url }}" target="_blank"
                                                            title="{{ $doc->full_url }}">{{ $doc->url }}</a>
                                                    </small>

                                                    <i class="bi bi-copy ms-1 copy-icon"
                                                        data-copy-text="{{ $doc->full_url }}"></i>
                                                </td>
                                                <td>
                                                    @switch($doc->status)
                                                        @case('published')
                                                            <span class="badge badge-green">Published</span>
                                                        @break

                                                        @case('draft')
                                                            <span class="badge badge-orange">Draft</span>
                                                        @break

                                                        @case('private')
                                                            <span class="badge badge-purple">Private</span>
                                                        @break

                                                        @case('archived')
                                                            <span class="badge badge-black">Archived</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-dark">
                                                                {{ ucfirst($doc->status) }}
                                                            </span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    {{ $doc->latestRelease?->version }}
                                                </td>
                                                <td>
                                                    {{ $doc->updated_at?->format('M d, Y') ?? '' }}
                                                    <small class="text-muted">
                                                        {{ $doc->updated_at?->format('h:i a') ?? '' }}
                                                    </small>
                                                </td>

                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8">
                                                        <x-no-data />
                                                    </td>
                                                </tr>
                                            @endforelse

                                        </table>
                                    </div>

                                    <div class="mx-3 my-3">{{ $documentations->withQueryString()->links() }}</div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            @include('layout.components.copyright')
        </div>

    @endsection
