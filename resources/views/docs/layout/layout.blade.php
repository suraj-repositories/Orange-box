@include('docs.layout.header')

<div id="app-layout">

    @include('docs.layout.navbar')

    <div class="container-xxl">
        @include('docs.layout.sidebar')

        @yield('content')
    </div>

</div>

@include('docs.layout.footer')
