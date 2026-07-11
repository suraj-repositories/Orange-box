@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.css') }}">
@endsection
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
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.templates.index') }}">Templates</a></li>

                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row g-3 mb-3">

                    <div class="col-12 col-md-8">
                        <div class="row g-3">

                            <!-- Header Section -->
                            <div class="col-12">
                                <h2 class="fw-bold">{{ $template->title ?? '' }}</h2>
                                {{-- <p class="text-muted mb-2">HTML5 Bootstrap 5 Template for Agency Website</p> --}}

                                <!-- Rating -->
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-warning">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < ($template->reviews_avg_rating ?? 0))
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </div>
                                    @if ($template->reviews_avg_rating > 0)
                                        <small>({{ round($template->reviews_avg_rating, 1) }})</small>
                                    @endif
                                    <span class="text-muted">
                                        {{ $template->reviews_count }}
                                        customer reviews
                                    </span>
                                </div>
                            </div>

                            <!-- Images Section -->
                            <div class="col-12">
                                <div class="template-images" data-media-preview="true" data-media-downloadable="true">
                                    <div class="row">
                                        <div class="col-12">
                                            <img src="{{ $template->preview_image_url }}"
                                                class="img-fluid rounded shadow-sm w-100"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/placeholder-600x400.svg') }}';" />
                                        </div>

                                        <div class="col-12">
                                            <div class="image-scroll-wrapper">
                                                <button type="button" class="image-scroll-btn left">
                                                    <i class="bx bx-chevron-left"></i>
                                                </button>

                                                <div class="multi-image-container-scrollable">
                                                    @if ($template?->files?->isNotEmpty())
                                                        @foreach ($template->files as $file)
                                                            <div class="image-box border">
                                                                <img src="{{ $file->getFileUrl() }}" alt="Image">
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>

                                                <button type="button" class="image-scroll-btn right">
                                                    <i class="bx bx-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Card -->
                            <div class="col-12">
                                <div class="card shadow-sm mb-0">
                                    <div class="card-body">
                                        <h5 class="fw-semibold mb-3">Description</h5>
                                        <div class="text-muted">
                                            {{ $template->description ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews Section -->
                            <div class="col-12">
                                <div class="card shadow-sm mb-0">
                                    <div class="card-body">
                                        <div class="row  g-2">

                                            <div class="col-12">
                                                <h5 class="fw-semibold mb-0">Customer Reviews
                                                    @if ($template->reviews_count > 0)
                                                        ({{ $template->reviews_count }})
                                                    @endif
                                                </h5>
                                            </div>

                                            <div class="col-12">
                                                <form
                                                    action="{{ route('ajax.doc-template.add-review', ['template' => $template]) }}"
                                                    method="POST" data-submit-type='ajax'>
                                                    @csrf
                                                    <div class="card p-0 border mb-0">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <textarea name="comment" resizeable="true" id="" cols="30" rows="1" style="height: 55px; min-height: 55px"
                                                                    class="form-control  border-0 border-bottom" placeholder="Write your review here..."></textarea>
                                                            </div>

                                                            <div class="col-12">
                                                                <div
                                                                    class="d-flex px-2 pb-2 align-items-center flex-wrap justify-content-between gap-2">
                                                                    <input type="hidden" name="rating"
                                                                        class="form-control" id="ratingInputElement">
                                                                    <div class="d-flex gap-2 rating-component"
                                                                        data-update-input="#ratingInputElement">
                                                                        <i class="bi bi-star star"></i>
                                                                        <i class="bi bi-star star"></i>
                                                                        <i class="bi bi-star star"></i>
                                                                        <i class="bi bi-star star"></i>
                                                                        <i class="bi bi-star star"></i>
                                                                    </div>

                                                                    <button class="btn btn-outline-dark btn-sm rounded-pill">Add
                                                                        Review</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-12">
                                                <div class="review-container ">
                                                    @foreach ($template->reviews()->latest()->take(5)->get() ?? [] as $review)
                                                        <x-review.review-component :review="$review" />
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if ($template->reviews_count > 5)
                                                <div class="col-12 text-center">
                                                    <button class="btn btn-outline-secondary btn-sm" data-offset="5"
                                                        data-template-uuid="{{ $template->uuid }}"
                                                        id="loadMoreReviewButton">Load
                                                        More Reviews</button>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="row g-3">

                            <div class="col-12">
                                <div class="card mb-0">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">License Options</h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-3">

                                            @if (($template->price ?? 0) > 0)
                                                <div class="col-12">
                                                    <h4 class="text-center mb-0">
                                                        <del
                                                            class="text-muted">${{ number_format($template->original_price, 2) }}</del>
                                                        ${{ number_format($template->price, 2) }}

                                                        @if ($template->original_price > 0 && $template->original_price > $template->price)
                                                            <small class="text-success">
                                                                ({{ round((($template->original_price - $template->price) / $template->original_price) * 100) }}%
                                                                Off)
                                                            </small>
                                                        @endif
                                                    </h4>
                                                </div>
                                            @endif

                                            <div class="col-12">
                                                @if (($template->price ?? 0) > 0 && !$isPurchased)
                                                    <form
                                                        action="{{ authRoute('user.template.add-to-cart', ['template' => $template]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button class="btn btn-outline-primary w-100">Add to Cart <i
                                                                class='bx bx-cart'></i></button>
                                                    </form>
                                                @else
                                                    <a href="{{ authRoute('user.template.licence', ['template' => $template]) }}"
                                                        class="btn btn-outline-primary w-100">View Licence <i
                                                            class='bx bx-link-external'></i></a>
                                                @endif
                                            </div>

                                            @if (!empty($template->preview_url))
                                                <div class="col-12">
                                                    <a href="{{ $template->preview_url ?? 'javascript:void(0)' }}"
                                                        target="_blank" class="btn btn-light text-dark border w-100">Live
                                                        Preview <i class='bx bx-link-external'></i></a>
                                                </div>
                                            @endif

                                            <div class="col-12">
                                                <ul class="mb-0">
                                                    <li>Open Source</li>
                                                    <li>Use in commercial project</li>
                                                    <li>Free Lifetime Updates</li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-footer border-top py-2">
                                        <div class="d-flex flex-wrap gap-2">
                                            @if (($template->price ?? 0) > 0)
                                                <span class="badge border"> {{ $template->purchases_count }}
                                                    {{ $template->purchases_count > 1 ? 'Purchases' : 'Purchase' }}</span>
                                            @endif

                                            @if (($template->documentations_count ?? 0) > 0)
                                                <span class="badge border">{{ $template->documentations_count }} Running
                                                    Documentation</span>
                                            @endif

                                            @if (($template->price ?? 0) == 0 && ($template->documentations_count ?? 0) == 0)
                                                <div class="d-flex align-items-center flex-wrap gap-1">
                                                    <i class="bx bx-check-circle"></i>
                                                    Professional Grade Template
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card mb-0 border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <strong>Questions?</strong>
                                            <a href="{{ authRoute('user.contact-us') }}"
                                                class="btn btn-dark btn-sm">Contact
                                                Author</a>
                                        </div>
                                    </div>
                                </div>
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
    <script src="{{ asset('assets/js/pages/documentation-template.js') }}"></script>
    <script src="{{ asset('assets/libs/image-preview-lib/oranbyte-image-preview.js') }}"></script>
@endsection
