@extends('admin.layout.layout')
@section('title', Route::is('admin.docs.templates.index') ? $title : '🟢🟢🟢')

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
                                        <a href="{{ route('admin.docs.templates.create') }}"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div>New</div>
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
                                                <th>Title</th>
                                                <th>Key</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @forelse ($templates as $template)
                                            <tr>
                                                <td>
                                                    {{ $templates->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td>
                                                    <a href="#" class="text-dark truncate-2">
                                                        {{ $template->title }}
                                                    </a>
                                                </td>

                                                <td>{{ $template->key }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($template->description, 50, '...') }}
                                                </td>
                                                <td>{{ empty(round($template->price ?? 0, 2)) ? 'Free' : "$ ". round($template->price ?? 0, 2) }}
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input statusSwitch" type="checkbox"
                                                            role="switch"
                                                            data-url="{{ route('admin.docs.templates.status.update', ['template' => $template]) }}"
                                                            {{ $template->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="action-container m-0 gap-1">
                                                        <a href="{{ route('admin.docs.templates.show', ['template' => $template]) }}" class="info ms-0 ">
                                                            <i class='bx bx-info-circle fs-4'></i>
                                                        </a>

                                                        <a href="{{ route('admin.docs.templates.edit', ['template' => $template]) }}" class="edit">
                                                            <i class='bx bx-edit fs-4'></i>
                                                        </a>

                                                        <form action="#" method="post">
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
                                        {{ $templates->withQueryString()->links() }}
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
    <script src="{{ asset('assets/js/pages/admin/docs-templates.js') }}"></script>
@endsection
