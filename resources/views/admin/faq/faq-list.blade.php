@extends('admin.layout.layout')
@section('title', Route::is('admin.faq.index') ? $title : '🟢🟢🟢')

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
                            <li class="breadcrumb-item active">Faqs</li>
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
                                        <a href="{{ route('admin.faq.create') }}"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div>New</div>
                                        </a>
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
                                                    <a href="{{ route('admin.faq.index') }}"
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
                                                <th>Question</th>
                                                <th>Answer</th>
                                                <th>Last Updated</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @forelse ($faqs as $faq)
                                            <tr>
                                                <td>
                                                    {{ $faqs->firstItem() + $loop->iteration - 1 }}
                                                </td>

                                                <td>{{ \Illuminate\Support\Str::limit($faq->question, 50, '...') }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($faq->answer, 50, '...') }}</td>
                                                <td>
                                                    {{ $faq->updated_at->format('d M Y') }} <small
                                                        class="text-muted">{{ $faq->updated_at->format('h:i a') }} </small>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input statusSwitch" type="checkbox"
                                                            role="switch"
                                                            data-url="{{ route('admin.faq.status.update', ['faq' => $faq]) }}"
                                                            {{ $faq->is_active ? 'checked' : '' }}>

                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="action-container m-0 gap-1">

                                                        <a href="#" class="info ms-0" data-bs-toggle="modal"
                                                            data-bs-target="#faqModal{{ $faq->id }}">
                                                            <i class="bx bx-info-circle fs-4"></i>
                                                        </a>

                                                        <div class="modal fade" id="faqModal{{ $faq->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="faqModalLabel{{ $faq->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="faqModalLabel{{ $faq->id }}">
                                                                            FAQ Details
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="mb-4">
                                                                            <h6 class="fw-bold">Question</h6>
                                                                            <p class="mb-0">{{ $faq->question }}</p>
                                                                        </div>

                                                                        <div>
                                                                            <h6 class="fw-bold">Answer</h6>
                                                                            <div>
                                                                                {!! nl2br(e($faq->answer)) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn border btn-light"
                                                                            data-bs-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a href="{{ route('admin.faq.edit', ['faq' => $faq]) }}"
                                                            class="edit">
                                                            <i class='bx bx-edit fs-4'></i>
                                                        </a>

                                                        <form action="{{ route('admin.faq.delete', ['faq' => $faq]) }}"
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
                                                <td colspan="6">
                                                    <x-no-data />
                                                </td>
                                            </tr>
                                        @endforelse

                                    </table>

                                    <div class="m-3 mb-0">
                                        {{ $faqs->withQueryString()->links() }}
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
