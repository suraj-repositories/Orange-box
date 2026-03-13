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
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentations</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.show.latest', ['documentation' => $documentation]) }}">{{ $documentation->title }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
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
                                        <i data-feather="crosshair" class="widgets-icons"></i>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $title }}</h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#realease-form-modal"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div>New</div>
                                        </a>
                                    </div>
                                    @include('user.documentation.releases.realase_form_modal')
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Group Title</th>
                                                <th>Version</th>
                                                <th>Creation Date</th>
                                                <th>Release Date</th>
                                                <th>Visibility</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @forelse ($releases as $release)
                                            <tr>
                                                <td>
                                                    {{ $releases->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td>{{ $release->title }}</td>
                                                <td>{{ $release->version }}</td>
                                                <td>{{ $release->created_at?->format('M d, Y h:i a') }}</td>
                                                <td>{{ $release->released_at?->format('M d, Y h:i a') ?? '-' }}</td>
                                                <td>

                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input releaseStatusToggleSwitch"
                                                            type="checkbox" role="switch"
                                                            data-documentation-release-id="{{ $release->id }}"
                                                            {{ $release->is_published ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="action-container m-0 gap-1">
                                                        <a href="{{ authRoute('user.documentation.pages.index', ['documentation' => $release->documentation, 'release' => $release]) }}"
                                                            class="info ms-0 ">
                                                            <i class='bx bx-info-circle fs-4'></i>
                                                        </a>

                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#realease-form-modal-{{ $release->id }}"
                                                            class="edit">
                                                            <i class='bx bx-edit fs-4'></i>
                                                        </a>
                                                        @include(
                                                            'user.documentation.releases.realase_form_modal',
                                                            [
                                                                'release' => $release,
                                                            ]
                                                        )

                                                        <form
                                                            action="{{ authRoute('user.documentation.release.delete', ['release' => $release]) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="delete btn-no-style">
                                                                <i class='bx bx-trash fs-4'></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <x-no-data />
                                                </td>
                                            </tr>
                                        @endforelse

                                    </table>

                                    <div class="m-3 mb-0">
                                        {{ $releases->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/documentation-releases.js') }}"></script>
@endsection
