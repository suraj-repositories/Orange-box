@if (Setting::get('enable_change_password', false))
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
            <div>
                <h2 class="fs-7 m-0">Change Password
                </h2>
                <p class="fs-8 m-0 text-muted">
                </p>
            </div>
        </div>

        <div class="buttonArea px-2 ps-3 border-start ms-auto">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-2" data-bs-toggle="modal"
                data-bs-target="#change-password-modal">
                Change
            </button>

            <div class="modal fade change-password-modal" id="change-password-modal" tabindex="-1"
                aria-labelledby="change-password-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="change-password-modal-title">Change Password</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ authRoute('ajax.settings.security.change.password') }}"
                            id="changePasswordForm">

                            <div class="modal-body">
                                <div class="mb-3 input-group-wrapper" id="emailInput">
                                    <div class="row align-items-center g-2">
                                        <div class="col-12">
                                            <label for="username-input" class="form-label">Current Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-lock fs-5"></i></span>
                                                <input type="password" class="form-control rounded-end pe-35px"
                                                    name="current_password" placeholder="current password">
                                                <button type="button"
                                                    class="show-password-btn btn-no-style text-dark me-1">
                                                    <i class="bi bi-eye fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="username-input" class="form-label mt-2">New Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-lock fs-5"></i></span>
                                                <input type="password" class="form-control rounded-end pe-35px"
                                                    name="password" placeholder="new password">
                                                <button type="button"
                                                    class="show-password-btn btn-no-style text-dark me-1">
                                                    <i class="bi bi-eye fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="username-input" class="form-label mt-2">Confirm Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-lock fs-5"></i></span>
                                                <input type="password" class="form-control rounded-end pe-35px"
                                                    name="password_confirmation" placeholder="confirm password">
                                                <button type="button"
                                                    class="show-password-btn btn-no-style text-dark me-1">
                                                    <i class="bi bi-eye fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="confirm-change-btn">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (Setting::get('lock_screen_enabled'))
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
            <div>
                <h2 class="fs-7 m-0">Lock Screen Key
                    @if (($userSettings['lock_screen_enabled'] ?? '0') == '1')
                        <small class="badge badge-green">active</small>
                    @else
                        <small class="badge badge-red">inactive</small>
                    @endif
                </h2>
                <p class="fs-8 m-0 text-muted">
                </p>
            </div>


        </div>

        <div class="buttonArea px-2 ps-3 border-start ms-auto">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal"
                data-bs-target="#lock-screen-modal">
                @if (Setting::get('lock_screen_password_set') == true)
                    Edit
                @else
                    Add
                @endif
            </button>

            <div class="modal fade lock-screen-modal" id="lock-screen-modal" tabindex="-1"
                aria-labelledby="lock-screen-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="lock-screen-modal-title">
                                @if (Setting::get('lock_screen_password_set') == true)
                                    Update Lock Screen Credentials
                                @else
                                    Enable Lock Screen
                                @endif
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ authRoute('ajax.settings.security.update.lock.screen.pin') }}"
                            id="updateScreenLockPinForm">

                            <div class="modal-body">
                                <div class="mb-3 input-group-wrapper" id="emailInput">
                                    <div class="row align-items-center g-2">
                                        <div class="col-12">
                                            <label for="username-input" class="form-label">
                                                @if (Setting::get('lock_screen_password_set') == true)
                                                    Update Unlock PIN
                                                @else
                                                    Create Unlock PIN
                                                @endif
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-lock fs-5"></i></span>
                                                <input type="password" class="form-control rounded-end pe-35px"
                                                    name="pin" placeholder="Type a pin use to unlock">
                                                <button type="button"
                                                    class="show-password-btn btn-no-style text-dark me-1">
                                                    <i class="bi bi-eye fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="username-input" class="form-label mt-2">Your password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-lock fs-5"></i></span>
                                                <input type="password" class="form-control rounded-end pe-35px"
                                                    name="password" placeholder="Enter your password">
                                                <button type="button"
                                                    class="show-password-btn btn-no-style text-dark me-1">
                                                    <i class="bi bi-eye fs-5"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary"
                                    id="confirm-change-btn">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endif


<div class="d-flex align-items-center mb-3">
    <div class="d-flex align-items-center">
        <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
        <div>
            <h2 class="fs-7 m-0">PEM File Setup
                @if (Setting::get('is_pem_key_set') == true)
                    <small class="badge badge-green">active</small>
                @else
                    <small class="badge badge-red">inactive</small>
                @endif
            </h2>
            <p class="fs-8 m-0 text-muted">
            </p>
        </div>
    </div>

    <div class="buttonArea px-2 ps-3 border-start ms-auto">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3">
            @if (Setting::get('is_pem_key_set') == true)
                Edit
            @else
                Add
            @endif
        </button>
    </div>
</div>

<div class="d-flex align-items-center mb-3">
    <div class="d-flex align-items-center">
        <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
        <div>
            <h2 class="fs-7 m-0">Master Key Setup
                @if (Setting::get('is_master_password_set') == true)
                    <small class="badge badge-green">active</small>
                @else
                    <small class="badge badge-red">inactive</small>
                @endif
            </h2>
            <p class="fs-8 m-0 text-muted">
            </p>
        </div>
    </div>

    <div class="buttonArea px-2 ps-3 border-start ms-auto">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3">
            @if (Setting::get('is_master_password_set') == true)
                Edit
            @else
                Add
            @endif
        </button>
    </div>

</div>


<div class="d-flex align-items-center mb-3">
    <div class="d-flex align-items-center">
        <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
        <div>
            <h2 class="fs-7 m-0">App Based Authentication
                @if (Setting::get('is_app_auth_set') == true)
                    <small class="badge badge-green">active</small>
                @else
                    <small class="badge badge-red">inactive</small>
                @endif
            </h2>
            <p class="fs-8 m-0 text-muted">
            </p>
        </div>
    </div>

    <div class="buttonArea px-2 ps-3 border-start ms-auto">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3">
            @if (Setting::get('is_app_auth_set') == true)
                Edit
            @else
                Add
            @endif
        </button>
    </div>

</div>
