@extends('admin.layout.layout')

@section('title', empty($template) ? 'Create Template' : 'Edit Template')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Templates</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('admin.docs.templates.index') }}">Templates</a></li>

                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />



                <div class="row g-3">

                    <div class="col-12 col-md-8">
                        <div class="container">



                            <!-- Header Section -->
                            <div class="row align-items-center mb-4">
                                <div class="col-12">
                                    <h2 class="fw-bold">Elixir – Premium Bootstrap 5 Agency Website Template</h2>
                                    <p class="text-muted mb-2">HTML5 Bootstrap 5 Template for Agency Website</p>

                                    <!-- Rating -->
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-warning">
                                            ★★★★★
                                        </div>
                                        <span class="text-muted">9 customer reviews</span>
                                    </div>
                                </div>

                            </div>

                            <img src="https://placehold.co/600x300" class="img-fluid rounded shadow-sm mb-4 w-100" />

                            <!-- Description Card -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="fw-semibold mb-3">Description</h5>
                                    <p class="text-muted">
                                        Elixir is an impressive business website template, noted for its thoughtful
                                        structure
                                        and smooth animations. Users value its reliability in real-world projects and
                                        consistently
                                        praise the responsive, professional support.
                                    </p>

                                    <p class="text-muted">
                                        Developed for agencies, consultancies, and corporate ventures, this theme transforms
                                        Bootstrap 5 into a versatile, modular design system. With a focus on scalability and
                                        adaptability, Elixir delivers a polished foundation for both one-page and multipage
                                        business websites.
                                    </p>

                                    <p class="text-muted">
                                        The architecture emphasizes clean, responsive layouts and semantic markup. It
                                        incorporates
                                        optimized assets to ensure rapid performance, accessibility compliance, and strong
                                        SEO
                                        potential. Developers benefit from an intuitive, extensible codebase that balances
                                        sophisticated design with streamlined deployment.
                                    </p>
                                </div>
                            </div>

                            <!-- Features Section -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold">⚡ Fast Performance</h6>
                                        <p class="text-muted small mb-0">Optimized assets for speed and smooth UX.</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold">📱 Fully Responsive</h6>
                                        <p class="text-muted small mb-0">Looks perfect on all devices and screens.</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold">🧩 Modular Design</h6>
                                        <p class="text-muted small mb-0">Reusable components for faster development.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews Section -->
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-semibold mb-3">Customer Reviews (9)</h5>

                                    <!-- Review Item -->
                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <strong>John Doe</strong>
                                            <small class="text-warning">★★★★★</small>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Excellent template! Clean code and very easy to customize.
                                        </p>
                                    </div>

                                    <div class="border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between">
                                            <strong>Sarah Smith</strong>
                                            <small class="text-warning">★★★★☆</small>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            Great support and beautiful design. Highly recommended.
                                        </p>
                                    </div>

                                    <div class="text-center mt-3">
                                        <button class="btn btn-outline-secondary btn-sm">View All Reviews</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">License Options
                                </h5>
                            </div>

                            <div class="card-body">

                            </div>

                        </div>
                    </div>



                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>
@endsection

@section('js')

@endsection
