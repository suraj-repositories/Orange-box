@include('docs.layout.header')

<div id="app-layout">

    @include('docs.layout.navbar')

    @include('docs.layout.vp-local-nav')

    <div class="container-xxl">

        @include('docs.layout.sidebar')

        @yield('content')

    </div>

</div>

@include('docs.layout.footer')
