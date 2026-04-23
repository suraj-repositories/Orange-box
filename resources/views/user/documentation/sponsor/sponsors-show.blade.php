@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/editorjs-custom.css') }}">
@endsection

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentations</a>
                            </li>

                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.document.pages.index', ['documentation' => $documentation]) }}">
                                    Page Editor
                                </a>
                            </li>

                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        @include('user.documentation.sponsor.sponsors-table')
                    </div>
                    <div class="col-12">
                        @include('user.documentation.sponsor.sponsors-content-editor')
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
    @include('layout.extras.ckeditor5')

    <script src="{{ asset('assets/js/pages/documentation-sponsors.js') }}"></script>

@endsection
