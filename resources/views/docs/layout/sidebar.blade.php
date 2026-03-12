<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="dropdown version-dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="icon-box">
                        @if ($release->id == $documentation->latestRelease->id)
                            <i class='bx bx-purchase-tag'></i>
                        @else
                            <i class='bx bx-box'></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="title">{{ $release->title }}</h4>
                        <p class="version-tag">{{ $release->version }}</p>
                    </div>

                    <div class="toggle-icon">
                        <i class='bx bx-code bx-rotate-90'></i>
                    </div>

                </button>
                <ul class="dropdown-menu mt-3">
                    @foreach ($top5Releases as $r)
                        <li>
                            <a class="dropdown-item btn"
                                href="{{ route('docs.switchVersion', [
                                    'user' => $user->username,
                                    'slug' => $documentation->url,
                                    'version' => $r->version,
                                    'path' => request()->route('path'),
                                ]) }}">
                                <div class="icon-box">
                                    @if ($r->id == $documentation->latestRelease->id)
                                        <i class='bx bx-purchase-tag'></i>
                                    @else
                                        <i class='bx bx-box'></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="title">{{ $r->title }}</h4>
                                    <p class="version-tag">{{ $r->version }}</p>
                                </div>

                                @if ($r->id == $release->id)
                                    <div class="toggle-icon">
                                        <i class='bx bx-check'></i>
                                    </div>
                                @endif
                            </a>
                        </li>
                    @endforeach

                </ul>
            </div>

            <ul id="side-menu">
                @foreach ($pages as $page)
                    <li>
                        @if ($page->type == 'folder')
                            <div class="top-level-folder">
                                <span>{{ $page->title }}</span>
                            </div>

                            @foreach ($page->children as $child)
                                <x-docs.tree-node :page="$child" :open="$loop->first" />
                            @endforeach
                        @elseif($page->type == 'file')
                            <a href="{{ route('docs.show', [
                                'user' => request()->route('user')->username,
                                'slug' => request()->slug,
                                'version' => request()->version,
                                'path' => $page->full_path,
                            ]) }}"
                                class="top-level-file {{ request()->is('*' . $page->full_path) ? 'active' : '' }}">
                                <span>{{ $page->title }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>

        </div>
        <div class="clearfix"></div>

    </div>
</div>
