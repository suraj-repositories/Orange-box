<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-4 g-3 file-manager-files"
    data-media-preview="true">

    @php $isEmpty = false; @endphp
    @forelse ($items as $item)
        <div class="col">
            <div class="file-card d-flex flex-column h-100">

                <div class="file-img-box">
                    <input type="checkbox" class="form-check-input select-checkbox" data-name="{{ $item->item_name }}"
                        data-type="{{ $item->item_type }}" data-id="{{ $item->id }}"
                        data-file-url="{{ $item->item_type === 'file' ? $item->file_url : '' }}"
                        data-mime-type="{{ $item->mime_type }}"
                        data-modified="{{ date('d M Y h:i a', strtotime($item->updated_at)) }}"
                        data-created="{{ date('d M Y h:i a', strtotime($item->created_at)) }}"
                        data-item-count="{{ $item->item_count ?? '' }}"
                        data-size="{{ $item?->formatted_file_size ?? '' }}">

                    @if ($item->item_type === 'file')
                        @if ($item->mime_type && str_starts_with($item->mime_type, 'image/'))
                            <img class="card-img-top rounded-top object-fit-contain" src="{{ $item->file_url }}"
                                data-media-downloadable="true" alt="{{ $item->item_name }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/placeholder-600x400.svg') }}';">
                        @else
                            <i class="bx {{ $item->extension_icon }} file-icon"></i>
                        @endif
                    @else
                        <img class="card-img-top object-fit-contain" src="{{ $item->icon_url }}"
                            style="max-width: 100px" alt="{{ $item->item_name }}" data-previewable="false"
                            onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/placeholder-600x400.svg') }}';">
                    @endif

                    <div class="dropdown">
                        <a class="dropdown-toggle center-content text-dark btn border btn-sm px-1 bg-light"
                            data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded fs-5"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-lg-end">

                            @if ($item->item_type === 'folder')
                                <li>
                                    <button class="dropdown-item file-info-button" data-name="{{ $item->item_name }}"
                                        data-type="{{ $item->item_type }}"
                                        data-file-url="{{ $item->item_type === 'file' ? $item->file_url : '' }}"
                                        data-mime-type="{{ $item->mime_type }}"
                                        data-modified="{{ date('d M Y h:i a', strtotime($item->updated_at)) }}"
                                        data-created="{{ date('d M Y h:i a', strtotime($item->created_at)) }}"
                                        data-item-count="{{ $item->item_count ?? '' }}"
                                        data-size="{{ $item?->formatted_file_size ?? '' }}">
                                        <i class="bx bx-info-circle me-1"></i>
                                        File Info
                                    </button>
                                </li>

                                @if (request()->filter == 'trash')
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-refresh me-1"></i>
                                            Restore
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ authRoute('user.folder-factory.files.index', ['folderId' => $item->id]) }}">
                                            <i class="bx bx-folder-open me-1"></i>
                                            Open Folder
                                        </a>
                                    </li>

                                    <li>
                                        <button class="dropdown-item edit-form-factory-btn"
                                            data-ob-folder-factory-id="{{ $item->id }}"
                                            data-ob-folder-factory-name="{{ $item->item_name }}"
                                            data-ob-folder-factory-icon="">
                                            <i class="bx bx-edit me-1"></i>
                                            Rename
                                        </button>
                                    </li>

                                    <li>
                                        <button class="dropdown-item file-reallocation" data-action-title="Copy Folder"
                                            data-submit-url="{{ authRoute('user.folders.copy', ['folder' => $item->id]) }}">
                                            <i class="bx bx-copy me-1"></i>
                                            Copy Folder
                                        </button>
                                    </li>

                                    <li>
                                        <button class="dropdown-item file-reallocation" data-action-title="Move Folder"
                                            data-submit-url="{{ authRoute('user.folders.move', ['folder' => $item->id]) }}">
                                            <i class="bx bx-log-in me-1"></i>
                                            Move Folder
                                        </button>
                                    </li>
                                @endif

                                <li>
                                    <button class="dropdown-item text-danger bg-light-danger delete-file-button"
                                        data-type="file" data-id="{{ $item->id }}">
                                        <i class="bx bx-trash me-1"></i>
                                        Delete
                                    </button>

                                </li>
                            @else
                                <li>
                                    <button class="dropdown-item file-info-button" data-name="{{ $item->item_name }}"
                                        data-type="{{ $item->item_type }}"
                                        data-file-url="{{ $item->item_type === 'file' ? $item->file_url : '' }}"
                                        data-mime-type="{{ $item->mime_type }}"
                                        data-modified="{{ date('d M Y', strtotime($item->updated_at)) }}"
                                        data-created="{{ date('d M Y', strtotime($item->created_at)) }}"
                                        data-item-count="{{ $item->item_count ?? '' }}"
                                        data-size="{{ $item?->formatted_file_size ?? '' }}">
                                        <i class="bx bx-info-circle me-1"></i>
                                        File Info
                                    </button>
                                </li>

                                {{-- <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-share me-1"></i>
                                        Share
                                    </a>
                                </li> --}}

                                @if (request()->filter == 'trash')
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-refresh me-1"></i>
                                            Restore
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ $item->file_url }}" target="_blank">
                                            <i class="bx bx-show-alt me-1"></i>
                                            View File
                                        </a>
                                    </li>

                                    <li>
                                        <button class="dropdown-item file-reallocation" data-action-title="Copy File"
                                            data-submit-url="{{ authRoute('user.files.copy', ['file' => $item->id]) }}">
                                            <i class="bx bx-copy me-1"></i>
                                            Copy File
                                        </button>
                                    </li>

                                    <li>
                                        <button class="dropdown-item file-reallocation" data-action-title="Move File"
                                            data-submit-url="{{ authRoute('user.files.move', ['file' => $item->id]) }}">
                                            <i class="bx bx-log-in me-1"></i>
                                            Move File
                                        </button>
                                    </li>

                                    <li>
                                        <button class="dropdown-item favourite_toggle"
                                            data-is-favourite="{{ $item->is_favourite }}"
                                            data-item-type="{{ $item->item_type }}"
                                            data-item-id="{{ $item->id }}">
                                            <i class="bx {{ $item->is_favourite ? 'bxs-star' : 'bx-star' }} me-1"></i>
                                            <span class="text">
                                                Make Favourite
                                            </span>
                                        </button>
                                    </li>

                                    <li>
                                        <button class="dropdown-item" data-ob-file-id="{{ $item->id }}"
                                            data-ob-file-name="{{ $item->item_name }}">
                                            <i class="bx bx-rename me-1"></i>
                                            Rename
                                        </button>
                                    </li>
                                @endif

                                <li>

                                    <button class="dropdown-item text-danger delete-file-button" data-type="file"
                                        data-id="{{ $item->id }}">
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
                        <button class="btn-no-style favourite_toggle" data-is-favourite="{{ $item->is_favourite }}"
                            data-item-type="{{ $item->item_type }}" data-item-id="{{ $item->id }}">
                            <i class="bx {{ $item->is_favourite ? 'bxs-star' : 'bx-star' }} fs-4"></i>
                        </button>

                        <div>
                            <h2 class="mb-0">
                                {{ $item->item_name }}
                            </h2>

                            <small class="file-size text-muted">
                                @if ($item->item_type === 'file')
                                    {{ $item->formatted_file_size }} | {{ strtoupper($item->extension ?? '') }}
                                @else
                                    {{ $item->item_count }} Items | Folder
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    @empty
        @php $isEmpty = true; @endphp
    @endforelse

</div>

@if ($isEmpty)
    <div class="mt-2">
        <x-no-data message="No Files yet." />
    </div>
@endif
