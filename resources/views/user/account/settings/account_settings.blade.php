@if (Setting::get('username_update') == true)
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <div class="circle-30 me-2">
                <i class="{{ Setting::getIcon('username_update') }} fs-5"></i>
            </div>
            <div>
                <h2 class="fs-7 m-0">
                    Change Username
                </h2>
                <p class="fs-8 m-0 text-muted">
                    {{ Setting::getDescription('username_update') }}
                </p>
            </div>
        </div>

        <div class="buttonArea px-2 ps-3 border-start ms-auto">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-2" data-bs-toggle="modal"
                data-bs-target="#pre-change-username-modal">
                Change
            </button>

            <div class="modal fade pre-change-username-modal" id="pre-change-username-modal" tabindex="-1"
                aria-labelledby="pre-change-username-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="pre-change-username-modal-title">Really change your
                                username?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle fs-5 me-2"></i>
                                <div>Unexpected bad things will happen if you don’t read this!</div>
                            </div>

                            <ul>
                                <li>We will not set up redirects for your old profile page.</li>
                                <li>We will not set up redirects for Pages sites.</li>
                                <li>We will create redirects for your repositories (web and git access).</li>
                                <li>Renaming may take a few minutes to complete.</li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>

                            <!-- THIS BUTTON CLOSES FIRST MODAL AND OPENS SECOND MODAL -->
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal"
                                data-bs-target="#change-username-modal">
                                I understand let's change my username
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade change-username-modal" id="change-username-modal" tabindex="-1"
                aria-labelledby="change-username-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="change-username-modal-title">Change Username</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ authRoute('ajax.settings.account.change.username') }}" id="changeUsernameForm">

                            <div class="modal-body">
                                <div class="mb-3 input-group-wrapper" id="emailInput">
                                    <div class="row align-items-center g-2">
                                        <div class="col-12">
                                            <label for="username-input" class="form-label">Type new username</label>
                                            <div class="input-group">
                                                <span class="input-group-text fw-bold fs-5 py-1">@</span>
                                                <input type="text" class="form-control" id="username-input"
                                                    name="username" placeholder="Type new username">
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mt-2 text-danger" id="errorView"></p>
                                    <p id="suggestions">

                                    </p>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="confirm-change-btn">Change</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

@if (Setting::get('primary_email_update') == true)
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <div class="circle-30 me-2">
                <i class="{{ Setting::getIcon('primary_email_update') }} fs-5"></i>
            </div>
            <div>
                <h2 class="fs-7 m-0">
                    Change Primary Email
                </h2>
                <p class="fs-8 m-0 text-muted">
                    {{ Setting::getDescription('primary_email_update') }}
                </p>
            </div>
        </div>

        <div class="buttonArea px-2 ps-3 border-start ms-auto">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-2" data-bs-toggle="modal"
                data-bs-target="#pre-change-email-modal">
                Change
            </button>

            <div class="modal fade pre-change-email-modal" id="pre-change-email-modal" tabindex="-1"
                aria-labelledby="pre-change-email-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="pre-change-email-modal-title">
                                Change your primary email?
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle fs-5 me-2"></i>
                                <div>Please review the following before updating your email.</div>
                            </div>

                            <ul>
                                <li>Your login email will be updated to the new address.</li>
                                <li>You may need to verify the new email before it becomes active.</li>
                                <li>All future notifications will be sent to the new email.</li>
                                <li>Make sure you have access to the new email address.</li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                data-bs-toggle="modal" data-bs-target="#change-email-modal">
                                Continue to change email
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade change-email-modal" id="change-email-modal" tabindex="-1"
                aria-labelledby="change-email-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="change-email-modal-title">Change Primary Email</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ authRoute('ajax.settings.account.change.primary-email') }}"
                            id="changeEmailForm">

                            <div class="modal-body">
                                <div class="mb-3 input-group-wrapper" id="emailInput">
                                    <div class="row align-items-center g-2">
                                        <div class="col-12">
                                            <label for="email-input" class="form-label">Type new email</label>
                                            <div class="input-group">
                                                <span class="input-group-text fw-bold fs-5 py-1"> <i
                                                        class="bx bx-envelope"></i> </span>
                                                <input type="email" class="form-control" id="email-input"
                                                    name="email" placeholder="Type new email">
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mt-2 text-danger" id="errorView"></p>
                                    <p id="suggestions">

                                    </p>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary"
                                    id="confirm-change-btn">Change</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

@if (Setting::get('delete_account') == true)
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <div class="circle-30 me-2">
                <i class="{{ Setting::getIcon('delete_account') }} fs-5"></i>
            </div>

            <div>
                <h2 class="fs-7 m-0">
                    Delete Account
                </h2>
                <p class="fs-8 m-0 text-muted">
                    {{ Setting::getDescription('delete_account') }}
                </p>
            </div>
        </div>

        <div class="buttonArea px-2 ps-3 border-start ms-auto">
            <button class="btn btn-outline-danger btn-sm rounded-pill px-2" data-bs-toggle="modal"
                data-bs-target="#pre-delete-account-modal">
                Delete
            </button>

            <div class="modal fade pre-delete-account-modal" id="pre-delete-account-modal" tabindex="-1"
                aria-labelledby="pre-delete-account-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="pre-delete-account-modal-title">
                                Delete your account?
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle fs-5 me-2"></i>
                                <div>This action is permanent and cannot be undone.</div>
                            </div>

                            <ul>
                                <li>Your account and all associated data will be permanently deleted.</li>
                                <li>You will lose access to your profile, settings, and history.</li>
                                <li>This action cannot be reversed once completed.</li>
                                <li>You may not be able to reuse the same email/username again.</li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <form action="{{ authRoute('ajax.settings.account.delete-account') }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">
                                    I understand, delete my account
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
