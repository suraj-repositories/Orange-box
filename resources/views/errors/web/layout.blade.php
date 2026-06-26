<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <style>
        .page_404 {
            padding: 40px 0;
            background: #fff;
        }

        .page_404 img {
            width: 100%;
        }

        .four_zero_four_bg {
            background-image: url({{ asset('assets/images/defaults/error_page.gif') }});
            height: 400px;
            background-position: center;
        }


        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .go_back_to_home {
            color: #fff !important;
            padding: 10px 20px;
            background: #42424b;
            margin: 20px 0;
            display: inline-block;
            text-decoration: none;
        }

        .contant_box_404 {
            margin-top: -50px;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center w-100" style="height: 100vh">
        <section class="page_404 w-100">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 ">
                        <div class="col-sm-12 col-sm-offset-1  text-center">
                            <div class="four_zero_four_bg">
                                <h1 class="text-center ">@yield('code')</h1>


                            </div>

                            <div class="contant_box_404">
                                <h3 class="h2">
                                    @yield('message')
                                </h3>

                                <p>Look like you're lost!</p>

                                <a href="{{ route('home') }}" class="go_back_to_home rounded">Go to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
