<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo logo-light' href='index.html'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="">
                    </span>
                </a>
                <a class='logo logo-dark' href='index.html'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                <li>
                    <a href="#sidebarDashboards" data-bs-toggle="collapse">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarDashboards">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('admin.dashboard') }}'>Home</a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarTemplates" data-bs-toggle="collapse">
                        <i class="bi bi-file-richtext fs-5"></i>
                        <span> Docs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTemplates">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ route('admin.docs.templates.index') }}'>Templates</a>
                            </li>

                        </ul>
                    </div>
                </li>

            </ul>

        </div>
        <div class="clearfix"></div>

    </div>
</div>
