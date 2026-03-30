@extends('docs.layout.layout')
@section('title', $title ?? '🟢🟢🟢')

@section('content')

    <div class="content-page ms-0">
        <div class="content">
            <div class="container-xxl">
                <div class="row g-3">
                    <div class="col-12">

                        @if (!empty($spotlightPartner))

                            <div class="text-center my-4 d-flex flex-column align-items-center">
                                <h1>{{ $documentation->title }} Partners</h1>
                                <p style="max-width: 600px">Vue Partners are Vue-team endorsed agencies that provide
                                    first-class
                                    Vue consulting and
                                    development services. If your company is interested in being listed as a partner, please
                                    register your interest here.</p>
                            </div>

                            <div class="container bg-gray-100 py-4 spotlight-partner-container">
                                <h6 class="fw-bold text-second-dark">
                                    Partner Spotlight
                                </h6>

                                <div class="card border partner-card shadow">
                                    <div class="card-body p-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <img src="{{ Storage::url($spotlightPartner->logo_light) }}" alt=""
                                                    width="200px">

                                                <small class="location-text mt-3 d-flex align-items-center flex-wrap">
                                                    <i class='bx bx-map fs-6 me-1'></i> {{ $spotlightPartner->location }}
                                                </small>

                                                <p class="my-3 ">
                                                    {{ $spotlightPartner->short_description }}
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
                                                    <img src="{{ Storage::url($spotlightPartner->banner) }}" class="w-100"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (!empty($searchable) && $searchable)
                            <div class="text-center my-4 d-flex flex-column align-items-center">
                                <h1>Browse All Partners</h1>
                                <p style="max-width: 600px">Vue Partners are Vue-team endorsed agencies that provide
                                    first-class
                                    Vue consulting and
                                    development services. If your company is interested in being listed as a partner, please
                                    register your interest here.</p>
                            </div>
                        @endif

                        <div class="mx-width-container  mt-4">

                            @if (!empty($searchable) && $searchable)
                                <div class="in-page-search-box mb-4">
                                    <i class="bx bx-search"></i>
                                    <input type="text" class="form-control" id="partnersSearchInput"
                                        data-search-url="{{ route('docs.partners.search.component', ['user' => $user, 'slug' => request('slug')]) }}"
                                        placeholder="Search partners by name or region">
                                </div>
                            @endif

                            @if (empty($searchable))
                                <h5 class="fw-bold text-second-dark mb-4">
                                    Featured Partners
                                </h5>
                            @endif
                            <div id="partnersListRender">
                                <x-docs.partners-list :partners="$partners" />
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
    <script src="{{ asset('assets/js/pages/docs/partners.js') }}"></script>
@endsection
