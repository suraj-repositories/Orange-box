<div class="container-fluid">
    @if ($projectBoards->isEmpty())
        <div class="mt-4">
            <x-no-data />
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3">
            @foreach ($projectBoards as $projectBoard)
                <x-project.project-board-card-component :project-board="$projectBoard" />
            @endforeach
        </div>
    @endif
    {{ $projectBoards->withQueryString()->links() }}
</div>
