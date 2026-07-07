@extends('admin.layout.layout')
@section('title', Route::is('admin.contact.index') ? $title : '🟢🟢🟢')

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
                            <li class="breadcrumb-item active">Contact Messages</li>
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
                                        <i class="bi-chat-dots bi fs-5"></i>
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
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Category</th>
                                                <th>Subject</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @forelse ($contactMessages as $contact)
                                            <tr>
                                                <td>
                                                    {{ $contactMessages->firstItem() + $loop->iteration - 1 }}
                                                </td>

                                                <td>{{ $contact->name }}</td>
                                                <td>{{ $contact->email }}</td>
                                                <td>{{ $contact->category }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($contact->subject, 35, '...') }}</td>

                                                <td>
                                                    {{ $contact->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.contact.status.update', $contact) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')

                                                        <select name="status" class="form-select form-select-sm"
                                                            onchange="this.form.submit()">
                                                            <option value="pending"
                                                                {{ $contact->status == 'pending' ? 'selected' : '' }}>
                                                                Pending
                                                            </option>
                                                            <option value="read"
                                                                {{ $contact->status == 'read' ? 'selected' : '' }}>
                                                                Read
                                                            </option>
                                                            <option value="replied"
                                                                {{ $contact->status == 'replied' ? 'selected' : '' }}>
                                                                Replied
                                                            </option>
                                                            <option value="closed"
                                                                {{ $contact->status == 'closed' ? 'selected' : '' }}>
                                                                Closed
                                                            </option>
                                                        </select>
                                                    </form>
                                                </td>

                                                <td>
                                                    <div class="action-container m-0 gap-1">

                                                        <a href="#" class="info ms-0" data-bs-toggle="modal"
                                                            data-bs-target="#contactModal{{ $contact->id }}">
                                                            <i class="bx bx-info-circle fs-4"></i>
                                                        </a>

                                                        <div class="modal fade" id="contactModal{{ $contact->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="contactModalLabel{{ $contact->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">

                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="contactModalLabel{{ $contact->id }}">
                                                                            Contact Message Details
                                                                        </h5>

                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="row g-3">

                                                                            <div class="col-md-6">
                                                                                <label class="fw-bold">Name</label>
                                                                                <p class="mb-0">{{ $contact->name }}</p>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="fw-bold">Email</label>
                                                                                <p class="mb-0">
                                                                                    <a href="mailto:{{ $contact->email }}">
                                                                                        {{ $contact->email }}
                                                                                    </a>
                                                                                </p>
                                                                            </div>



                                                                            <div class="col-md-6">
                                                                                <label class="fw-bold">Category</label>
                                                                                <p class="mb-0">
                                                                                    {{ $contact->category ?? 'N/A' }}
                                                                                </p>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="fw-bold">Status</label>
                                                                                <p class="mb-0">
                                                                                    <span
                                                                                        class="badge  badge-{{ $contact->status == 'pending' ? 'yellow' : 'green' }}">
                                                                                        {{ ucfirst($contact->status) }}
                                                                                    </span>
                                                                                </p>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <label class="fw-bold">Received On</label>
                                                                                <p class="mb-0">
                                                                                    {{ $contact->created_at->format('d M Y') }}
                                                                                    <small class="text-muted">
                                                                                        {{ $contact->created_at->format('h:i A') }}
                                                                                    </small>
                                                                                </p>
                                                                            </div>

                                                                            @if ($contact->attachment)
                                                                                <div class="col-md-6">
                                                                                    <label
                                                                                        class="fw-bold">Attachment</label>
                                                                                    <p class="mb-0">
                                                                                        <a href="{{ asset('storage/' . $contact->attachment) }}"
                                                                                            target="_blank"
                                                                                            class="btn btn-sm btn-outline-primary w-fit d-flex align-items-center justify-content-center">
                                                                                            <i
                                                                                                class="bx bx-paperclip me-1"></i>
                                                                                            View Attachment
                                                                                        </a>
                                                                                    </p>
                                                                                </div>
                                                                            @endif

                                                                            <div class="col-12">
                                                                                <label class="fw-bold">Subject</label>
                                                                                <p class="mb-0">{{ $contact->subject }}
                                                                                </p>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <label class="fw-bold">Message</label>
                                                                                <div class="border rounded p-2 bg-light">
                                                                                    {!! nl2br(e($contact->message)) !!}
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-light border"
                                                                            data-bs-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <form
                                                            action="{{ route('admin.contact.delete', ['contact' => $contact]) }}"
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
                                                <td colspan="8">
                                                    <x-no-data />
                                                </td>
                                            </tr>
                                        @endforelse

                                    </table>

                                    <div class="m-3 mb-0">
                                        {{ $contactMessages->withQueryString()->links() }}
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
