@extends('user.layout.layout')
@section('title', Route::is('user.dashboard') ? $title : '🟢🟢🟢')


@section('css')
    <style>

    </style>
@endsection
@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0"> {{ $title }}</h4>
                    </div>
                </div>

                @include('user.dashboard.partials.tags', ['tags' => $tags])

                @include('user.dashboard.partials.dashboard-cards-section')

                @include('user.dashboard.partials.who-to-follow')


                <div class="d-flex align-items-center gap-3 flex-wrap mt-3 mb-2">
                    <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-gem me-1"></i> Featured Templates</h3>
                </div>

                <x-user.docs.template-list :templates="[1, 2, 3, 4, 5, 6]" />

                <div class="d-flex align-items-center gap-3 flex-wrap mt-3 mb-2">
                    <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-journal-richtext me-1"></i> Top Templates</h3>
                </div>


                <x-user.docs.template-list :templates="[1, 2]" />

                <div class="my-4 mt-5 text-center">
                    <h2 class="fs-6 fw-bold">Need a quick walkthrough?</h2>
                    <p class="text-muted mx-auto" style="max-width: 320px;">
                        Learn how to manage projects, organize ideas, and use tools like Think Space and Project Tracker
                        efficiently.
                    </p>
                    <button class="btn btn-primary btn-sm">View Guide</button>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>

@endsection
@section('js')
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

@endsection
