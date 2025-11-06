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

                                <div class="accordion accordion-flush plain-accordion notification-view"
                                    id="notificationsList">
                                    @forelse ($notifications as $notification)
                                        <div class="accordion-item {{ empty($notification->read_at) ? '' : 'readed' }}"
                                            data-ob-notification-status="{{ empty($notification->read_at) ? 'unread' : 'read' }}">
                                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                                <div class="accordion-button fw-medium collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $loop->iteration }}" aria-expanded="false"
                                                    aria-controls="collapse-{{ $loop->iteration }}">

                                                    <div
                                                        class="file-toggle d-flex align-items-center overflow-hidden w-sm-100 min-w-200  me-sm-4">
                                                        <div class="icon me-2 position-relative">

                                                            <img class="img-badge-40 rounded-circle"
                                                                src="{{ $notification?->from_user?->avatar_url }}"
                                                                alt="">

                                                            <span class="unread-badge"></span>

                                                        </div>
                                                        <div class="name me-2 w-100 ">
                                                            <div class="w-100 d-flex">
                                                                <div class="text-truncate">
                                                                    {{ $notification?->from_user?->fullname() ?? ($notification?->from_user?->username ?? '') }}
                                                                </div>
                                                                <div class="ms-auto sm-date">
                                                                    {{ $notification?->created_at?->diffForHumans() ?? '' }}
                                                                </div>
                                                            </div>
                                                            <small
                                                                class="sm-message truncate-1 text-muted">{{ $notification?->data['message'] ?? '-' }}</small>
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
                                            <div id="collapse-{{ $loop->iteration }}"
                                                @if (empty($notification->read_at)) data-ob-notification-id="{{ $notification->id }}" @endif
                                                class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $loop->iteration }}"
                                                data-bs-parent="#notificationsList">
                                                <div class="accordion-body">

                                                    <ul class="list-unstyled fs-6 mb-3">
                                                        <li class="mb-2">
                                                            <strong>Message : </strong>
                                                            {{ $notification?->data['message'] ?? '-' }}

                                                        </li>
                                                        <li>
                                                            <div class="d-flex align-items-center">
                                                                <strong>Details : </strong>


                                                                <a class="mx-2 d-flex align-items-center flex-wrap"
                                                                    href="{{ $notification?->data['visit_url'] ?? '#' }}">
                                                                    <i class='bx bx-package fs-5 me-1'></i><span>{{ $notification->link_text }}</span>
                                                                </a>

                                                                @if (!empty($notification?->data['priority']))
                                                                    <i class="bx bxs-circle me-1 fs-4 priority-{{ $notification?->data['priority'] }}"></i>
                                                                    {{ ucfirst($notification?->data['priority']) }}
                                                                    Priority
                                                                @endif
                                                            </div>
                                                        </li>
                                                        <li><strong>Type :</strong>
                                                            {{ ucfirst($notification->data['type'] ?? '-') }}
                                                        </li>
                                                        <li><strong>Creation date :</strong>
                                                            {{ $notification?->created_at?->format('F d, Y h:i a') ?? '' }}
                                                        </li>

                                                    </ul>


                                                    <!-- Actions -->
                                                    <h6 class="mb-2">Actions</h6>
                                                    <div class="action d-flex gap-2 flex-wrap">


                                                        <button
                                                            class="btn btn-outline-danger btn-sm delete-notification-button"
                                                            data-ob-notification-id="{{ $notification->id }}">
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

                                <div class="m-2">
                                    {{ $notifications->links() }}
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
    <script src="{{ asset('assets/js/pages/notification.js') }}"></script>
@endsection
