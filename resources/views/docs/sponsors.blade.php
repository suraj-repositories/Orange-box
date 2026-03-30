@extends('docs.layout.layout')
@section('title', $title ?? '🟢🟢🟢')

@section('content')

    <div class="content-page ms-0">
        <div class="content">
            <div class="container-xxl">
                <div class="row g-3">
                    <div class="col-12 col-xl-9">
                        <div class="container mt-3 px-1 px-sm-4 mb-5 documentation-content">

                            <div id="documentationDocumentContent" data-ob-preview-type="editorjs"
                                data-ob-content="{{ $sponsorDocument->content }}">
                            </div>

                            <hr class="opacity-15 mt-4">

                            @foreach ($sponsors as $tier => $tierSponsors)
                                @php
                                    $colClass = match ($tier) {
                                        'platinum' => 'col-6 col-md-6',
                                        'gold' => 'col-6 col-md-4',
                                        'silver' => 'col-6 col-md-3',
                                        default => 'col-6 col-md-2',
                                    };
                                @endphp

                                <div class="sponsor-tier mb-5">

                                    <h4 class="text-uppercase mb-3">{{ ucfirst($tier) }}</h4>

                                    <div class="row g-1">
                                        @foreach ($tierSponsors as $sponsor)
                                            @php
                                                $logoLight = $sponsor->logo_light;
                                                $logoDark = $sponsor->logo_dark;
                                                $hasLogo = !empty($logoLight) || !empty($logoDark);
                                            @endphp

                                            <div class="{{ $colClass }}">
                                                <div
                                                    class="border border-gray-100 rounded p-3 h-100 text-center d-flex align-items-center justify-content-center">

                                                    @if (!empty($sponsor->website_url))
                                                        <a href="{{ $sponsor->website_url }}" target="_blank" rel="noopener"
                                                            class="w-100 text-decoration-none">
                                                    @endif

                                                    @if ($hasLogo)
                                                        @if ($logoLight)
                                                            <img src="{{ Storage::url($logoLight) }}"
                                                                alt="{{ $sponsor->name }}"
                                                                class="img-fluid sponsor-logo logo-light">
                                                        @endif

                                                        @if ($logoDark)
                                                            <img src="{{ Storage::url($logoDark) }}"
                                                                alt="{{ $sponsor->name }}"
                                                                class="img-fluid sponsor-logo logo-dark">
                                                        @endif
                                                    @else
                                                        <span class="fw-semibold fs-3">{{ $sponsor->name }}</span>
                                                    @endif

                                                    @if (!empty($sponsor->website_url))
                                                        </a>
                                                    @endif

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach

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
    <script src="{{ asset('assets/js/pages/docs/extras.js') }}"></script>
@endsection
