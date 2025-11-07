@if (Setting::get('username_update') == true)
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
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
            <button class="btn btn-outline-primary btn-sm rounded-pill px-2">
                Change
            </button>
        </div>
    </div>
@endif

@if (Setting::get('primary_email_update') == true)
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
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
            <button class="btn btn-outline-primary btn-sm rounded-pill px-2">
                Change
            </button>
        </div>
    </div>
@endif

@if (Setting::get('delete_account') == true)
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
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
            <button class="btn btn-outline-danger btn-sm rounded-pill px-2">
                Delete
            </button>
        </div>
    </div>
@endif
