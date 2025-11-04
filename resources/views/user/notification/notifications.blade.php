@extends('user.layout.layout')

@section('title', Route::is('user.notifications.index') ? 'Notifications' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Notifications</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Account</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notifications</h5>
                            </div>

                            <div class="card-body p-0">

                                <div class="accordion plain-accordion" id="accordionExample">
                                    @forelse ($notifications as $notification)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                                <div class="accordion-button fw-medium collapsed" type="button">

                                                    <div class="file-toggle d-flex align-items-center overflow-hidden min-w-200  me-4"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $loop->iteration }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $loop->iteration }}">
                                                        <div class="icon me-2">

                                                            <img class="img-badge-40 rounded-circle"
                                                                src="{{ $notification?->from_user?->avatar_url }}"
                                                                alt="">

                                                        </div>
                                                        <div class="name text-truncate w-fit me-2"
                                                            id="id{{ $loop->iteration }}">
                                                            <div>{{ $notification?->from_user?->fullname() ?? '' }}</div>
                                                            <div class="strong-italic text-dark-emphasis fs-10px">
                                                                {{ $notification?->from_user?->username ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="type ms-3 mx-auto w-100 text-start truncate-1">
                                                        {{ $notification?->data['message'] ?? '-' }}</div>
                                                    <div class="type me-2 ms-auto min-w-100 text-center">
                                                        {{ ucfirst($notification->data['type'] ?? '') }}
                                                    </div>
                                                    <small class="date me-2 w-fit-content min-w-100 text-center">
                                                        {{ $notification?->created_at?->diffForHumans() ?? '' }}</small>
                                                </div>
                                            </h2>
                                            <div id="collapse-{{ $loop->iteration }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $loop->iteration }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="d-md-snone">

                                                        <ul class="list-unstyled fs-6 mb-3">
                                                            <li>
                                                                <strong>Message : </strong> {{ $notification?->data['message'] ?? '-' }}
                                                            </li>
                                                            <li>
                                                                <div class="d-flex align-items-center">
                                                                    <strong>Details : </strong>
                                                                    <a class="mx-2 "
                                                                        href="{{ $notification?->data['visit_url'] ?? '#' }}">+
                                                                        1 Task
                                                                    </a>
                                                                    <i class="bx bxs-circle me-1 fs-4 priority-low"></i>
                                                                    Low Priority
                                                                </div>
                                                            </li>
                                                            <li><strong>Type :</strong>
                                                                {{ ucfirst($notification->data['type'] ?? '-') }}
                                                            </li>
                                                            <li><strong>Creation date :</strong>
                                                                {{ $notification?->created_at?->diffForHumans() ?? '' }}
                                                            </li>

                                                        </ul>
                                                    </div>

                                                    <!-- Actions -->
                                                    <h6 class="mb-2">Actions</h6>
                                                    <div class="action d-flex gap-2 flex-wrap">
                                                        <a class="btn btn-outline-primary btn-sm d-flex align-items-center"
                                                            href="#" target="_blank">
                                                            <i class='bx bx-show'></i>&nbsp;View
                                                        </a>

                                                        <button class="btn btn-outline-danger btn-sm delete-file-button">
                                                            <i class='bx bx-trash-alt'></i> Delete
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <x-no-data />
                                    @endforelse
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
