@extends('user.layout.layout')

@section('title', Route::is('user.faq.index') ? "FAQ's" : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">FAQ's</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">FAQ's</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Frequently Asked Questions</h5>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="accordion" id="accordionExample">
                                            @foreach ($leftFaqs as $index => $faq)
                                                @php
                                                    $heading = 'headingLeft' . $index;
                                                    $collapse = 'collapseLeft' . $index;
                                                @endphp

                                                <div class="accordion-item accordion-item-faq shadow-sm">
                                                    <div class="accordion-header" id="{{ $heading }}">
                                                        <button
                                                            class="accordion-button fw-medium {{ $index != 0 ? 'collapsed' : '' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ $collapse }}"
                                                            aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                            aria-controls="{{ $collapse }}">
                                                            {{ $faq->question }}
                                                        </button>
                                                    </div>

                                                    <div id="{{ $collapse }}"
                                                        class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                                        aria-labelledby="{{ $heading }}"
                                                        data-bs-parent="#accordionExample">

                                                        <div class="accordion-body pb-0">
                                                            <p class="mb-0 text-muted">
                                                                {{ $faq->answer }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="accordion" id="accordionExample-1">
                                            @foreach ($rightFaqs as $index => $faq)
                                                @php
                                                    $heading = 'headingRight' . $index;
                                                    $collapse = 'collapseRight' . $index;
                                                @endphp

                                                <div class="accordion-item accordion-item-faq shadow-sm">
                                                    <div class="accordion-header" id="{{ $heading }}">
                                                        <button
                                                            class="accordion-button fw-medium {{ $index != 0 ? 'collapsed' : '' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ $collapse }}"
                                                            aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                            aria-controls="{{ $collapse }}">
                                                            {{ $faq->question }}
                                                        </button>
                                                    </div>

                                                    <div id="{{ $collapse }}"
                                                        class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                                        aria-labelledby="{{ $heading }}"
                                                        data-bs-parent="#accordionExample-1">

                                                        <div class="accordion-body pb-0">
                                                            <p class="mb-0 text-muted">
                                                                {{ $faq->answer }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
