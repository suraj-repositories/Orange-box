@extends('docs.layout.layout')
@section('title', $document->title ?? '🟢🟢🟢')

@section('content')

    <div class="content-page ms-0">
        <div class="content">
            <div class="container-xxl">
                <div class="row g-3">
                    <div class="col-12 col-xl-9">
                        <div class="container mt-3 px-1 px-sm-4 mb-5">

                            <div id="documentationDocumentContent" data-ob-preview-type="editorjs"
                                data-ob-content="{{ $document->content }}" class="documentation-content">
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
    <script src="{{ asset('assets/js/pages/docs/extras.js') }}"></script>
@endsection
