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
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-14 mb-1">Daily Learnings Saved</div>
                                        </div>

                                        <div class="d-flex align-items-baseline mb-2">
                                            <div class="fs-22 mb-0 me-2 fw-semibold text-black">
                                                {{ $counts->digestionCount }}</div>
                                            <div class="me-auto">
                                                <span class="text-primary d-inline-flex align-items-center">
                                                    15%
                                                    <i data-feather="trending-up" class="ms-1"
                                                        style="height: 22px; width: 22px;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="website-visitors" class="apex-charts"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-14 mb-1">Ideas Added</div>
                                        </div>

                                        <div class="d-flex align-items-baseline mb-2">
                                            <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ $counts->thinkPadCount }}
                                            </div>
                                            <div class="me-auto">
                                                <span class="text-danger d-inline-flex align-items-center">
                                                    10%
                                                    <i data-feather="trending-down" class="ms-1"
                                                        style="height: 22px; width: 22px;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="conversion-visitors" class="apex-charts"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-14 mb-1">Syntax saved</div>
                                        </div>

                                        <div class="d-flex align-items-baseline mb-2">
                                            <div class="fs-22 mb-0 me-2 fw-semibold text-black">{{ $counts->syntaxCount }}
                                            </div>
                                            <div class="me-auto">
                                                <span class="text-success d-inline-flex align-items-center">
                                                    25%
                                                    <i data-feather="trending-up" class="ms-1"
                                                        style="height: 22px; width: 22px;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="session-visitors" class="apex-charts"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-14 mb-1">Documentation Sites</div>
                                        </div>

                                        <div class="d-flex align-items-baseline mb-2">
                                            <div class="fs-22 mb-0 me-2 fw-semibold text-black">
                                                {{ $counts->documentationCount }}</div>
                                            <div class="me-auto">
                                                <span class="text-success d-inline-flex align-items-center">
                                                    4%
                                                    <i data-feather="trending-up" class="ms-1"
                                                        style="height: 22px; width: 22px;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="active-users" class="apex-charts"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end sales -->
                </div> <!-- end row -->

                <!-- Start Monthly Sales -->
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <x-user.analytics.documentation-visitors-card duration="week" />
                    </div>

                    <div class="col-md-6 col-xl-6">
                        <div class="card overflow-hidden">

                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                        <i data-feather="tablet" class="widgets-icons"></i>
                                    </div>
                                    <h5 class="card-title mb-0">Best Traffic Source</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th>Network</th>
                                                    <th colspan="2">Visitors</th>
                                                </tr>
                                            </thead>

                                            <tr>
                                                <td>Instagram</td>
                                                <td>3,550</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-danger" style="width: 80.0%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Facebook</td>
                                                <td>1,245</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-primary" style="width: 55.9%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Twitter</td>
                                                <td>1,798</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-secondary" style="width: 67.0%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>YouTube</td>
                                                <td>986</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-success" style="width: 38.72%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Pinterest</td>
                                                <td>854</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-danger" style="width: 45.08%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Linkedin</td>
                                                <td>650</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-warning" style="width: 68.0%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Nextdoor</td>
                                                <td>420</td>
                                                <td class="w-50">
                                                    <div class="progress progress-md mt-0">
                                                        <div class="progress-bar bg-info" style="width: 56.4%">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Monthly Sales -->

                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <x-user.analytics.documentation-audience-heatmap-card />
                    </div>

                    <div class="col-md-6 col-xl-6">
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
