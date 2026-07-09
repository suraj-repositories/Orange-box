<div class="topbar-custom">
    <div class="container-xxl">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link ps-0">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                @if (session()->has('impersonator_admin_id'))
                    <li>
                        <div class=" nav-link ps-0">
                            <a href="{{ route('stop-impersonation') }}" class="btn border py-1 d-flex align-items-center w-fit justify-content-center px-2">
                                <i class="bi bi-box-arrow-in-left fs-5"></i><span class="d-none d-sm-block ps-1">Admin Panel</span>
                            </a>
                        </div>
                    </li>
                @endif
                <li class="d-none d-lg-block">
                    <div class="position-relative topbar-search" id="search-button">
                        <input type="text" class="form-control bg-light bg-opacity-75 border-light ps-4"
                            placeholder="Search..." value="{{ request()->get('q') ?? '' }}">
                        <i
                            class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>
                    </div>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                <li>
                    <x-user.shortcut.shortcut-nav-button />
                </li>
                <li>
                    <x-notification.notification-component />
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ auth()->user()->profilePicture() }}" alt="user-image"
                            class="rounded-circle user-image user_profile_picture">
                        <span class="pro-user-name ms-1"> {{ auth()->user()->name() }} <i
                                class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown p-2">

                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome {{ auth()->user()->name() }}!</h6>
                        </div>

                        <a class='dropdown-item notify-item' href='{{ authRoute('user.profile.index') }}'>
                            <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                            <span>My Profile</span>
                        </a>

                        <button class='dropdown-item notify-item lock_screen_btn'>
                            <i class="mdi mdi-lock-outline fs-16 align-middle"></i>
                            <span>Lock Screen</span>
                        </button>

                        <a class='dropdown-item notify-item' href='{{ authRoute('user.settings.index') }}'>
                            <i class="mdi mdi-cog-outline fs-16 align-middle"></i>
                            <span>Settings</span>
                        </a>
                        <a class='dropdown-item notify-item' href='{{ authRoute('user.faq.index') }}'>
                            <i class="mdi mdi-help-circle-outline fs-16 align-middle"></i>
                            <span>Help</span>
                        </a>
                        <a class='dropdown-item notify-item' href='{{ authRoute('user.contact-us') }}'>
                            <i class="mdi mdi-chat-outline fs-16 align-middle"></i>
                            <span>Contact Us</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button class='dropdown-item notify-item logout-button'>
                                <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                <span>Logout</span>
                            </button>
                        </form>

                    </div>
                </li>

            </ul>
        </div>

    </div>

</div>

<div class="modal ux-app-search-modal" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ux-app-search-dialog">
        <div class="modal-content ux-app-search-content">

            <div class="ux-app-search-header">
                <i class="bx bx-search lence-icon"></i>
                <input type="text" class="ux-app-search-input" id="search-input" autocomplete="off"
                    placeholder="Search pages, projects, or anything..." autofocus
                    data-username="{{ auth()?->user()?->username ?? '' }}">
                <button type="button" class="ux-app-search-close" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                </button>
                <script>
                    window.searchText = '{{ request('q') }}';
                </script>
            </div>

            <div class="ux-app-search-body p-2">

            </div>

            <div class="ux-app-search-footer">
                <span><span class="shortcut-badge">↑</span> <span class="shortcut-badge">↓</span> to navigate</span>
                <span><span class="shortcut-badge">Enter</span> to select</span>
                <span> <span class="shortcut-badge">Esc</span> to close</span>
            </div>

        </div>
    </div>
</div>
