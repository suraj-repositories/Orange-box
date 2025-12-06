<div class="dropdown notification-list topbar-dropdown notification-drawer">
    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
        aria-expanded="false">
        <i data-feather="bell" class="noti-icon"></i>
        @if ($unreadNotificationCount > 0)
            <span
                class="badge bg-dark {{ $unreadNotificationCount < 10 ? 'rounded-circle' : 'rounded-pill' }} noti-icon-badge">{{ $unreadNotificationCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-lg">

        <!-- item-->
        <div class="dropdown-item noti-title">
            <h5 class="m-0">
                <span class="float-end">
                    @if ($unreadNotificationCount > 0)
                        <a href="javascript:void(0)" id="clearAllNotificationsBtn" class="text-dark">
                            <small>Clear All</small>
                        </a>
                    @else
                        <a href="{{ authRoute('user.notifications.index') }}"
                            class="text-primary  d-flex align-items-center">
                            <small class="text-decoration-underline"> View all</small> <i
                                class="bx bx-right-arrow-alt"></i>
                        </a>
                    @endif
                </span>Notification
            </h5>
        </div>

        <div class="noti-scroll" data-simplebar>

            @forelse ($unreadNotifications as $notification)
                <a href="{{ authRoute('user.notifications.index', ['search' => 'id-' . $notification->id]) }}"
                    class="dropdown-item notify-item text-muted link-primary active">
                    <div class="notify-icon">
                        <img src="{{ $notification?->from_user?->avatar_url }}" class="img-fluid rounded-circle"
                            alt="" />
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="notify-details">
                          {{ (empty($notification?->from_user?->fullname()) ? null : $notification?->from_user?->fullname()) ?? ($notification?->from_user?->username ?? '') }}
                        </p>
                        <small class="text-muted">{{ $notification?->created_at?->diffForHumans() ?? '' }}</small>
                    </div>
                    <p class="mb-0 user-msg">
                        <small class="fs-14 truncate-1">
                            {{ $notification?->data['message'] ?? '' }}
                        </small>
                        {{--
                         <div class="d-flex mt-2 align-items-center ms-3">
                            <div class="notify-sub-icon">
                                <i class="mdi mdi-download-box text-dark"></i>
                            </div>

                            <div>
                                <p class="notify-details mb-0">dark-themes.zip</p>
                                <small class="text-muted">2.4 MB</small>
                            </div>
                            </div>
                         --}}
                    </p>
                </a>
            @empty
                <div class="pb-3">
                    <x-no-data />
                </div>
            @endforelse

        </div>

        @if ($unreadNotificationCount > 0)
            <!-- All-->
            <a href="{{ authRoute('user.notifications.index') }}"
                class="dropdown-item text-center text-primary notify-item notify-all">
                View all
                <i class="bx bx-right-arrow-alt"></i>
            </a>
        @endif
    </div>
</div>
