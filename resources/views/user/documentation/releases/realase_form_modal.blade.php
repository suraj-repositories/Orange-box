<div class="modal fade" id="realease-form-modal{{ !empty($release) ? ('-' . $release->id) : '' }}" tabindex="-1"
    aria-labelledby="realease-form-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="realease-form"
                action="{{ empty($release)
                    ? authRoute('user.documentation.release.save', ['documentation' => $documentation])
                    : authRoute('user.documentation.release.update', ['documentation' => $documentation, 'release' => $release]) }}"
                method="post" data-submit-type="ajax">
                @csrf
                @if (!empty($release))
                    @method('PATCH')
                @endif
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="realease-form-title">
                        {{ empty($release) ? 'Create New' : 'Update' }} Version Release</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col col-12 mb-2">
                            <label for="version-title-input" class="form-label">Version Group Title</label>

                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="bi bi-alphabet-uppercase"></i> </span>
                                <input type="text" class="form-control" placeholder="Version 10 Releases, Version 15"
                                    id="version-title-input" name="title"
                                    value="{{ empty($release) ? '' : $release->title }}">
                            </div>


                        </div>
                        <div class="col col-12 mb-3">
                            <label for="version-input" class="form-label">Version tag</label>

                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="bi bi-cpu"></i> </span>
                                <input type="text" class="form-control" placeholder="v1.0.0, v1, v2.0, v2.0-SNAPSHOT"
                                    id="version-input" name="version"
                                    value="{{ empty($release) ? '' : $release->version }}">
                            </div>

                            <p class="mt-2 text-muted">
                                @if (empty($release))
                                    <small>
                                        A new version will be created from your current documentation structure.<br>
                                        Both versions will remain separate, allowing you to update them independently.
                                    </small>
                                @else
                                    <small>
                                        Updated version tag will directly reflect to the documentation site.
                                    </small>
                                @endif
                            </p>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"
                        id="save-btn" disabled>{{ empty($release) ? 'Create' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
