@extends('admin.layout.layout')
@section('title', Route::is('admin.users.index') ? $title : '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0"> {{ $title }}</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections">
                                        <i data-feather="crosshair" class="widgets-icons"></i>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $title }}</h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">
                                        <a href="#" class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-filter fs-5"></i>
                                            <div>Filters</div>
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
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>Since</th>
                                                <th>Status</th>
                                                <th>Login</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>
                                                    {{ $users->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td>
                                                    <div class="text-dark truncate-2 d-flex align-items-center">
                                                        <img src="{{ $user->profilePicture() }}" alt=""
                                                            class="avatar-sm rounded-circle me-1">
                                                        <span>
                                                            <div>{{ $user?->fullname() }}</div>
                                                            <div><strong>{{ $user->username }}</strong></div>

                                                        </span>
                                                    </div>
                                                </td>

                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    {{ $user?->details?->contact ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $user->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input statusSwitch" type="checkbox"
                                                            role="switch"
                                                            data-url="{{ route('admin.users.status.update', ['user' => $user]) }}"
                                                            {{ $user->is_active ? 'checked' : '' }}>

                                                    </div>
                                                </td>

                                                <td>

                                                    <a href="{{ route('admin.users.login', ['user' => $user]) }}"
                                                        class="btn btn-sm border">Login</a>

                                                </td>

                                                <td>
                                                    <div class="action-container m-0 gap-1">


                                                        <form action="{{ route('admin.users.delete', ['user' => $user]) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="delete btn-no-style">
                                                                <i class='bx bx-trash fs-4'></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="8">
                                                    <x-no-data />
                                                </td>
                                            </tr>
                                        @endforelse

                                    </table>

                                    <div class="m-3 mb-0">
                                        {{ $users->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>

@endsection
@section('js')

@endsection
