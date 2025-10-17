<div class="container-fluid">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3">
        @forelse ($projectBoards as $projectBoard)
            <x-project.project-board-card-component :project-board="$projectBoard" />
        @empty
            <x-no-data />
        @endforelse
    </div>
    {{ $projectBoards->withQueryString()->links() }}
</div>
