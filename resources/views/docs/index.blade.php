@extends('docs.layout.layout')
@section('title', $currentPage->title ?? '🟢🟢🟢')

@section('content')

    <div class="content-page analytics-dashboard">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="row g-3">
                    <div class="col-12 col-md-9">
                        <div class="container mt-3" id="documentationContent">
                            {!! $currentPage->content_html ?? '' !!}
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end">
                            <nav class="nav nav-pills flex-column">
                                <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </nav>
                        </nav>
                    </div>

                </div>


            </div>
        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            generateScrollSpy();
            document.querySelectorAll('#navbar-example3 a').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href'))
                        .scrollIntoView({
                            behavior: 'smooth'
                        });
                });
            });
        });

        function generateScrollSpy() {
            const content = document.getElementById('documentationContent');
            const navContainer = document.querySelector('#navbar-example3 .nav');

            if (!content || !navContainer) return;

            navContainer.innerHTML = '';

            const headings = content.querySelectorAll('h2, h3');

            let currentParentNav = null;

            headings.forEach((heading, index) => {

                // Create ID if not exists
                if (!heading.id) {
                    heading.id = heading.textContent
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/(^-|-$)/g, '');
                }

                if (heading.tagName === 'H2') {

                    // Create parent link
                    const parentLink = document.createElement('a');
                    parentLink.className = 'nav-link';
                    parentLink.href = `#${heading.id}`;
                    parentLink.textContent = heading.textContent;

                    navContainer.appendChild(parentLink);

                    // Create child container
                    currentParentNav = document.createElement('nav');
                    currentParentNav.className = 'nav nav-pills flex-column';
                    navContainer.appendChild(currentParentNav);

                } else if (heading.tagName === 'H3' && currentParentNav) {

                    const childLink = document.createElement('a');
                    childLink.className = 'nav-link ms-3 my-1';
                    childLink.href = `#${heading.id}`;
                    childLink.textContent = heading.textContent;

                    currentParentNav.appendChild(childLink);
                }
            });

            // Activate Bootstrap ScrollSpy
            new bootstrap.ScrollSpy(document.body, {
                target: '#navbar-example3',
                offset: 100
            });
        }
    </script>


@endsection
