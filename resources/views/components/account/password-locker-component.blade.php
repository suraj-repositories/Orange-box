<div class="row">
    <div class="col-md-12">
        <div class="card overflow-hidden">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="rounded-2 me-2 widget-icons-sections">
                        <i class='bx bx-lock fs-4'></i>
                    </div>
                    <h5 class="card-title mb-0"> Password Locker </h5>

                    <div class="ms-auto fw-semibold d-flex gap-1">

                        <a href="javascript:void(0)" id="create-password-locker"
                            class="btn btn-light btn-sm border center-content gap-1">
                            <i class="bx bx-plus fs-5"></i>
                            <div>New</div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-traffic mb-0">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>URL</th>
                                <th>Expiry</th>
                                <th>Last Use</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($passwords as $password)
                                <tr>
                                    <th> {{ $passwords->firstItem() + $loop->iteration - 1 }}</th>
                                    <td>{{ $password->username }}</td>
                                    <td>
                                        *******
                                        <a href="javascript:void(0)" class="reveal-password-btn"
                                            data-password-locker-id="{{ $password->id }}">
                                            <i class='bx bx-show-alt fs-5 text-dark'></i>
                                        </a>
                                    </td>
                                    <td><a href="{{ $password->url }}" target="_blank">{{ $password->domain }}</a></td>
                                    <td>
                                        <small title="{{ $password->expires_at?->format('F d, Y h:i a') ?? '-' }}">
                                            {{ $password->expires_at?->diffForHumans() ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small title="{{ $password->last_used_at?->format('F d, Y h:i a') ?? '-' }}">
                                            {{ $password->last_used_at?->diffForHumans() ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="action-container m-0 gap-1">
                                            <a href="javascript:void(0)" class="info ms-0 show-locker-info"
                                                data-ob-created-at="{{ $password->created_at?->diffForHumans() ?? '-' }}"
                                                data-ob-created-at-title="{{ $password->created_at?->format('F d, Y h:i a') ?? '-' }}"
                                                data-ob-updated-at="{{ $password->updated_at?->diffForHumans() ?? '-' }}"
                                                data-ob-updated-at-title="{{ $password->updated_at?->format('F d, Y h:i a') ?? '-' }}"
                                                data-ob-notes-content="{!! Str::markdown($password->notes ?? '') !!}">
                                                <i class='bx bx-info-circle fs-5'></i>
                                            </a>

                                            <a href="javascript:void(0)" class="edit edit-password-locker-btn"
                                                data-ob-username="{{ $password->username }}"
                                                data-ob-url="{{ $password->url }}"
                                                data-ob-expires-at="{{ $password->expires_at?->format('Y-m-d') }}"
                                                data-ob-notes="{!! Str::markdown($password->notes ?? '') !!}"
                                                data-ob-submit-url="{{ authRoute('user.password_locker.update', ['passwordLocker' => $password]) }}">
                                                <i class='bx bx-edit fs-5'></i>
                                            </a>


                                            <button
                                                data-ob-password-locker-delete-url="{{ authRoute('user.password_locker.delete', ['passwordLocker' => $password]) }}"
                                                class="delete btn-no-style delete-password-locker-button">
                                                <i class='bx bx-trash fs-5'></i>
                                            </button>


                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="text-center">No Data...</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                    @if ($passwords->lastPage() > 1)
                        <div class="m-3 mb-0">
                            {{ $passwords->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <div class="modal fade password-locker" id="password-locker-form-modal" tabindex="-1"
            aria-labelledby="password-locker-form-title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="password-locker-form" action="#" method="post">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="password-locker-form-title">Create Password Lock</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Username -->
                                <div class="col-md-6">
                                    <label for="username-input" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bx-user-circle fs-5'></i></span>
                                        <input type="text" class="form-control" id="username-input" name="username"
                                            placeholder="Username">
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="create-password-input" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bx-key fs-5'></i></span>
                                        <input type="password" class="form-control pe-4 rounded-end"
                                            name="password" placeholder="Password" id="create-password-input">
                                        <button type="button" class="show-password-btn btn-no-style text-danger"
                                            id="toggle-password">
                                            <i class="bi bi-eye fs-5"></i>
                                    </button>
                                    </div>
                                </div>


                                <!-- URL -->
                                <div class="col-md-6">
                                    <label for="url-input" class="form-label">URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bx-link fs-5'></i></span>
                                        <input type="url" class="form-control" id="url-input" name="url"
                                            placeholder="https://example.com">
                                    </div>
                                </div>

                                <!-- Expiry -->
                                <div class="col-md-6">
                                    <label for="expiry-input" class="form-label">Expiry Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class='bx bx-calendar-x fs-5'></i></span>
                                        <input type="date" class="form-control" id="expiry-input"
                                            min="{{ date('Y-m-d') }}" name="expires_at">
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="col-12">
                                    <label for="editor" class="form-label">Note</label>
                                    <textarea class="form-control ckeditor ckeditor-minimal" id="locker-editor" name="notes" rows="2"
                                        placeholder="Add a note"></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade password-locker-info" id="password-locker-info-modal" tabindex="-1"
            aria-labelledby="password-locker-info-title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">


                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="password-locker-info-title">Locker Info</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="d-flex justify-content-between flex-wrap gap-1">
                            <span>
                                <strong>Created Date:</strong>
                                <span id="created_at"></span>
                            </span>
                            <span>
                                <strong>Last Updated:</strong>
                                <span id="updated_at"></span>
                            </span>
                        </div>

                        <div class="rich-editor-content mt-3" id="notes-content">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>

                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade password-locker-show" id="password-locker-show-modal" tabindex="-1"
            aria-labelledby="password-locker-show-title" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">


                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="password-locker-show-title">Open Locker</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form class="d-none" action="#" id="no-submit-form">
                            <div class="d-flex align-items-center justify-content-center password-options gap-2">
                                <div class="password-options  justify-content-center d-flex gap-3">
                                    <input type="radio" name="keytype" id="pemFile" class="d-none">
                                    <label class="option-circle" for="pemFile">
                                        <i class='bx bx-file'></i>
                                        <span>.pem</span>
                                    </label>

                                    <input type="radio" name="keytype" id="key" class="d-none">
                                    <label class="option-circle" for="key">
                                        <i class='bx bx-key'></i>
                                        <span>Key</span>
                                    </label>

                                    <input type="radio" name="keytype" id="email" class="d-none">
                                    <label class="option-circle" for="email">
                                        <i class='bx bx-envelope'></i>
                                        <span>Email</span>
                                    </label>

                                    <input type="radio" name="keytype" id="app" class="d-none">
                                    <label class="option-circle" for="app">
                                        <i class='bx bxl-android'></i>
                                        <span>App</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Input groups -->
                            <div class="my-3 input-group-wrapper" id="pemFileInput">
                                <div class="input-group">
                                    <span class="input-group-text fw-bold fs-5 py-1">
                                        <i class='bx bx-scan'></i>
                                    </span>
                                    <input type="file" class="form-control" id="pem-file-input" name="pem"
                                        accept=".pem">
                                </div>
                            </div>

                            <div class="my-3 input-group-wrapper" id="keyInput">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-lock fs-5"></i></span>
                                    <input type="password" class="form-control rounded-end pe-35px"
                                          name="key" placeholder="Master Key" id="master-password-input">
                                    <button type="button" class="show-password-btn btn-no-style text-dark me-1"
                                        id="toggle-master-password">
                                        <i class="bi bi-eye fs-5"></i>
                                </button>
                                </div>

                                <small class="text-muted">For better security, always change your master key after every use.</small>
                            </div>

                            <div class="my-3 input-group-wrapper" id="emailInput">
                                <div class="row align-items-center g-2">
                                    <div class="col-12 col-lg-9">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold fs-5 py-1"> @ </span>
                                            <input type="text" class="form-control" id="otp-input" name="otp"
                                                placeholder="OTP">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <small class="text-muted resend-message d-none"></small>
                                       <button type="button" class="btn w-100 btn-primary " id="send-email-otp-btn">Send Otp</button>
                                    </div>
                                </div>
                            </div>

                            <div class="my-3 input-group-wrapper" id="appInput">
                                <div class="input-group">
                                    <span class="input-group-text fw-bold fs-5 py-1"><i
                                            class='bx bx-dialpad fs-6'></i>
                                    </span>
                                    <input type="number" class="form-control hide-btns" id="code-input"
                                        name="code" placeholder="_ _ _  _ _ _">
                                </div>
                            </div>
                        </form>
                        <div class="d-block" id="reveal-area" class="d-none">
                            <div class="d-flex align-items-center flex-column gap-3">
                                <div class="circular-progress">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="40"></circle>
                                        <circle cx="50" cy="50" r="40" id="pct-ind"></circle>
                                    </svg>
                                    <p class="pct">15s</p>
                                </div>

                                <div id="reveal-password-txt"
                                    class="p-2 fs-6 px-3 border rounded text-wrap badge badge-orange border-primary border-dashed">
                                    *******
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="unlock-btn">Unlock</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
