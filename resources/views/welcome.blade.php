<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @auth
        @if (Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        @elseif(Auth::user()->hasRole('editor', Auth::user()->id))
            <a href="{{ route('editor.dashboard') }}">Dashboard</a>
        @elseif(Auth::user()->hasRole('user'))
            <a href="{{ authRoute('user.dashboard') }}">Dashboard</a>
        @endif
    @endauth
    @guest

        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endguest
</body>

</html>
