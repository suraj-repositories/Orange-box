@extends('user.layout.layout')

@section('title', Route::is('user.project-board') ? 'Project Board' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Project Board List</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Project Board</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <x-alert-component />

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-2">
                @forelse ($projectBoards as $projectBoard)
                    <div class="col">
                        <div class="card d-flex flex-column h-100">
                            <img class="card-img-top rounded-top" src="{{ $projectBoard->thumbnail_url }}"
                                onerror="this.onerror=null;this.src='{{ config('constants.DEFAULT_PROJECT_THUMBNAIL') }}';"
                                alt="">

                            <div class="card-body flex-grow-1 pb-0">
                                <a href="{{ authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}">
                                    <h6 class="fw-bold truncate-3">
                                        {{ $projectBoard->title }}
                                    </h6>
                                </a>
                                <p class="card-text text-muted mb-0 truncate-3">
                                    {{ $projectBoard->preview_text }}
                                </p>
                            </div>

                            <div class="card-footer mt-auto pt-0">
                                <div class="action-container d-flex justify-content-between align-items-center">
                                    <div class="ago-string">
                                        <strong>Created: </strong> {{ $projectBoard->created_at->diffForHumans() }}
                                    </div>
                                    <div class="action-buttons d-flex gap-1">
                                        <a href="{{ authRoute('user.project-board.show', ['slug' => $projectBoard->slug]) }}"
                                            class="info"><i class='bx bx-info-circle'></i></a>
                                        <a href="{{ authRoute('user.project-board.edit', ['slug' => $projectBoard->slug]) }}" class="edit"><i class='bx bx-edit'></i></a>
                                        <form action="{{ authRoute('user.project-board.delete', ['slug' => $projectBoard->slug]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="delete btn-no-style">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <x-no-data />
                @endforelse


            </div>

            {{ $projectBoards->links() }}

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>
@endsection
