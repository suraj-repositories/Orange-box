@extends('user.layout.layout')
@section('title', Route::is('user.dashboard') ? 'Dashboard' : '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
                    </div>
                </div>


            </div> <!-- container-fluid -->
        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')

    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/lib-config/analytics-dashboard.init.js') }}"></script>

@endsection
