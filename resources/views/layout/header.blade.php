<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> @yield('title') | {{ config('app.name') }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="An Orange-box project is content storing system." />
    <meta name="author" content="ProjectAndPrograms" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/ico" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}">
    <link rel='stylesheet' href='{{ asset('assets/icons/boxicons-2.1.4/css/boxicons.min.css') }}'>

    <link rel="stylesheet" href="{{ asset('assets/ckeditor5/ckeditor5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/summernote-0.9.0/summernote-lite.min.css') }}">

    @include('sweetalert2::index')
    @routes
    <script>
        function authRoute(url, args){
            const uid = @json(Auth::check() ? Auth::user()->username : null);
            return route(url, {userid: uid, ...args});
        }
    </script>
</head>

<!-- body start -->

<body data-menu-color="light" data-sidebar="default">
