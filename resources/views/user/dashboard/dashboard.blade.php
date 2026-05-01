@extends('user.layout.layout')
@section('title', Route::is('user.dashboard') ? $title : '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0"> {{ $title }}</h4>
                    </div>
                </div>

                @if (!$quickLinks->isEmpty())
                    @include('user.dashboard.partials.tags', ['quickLinks' => $quickLinks])
                @endif

                @include('user.dashboard.partials.dashboard-cards-section')

                @if (!$users->isEmpty())
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mt-3 mb-2">
                        <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-globe-americas me-1"></i> Who to Follow</h3>

                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm border">Show All <i
                                class="bi bi-arrow-right"></i></a>

                    </div>
                    @include('user.dashboard.partials.user-card-slider', [
                        'users' => $users,
                    ])
                @endif


                @if (!$featuredTemplates->isEmpty())
                    <div class="d-flex align-items-center gap-3 flex-wrap mt-3 mb-2">
                        <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-gem me-1"></i> Featured Templates</h3>
                    </div>

                    <x-user.docs.template-list :templates="$featuredTemplates" />
                @endif

                @if (!$topTemplates->isEmpty())
                    <div class="d-flex align-items-center gap-3 flex-wrap mt-3 mb-2">
                        <h3 class="mb-0 fs-6 text-muted"> <i class="bi bi-journal-richtext me-1"></i> Top Templates</h3>
                    </div>

                    <x-user.docs.template-list :templates="$topTemplates" />
                @endif

                <div class="my-4 mt-5 text-center">
                    <h2 class="fs-6 fw-bold">Need a quick walkthrough?</h2>
                    <p class="text-muted mx-auto" style="max-width: 320px;">
                        Learn how to manage projects, organize ideas, and use tools like Think Space and Project Tracker
                        efficiently.
                    </p>
                    <a class="btn btn-primary btn-sm" href="{{ authRoute('user.faq.index') }}">View Guide</a>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>

@endsection
@section('js')
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
@endsection
