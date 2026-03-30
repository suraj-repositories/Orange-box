@extends('docs.layout.layout')
@section('title', $title ?? '🟢🟢🟢')

@section('content')

    <div class="content-page show-partner ms-0">
        <div class="content">

            <div class="mx-width-container mt-4">
                <a href="{{ route('docs.partners.all.index', [
                    'user' => $user,
                    'slug' => request('slug'),
                ]) }}"
                    class="btn border mt-4 d-flex align-items-center w-fit back-btn">
                    <i class='bx bx-chevron-left fs-5'></i> Back to all partners
                </a>


                <div class="bg-gray-100 py-4 pt-3 spotlight-partner-container">

                    <div class="card border partner-card shadow">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <img src="{{ Storage::url($partner->logo_light) }}" alt="" width="200px">

                                    <small class="location-text mt-3 d-flex align-items-center flex-wrap">
                                        <i class='bx bx-map fs-6 me-1'></i> {{ $partner->location }}
                                    </small>

                                    <p class="my-3 ">
                                        {{ $partner->short_description }}
                                    </p>

                                    <h6 class="fw-semibold">Proficiencies</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach ([1, 2, 3, 5, 6] as $tag)
                                            <div class="badge tech-badge">
                                                Tech
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center h-100 w-100 justify-content-center">
                                        <img src="{{ Storage::url($partner->banner) }}" class="w-100" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-1">
                    <h3 class="text-center">
                        About {{ $partner->name }}
                    </h3>

                    <div class="mt-4 md-render">
                        {!! Str::markdown($partner->description ?? '') !!}
                    </div>

                    <div class="mt-4 d-flex align-items-center justify-content-center gap-2">
                        @if (!empty($partner->website_url))
                            <a href="{{ $partner->website_url }}" target="_blank" class="btn btn-primary">
                                Visit Website
                            </a>
                        @endif

                        @if (!empty($partner->email))
                            <a href="mailto:{{ $partner->email }}" class="btn btn-dark">
                                Contact
                            </a>
                        @endif
                    </div>
                </div>


                @include('layout.components.copyright')

            </div>

        @endsection

        @section('js')
            <script src="{{ asset('assets/js/pages/docs/index.js') }}"></script>
            <script src="{{ asset('assets/js/pages/docs/extras.js') }}"></script>
        @endsection
