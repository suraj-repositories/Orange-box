@extends('user.layout.layout')

@section('title', Route::is('user.notifications.index') ? 'Notifications' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Notifications</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Account</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notifications</h5>
                            </div>

                            <div class="card-body p-0">

                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">

                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>From</th>
                                                <th>Message</th>
                                                <th>Type</th>
                                                <th>Desc Link</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        @forelse ($notifications as $notification)
                                            <tr>
                                                <td>
                                                    {{ $notifications->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td>
                                                    {{ $notification->type }}
                                                </td>
                                                <td>
                                                    {{ $notification->data['message'] ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $notification->data['type'] ?? '' }}
                                                </td>
                                                <td>
                                                    <a href="">{{ $notification->data['message'] ?? '' }}</a>
                                                </td>
                                                <td>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </td>
                                                <td>
                                                    Delete
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="6">
                                                    <x-no-data />
                                                </td>
                                            </tr>
                                        @endforelse

                                    </table>

                                    <div class="m-3 mb-0">
                                        {{ $notifications->withQueryString()->links() }}
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
