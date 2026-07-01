<div id="reloadActionArea" data-load-in-progress="{{ $release->sync_status == 'syncing' ? 'true' : 'false' }}"
    class="d-flex gap-2 flex-wrap align-items-center reloadActionArea {{ $release->sync_status == 'syncing' ? 'loading-doc' : '' }}"
    data-progress-url="{{ authRoute('user.documentation.sync.pages.progress', ['documentation' => $documentation, 'release' => $release]) }}">

    <div class="load-progress d-none">

        <div class="text-muted" id="syncCount">
            0 / 0
        </div>

        <div class="sync-progress-circle">
            <svg width="50" height="50" viewBox="0 0 100 100">
                <!-- Background -->
                <circle class="progress-bg" cx="50" cy="50" r="42" />

                <!-- Progress -->
                <circle class="progress-bar" cx="50" cy="50" r="42" />
            </svg>

            <div class="progress-percent" id="syncPercent">0%</div>
        </div>

    </div>


    <button class="btn btn-primary rounded-3 refresh-pages-btn" data-bs-toggle="modal"
        data-bs-target="#refreshPagesModal">
        Refresh Pages
    </button>

    <div class="modal fade" id="refreshPagesModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
        tabindex="-1" aria-labelledby="refreshPagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="refreshPagesModalLabel">
                        Refresh Pages
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <h5 class="mb-3">Important Points - </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start mb-1">
                            <i class="bi bi-arrow-repeat text-primary me-2 mt-1"></i>
                            <span>Updates the content of existing documentation pages from the connected
                                repository.</span>
                        </li>

                        <li class="d-flex align-items-start mb-1">
                            <i class="bi bi-file-earmark-check text-success me-2 mt-1"></i>
                            <span>Only pages that already exist will be refreshed.</span>
                        </li>

                        <li class="d-flex align-items-start mb-1">
                            <i class="bi bi-folder-plus text-secondary me-2 mt-1"></i>
                            <span>No new pages or folders will be created during this process.</span>
                        </li>

                        <li class="d-flex align-items-start mb-1">
                            <i class="bi bi-git text-info me-2 mt-1"></i>
                            <span>Pages that are not connected to a Git repository will remain unchanged.</span>
                        </li>

                        <li class="d-flex align-items-start mb-1">
                            <i class="bi bi-exclamation-triangle text-warning me-2 mt-1"></i>
                            <span>Local edits to existing page content will be replaced with the latest repository
                                version.</span>
                        </li>

                        <li class="d-flex align-items-start">
                            <i class="bi bi-x-octagon text-danger me-2 mt-1"></i>
                            <span>This action cannot be undone.</span>
                        </li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" id="refreshPagesButton" class="btn btn-primary"
                        data-submit-url="{{ authRoute('user.documentation.sync.pages', ['documentation' => $documentation, 'release' => $release]) }}"
                        data-progress-url="{{ authRoute('user.documentation.sync.pages.progress', ['documentation' => $documentation, 'release' => $release]) }}"
                        data-loading-text="Loading...">
                        Refresh Pages
                    </button>
                </div>

            </div>
        </div>
    </div>

    <button class="btn btn-primary rounded-3 load-entire-doc-btn" data-bs-toggle="modal"
        data-bs-target="#loadDocsModal">
        Load Entire Docs
    </button>

    <div class="modal fade" id="loadDocsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="loadDocsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="refreshEntireDocsForm"
                    data-progress-url="{{ authRoute('user.documentation.sync.pages.progress', ['documentation' => $documentation, 'release' => $release]) }}"
                    action="{{ authRoute('user.documentation.import.github', ['documentation' => $documentation, 'release' => $release]) }}"
                    method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="loadDocsModalLabel">
                            Load Documentation
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="docsUrl" class="form-label">
                                Documentation URL
                            </label>

                            <input type="url" class="form-control" id="docsUrl" name="github_url"
                                value="{{ $release?->load_url }}" placeholder="https://example.com/docs" required>

                            <div class="form-text">
                                Enter the root URL of your documentation repository or documentation folder.
                            </div>
                        </div>

                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start mb-2">
                                <i class="bi bi-folder2-open text-primary me-2 mt-1"></i>
                                <span>
                                    Imports the complete documentation structure, including folders and Markdown
                                    (<code>.md</code>) pages.
                                </span>
                            </li>

                            <li class="d-flex align-items-start mb-2">
                                <i class="bi bi-arrow-repeat text-success me-2 mt-1"></i>
                                <span>
                                    Synchronizes your documentation by creating new pages, updating existing ones, and
                                    removing pages that no longer exist in the source.
                                </span>
                            </li>

                            <li class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2 mt-1"></i>
                                <span>
                                    Existing documentation may be modified during synchronization. This action cannot be
                                    undone.
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="Loading...">
                            Load Docs
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
