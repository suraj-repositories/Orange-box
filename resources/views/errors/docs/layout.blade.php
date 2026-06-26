@extends('docs.layout.layout')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-xxl">
                <div class="d-flex align-items-center justify-content-center w-100 app-error-component">
                    <section class="page_404 w-100">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="col-sm-12 col-sm-offset-1  text-center">
                                        <div class="four_zero_four_bg" style="background-image: url({{ asset('assets/images/defaults/error_page.gif') }})">
                                            <h1 class="text-center ">@yield('code')

                                            </h1>
                                        </div>

                                        <div class="contant_box_404">
                                            <h3 class="h2">
                                                @yield('message')
                                            </h3>

                                            <p>Look like you're lost!</p>

                                            <a href="#" class="go_back_to_home rounded">Go to Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
@endsection
