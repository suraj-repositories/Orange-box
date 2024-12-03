@include('layout.header')

<div id="app-layout">

    @include('user.layout.navbar')

    @include('user.layout.sidebar')

    @yield('content')

</div>

@include('layout.footer')
