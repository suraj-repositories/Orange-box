@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.documentation.index') }}">Documentations</a></li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.show.latest', ['documentation' => $documentation]) }}">{{ $documentation->title }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card overflow-hidden">
                            <div class="card-body p-0">

                                <script>
                                    window.pmReleasesData = @json($formattedVersion);
                                    window.pmApiBase =
                                        "{{ authRoute('user.documentation.document.pages.list', ['documentation' => $documentation]) }}"
                                        .replace('/list', '');
                                    window.pmCsrfToken = "{{ csrf_token() }}";
                                </script>

                                <div class="pm-root" id="policyManager">

                                    <div class="pm-shell">

                                        <aside class="pm-sidebar">
                                            <div class="pm-sidebar-inner">
                                                <div class="pm-brand">
                                                    <span class="pm-brand-icon">
                                                        <svg width="16" height="16" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M8 2L3 4v4c0 3 2.5 5 5 6 2.5-1 5-3 5-6V4L8 2z" />
                                                        </svg>
                                                    </span>
                                                    Other Pages
                                                </div>

                                                <div class="pm-sidebar-section">
                                                    <p class="pm-sidebar-label">Pages</p>
                                                    <button class="pm-nav-item active" data-view="all">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <rect x="2" y="2" width="12" height="12"
                                                                rx="1.5" />
                                                            <path d="M5 5h6M5 8h6M5 11h4" />
                                                        </svg>
                                                        All Pages
                                                        <span class="pm-count" id="pm-count-all">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <path d="M8 2L3 4v4c0 3 2.5 5 5 6 2.5-1 5-3 5-6V4L8 2z" />
                                                        </svg>
                                                        Privacy
                                                        <span class="pm-count" id="pm-count-privacy">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="terms">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <rect x="3" y="1" width="10" height="14"
                                                                rx="1" />
                                                            <path d="M5.5 5h5M5.5 8h5M5.5 11h3" />
                                                        </svg>
                                                        Terms
                                                        <span class="pm-count" id="pm-count-terms">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="conduct">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <circle cx="8" cy="5" r="2.5" />
                                                            <path d="M3 13c0-2.76 2.24-5 5-5s5 2.24 5 5" />
                                                        </svg>
                                                        Code of Conduct
                                                        <span class="pm-count" id="pm-count-conduct">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="cookie">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <circle cx="8" cy="8" r="6" />
                                                            <circle cx="6" cy="7" r="1"
                                                                fill="currentColor" />
                                                            <circle cx="10" cy="6" r="1"
                                                                fill="currentColor" />
                                                            <circle cx="9" cy="10" r="1"
                                                                fill="currentColor" />
                                                        </svg>
                                                        Cookie Policy
                                                        <span class="pm-count" id="pm-count-cookie">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="custom">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <path d="M8 3v10M3 8h10" />
                                                        </svg>
                                                        Custom
                                                        <span class="pm-count" id="pm-count-custom">0</span>
                                                    </button>
                                                </div>

                                                <div class="pm-sidebar-section">
                                                    <p class="pm-sidebar-label">Releases</p>
                                                    @foreach ($releases as $release)
                                                        <div
                                                            class="pm-release-pill {{ $release->is_current ? 'pm-release-current' : '' }}">
                                                            <span
                                                                class="pm-release-dot {{ $release->is_published ? 'published' : 'unpublished' }}"></span>
                                                            <span class="pm-release-name">{{ $release->version }}</span>
                                                            @if ($release->is_current)
                                                                <span class="pm-release-badge">current</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="pm-sidebar-footer">
                                                <button class="btn pm-btn-primary w-100" onclick="pmOpenModal()">
                                                    + New Page
                                                </button>
                                            </div>
                                        </aside>

                                        <!-- MAIN CONTENT -->
                                        <main class="pm-main">
                                            <div id="pm-view-pages">
                                                <div class="pm-page-header">
                                                    <div>
                                                        <h2 class="pm-page-title" id="pm-view-title">All Pages</h2>
                                                        <p class="pm-page-subtitle" id="pm-view-subtitle">Manage all
                                                            policy and legal pages across versions</p>
                                                    </div>
                                                    <button class="btn pm-btn-primary" onclick="pmOpenModal()">
                                                        <svg width="13" height="13" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <path d="M8 3v10M3 8h10" />
                                                        </svg>
                                                        New Page
                                                    </button>
                                                </div>

                                                <!-- STATS -->
                                                <div class="row g-3 mb-4">
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val" id="pm-stat-total">0</div>
                                                            <div class="pm-stat-label">Total Pages</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val pm-val-live" id="pm-stat-live">0</div>
                                                            <div class="pm-stat-label">Live</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val pm-val-draft" id="pm-stat-draft">0
                                                            </div>
                                                            <div class="pm-stat-label">Draft</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val pm-val-off" id="pm-stat-off">0</div>
                                                            <div class="pm-stat-label">Off</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- CONTROLS -->
                                                <div class="pm-controls">
                                                    <input class="pm-search" type="text" placeholder="Search pages…"
                                                        id="pm-search">
                                                    <div class="pm-filter-group">
                                                        <button class="pm-filter-btn" id="pmf-all"
                                                            onclick="pmSetFilter('all')">All</button>
                                                        <button class="pm-filter-btn active" id="pmf-live"
                                                            onclick="pmSetFilter('live')">Live</button>
                                                        <button class="pm-filter-btn" id="pmf-draft"
                                                            onclick="pmSetFilter('draft')">Draft</button>
                                                        <button class="pm-filter-btn" id="pmf-off"
                                                            onclick="pmSetFilter('off')">Off</button>
                                                    </div>
                                                    <select class="pm-select" id="pm-ver-filter"
                                                        onchange="pmSetVersionFilter(this.value)">
                                                        <option value="">All Releases</option>
                                                        @foreach ($releases as $release)
                                                            <option value="{{ $release->id }}">
                                                                {{ $release->version }} — {{ $release->title }}
                                                                {{ $release->is_current ? '(current)' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- LIST -->
                                                <div id="pm-pages-list"></div>
                                            </div>
                                        </main>
                                    </div>

                                    <!-- TOAST -->
                                    <div class="pm-toast" id="pm-toast"></div>

                                    <div class="modal fade" id="pmPageModal" tabindex="-1"
                                        aria-labelledby="pm-page-modal-title" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">

                                                <div class="modal-header pm-modal-header">
                                                    <h5 class="modal-title pm-modal-title" id="pm-page-modal-title">Create
                                                        New Page</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body pm-modal-body">
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="pm-label">Page Name</label>
                                                            <input type="text" class="pm-input" id="pm-page-name"
                                                                placeholder="e.g. Privacy Policy">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="pm-label">Page Type</label>
                                                            <select class="pm-input" id="pm-page-type">
                                                                <option value="privacy">Privacy Policy</option>
                                                                <option value="terms">Terms of Service</option>
                                                                <option value="code_of_conduct">Code of Conduct</option>
                                                                <option value="cookie">Cookie Policy</option>
                                                                <option value="guide">Guide</option>
                                                                <option value="custom">Custom</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="pm-label">URL Slug</label>
                                                        <input type="text" class="pm-input" id="pm-page-slug"
                                                            placeholder="e.g. /privacy-policy">
                                                        <p class="pm-hint">The URL path where this page will be accessible
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="pm-label">Release Scope</label>
                                                        <div class="row g-2 mt-1">
                                                            <div class="col-6">
                                                                <div class="pm-scope-card selected" id="pm-scope-all"
                                                                    onclick="pmSelectScope('all')">
                                                                    <div class="pm-scope-title">All Releases</div>
                                                                    <div class="pm-scope-desc">Show this page for every
                                                                        release</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="pm-scope-card" id="pm-scope-specific"
                                                                    onclick="pmSelectScope('specific')">
                                                                    <div class="pm-scope-title">Specific Release</div>
                                                                    <div class="pm-scope-desc">Show only for one selected
                                                                        release</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 pm-hidden" id="pm-version-picker">
                                                        <label class="pm-label">Select Release</label>
                                                        <div class="pm-ver-checks" id="pm-ver-checkboxes"></div>
                                                        <p class="pm-hint">Page will only appear for the selected release
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="pm-label">Status</label>
                                                        <select class="pm-input" id="pm-page-status">
                                                            <option value="draft">Draft — not visible</option>
                                                            <option value="live">Live — publicly visible</option>
                                                            <option value="off">Off — disabled</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="pm-label">Notes</label>
                                                        <textarea class="pm-input pm-textarea" id="pm-page-description"
                                                            placeholder="Add a short description excerpt…"></textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer pm-modal-footer">
                                                    <button type="button" class="btn pm-btn-outline"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn pm-btn-primary" id="pm-save-btn"
                                                        onclick="pmSavePage()">Save
                                                        Page</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- END POLICY MANAGER COMPONENT --}}

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
    <script src="{{ asset('assets/js/pages/document-pages.js') }}"></script>
@endsection
