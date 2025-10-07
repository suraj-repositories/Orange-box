@extends('user.layout.layout')

@section('title', Route::is('user.folder-factory') ? 'Folder Factory' : '🟢🟢🟢')

@section('css')

@endsection

@section('content')
    <div class="content-page folder-factory">
        <div class="content">


            <div class="container-xxl">

                <div class="pt-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Folder Factory</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.folder-factory') }}">Folder Factory</a>
                            </li>
                            <li class="breadcrumb-item active">Folder List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-4 shadow-sm syntax-store-show-card show-card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap w-100 align-items-center">
                                <h4 class="card-title mb-0 d-flex gap-1">
                                    <div
                                        class="p-2 bg-light text-dark border rounded text-center flex-fill d-flex align-items-center gap-2">
                                        <div class="fs-5 fw-bold">{{ $folderFactories->total() ?? 'Unknown' }}</div>
                                        <div class="fs-6">Folders</div>
                                    </div>
                                    <div
                                        class="p-2 bg-light text-dark border rounded text-center flex-fill d-flex align-items-center  gap-2">
                                        <div class="fs-5 fw-bold">{{ $totalFiles ?? 0 }}</div>
                                        <div class="fs-6">Files</div>
                                    </div>
                                    <div
                                        class="p-2 bg-light text-dark border rounded text-center flex-fill d-flex align-items-center  gap-2">
                                        @php
                                            $sizeParts = explode(' ', $totalSize ?? '0 Bytes');
                                            $sizeNumber = $sizeParts[0] ?? '0';
                                            $sizeUnit = $sizeParts[1] ?? '';
                                        @endphp
                                        <div class="fs-5 fw-bold">{{ $sizeNumber }}</div>
                                        <div class="fs-6">{{ $sizeUnit }}</div>
                                    </div>
                                </h4>


                                <div class="ms-auto fw-semibold">
                                    <button type="button" class="btn btn-light btn-sm border center-content gap-1"
                                        data-bs-toggle="modal" data-bs-target="#create-folder-modal">
                                        <i class="bx bx-plus"></i>
                                        <div> New</div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">

                                @forelse ($folderFactories as $folderFactory)
                                    <div class="col">
                                        <div class="folder-service-box folder-card rounded-0 border" style="border: none">
                                            <div class="card-body">
                                                <div class="image-with-title">
                                                    <div class="img-alt">
                                                        <img src="{{ $folderFactory->getIconUrl() }}" alt="folder">

                                                        <svg xmlns="http://www.w3.org/2000/svg" width="48"
                                                            height="48" viewBox="0 0 48 48">
                                                            <path fill="#8fbffa"
                                                                d="M19.741 5.193c-1.562-1.825-3.897-2.865-6.335-2.67c-3.136.251-5.378.592-6.935.909c-2.486.505-4.136 2.515-4.39 4.951c-.279 2.67-.581 7.413-.581 15.442s.302 12.772.58 15.442c.255 2.435 1.903 4.445 4.389 4.95C9.4 44.814 14.776 45.5 24 45.5s14.599-.686 17.531-1.283c2.486-.505 4.134-2.515 4.388-4.95c.279-2.67.581-7.413.581-15.442c0-3.147-.046-5.788-.12-7.997c-.137-4.148-3.556-7.307-7.617-7.307H23.82a2.68 2.68 0 0 1-2.036-.943z" />
                                                        </svg>
                                                    </div>
                                                    <div class="title-area">
                                                        <a
                                                            href="{{ authRoute('user.folder-factory.files.index', ['slug' => $folderFactory->slug]) }}">
                                                            <div class="title text-dark"> {{ $folderFactory->name }}</div>
                                                        </a>
                                                        <div class="sub-title">
                                                            <small class="text-dark fw-bold"
                                                                title="{{ $folderFactory->files_sum_file_size ?? 0 }} Bytes">
                                                                {{ $folderFactory->readable_file_size ?? 0 }}
                                                            </small>
                                                            <small class="text-muted ms-1">
                                                                <i class="bx bx-calendar"></i>
                                                                {{ $folderFactory->updated_at->diffForHumans() }}
                                                            </small>

                                                            <div class="folder-actions">
                                                                <div class="dropdown">
                                                                    <a class="dropdown-toggle center-content text-dark "
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <i class='bx bx-dots-vertical-rounded fs-5'></i>
                                                                    </a>

                                                                    <ul class="dropdown-menu dropdown-menu-lg-end">
                                                                        <li><a class="dropdown-item"
                                                                                href="{{ authRoute('user.folder-factory.files.index', ['slug' => $folderFactory->slug]) }}">
                                                                                <i class='bx bx-show-alt me-1'></i> Visit
                                                                            </a>
                                                                        </li>
                                                                        <li><a class="dropdown-item" href="#"><i
                                                                                    class='bx bx-edit me-1'></i> Edit
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <button
                                                                                class="dropdown-item text-danger bg-light-danger delete-folder-button"
                                                                                data-folder-factory-id="{{ $folderFactory->id }}"><i
                                                                                    class='bx bx-trash me-1'></i>
                                                                                Delete</button>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="actions">
                                                <i class="bx bxs-circle text-{{ ["danger", 'secondary', 'info'][rand(0, 2)] }}"></i>
                                            </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <x-no-data />
                                @endforelse


                                {{ $folderFactories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- container-fluid -->
    </div>
    <!-- content -->

    <!-- Footer Start -->
    @include('layout.components.copyright')
    <!-- end Footer -->
    <div class="modal fade folder-factory" id="create-folder-modal" tabindex="-1"
        aria-labelledby="create-folder-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ authRoute('user.folder-factory.save') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="create-folder-modalLabel">Create Folder</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col col-12 mb-3">
                                <label for="name-input" class="form-label">Folder Name</label>

                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="bi bi-folder-plus"></i> </span>
                                    <input type="text" class="form-control" placeholder="Enter folder name"
                                        id="name-input" name="name" value="">
                                </div>
                            </div>
                            <div class="col col-12 mb-3">
                                <p class="d-inline-flex gap-1">
                                    <a class="link center-content" data-bs-toggle="collapse" href="#advance-options"
                                        role="button" aria-expanded="false" aria-controls="advance-options">
                                        <span>Advance options</span><i class='bx bx-right-arrow-alt pt-1'></i>
                                    </a>
                                </p>
                                <div class="collapse" id="advance-options">
                                    <label for="folder-icon" class="form-label">Folder Icon</label>

                                    <div class="svg-grid">

                                        @foreach ($icons as $icon)
                                            <input type="radio" name="icon" value="{{ $icon->id }}"
                                                id="folder-icon-{{ $icon->id }}">
                                            <label class="svg-card" for="folder-icon-{{ $icon->id }}">
                                                <img src="{{ $icon->getUrl() }}" alt="{{ $icon->name }}">
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/folder-factory-list.js') }}"></script>
@endsection
