@include('docs.layout.header')

<div id="app-layout">

    @include('docs.layout.navbar')
    <div class="vp-local-nav">
        <button class="button-toggle-menu">
            <i class='bx bx-menu-alt-right'></i>
            Menu
        </button>
        <div class="dropdown">
            <button type="button" id="onThisPageDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                On This Page
                <i class='bx bx-chevron-down'></i>
            </button>

            <ul class="dropdown-menu" aria-labelledby="onThisPageDropdown">
                <li><a class="dropdown-item" href="#section1">Section 1</a></li>
                <li><a class="dropdown-item" href="#section2">Section 2</a></li>
                <li><a class="dropdown-item" href="#section3">Section 3</a></li>
            </ul>
        </div>
    </div>

    <div class="container-xxl">
        @include('docs.layout.sidebar')

        @yield('content')
    </div>

</div>

@include('docs.layout.footer')
