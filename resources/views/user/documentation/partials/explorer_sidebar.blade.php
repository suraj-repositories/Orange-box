<div class="box" id="explorer-sidebar">
    <div class="explorer-nav">
        <strong class="title">Explorer</strong>
        <div class="action-btns">
            <button id="newFile">
                <i class="bi bi-file-earmark-plus"></i>
            </button>
            <button id="newFolder">
                <i class="bi bi-folder-plus"></i>
            </button>
            <button id="refreshExplorer">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>

    <ul class="directory-list">
        @foreach ($pages as $page)
            <x-user.docs.tree-node :page="$page" />
        @endforeach
    </ul>

</div>
