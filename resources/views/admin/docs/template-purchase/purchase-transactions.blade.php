@extends('admin.layout.layout')
@section('title', Route::is('admin.docs.templates-purchases.index') ? $title : '🟢🟢🟢')

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
                            <li class="breadcrumb-item active">Template Puchases</li>
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
                                                    <a href="{{ route('admin.docs.templates-purchases.index') }}"
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
                                                <th>Template</th>
                                                <th>Transaction ID</th>
                                                <th>Price</th>
                                                <th>Purchased At</th>
                                                <th>Status</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        @forelse ($transactions as $transaction)
                                            <tr>
                                                <td>
                                                    {{ $transactions->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td>
                                                    <div class="text-dark truncate-2 d-flex align-items-center">
                                                        <img src="{{ $transaction?->user?->profilePicture() ?? '' }}"
                                                            alt="" class="avatar-sm rounded-circle me-1">
                                                        <span>
                                                            <div>{{ $transaction?->user?->fullname() ?? '' }}</div>
                                                            <div>
                                                                <strong>{{ $transaction?->user?->username ?? '' }}</strong>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>

                                                    @if (isset($transaction->template))
                                                        <a href="{{ route('admin.docs.templates.show', ['template' => $transaction->template]) }}"
                                                            class="info ms-0 d-flex align-items-center gap-2">
                                                            {{ $transaction->template->title }} <i
                                                                class="bi bi-box-arrow-up-right"></i>
                                                        </a>
                                                    @else
                                                        <i class="text-muted">Not Available!</i>
                                                    @endif

                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit($transaction->transaction_id, 50, '...') }}
                                                </td>
                                                <td>${{ number_format($transaction->price ?? 0, 2) }}</td>
                                                <td>
                                                    {{ $transaction->purchased_at->format('d M Y') }} <small
                                                        class="text-muted">{{ $transaction->purchased_at->format('h:i a') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    @switch($transaction->payment_status)
                                                        @case('paid')
                                                            <span class="badge badge-green">Paid</span>
                                                        @break

                                                        @case('pending')
                                                            <span class="badge badge-orange text-dark">Pending</span>
                                                        @break

                                                        @case('failed')
                                                            <span class="badge badge-red">Failed</span>
                                                        @break

                                                        @case('refunded')
                                                            <span class="badge badge-purple">Refunded</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-secondary">
                                                                {{ ucfirst($transaction->payment_status) }}
                                                            </span>
                                                    @endswitch
                                                </td>

                                                {{-- <td>
                                                    <div class="action-container m-0 gap-1">

                                                    </div>
                                                </td> --}}
                                            </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="8">
                                                        <x-no-data />
                                                    </td>
                                                </tr>
                                            @endforelse

                                        </table>

                                        <div class="m-3 mb-0">
                                            {{ $transactions->withQueryString()->links() }}
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
