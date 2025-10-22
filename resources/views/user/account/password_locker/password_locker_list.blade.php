@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

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
                            <li class="breadcrumb-item"><a href="#">Passwords</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <x-account.password-locker-component :passwords="$passwords" />
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
    <script src="{{asset('assets/js/pages/password-locker.js')}}"></script>
@endsection
