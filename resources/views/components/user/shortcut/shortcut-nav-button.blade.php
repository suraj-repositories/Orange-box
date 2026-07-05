<div class="dropdown topbar-dropdown">
    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
        aria-expanded="false">
        <i data-feather="grid" class="noti-icon"></i>

    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
        <div class="noti-scroll p-3 pt-4" data-simplebar style="max-height: 70dvh;">

            <div class="row row-cols-3 g-2 shortcut-icons">

                <div class="col">
                    @if (request()->routeIs('user.dashboard'))
                        <a href="{{ authRoute('user.dashboard.analytics') }}"
                            class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                            <div class="icon-box theme-1">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div class="title mb-2 mt-1">Analytics</div>
                        </a>
                    @else
                        <a href="{{ authRoute('user.dashboard') }}"
                            class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                            <div class="icon-box theme-1">
                                <i class="bi bi-house"></i>
                            </div>
                            <div class="title mb-2 mt-1">Dashboard</div>
                        </a>
                    @endif

                </div>

                <div class="col">
                    <a href="{{ authRoute('user.daily-digest.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-2">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="title mb-2 mt-1">Create Digestion</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.think-pad.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-3">
                            <i class="bi bi-clipboard2"></i>
                        </div>
                        <div class="title mb-2 mt-1">Think Pad</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.syntax-store.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-4">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <div class="title mb-2 mt-1">Add Syntax</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.documentation.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-5">
                            <i class="bi bi-file-richtext"></i>
                        </div>
                        <div class="title mb-2 mt-1">New Doc</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.folder-factory.files.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box theme-6">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div class="title mb-2 mt-1">Upload Files</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.project-board.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-7">
                            <i class="bi bi-kanban"></i>
                        </div>
                        <div class="title mb-2 mt-1">Project Board</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.modules.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-8">
                            <i class="bi bi-box"></i>
                        </div>
                        <div class="title mb-2 mt-1">Project Module</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.tasks.create') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box plus-create theme-9">
                            <i class="bi bi-check2-square"></i>
                        </div>
                        <div class="title mb-2 mt-1">Project Tasks</div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ authRoute('user.settings.index') }}"
                        class="shortcut-icon d-flex flex-column justify-content-center align-items-center gap-1">
                        <div class="icon-box theme-10">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div class="title mb-2 mt-1">Settings</div>
                    </a>
                </div>

            </div>

        </div>
    </div>
