<div class="topbar-custom">


    <div class="container-xxl">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <div class="logo-box">
                        <a class='logo logo-light' href='index.html'>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ $documentation->logo_url }}" alt="">
                            </span>
                        </a>
                        <a class='logo logo-dark' href='index.html'>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ $documentation->logo_url }}" alt="">
                            </span>
                        </a>
                    </div>
                </li>

                <li class="d-none d-lg-block">
                    <div class="position-relative topbar-search">
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
                        <a class="nav-link in-full-nav dropdown-toggle nav-user me-0 active">
                            <span class="pro-user-name ms-1">Docs</span>
                        </a>
                    </li>
                    <li><a class="nav-link in-full-nav dropdown-toggle nav-user me-0"><span
                                class="pro-user-name ms-1">Sponsor</span></a></li>
                    <li><a class="nav-link in-full-nav dropdown-toggle nav-user me-0"><span
                                class="pro-user-name ms-1">Partners</span></a></li>

                    <li class="dropdown in-full-nav dropdown-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#"
                            role="button">
                            <span class="pro-user-name ms-1 dropdown-icon">About <i class="mdi mdi-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <a class='dropdown-item notify-item' href='#'><span>FAQ's</span></a>
                            <a class='dropdown-item notify-item' href='#'><span>Releases</span></a>
                            <a class='dropdown-item notify-item' href='#'><span>Code of conduct</span></a>
                            <a class='dropdown-item notify-item' href='#'><span>Privacy Policy</span></a>
                        </div>
                    </li>

                    <li class="mobile-dots-container">
                        <div class="dropdown">
                            <button type="button" class="btn three-dot-btn nav-link d-flex align-items-center justify-content-center"
                                data-bs-toggle="dropdown">
                                <i class='bx bx-dots-horizontal-rounded fs-4'></i>
                            </button>
                            <ul class="dropdown-menu mobile-social-menu dropdown-menu-end p-2">
                                <li class="dropdown-item apearence-card d-flex align-items-center justify-content-between">
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
                                    <a class="dropdown-item p-2 text-center" href="#"><i
                                            class='bx bxl-github fs-4'></i></a>
                                    <a class="dropdown-item p-2 text-center" href="#"><i
                                            class='bx bxl-twitter fs-4'></i></a>
                                    <a class="dropdown-item p-2 text-center" href="#"><i
                                            class='bx bxl-discord fs-4'></i></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</div>
