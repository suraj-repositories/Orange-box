<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo logo-light' href='index.html'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="24">
                    </span>
                </a>
                <a class='logo logo-dark' href='index.html'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="24">
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
                                <a class='tp-link' href='{{ authRoute('user.dashboard') }}'>Analytical</a>
                            </li>
                            <li>
                                <a class='tp-link' href='#'>Management</a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="menu-title">Think Space</li>

                <li>
                    <a href="#dailyDigest" data-bs-toggle="collapse">
                        <i data-feather="file-text"></i>
                        <span> Daily Digest </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="dailyDigest">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href="{{ authRoute('user.daily-digest.create') }}">Add Digestion</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.daily-digest') }}'>Digestions List</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#creative" data-bs-toggle="collapse">
                        <i class="bi bi-clipboard2 fs-5"></i>
                        <span> Think Pad </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="creative">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.think-pad.create') }}'>Create Think Pad</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.think-pad') }}'>Think Pads</a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#code-syntax-container" data-bs-toggle="collapse">
                        <i data-feather="alert-octagon"></i>
                        <span> Syntax Store </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="code-syntax-container">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.syntax-store.create') }}'>Write Syntax</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.syntax-store') }}'>Syntax Store</a>
                            </li>
                            <li>

                        </ul>
                    </div>
                </li>



                <li class="menu-title mt-2">Orbit Zone</li>
                <li>
                    <a href="#folder-insights" data-bs-toggle="collapse">
                        <i class="bx bx-folder fs-5"></i>
                        <span> Folder Factory </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="folder-insights">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.folder-factory.files.create') }}'>Upload File</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.folder-factory') }}'>Folder List</a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="menu-title mt-2">Project Tracker</li>
                <li>
                    <a href="#project-zone" data-bs-toggle="collapse">
                        <i class='bx bx-cube-alt fs-5' ></i>
                        <span> Project Board </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="project-zone">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.project-board.create') }}'>Create Project</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.project-board') }}'>Project List</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#project-module" data-bs-toggle="collapse">
                        <i class='bx bxs-cube-alt fs-5'></i>
                        <span> Project Module </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="project-module">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.modules.create') }}'>Create Module</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.modules.index') }}'>Project Module List</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#project-task" data-bs-toggle="collapse">
                       <i class='bx bx-target-lock fs-5'></i>
                        <span> Project Tasks </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="project-task">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.tasks.create') }}'>Create Task</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.tasks.index') }}'>Task List</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#collaborations" data-bs-toggle="collapse">
                       <i class='bx bx-donate-heart fs-5'></i>
                        <span> Collaboration </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="collaborations">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.collab.project-board.index') }}'>Project Board</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.collab.modules.index') }}'>Modules</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ authRoute('user.collab.tasks.index') }}'>Tasks</a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="menu-title mt-2">Account</li>
                <li>
                    <a href="{{ authRoute('user.profile.index') }}" class='tp-link' href='calendar'>
                        <i data-feather="user"></i>
                        <span> My Profile </span>
                    </a>
                </li>
                <li>
                    <a class='tp-link' href='calendar'>
                        <i data-feather="settings"></i>
                        <span> Settings </span>
                    </a>
                </li>
            </ul>

        </div>
        <div class="clearfix"></div>

    </div>
</div>
