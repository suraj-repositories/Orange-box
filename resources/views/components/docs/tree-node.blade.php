<li>
    @if ($page->type === 'folder')

        <a href="#node-{{ $page->id }}"
           data-bs-toggle="collapse"
           role="button"
           aria-expanded="{{ request()->is('*'.$page->full_path.'*') ? 'true' : 'false' }}">

            <span>{{ $page->title }}</span>
            <span class="menu-arrow"></span>
        </a>

        <div class="collapse {{ request()->is('*'.$page->full_path.'*') ? 'show' : '' }}"
             id="node-{{ $page->id }}">

            <ul class="nav-second-level">
                @foreach ($page->children as $child)
                    <x-docs.tree-node :page="$child" />
                @endforeach
            </ul>
        </div>

    @else

        <a href="{{ route('docs.show', [
                'user' => request()->route('user')->username,
                'slug' => request()->slug,
                'path' => $page->full_path
            ]) }}"
           class="{{ request()->is('*'.$page->full_path) ? 'active' : '' }}">

            <span>{{ $page->title }}</span>
        </a>

    @endif
</li>
