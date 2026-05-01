@extends('user.layout.layout')

@section('title', Route::is('users.index') ? 'Users' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Users</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>


                @if (!$suggestions->isEmpty())
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3 mb-2">
                        <h3 class="mb-0 fs-6 text-muted"><i class="bi bi-lightbulb me-1"></i> Suggestions</h3>

                        <a href="{{ route('users.list', ['type' => 'suggestions']) }}"
                            class="btn btn-light btn-sm border">Show All <i class="bi bi-arrow-right"></i></a>

                    </div>

                    @include('user.users.partials.users-list', ['users' => $suggestions])
                @endif

                @if (!$mutualConnections->isEmpty())
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3 mb-2">
                        <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-people-fill me-1"></i> Mutual Connections</h3>

                        <a href="{{ route('users.list', ['type' => 'mutual']) }}" class="btn btn-light btn-sm border">Show
                            All <i class="bi bi-arrow-right"></i></a>

                    </div>

                    @include('user.users.partials.users-list', ['users' => $mutualConnections])
                @endif


                @if (!$followers->isEmpty())
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3 mb-1">
                        <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-person-heart me-1"></i> Followers</h3>

                        <a href="{{ route('users.list', ['type' => 'followers']) }}"
                            class="btn btn-light btn-sm border">Show All <i class="bi bi-arrow-right"></i></a>

                    </div>
                    @include('user.dashboard.partials.user-card-slider', [
                        'users' => $followers,
                    ])
                @endif
                @if (!$following->isEmpty())
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3 mb-1">
                        <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-person-plus-fill me-1"></i> Following</h3>

                        <a href="{{ route('users.list', ['type' => 'following']) }}"
                            class="btn btn-light btn-sm border">Show All <i class="bi bi-arrow-right"></i></a>

                    </div>
                    @include('user.dashboard.partials.user-card-slider', [
                        'users' => $following,
                    ])
                @endif



            </div>
        </div>

        @include('layout.components.copyright')
    </div>


@endsection
@section('js')
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
@endsection
