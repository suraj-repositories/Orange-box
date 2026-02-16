<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">



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
