<div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3 mb-2">
    <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-globe-americas me-1"></i> Who to follow</h3>
    <a href="" class="btn btn-light btn-sm border">Show All <i class="bi bi-arrow-right"></i></a>
</div>

<div class="user-scroll-wrapper position-relative">

    <button class="scroll-btn left" onclick="scrollUsers(-1)"><i class='bx bx-chevron-left'></i></button>

    <div id="userScrollContainer" class="user-scroll d-flex users-scroll-list">

        @foreach ($users as $user)
            <div class="user-item">
                <div class="card user-card mb-0">
                    <a href="#">
                        <div class="card-body blur-bg" data-image="{{ $user->avatar_url }}">

                            <img class="img-fluid user-img" src="{{ $user->avatar_url }}">
                        </div>
                    </a>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="#">
                            <h2 class="fs-6 mb-0 text-dark">{{ '@' . $user->username }}</h2>
                        </a>
                        <button class="btn btn-sm btn-light border">Follow</button>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- Repeat .user-item -->
    </div>

    <!-- Right Button -->
    <button class="scroll-btn right" onclick="scrollUsers(1)"> <i class='bx bx-chevron-right'></i></button>

</div>
