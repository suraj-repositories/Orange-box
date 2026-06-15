<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-4 g-3 file-manager-files">

    @foreach ($items as $item)
        <div class="col">
            <div class="file-card d-flex flex-column h-100">

                <div class="file-img-box">
                    <input type="checkbox" class="form-check-input select-checkbox">

                    @if ($item->item_type === 'file')
                        @if ($item->mime_type && str_starts_with($item->mime_type, 'image/'))
                            <img class="card-img-top rounded-top object-fit-contain"
                                src="{{ $item->file_url }}" alt="{{ $item->item_name }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/placeholder-600x400.svg') }}';">
                        @else
                            <i class="bx bx-file file-icon"></i>
                        @endif
                    @else
                        <i class="bx bx-folder file-icon"></i>
                    @endif

                    <div class="dropdown">
                        <a class="dropdown-toggle center-content text-dark btn border btn-sm px-1"
                            data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded fs-5"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-lg-end">

                            @if ($item->item_type === 'folder')
                                <li>
                                    <a class="dropdown-item"
                                        href="#">
                                        <i class="bx bx-folder-open me-1"></i>
                                        Open Folder
                                    </a>
                                </li>

                                <li>
                                    <button class="dropdown-item edit-form-factory-btn"
                                        data-ob-folder-factory-id="{{ $item->id }}">
                                        <i class="bx bx-edit me-1"></i>
                                        Edit
                                    </button>
                                </li>

                                <li>
                                    <button class="dropdown-item text-danger bg-light-danger delete-folder-button"
                                        data-folder-factory-id="{{ $item->id }}">
                                        <i class="bx bx-trash me-1"></i>
                                        Delete
                                    </button>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-info-circle me-1"></i>
                                        File Info
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-share me-1"></i>
                                        Share
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ Storage::url($item->file_path) }}"
                                        target="_blank">
                                        <i class="bx bx-show-alt me-1"></i>
                                        View File
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-copy me-1"></i>
                                        Copy File
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-log-in me-1"></i>
                                        Move File
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bxs-star me-1"></i>
                                        Make Favourite
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-rename me-1"></i>
                                        Rename
                                    </a>
                                </li>

                                <li>
                                    <button class="dropdown-item text-danger">
                                        <i class="bx bx-trash me-1"></i>
                                        Delete
                                    </button>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>

                <div class="file-meta">
                    <div class="user-title">
                        <i class="bx bx-star fs-4"></i>

                        <div>
                            <h2 class="mb-0">
                                {{ $item->item_name }}
                            </h2>

                            <small class="file-size text-muted">
                                @if ($item->item_type === 'file')
                                    {{ number_format(($item->file_size ?? 0) / 1024, 2) }} KB
                                @else
                                    Folder
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach

</div>


