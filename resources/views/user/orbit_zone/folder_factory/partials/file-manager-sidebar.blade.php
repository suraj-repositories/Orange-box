<aside class="pm-sidebar">
    <div class="pm-sidebar-inner">
        <div class="pm-brand">
            <span class="pm-brand-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 2L3 4v4c0 3 2.5 5 5 6 2.5-1 5-3 5-6V4L8 2z" />
                </svg>
            </span>
            File Manager
        </div>

        <div class="pm-sidebar-section">
            <p class="mb-1 fw-bold px-3 fs-6">My Files</p>
            <a href=""
                class="pm-nav-item {{ request('filter') == 'all' || empty(request('filter')) ? 'active' : '' }}"
                data-view="all">
                <i class="bx bx-file"></i>
                My Drive

            </a>
            {{-- <button class="pm-nav-item" data-view="privacy">
                                                        <i class="bx bx-share"></i>
                                                        Shared
                                                    </button> --}}
            <a href="{{ request()->fullUrlWithQuery(['filter' => 'recent']) }}"
                class="pm-nav-item {{ request('filter') == 'recent' ? 'active' : '' }}" data-view="privacy">
                <i class="bx bx-user"></i>
                Recent
            </a>
            <a href="{{ request()->fullUrlWithQuery(['filter' => 'folder']) }}"
                class="pm-nav-item {{ request('filter') == 'folder' ? 'active' : '' }}" data-view="privacy">
                <i class="bx bx-folder"></i>
                Folder
            </a>
            <a href="{{ request()->fullUrlWithQuery(['filter' => 'favourite']) }}"
                class="pm-nav-item {{ request('filter') == 'favourite' ? 'active' : '' }}" data-view="privacy">
                <i class="bx bx-star"></i>
                Favourite
            </a>

            <a href="{{ request()->fullUrlWithQuery(['filter' => 'trash']) }}"
                class="pm-nav-item {{ request('filter') == 'trash' ? 'active' : '' }}" data-view="privacy">
                <i class="bx bx-trash"></i>
                Trash
            </a>

        </div>


        <div class="pm-sidebar-section px-2">
            <p class="mb-1 fw-bold px-1 fs-6">Your Storage</p>

            <strong>{{ $storageStats['percentage'] }}% Full</strong>

            <div class="progress-stacked storage-progress">

                <div class="progress storage-photos" style="width: {{ $photoPercent }}%">
                    <div class="progress-bar"></div>
                </div>

                <div class="progress storage-videos" style="width: {{ $videoPercent }}%">
                    <div class="progress-bar"></div>
                </div>

                <div class="progress storage-documents" style="width: {{ $documentPercent }}%">
                    <div class="progress-bar"></div>
                </div>

                <div class="progress storage-others" style="width: {{ $otherPercent }}%">
                    <div class="progress-bar"></div>
                </div>

            </div>

            <p class="text-muted fs-7 mt-1">
                Used: {{ $storageStats['used'] }}
                of
                {{ $storageStats['limit'] }}
            </p>

            <ul class="storage-list">

                <li class="storage-item">
                    <div class="storage-icon photos">
                        <i class='bx bx-image'></i>
                    </div>

                    <div class="storage-content">
                        <h6 class="storage-title">Photos</h6>
                        <span class="storage-count">{{ $fileStats['photos']['count'] }}
                            files</span>
                    </div>

                    <div class="storage-size">{{ $fileStats['photos']['size'] }}
                    </div>
                </li>

                <li class="storage-item">
                    <div class="storage-icon videos">
                        <i class='bx bx-video'></i>
                    </div>

                    <div class="storage-content">
                        <h6 class="storage-title">Videos</h6>
                        <span class="storage-count">{{ $fileStats['videos']['count'] }}
                            files</span>
                    </div>

                    <div class="storage-size">{{ $fileStats['videos']['size'] }}
                    </div>
                </li>

                <li class="storage-item">
                    <div class="storage-icon documents">
                        <i class='bx bx-file'></i>
                    </div>

                    <div class="storage-content">
                        <h6 class="storage-title">Documents</h6>
                        <span class="storage-count">{{ $fileStats['documents']['count'] }}
                            files</span>
                    </div>

                    <div class="storage-size">{{ $fileStats['documents']['size'] }}
                    </div>
                </li>


                <li class="storage-item">
                    <div class="storage-icon others">
                        <i class='bx bx-folder'></i>
                    </div>

                    <div class="storage-content">
                        <h6 class="storage-title">Others</h6>
                        <span class="storage-count">{{ $fileStats['others']['count'] }}
                            files</span>
                    </div>

                    <div class="storage-size">{{ $fileStats['others']['size'] }}
                    </div>
                </li>

            </ul>
        </div>




    </div>

    <div class="pm-sidebar-footer">
        <div class="d-flex w-100 align-items-center justify-content-center flex-column">
            <img class="img-fluid" src="{{ asset('assets/images/defaults/storage.webp') }}" style="height: 100px">
            <p class="text-center mb-1">
                Want to Increase Storage Capacity?
            </p>
            <button class="btn btn-outline-primary btn-sm w-100" tabindex="0" type="button">Upgrade</button>
        </div>
    </div>
</aside>
