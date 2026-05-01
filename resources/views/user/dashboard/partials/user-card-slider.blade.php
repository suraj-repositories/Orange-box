<div class="user-scroll-wrapper position-relative">

    <button class="scroll-btn left" onclick="scrollUsers(-1)"><i class='bx bx-chevron-left'></i></button>

    <div id="userScrollContainer" class="user-scroll d-flex users-scroll-list user-scroll-container">

        @foreach ($users as $user)
            <div class="user-item">
                <div class="card user-card mb-0">
                    <a href="{{ route('users.profile.index', ['user' => $user]) }}">
                        <div class="card-body blur-bg" data-image="{{ $user->avatar_url }}">

                            <img class="img-fluid user-img" src="{{ $user->avatar_url }}">
                        </div>
                    </a>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('users.profile.index', ['user' => $user]) }}">
                            <h2 class="fs-6 mb-0 text-dark">{{ '@' . $user->username }}</h2>
                        </a>
                        @php $isFollowing = Auth::user()->isFollowing($user) @endphp
                        <button class="btn btn-sm btn-light border follow-btn"
                            data-follow-url="{{ authRoute('user.follow', ['id' => $user->id]) }}"
                            data-unfollow-url="{{ authRoute('user.unfollow', ['id' => $user->id]) }}"
                            data-following="{{ $isFollowing ? 'true' : 'false' }}">{{ $isFollowing ? 'Unfollow' : 'Follow' }}</button>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- Repeat .user-item -->
    </div>

    <!-- Right Button -->
    <button class="scroll-btn right" onclick="scrollUsers(1)"> <i class='bx bx-chevron-right'></i></button>

</div>
