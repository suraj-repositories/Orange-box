@extends('user.layout.layout')

@section('title', (Route::is('user.project-board.modules.index') || Route::is('user.tasks.index'))? 'Project Tasks' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Project Tasks</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="#">Project Tasks</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-md-12">
                      <x-project.project-module-task-list-component :tasks="$tasks" />
                    </div>
                </div>
            </div>
        </div>
        <!-- End content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')

    <script src="{{ asset('assets/js/pages/project-module.js') }}"></script>
@endsection
