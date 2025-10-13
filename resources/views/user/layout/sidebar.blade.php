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
                                <a class='tp-link' href='index.html'>Analytical</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ecommerce.html'>E-commerce</a>
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
                                <a class='tp-link' href='{{ authRoute('user.project-board') }}'>Task List</a>
                            </li>

                        </ul>
                    </div>
                </li>


                {{-- <li>
                    <a class='tp-link' href='calendar'>
                        <i data-feather="calendar"></i>
                        <span> Calendar </span>
                    </a>
                </li> --}}

                <li class="menu-title mt-2">Account</li>
                <li>
                    <a class='tp-link' href='calendar'>
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

                {{-- <li>
                    <a href="#sidebarBaseui" data-bs-toggle="collapse">
                        <i data-feather="package"></i>
                        <span> Components </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarBaseui">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='ui-accordions'>Accordions</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-alerts'>Alerts</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-badges.html'>Badges</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-breadcrumb'>Breadcrumb</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-buttons.html'>Buttons</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-cards.html'>Cards</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-collapse.html'>Collapse</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-dropdowns.html'>Dropdowns</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-video.html'>Embed Video</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-grid.html'>Grid</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-images.html'>Images</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-list.html'>List Group</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-modals.html'>Modals</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-placeholders'>Placeholders</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-pagination.html'>Pagination</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-popovers.html'>Popovers</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-progress.html'>Progress</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-scrollspy.html'>Scrollspy</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-spinners.html'>Spinners</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-tabs.html'>Tabs</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-tooltips.html'>Tooltips</a>
                            </li>
                            <li>
                                <a class='tp-link' href='ui-typography'>Typography</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a class='tp-link' href='widgets.html'>
                        <i data-feather="aperture"></i>
                        <span> Widgets </span>
                    </a>
                </li>

                <li>
                    <a href="#sidebarAdvancedUI" data-bs-toggle="collapse">
                        <i data-feather="cpu"></i>
                        <span> Extended UI </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarAdvancedUI">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='extended-carousel.html'>Carousel</a>
                            </li>
                            <li>
                                <a class='tp-link' href='extended-notifications.html'>Notifications</a>
                            </li>
                            <li>
                                <a class='tp-link' href='extended-offcanvas.html'>Offcanvas</a>
                            </li>
                            <li>
                                <a class='tp-link' href='extended-range-slider'>Range Slider</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarIcons" data-bs-toggle="collapse">
                        <i data-feather="award"></i>
                        <span> Icons </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarIcons">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='icons-feather.html'>Feather Icons</a>
                            </li>
                            <li>
                                <a class='tp-link' href='icons-mdi.html'>Material Design Icons</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarForms" data-bs-toggle="collapse">
                        <i data-feather="briefcase"></i>
                        <span> Forms </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarForms">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='forms-elements'>General Elements</a>
                            </li>
                            <li>
                                <a class='tp-link' href='forms-validation.html'>Validation</a>
                            </li>
                            <li>
                                <a class='tp-link' href='forms-quilljs.html'>Quilljs Editor</a>
                            </li>
                            <li>
                                <a class='tp-link' href='forms-pickers'>Picker</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarTables" data-bs-toggle="collapse">
                        <i data-feather="table"></i>
                        <span> Tables </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTables">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='tables-basic.html'>Basic Tables</a>
                            </li>
                            <li>
                                <a class='tp-link' href='tables-datatables.html'>Data Tables</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarCharts" data-bs-toggle="collapse">
                        <i data-feather="pie-chart"></i>
                        <span> Apex Charts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCharts">
                        <ul class="nav-second-level">
                            <li>
                                <a href='charts-line'>Line</a>
                            </li>
                            <li>
                                <a href='charts-area.html'>Area</a>
                            </li>
                            <li>
                                <a href='charts-column.html'>Column</a>
                            </li>
                            <li>
                                <a href='charts-bar.html'>Bar</a>
                            </li>
                            <li>
                                <a href='charts-mixed.html'>Mixed</a>
                            </li>
                            <li>
                                <a href='charts-timeline.html'>Timeline</a>
                            </li>
                            <li>
                                <a href='charts-rangearea.html'>Range Area</a>
                            </li>
                            <li>
                                <a href='charts-funnel.html'>Funnel</a>
                            </li>
                            <li>
                                <a href='charts-candlestick.html'>Candlestick</a>
                            </li>
                            <li>
                                <a href='charts-boxplot.html'>Boxplot</a>
                            </li>
                            <li>
                                <a href='charts-bubble.html'>Bubble</a>
                            </li>
                            <li>
                                <a href='charts-scatter'>Scatter</a>
                            </li>
                            <li>
                                <a href='charts-heatmap.html'>Heatmap</a>
                            </li>
                            <li>
                                <a href='charts-treemap.html'>Treemap</a>
                            </li>
                            <li>
                                <a href='charts-pie.html'>Pie</a>
                            </li>
                            <li>
                                <a href='charts-radialbar.html'>Radialbar</a>
                            </li>
                            <li>
                                <a href='charts-radar.html'>Radar</a>
                            </li>
                            <li>
                                <a href='charts-polararea.html'>Polar</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarMaps" data-bs-toggle="collapse">
                        <i data-feather="map"></i>
                        <span> Maps </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMaps">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='maps-google.html'>Google Maps</a>
                            </li>
                            <li>
                                <a class='tp-link' href='maps-vector.html'>Vector Maps</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
