@extends('user.layout.layout')

@section('title', !empty($title) ? $title : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? "" }}</h4>
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

            <x-project.project-board-card-list-component :project-boards="$projectBoards"/>

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>
@endsection
