@include('docs.layout.header')

<div id="app-layout">

    @include('docs.layout.navbar')

    @include('docs.layout.vp-local-nav')

    <div class="container-xxl">

        @if (Route::is('docs.show'))
            @include('docs.layout.sidebar')
        @endif

        @yield('content')

    </div>

</div>

@include('docs.layout.footer')
