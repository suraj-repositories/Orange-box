<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="rounded-2 me-2 widget-icons-sections">
                <svg xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 24 24">
                    <g fill="none">
                        <path fill="currentColor"
                            d="m10.15 8.802l-.442.606zM12 3.106l-.508.552a.75.75 0 0 0 1.015 0zm1.85 5.696l.442.606zM12 9.676v.75zm-1.408-1.48c-.69-.503-1.427-1.115-1.983-1.76c-.574-.665-.859-1.254-.859-1.721h-1.5c0 1.017.578 1.954 1.223 2.701c.663.768 1.501 1.457 2.235 1.992zM7.75 4.715c0-1.059.52-1.663 1.146-1.873c.652-.22 1.624-.078 2.596.816l1.015-1.104C11.23 1.38 9.704.988 8.418 1.42C7.105 1.862 6.25 3.096 6.25 4.715zm6.542 4.693c.734-.534 1.572-1.224 2.235-1.992c.645-.747 1.223-1.684 1.223-2.701h-1.5c0 .467-.284 1.056-.859 1.721c-.556.645-1.292 1.257-1.982 1.76zm3.458-4.693c0-1.619-.855-2.853-2.167-3.295c-1.286-.432-2.813-.04-4.09 1.134l1.015 1.104c.972-.894 1.945-1.036 2.597-.816c.625.21 1.145.814 1.145 1.873zM9.708 9.408c.755.55 1.354 1.018 2.292 1.018v-1.5c-.365 0-.565-.115-1.408-.73zm3.7-1.212c-.843.615-1.043.73-1.408.73v1.5c.938 0 1.537-.467 2.292-1.018z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                            d="M5 20.388h2.26c1.01 0 2.033.106 3.016.308a14.9 14.9 0 0 0 5.33.118c.868-.14 1.72-.355 2.492-.727c.696-.337 1.549-.81 2.122-1.341c.572-.53 1.168-1.397 1.59-2.075c.364-.582.188-1.295-.386-1.728a1.89 1.89 0 0 0-2.22 0l-1.807 1.365c-.7.53-1.465 1.017-2.376 1.162q-.165.026-.345.047m0 0l-.11.012m.11-.012a1 1 0 0 0 .427-.24a1.49 1.49 0 0 0 .126-2.134a1.9 1.9 0 0 0-.45-.367c-2.797-1.669-7.15-.398-9.779 1.467m9.676 1.274a.5.5 0 0 1-.11.012m0 0a9.3 9.3 0 0 1-1.814.004" />
                        <rect width="3" height="8" x="2" y="14" stroke="currentColor" stroke-width="1.5"
                            rx="1.5" />
                    </g>
                </svg>
            </div>
            <h5 class="card-title mb-0"> Sponsers </h5>

            <div class="ms-auto fw-semibold d-flex gap-1">

                <a href="javascript:void(0)" id="create-sponsor-button"
                    class="btn btn-light btn-sm border center-content gap-1">
                    <i class="bx bx-plus fs-5"></i>
                    <div>Add Sponser</div>
                </a>

                <div class="modal fade sponsor-creation" id="sponsor-form-modal" tabindex="-1"
                    aria-labelledby="sponsor-creation-form-title" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <form id="sponsor-creation-form" data-submit-type="ajax"
                            action="{{ authRoute('user.documentation.sponsors.save', ['documentation' => $documentation]) }}"
                            method="post">
                            @csrf
                            <div class="modal-content">
                                <input type="hidden" name="sponsor_id" id="sponsor-id">

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="sponsor-creation-form-title">
                                        Create
                                        Sponsor</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" style="max-height: 70vh;">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="row g-3">
                                                <!-- name -->
                                                <div class="col-md-12">
                                                    <label for="name-input" class="form-label">Sponsor
                                                        Name</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class='bx bx-user-pin fs-5'></i>
                                                        </span>
                                                        <input type="text" class="form-control"
                                                            id="sponsor-name-input" name="name"
                                                            placeholder="Sponsor name">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Sponsor
                                                        Website</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bx bx-link-alt"></i>
                                                        </span>
                                                        <input type="url" class="form-control" name="website_url"
                                                            placeholder="https://example.com">
                                                    </div>
                                                </div>

                                                <!-- Tier -->
                                                <div class="col-md-12">
                                                    <label class="form-label">Sponsor Tier</label>
                                                    <select class="form-select" name="tier">
                                                        <option value="">Select tier</option>
                                                        <option value="platinum">Platinum</option>
                                                        <option value="gold">Gold</option>
                                                        <option value="silver">Silver</option>
                                                        <option value="bronze">Bronze</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row g-3">
                                                <div class="col-12">

                                                    <div class="col-12">
                                                        <label for="logo_light_input" class="form-label">Sponser
                                                            Logo's</label>
                                                        <div class="doc-logo-picker mt-1">

                                                            <label>
                                                                <span>Logo (Light)</span>
                                                                <input type="file" name="logo_light" accept="image/*"
                                                                    id="logo_light_input">
                                                            </label>

                                                            <label class="dark-logo">

                                                                <span>Logo (Dark)</span>
                                                                <input type="file" name="logo_dark" accept="image/*">
                                                            </label>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <!-- Description -->
                                            <div class="col-12">
                                                <label for="description-editor" class="form-label">Description</label>
                                                <textarea class="form-control ckeditor ckeditor-minimal" id="description-editor" name="description" rows="2"
                                                    placeholder="Add a description..."></textarea>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-traffic mb-0">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Logo</th>
                        <th>Name</th>
                        {{-- <th>Description</th> --}}
                        <th>Tier</th>
                        <th width="140">Created At</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($sponsors as $sponsor)
                        <tr>
                            <th> {{ $sponsors->firstItem() + $loop->iteration - 1 }}</th>
                            <td>
                                @if (!empty($sponsor->website_url))
                                    <a href="{{ $sponsor->website_url }}" target="_blank">
                                @endif

                                @if (!empty($sponsor->logo_light))
                                    <img src="{{ Storage::url($sponsor->logo_light) }}"
                                        class="avatar avatar-sm img-fluid rounded-2 me-1 avatar-xl-logo"
                                        aria-label="{{ $sponsor->title }}">
                                @elseif (!empty($sponsor->logo_sm_light))
                                    <img src="{{ Storage::url($sponsor->logo_sm_light) }}"
                                        class="avatar avatar-sm img-fluid rounded-2 me-1"
                                        aria-label="{{ $sponsor->title }}">
                                @endif

                                @if (!empty($sponsor->website_url))
                                    </a>
                                @endif
                            </td>

                            <td>
                                @if (!empty($sponsor->website_url))
                                    <a href="{{ $sponsor->website_url }}" target="_blank">
                                        {{ $sponsor->name }}
                                    </a>
                                @else
                                    {{ $sponsor->name }}
                                @endif
                            </td>
                            <td>
                                {{ $sponsor->tier ?? '-' }}
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $sponsor->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input sponsorStatusToggleSwitch" type="checkbox"
                                        role="switch" data-documentation-sponsor-uuid="{{ $sponsor->uuid }}"
                                        {{ $sponsor->status == 'active' ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="action-container m-0 gap-1">

                                    <a class="edit edit-sponsor" data-id="{{ $sponsor->id }}"
                                        data-name="{{ $sponsor->name }}"
                                        data-website-url="{{ $sponsor->website_url }}"
                                        data-tier="{{ $sponsor->tier }}"
                                        data-description="{{ $sponsor->description }}"
                                        data-logo-light="{{ Storage::url($sponsor->logo_light) }}"
                                        data-logo-dark="{{ Storage::url($sponsor->logo_dark) }}">
                                        <i class='bx bx-edit fs-5'></i>
                                    </a>

                                    <form
                                        action="{{ authRoute('user.documentation.sponsor.delete', ['sponsor' => $sponsor]) }}"
                                        method="POST" data-confirm-delete>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" disabled
                                            class="delete btn-no-style delete-password-locker-button">
                                            <i class='bx bx-trash fs-5'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-no-data />
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

            @if ($sponsors->lastPage() > 1)
                <div class="m-3 mb-0">
                    {{ $sponsors->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
