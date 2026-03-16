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
                    <div class="col-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26px" height="26px"
                                            viewBox="0 0 80 80">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="5">
                                                <path
                                                    d="M4 27h12.343a4 4 0 0 0 2.829-1.172l3.485-3.485A8 8 0 0 1 28.314 20h4.372a8 8 0 0 1 5.27 1.98a8 8 0 0 0-.28.257l-6.806 6.555a5.954 5.954 0 1 0 8.34 8.498L41.5 35l15.964 12.417a2.653 2.653 0 0 1 .51 3.663l-1.608 2.194A7.9 7.9 0 0 1 50 56.5l-1.113 1.113a6.44 6.44 0 0 1-8.678.394L39 57l-.702.702a7.846 7.846 0 0 1-11.096 0l-7.53-7.53A4 4 0 0 0 16.843 49H4z" />
                                                <path
                                                    d="M46 30.5L41.5 35m0 0l-2.29 2.29a5.954 5.954 0 1 1-8.34-8.498l6.807-6.555A8 8 0 0 1 43.226 20h8.46a8 8 0 0 1 5.657 2.343l3.485 3.485A4 4 0 0 0 63.658 27H76v22H59.5zM12 27.059v22m56-22v22" />
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="card-title mb-0"> Partners </h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">

                                        <a href="{{ authRoute('user.documentation.partners.create', ['documentation' => $documentation]) }}"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div>Add Partner</div>
                                        </a>



                                    </div>
                                </div>
                            </div>


                            <div class="card-body p-0">

                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Logo</th>
                                                <th>Name</th>
                                                <th>Location</th>
                                                <th width="140">Created At</th>
                                                <th width="120">Spotlight</th>
                                                <th width="120">Status</th>
                                                <th width="120">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($partners as $partner)
                                                <tr>
                                                    <th> {{ $partners->firstItem() + $loop->iteration - 1 }}</th>
                                                    <td>
                                                        @if (!empty($partner->logo))
                                                            @if (!empty($partner->website_url))
                                                                <a href="{{ $partner->website_url }}" target="_blank">
                                                            @endif

                                                            <img src="{{ Storage::url($partner->logo) }}"
                                                                class="avatar avatar-sm img-fluid rounded-2 me-1 avatar-xl-logo"
                                                                aria-label="{{ $partner->name }}">


                                                            @if (!empty($partner->website_url))
                                                                </a>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if (!empty($partner->website_url))
                                                            <a href="{{ $partner->website_url }}" target="_blank">
                                                                {{ $partner->name }}
                                                            </a>
                                                        @else
                                                            {{ $partner->name }}
                                                        @endif

                                                        @if ($partner->is_spotlight_partner == '1')
                                                            <i class='bx bx-check-circle m1-2 fs-5 text-success'></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $partner->location ?? '-' }}
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $partner->created_at->diffForHumans() }}
                                                        </small>
                                                    </td>
                                                    <td>


                                                        <div class="form-check">
                                                            <input class="form-check-input spotlightUpdateRadio"
                                                                type="radio" name="is_spotlight_partner"
                                                                data-documentation-partner-id="{{ $partner->id }}"
                                                                {{ $partner->is_spotlight_partner == '1' ? 'checked' : '' }}>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input partnerStatusToggleSwitch"
                                                                type="checkbox" role="switch"
                                                                data-documentation-partner-id="{{ $partner->id }}"
                                                                {{ $partner->status == 'active' ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action-container m-0 gap-1">

                                                            <a class="edit edit-partner"
                                                                href="{{ authRoute('user.documentation.partner.edit', ['partner' => $partner]) }}">
                                                                <i class='bx bx-edit fs-5'></i>
                                                            </a>

                                                            <form
                                                                action="{{ authRoute('user.documentation.partner.delete', ['partner' => $partner]) }}"
                                                                method="POST" data-confirm-delete>
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" disabled
                                                                    class="delete btn-no-style delete-password-locker-button">
                                                                    <i class='bx bx-trash fs-5'></i>
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
                                        </tbody>

                                    </table>

                                    @if ($partners->lastPage() > 1)
                                        <div class="m-3 mb-0">
                                            {{ $partners->withQueryString()->links() }}
                                        </div>
                                    @endif
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
    <script src="{{ asset('assets/js/pages/documentation-partners.js') }}"></script>
@endsection
