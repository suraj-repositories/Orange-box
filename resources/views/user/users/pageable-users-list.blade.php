@extends('user.layout.layout')

@section('title', Route::is('users.list') ? $title : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title }}</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                @include('user.users.partials.users-list', ['users' => $users])

                <div class="mt-2">
                    {{ $users->links() }}
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>


@endsection
@section('js')
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
@endsection
