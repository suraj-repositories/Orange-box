@if (Setting::get('enable_change_password') == true)
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
            <button class="btn btn-outline-primary btn-sm rounded-pill px-2">
                Change
            </button>
        </div>
    </div>
@endif

<div class="d-flex align-items-center mb-3">
    <div class="d-flex align-items-center">
        <img class="circle-30 me-2" src="https://placehold.co/400" alt="alter">
        <div>
            <h2 class="fs-7 m-0">Lock Screen Key
                @if (Setting::get('lock_screen_password_set') == true)
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
            @if (Setting::get('lock_screen_password_set') == true)
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
