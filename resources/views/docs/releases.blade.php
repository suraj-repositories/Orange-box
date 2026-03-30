@extends('docs.layout.layout')
@section('title', $title ?? 'Releases')

@section('content')

    <div class="content-page ms-0">
        <div class="content">
            <div class="container-xxl">
                <div class="row g-3">
                    <div class="col-12 col-xl-9">
                        <div class="container mt-3 px-1 px-sm-4 mb-5 documentation-content">
                            <h1 class="pt-3 fw-bold">
                                Releases
                            </h1>

                            <p class="mt-3">The current latest stable version of
                                {{ $documentation->title ?? 'Documentation' }} is
                                <a
                                    href="{{ route('docs.switchVersion', [
                                        'user' => $user->username,
                                        'slug' => $documentation->url,
                                        'version' => $latestStableRelease->version ?? $release->version,
                                        'path' => request()->route('path'),
                                    ]) }}">{{ $latestStableRelease->version ?? $release->version }}</a>.
                            </p>
                            <p>A full changelog of past releases is available on GitHub.

                            </p>

                            <div class="doc-releases mt-4">


                                <hr class="opacity-15">

                                <h1 class="pt-3 fw-bold mb-4 fs-3">
                                    Release Catalog
                                </h1>

                                @php $previousTitle = null; @endphp
                                <ul class="list-unstyled">
                                    @foreach ($releases as $release)
                                        @if ($release->title !== $previousTitle)
                                            @if ($previousTitle)
                                </ul>
                                </li>
                                @endif

                                <li class="mb-2">
                                    <h3 class="fw-semibold fs-5">{{ $release->title ?? 'Documentation' }}</h3>
                                    <ul class="list-inline mb-3 ms-3 documentation-release-list">
                                        @endif

                                        <li class="list-item mb-1">
                                            <a class="fs-6"
                                                href="{{ route('docs.switchVersion', [
                                                    'user' => $user->username,
                                                    'slug' => $documentation->url,
                                                    'version' => $release->version,
                                                    'path' => request()->route('path'),
                                                ]) }}">
                                                {{ $release->version }}
                                            </a>
                                            <small class="text-muted ms-2">
                                                {{ $release->released_at ? \Carbon\Carbon::parse($release->released_at)->format('d M Y') : 'Unreleased' }}
                                            </small>
                                        </li>

                                        @php $previousTitle = $release->title; @endphp
                                        @endforeach
                                    </ul>
                                </li>
                                </ul>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-3 scrollpsy-column">
                        <nav id="scrollpsy-nav" class="sticky-sidebar">
                            <div class="on-this-page">On This Page</div>
                            <div class="nav nav-pills flex-column position-relative">
                                <div class="active-indicator"></div>
                                <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.components.copyright')
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/docs/releases.js') }}"></script>
@endsection
