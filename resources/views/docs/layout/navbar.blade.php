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

                {{-- <li>
                    <button class="button-toggle-menu nav-link ps-0">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li> --}}
                <li class="d-none d-lg-block">
                    <div class="position-relative topbar-search">
                        <input type="text" class="form-control bg-light bg-opacity-75 ps-4" placeholder="Search...">
                        <i
                            class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>

                        <span class="shortcut-badge">Ctrl K</span>
                    </div>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">


                <li>
                    <a class="nav-link dropdown-toggle nav-user me-0">
                        <span class="pro-user-name ms-1">Docs</span>
                    </a>
                </li>

                <li>
                    <a class="nav-link dropdown-toggle nav-user me-0">
                        <span class="pro-user-name ms-1">Sponser</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link dropdown-toggle nav-user me-0">
                        <span class="pro-user-name ms-1">Partners</span>
                    </a>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">

                        <span class="pro-user-name ms-1"> About <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">


                        <a class='dropdown-item notify-item' href='#'>
                            <span>FAQ's</span>
                        </a>
                        <a class='dropdown-item notify-item' href='#'>
                            <span>Releases</span>
                        </a>
                        <a class='dropdown-item notify-item' href='#'>
                            <span>Code of conduct</span>
                        </a>
                        <a class='dropdown-item notify-item' href='#'>
                            <span>Privacy Policy</span>
                        </a>

                    </div>
                </li>

                <li>
                    <label for="themeToggle" class="theme">
                        <span class="theme__toggle-wrap">
                            <input id="themeToggle" class="theme__toggle" type="checkbox" role="switch" name="theme"
                                value="dark" />
                            <span class="theme__fill"></span>
                            <span class="theme__icon">
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                                <span class="theme__icon-part"></span>
                            </span>
                        </span>
                    </label>
                </li>



                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link">
                        <i class='bx bxl-github align-middle'></i>
                    </button>
                </li>
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link">
                        <i class='bx bxl-twitter align-middle'></i>
                    </button>
                </li>
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link">
                        <i class='bx bxl-discord align-middle'></i>
                    </button>
                </li>




            </ul>
        </div>

    </div>

</div>
