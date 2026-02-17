@extends('docs.layout.layout')
@section('title', $currentPage->title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page analytics-dashboard">
        <div class="content">
            <div class="container-xxl">
                <div class="row g-3">
                    <div class="col-12 col-md-9">
                        <div class="container mt-3 px-4 mb-5">
                            <div id="documentationContent">
                                {!! $currentPage->content_html ?? '' !!}
                            </div>
                            <hr>
                            @if ($previousPage || $nextPage)
                                <div class="doc-pagination d-flex justify-content-between">

                                    @if ($previousPage)
                                        <a href="{{ route('docs.show', [$documentation->user, $documentation->url, $previousPath]) }}"
                                            class="prev-btn">

                                            <div class="prev-text">
                                                <i class='bx bx-chevrons-left'></i> Previous
                                            </div>

                                            <div class="prev-page-title">
                                                {{ $previousPage->title }}
                                            </div>
                                        </a>
                                    @endif

                                    @if ($nextPage)
                                        <a href="{{ route('docs.show', [$documentation->user, $documentation->url, $nextPath]) }}"
                                            class="next-btn text-end">

                                            <div class="next-text">
                                                Next <i class='bx bx-chevrons-right'></i>
                                            </div>

                                            <div class="next-page-title">
                                                {{ $nextPage->title }}
                                            </div>
                                        </a>
                                    @endif

                                </div>
                            @endif



                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <nav id="navbar-example3" class="sticky-sidebar">
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

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/docs/index.js') }}"></script>
@endsection
