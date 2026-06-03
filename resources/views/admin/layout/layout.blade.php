@include('layout.header')

<div id="app-layout">

    @include('admin.layout.navbar')

    @include('admin.layout.sidebar')

    @yield('content')

</div>

@include('layout.footer')
