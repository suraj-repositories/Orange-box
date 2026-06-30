<div class="topbar-custom">


    <div class="container-xxl">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <div class="logo-box">
                        <a class='logo logo-light'
                            href='{{ route('docs.show', [
                                'user' => $user->username,
                                'slug' => $documentation->url,
                                'version' => $release->version,
                                'path' => '',
                            ]) }}'>
                            <span class="logo-sm">
                                <img src="{{ Storage::url($documentation->logo_sm_light) }}" alt="">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ Storage::url($documentation->logo_light) }}" alt="">
                            </span>
                        </a>
                        <a class='logo logo-dark'
                            href='{{ route('docs.show', [
                                'user' => $user->username,
                                'slug' => $documentation->url,
                                'version' => $release->version,
                                'path' => '',
                            ]) }}'>
                            <span class="logo-sm">
                                <img src="{{ Storage::url($documentation->logo_sm_dark) }}" alt="">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ Storage::url($documentation->logo_dark) }}" alt="">
                            </span>
                        </a>
                    </div>
                </li>

                <li class="d-none d-lg-block">
                    <div class="position-relative topbar-search" id="search-button">
                        <input type="text" class="form-control bg-light bg-opacity-75 ps-4" placeholder="Search...">
                        <i
                            class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>

                        <span class="shortcut-badge">Ctrl K</span>
                    </div>


                </li>
            </ul>

            <ul class="list-unstyled topnav-menu right-menu mb-0 d-flex align-items-center d-flex d-sm-none">
                <li>
                    <button type="button" id="fullScreenNavToggle"
                        class="btn nav-link d-flex align-items-center justify-content-center">
                        <i class='bx bx-menu-alt-right'></i>
                    </button>
                </li>
            </ul>

            <div class="mobile-nav-screen">
                <ul class="list-unstyled topbar-menu topnav-menu right-menu mb-0 d-flex align-items-center">
                    <li>
                        <a href="{{ route('docs.show', [
                            'user' => $user->username,
                            'slug' => $documentation->url,
                            'version' => $release->version,
                            'path' => '',
                        ]) }}"
                            class="nav-link in-full-nav dropdown-toggle nav-user me-0 {{ Route::is('docs.show') ? 'active' : '' }}">
                            <span class="pro-user-name ms-1">Docs</span>
                        </a>
                    </li>
                    <li>
                        <x-docs.nav.sponsor-page-link :user="$user" :documentation="$documentation" :release="$release" />
                    </li>
                    <li>
                        <x-docs.nav.partners-page-link :user="$user" :documentation="$documentation" :release="$release" />
                    </li>

                    <li class="dropdown in-full-nav dropdown-list topbar-dropdown">
                        @php $isAboutPage = Route::is('docs.extras.show') && !in_array(request('type'), ['partners', 'sponsors']) ; @endphp
                        <a class="nav-link dropdown-toggle nav-user me-0  {{ $isAboutPage || Route::is('docs.releases.index') ? 'active' : '' }}"
                            data-bs-toggle="dropdown" href="#" role="button">
                            <span class="pro-user-name ms-1 dropdown-icon">About <i
                                    class="mdi mdi-chevron-down"></i></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">

                            <a class="dropdown-item notify-item"
                                href="{{ route('docs.releases.index', ['user' => $user->username, 'slug' => $documentation->url]) }}">
                                <span>Releases</span>
                            </a>

                            <x-docs.documentation-document-nav-items :user="$user" :documentation="$documentation"
                                :release="$release" />

                        </div>
                    </li>

                    <li class="mobile-dots-container">
                        <div class="dropdown">
                            <button type="button"
                                class="btn three-dot-btn nav-link d-flex align-items-center justify-content-center"
                                data-bs-toggle="dropdown">
                                <i class='bx bx-dots-horizontal-rounded fs-4'></i>
                            </button>
                            <ul class="dropdown-menu mobile-social-menu dropdown-menu-end p-2">
                                <li
                                    class="dropdown-item apearence-card d-flex align-items-center justify-content-between">
                                    <span class="me-2 apearance-txt">Appearance</span>
                                    <label for="themeToggle" class="theme">
                                        <span class="theme__toggle-wrap">
                                            <input id="themeToggle" class="theme__toggle" type="checkbox"
                                                role="switch" />
                                            <span class="theme__fill"></span>
                                            <span class="theme__icon">
                                                <span class="theme__icon-part"></span><span
                                                    class="theme__icon-part"></span>
                                                <span class="theme__icon-part"></span><span
                                                    class="theme__icon-part"></span>
                                                <span class="theme__icon-part"></span><span
                                                    class="theme__icon-part"></span>
                                                <span class="theme__icon-part"></span><span
                                                    class="theme__icon-part"></span>
                                                <span class="theme__icon-part"></span>
                                            </span>
                                        </span>
                                    </label>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="d-flex justify-content-around social-media-icons">
                                    <x-docs.social-media-links :documentation="$documentation" />
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal ux-search-modal" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ux-search-dialog">
        <div class="modal-content ux-search-content">

            <div class="ux-search-header">
                <i class="bx bx-search lence-icon"></i>
                <input type="text" class="ux-search-input" id="search-input" placeholder="Search docs, guides, or anything..."
                    autofocus data-username="{{ request('user')['username'] ?? "" }}" data-slug="{{ request('slug') }}"
                    data-version="{{ request('version') }}">
                <button type="button" class="ux-search-close" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                </button>
                <script>
                    window.searchText = '{{ request("q") }}';
                </script>
            </div>

            <div class="ux-search-body p-2">

            </div>

            <div class="ux-search-footer">
                <span><span class="shortcut-badge">↑</span> <span class="shortcut-badge">↓</span> to navigate</span>
                <span><span class="shortcut-badge">Enter</span> to select</span>
                <span> <span class="shortcut-badge">Esc</span> to close</span>
            </div>

        </div>
    </div>
</div>
