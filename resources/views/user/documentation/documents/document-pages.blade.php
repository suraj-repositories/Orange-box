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
                                                    <button class="pm-nav-item" data-view="sponsors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                            height="14" viewBox="0 0 24 24">
                                                            <g fill="none">
                                                                <path fill="currentColor"
                                                                    d="m10.15 8.802l-.442.606zM12 3.106l-.508.552a.75.75 0 0 0 1.015 0zm1.85 5.696l.442.606zM12 9.676v.75zm-1.408-1.48c-.69-.503-1.427-1.115-1.983-1.76c-.574-.665-.859-1.254-.859-1.721h-1.5c0 1.017.578 1.954 1.223 2.701c.663.768 1.501 1.457 2.235 1.992zM7.75 4.715c0-1.059.52-1.663 1.146-1.873c.652-.22 1.624-.078 2.596.816l1.015-1.104C11.23 1.38 9.704.988 8.418 1.42C7.105 1.862 6.25 3.096 6.25 4.715zm6.542 4.693c.734-.534 1.572-1.224 2.235-1.992c.645-.747 1.223-1.684 1.223-2.701h-1.5c0 .467-.284 1.056-.859 1.721c-.556.645-1.292 1.257-1.982 1.76zm3.458-4.693c0-1.619-.855-2.853-2.167-3.295c-1.286-.432-2.813-.04-4.09 1.134l1.015 1.104c.972-.894 1.945-1.036 2.597-.816c.625.21 1.145.814 1.145 1.873zM9.708 9.408c.755.55 1.354 1.018 2.292 1.018v-1.5c-.365 0-.565-.115-1.408-.73zm3.7-1.212c-.843.615-1.043.73-1.408.73v1.5c.938 0 1.537-.467 2.292-1.018z">
                                                                </path>
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-width="1.5"
                                                                    d="M5 20.388h2.26c1.01 0 2.033.106 3.016.308a14.9 14.9 0 0 0 5.33.118c.868-.14 1.72-.355 2.492-.727c.696-.337 1.549-.81 2.122-1.341c.572-.53 1.168-1.397 1.59-2.075c.364-.582.188-1.295-.386-1.728a1.89 1.89 0 0 0-2.22 0l-1.807 1.365c-.7.53-1.465 1.017-2.376 1.162q-.165.026-.345.047m0 0l-.11.012m.11-.012a1 1 0 0 0 .427-.24a1.49 1.49 0 0 0 .126-2.134a1.9 1.9 0 0 0-.45-.367c-2.797-1.669-7.15-.398-9.779 1.467m9.676 1.274a.5.5 0 0 1-.11.012m0 0a9.3 9.3 0 0 1-1.814.004">
                                                                </path>
                                                                <rect width="3" height="8" x="2" y="14"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    rx="1.5"></rect>
                                                            </g>
                                                        </svg>
                                                        Sponsors
                                                        <span class="pm-count" id="pm-count-sponsors">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="partners">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                            height="14" viewBox="0 0 80 80">
                                                            <g fill="none" stroke="currentColor"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="5">
                                                                <path
                                                                    d="M4 27h12.343a4 4 0 0 0 2.829-1.172l3.485-3.485A8 8 0 0 1 28.314 20h4.372a8 8 0 0 1 5.27 1.98a8 8 0 0 0-.28.257l-6.806 6.555a5.954 5.954 0 1 0 8.34 8.498L41.5 35l15.964 12.417a2.653 2.653 0 0 1 .51 3.663l-1.608 2.194A7.9 7.9 0 0 1 50 56.5l-1.113 1.113a6.44 6.44 0 0 1-8.678.394L39 57l-.702.702a7.846 7.846 0 0 1-11.096 0l-7.53-7.53A4 4 0 0 0 16.843 49H4z">
                                                                </path>
                                                                <path
                                                                    d="M46 30.5L41.5 35m0 0l-2.29 2.29a5.954 5.954 0 1 1-8.34-8.498l6.807-6.555A8 8 0 0 1 43.226 20h8.46a8 8 0 0 1 5.657 2.343l3.485 3.485A4 4 0 0 0 63.658 27H76v22H59.5zM12 27.059v22m56-22v22">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                        Partners
                                                        <span class="pm-count" id="pm-count-partners">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="guide">
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
                                                        Community Guide
                                                        <span class="pm-count" id="pm-count-guide">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="faq">
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
                                                        FAQ's
                                                        <span class="pm-count" id="pm-count-faq">0</span>
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
                                                                <option value="sponsors">Sponsors</option>
                                                                <option value="partners">Partners</option>
                                                                <option value="guide">Guide</option>
                                                                <option value="faq">FAQ's</option>
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
                                                        <textarea class="pm-input pm-textarea" id="pm-page-description" placeholder="Add a short description excerpt…"></textarea>
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
