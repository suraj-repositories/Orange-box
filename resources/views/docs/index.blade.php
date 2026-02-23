@extends('docs.layout.layout')
@section('title', $currentPage->title ?? '🟢🟢🟢')

@section('content')

    <div class="content-page">
        <div class="content">

            <div class="container-xxl">
                <div class="row g-3">
                    <div class="col-12 col-xl-9">
                        <div class="container mt-3 px-1 px-sm-4 mb-5">
                            <div id="documentationContent" class="documentation-content">
                                {!! $currentPage->content_html ?? '' !!}
                            </div>

                            <a href="#" class="d-flex align-items-center my-5 edit-on-github">
                                <i class='bx bx-edit'></i>
                                <span>Edit this page on GitHub</span>
                            </a>

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
                                            class="next-btn text-end {{ !$previousPage ? 'ms-auto' : '' }}">

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

                            <div class="my-5 d-flex align-items-center justify-content-center">
                                <x-docs.feedback-component />
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
    <script src="{{ asset('assets/js/pages/docs/index.js') }}"></script>
@endsection
