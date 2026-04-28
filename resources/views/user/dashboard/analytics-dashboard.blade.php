@extends('user.layout.layout')
@section('title', Route::is('user.dashboard.analytics') ? 'Analytics' : '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Analytics</h4>
                    </div>
                </div>

                <!-- start row -->
                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="row g-3">

                            <div class="col-md-6 col-xl-3">
                                <a href="{{ authRoute('user.daily-digest.me') }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-14 mb-1">Daily Digestions</div>
                                            </div>

                                            <div class="d-flex align-items-baseline mb-2">
                                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">
                                                    {{ $stats->digestionCount }}</div>
                                                <div class="me-auto">
                                                    <span
                                                        class="{{ $stats->digestionGrowthPositive ? 'text-success' : 'text-danger' }} d-inline-flex align-items-center">

                                                        {{ $stats->digestionGrowth }}%

                                                        <i data-feather="{{ $stats->digestionGrowthPositive ? 'trending-up' : 'trending-down' }}"
                                                            class="ms-1" style="height: 22px; width: 22px;"></i>

                                                    </span>
                                                </div>
                                            </div>
                                            <div id="website-visitors" class="apex-charts"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <a href="{{ authRoute('user.think-pad.me') }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-14 mb-1">Think Pads</div>
                                            </div>

                                            <div class="d-flex align-items-baseline mb-2">
                                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">
                                                    {{ $stats->thinkPadCount }}
                                                </div>
                                                <div class="me-auto">
                                                    <span
                                                        class="{{ $stats->thinkPadGrowthPositive ? 'text-success' : 'text-danger' }} d-inline-flex align-items-center">

                                                        {{ $stats->thinkPadGrowth }}%

                                                        <i data-feather="{{ $stats->thinkPadGrowthPositive ? 'trending-up' : 'trending-down' }}"
                                                            class="ms-1" style="height: 22px; width: 22px;"></i>

                                                    </span>
                                                </div>
                                            </div>
                                            <div id="conversion-visitors" class="apex-charts"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <a href="{{ authRoute('user.syntax-store.me') }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-14 mb-1">Syntax Store</div>
                                            </div>

                                            <div class="d-flex align-items-baseline mb-2">
                                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">
                                                    {{ $stats->syntaxCount }}
                                                </div>
                                                <div class="me-auto">
                                                    <span
                                                        class="{{ $stats->syntaxGrowthPositive ? 'text-success' : 'text-danger' }} d-inline-flex align-items-center">

                                                        {{ $stats->syntaxGrowth }}%

                                                        <i data-feather="{{ $stats->syntaxGrowthPositive ? 'trending-up' : 'trending-down' }}"
                                                            class="ms-1" style="height: 22px; width: 22px;"></i>

                                                    </span>
                                                </div>
                                            </div>
                                            <div id="session-visitors" class="apex-charts"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <a href="{{ authRoute('user.documentation.index') }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-14 mb-1">Documentation Sites</div>
                                            </div>

                                            <div class="d-flex align-items-baseline mb-2">
                                                <div class="fs-22 mb-0 me-2 fw-semibold text-black">
                                                    {{ $stats->documentationCount }}</div>
                                                <div class="me-auto">
                                                    <span
                                                        class="{{ $stats->uniqueVisitorPositive ? 'text-success' : 'text-danger' }} d-inline-flex align-items-center">

                                                        {{ $stats->uniqueVisitorGrowth }}%

                                                        <i data-feather="{{ $stats->uniqueVisitorPositive ? 'trending-up' : 'trending-down' }}"
                                                            class="ms-1" style="height: 22px; width: 22px;"></i>

                                                    </span>
                                                </div>
                                            </div>
                                            <div id="active-users" class="apex-charts"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> <!-- end sales -->
                </div> <!-- end row -->



                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <x-user.analytics.documentation-visitors-card duration="week" />
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <x-user.analytics.documentation-audience-heatmap-card />
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4 col-xl-4">
                        <x-user.analytics.memory-monitor />
                    </div>
                    <div class="col-md-8 col-xl-8">
                        <x-user.analytics.most-visisted-pages-card duration="week" />
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
