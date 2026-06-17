@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

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

                            </div>

                            <img src="{{ $template->preview_image_url }}" class="img-fluid rounded shadow-sm mb-4 w-100"
                                onerror="this.onerror=null;this.src='{{ asset('assets/images/defaults/placeholder-600x400.svg') }}';" />

                            <!-- Description Card -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="fw-semibold mb-3">Description</h5>
                                    <div class="text-muted">
                                        {{ $template->description ?? '' }}
                                    </div>


                                </div>
                            </div>

                            <!-- Reviews Section -->
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-semibold mb-3">Customer Reviews
                                        @if ($template->reviews_count > 0)
                                            ({{ $template->reviews_count }})
                                        @endif
                                    </h5>

                                    <form action="{{ route('ajax.doc-template.add-review', ['template' => $template]) }}"
                                        method="POST" data-submit-type='ajax' class="mb-3">
                                        @csrf
                                        <textarea name="comment" id="" cols="30" rows="3" class="form-control mb-2"></textarea>

                                        <input type="number" name="rating" class="form-control mb-2" id="">

                                        <button class="btn btn-primary btn-sm">Add Review</button>
                                    </form>

                                    <div class="review-container">
                                        @foreach ($template->reviews()->latest()->take(5)->get() ?? [] as $review)
                                            <x-review.review-component :review="$review" />
                                        @endforeach
                                    </div>

                                    @if ($template->reviews_count > 5)
                                        <div class="text-center mt-3">
                                            <button class="btn btn-outline-secondary  btn-sm" data-offset="5"
                                                data-template-uuid="{{ $template->uuid }}" id="loadMoreReviewButton">Load
                                                More Reviews</button>
                                        </div>
                                    @endif
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

                                @if (($template->price ?? 0) > 0)
                                    <h4 class="text-center mb-3">
                                        <del class="text-muted">${{ number_format($template->original_price, 2) }}</del>
                                        ${{ number_format($template->price, 2) }}

                                        @if ($template->original_price > 0 && $template->original_price > $template->price)
                                            <small class="text-success">
                                                ({{ round((($template->original_price - $template->price) / $template->original_price) * 100) }}%
                                                Off)
                                            </small>
                                        @endif
                                    </h4>
                                @endif

                                @if (($template->price ?? 0) > 0 && !$isPurchased)
                                    <form action="{{ authRoute('user.template.add-to-cart', ['template' => $template]) }}"
                                        method="POST">
                                        @csrf
                                        <button class="btn btn-outline-primary w-100 mb-3">Add to Cart <i
                                                class='bx bx-cart'></i></button>
                                    </form>
                                @else
                                    <a href="{{ authRoute('user.template.licence', ['template' => $template]) }}"
                                        class="btn btn-outline-primary w-100 mb-3">View Licence <i
                                            class='bx bx-link-external'></i></a>
                                @endif
                                <a href="{{ $template->preview_url ?? 'javascript:void(0)' }}" target="_blank"
                                    class="btn btn-light text-primary border w-100 ">Live Preview <i
                                        class='bx bx-link-external'></i></a>

                                <ul class="mt-4 mb-0">
                                    <li>Open Source</li>
                                    <li>Use in commercial project</li>
                                    <li>Free Lifetime Updates</li>
                                </ul>
                            </div>

                            <div class="card-footer border-top py-2">
                               <div class="d-flex flex-wrap gap-2">
                                 @if (($template->price ?? 0) > 0)
                                   <span class="badge border"> {{ $template->purchases_count }} {{ $template->purchases_count > 1 ? 'Purchases' : 'Purchase' }}</span>
                                @endif

                                  @if (($template->price ?? 0) > 0)
                                    <span class="badge border">{{ $template->documentations_count }} Running Documentation</span>
                                @endif
                               </div>
                            </div>

                        </div>

                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong>Questions?</strong>
                                    <a href="javascript:void()" class="btn btn-dark btn-sm">Contact Author</a>
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
@endsection
