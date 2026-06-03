@extends('admin.layout.layout')
@section('title', Route::is('admin.dashboard') ? $title : '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0"> {{ $title }}</h4>
                    </div>
                </div>



                <div class="my-4 mt-5 text-center">
                    <h2 class="fs-6 fw-bold">Need a quick walkthrough?</h2>
                    <p class="text-muted mx-auto" style="max-width: 320px;">
                        Learn how to manage projects, organize ideas, and use tools like Think Space and Project Tracker
                        efficiently.
                    </p>
                    <a class="btn btn-primary btn-sm" href="#">View Guide</a>
                </div>



            </div>
        </div>

        @include('layout.components.copyright')
    </div>

@endsection
@section('js')

@endsection
