<li class="{{ $page->type === 'folder' ? 'folder' : 'file' }}" data-doc-page-uuid="{{ $page->uuid }}">

    <span class="node-title">
        {{ $page->title }}
    </span>

    @if ($page->children->count())
        <ul>
            @foreach ($page->children as $child)
                <x-user.docs.tree-node :page="$child" />
            @endforeach
        </ul>
    @endif
</li>
