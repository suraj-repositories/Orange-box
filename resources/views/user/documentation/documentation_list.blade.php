@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Documentations</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections p-0">
                                        <i class="bi bi-file-richtext widgets-icons fs-5" ></i>

                                    </div>
                                    <h5 class="card-title mb-0">Documentations</h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">

                                    <a href="{{ authRoute('user.documentation.create') }}" class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div> New</div>
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
                                                <th>Link</th>
                                                <th>Status</th>
                                                <th>Creation</th>
                                                <th>Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        @forelse ($documentations as $doc)
                                            <tr>
                                                <td>

                                                    {{ $documentations->firstItem() + $loop->iteration - 1 }}
                                                </td>
                                                <td>
                                                    <img src="{{ Storage::url($doc->logo) }}" class="avatar avatar-sm img-fluid rounded-2 me-1" aria-label="{{ $doc->title }}" >
                                                    {{ $doc->title }}
                                                </td>
                                                <td>
                                                   <small> <a href="{{ $doc->full_url }}" target="_blank"
                                                        title="{{ $doc->full_url }}">{{ $doc->url }}</a>
                                                    </small>

                                                <i class="bi bi-copy ms-1 copy-icon" data-copy-text="{{ $doc->full_url }}"></i>
                                                </td>
                                                <td>
                                                    {{ ucfirst($doc->status) }}
                                                </td>
                                                <td>
                                                    {{ $doc->created_at?->format('M d, Y h:i A') ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $doc->updated_at?->diffForHumans() ?? '' }}
                                                </td>
                                                <td>

                                                    <div class="action-container m-0 gap-1">
                                                        <a href="{{ authRoute('user.documentation.pages.index', ['documentation' => $doc->uuid]) }}" class="text-reset">
                                                            <i class="bx bx-info-circle"></i>
                                                        </a>

                                                        <a href="{{ authRoute('user.documentation.edit', ['documentation' => $doc->uuid]) }}"
                                                            class="edit">
                                                            <i class="bx bx-edit"></i>
                                                        </a>
                                                        <form
                                                            action="http://localhost:8000/user@123/project-boards/orange-box"
                                                            method="post">
                                                            <input type="hidden" name="_method" value="DELETE"> <input
                                                                type="hidden" name="_token"
                                                                value="GoyVETZNjK2xobL5pAS8947RTHcxHwfb0Do823Lm"
                                                                autocomplete="off"> <button type="submit"
                                                                class="delete btn-no-style">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </form>

                                                    </div>

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
                                </div>
                                <div class="mx-3 my-3">{{ $documentations->withQueryString()->links() }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')

@endsection
