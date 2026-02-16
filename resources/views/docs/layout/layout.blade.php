@include('docs.layout.header')

<div id="app-layout">

    @include('docs.layout.navbar')

    @include('docs.layout.sidebar')


    @yield('content')

</div>

@include('docs.layout.footer')
