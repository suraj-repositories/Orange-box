<div class="row">
    <div class="col-md-6 col-sm-6 col-md-6 mb-4">
        <div class="">
            <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">About me</h5>
            <p>{{ $personalInfo?->bio ?? '' }}</p>
        </div>
        @if (!empty($skills) && count($skills) > 0)
            <div class="skills-details mt-3">
            <h6 class="text-uppercase fs-13">Skills</h6>

            <div class="d-flex flex-wrap gap-2">
                @foreach ($skills as $skill => $level)
                    <span class="badge bg-light border px-3 text-dark py-2 fw-semibold">{{ $skill }}</span>
                @endforeach
            </div>

        </div>
        @endif

    </div>

    <div class="col-md-6 col-sm-6 col-md-6 mb-4">
        <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Contact Details</h5>

        <div class="row">

            @if (!empty($personalInfo?->public_email ?? null))
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="profile-email">
                        <h6 class="text-uppercase fs-13">Email Address</h6>
                        <a href="mailto:{{ $personalInfo?->public_email ?? '' }}"
                            class="text-primary fs-14 text-decoration-underline">{{ $personalInfo?->public_email ?? '' }}</a>
                    </div>
                </div>
            @endif
            @if (count($userSocialMediaLinks) > 0)
                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="profile-email">
                        <h6 class="text-uppercase fs-13">Social Media</h6>
                        <ul class="social-list list-inline mt-0 mb-0">
                            @foreach ($socialMediaPlatforms as $link)
                                @if (!empty($link->user_link))
                                    <li class="list-inline-item">
                                        <a href="{{ $link->user_link }}" target="_blank"
                                            style="color: {{ $link->color }}; border-color: {{ $link->color }}; "
                                            class="social-item border d-flex justify-content-center align-items-center"><i
                                                class="{{ $link->icon }} fs-14"></i></a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (!empty($address))
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="profile-email mt-3">
                        <h6 class="text-uppercase fs-13">Location</h6>
                        <a href="https://www.google.com/search?q={{ urlencode($address->line1 . ', ' . ($address->line2 ? $address->line2 . ', ' : '') . $address->city . ', ' . $address->state . ', ' . $address->country . ' ' . $address->postal_code) }}"
                            class="fs-14" target="_blank">
                            {{ $address->line1 }}<br>
                            @if (!empty($address->line2))
                                {{ $address->line2 }}<br>
                            @endif
                            {{ $address->city }}, {{ $address->state }} {{ $address->country }} -
                            {{ $address->postal_code }}
                        </a>

                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-6 col-md-6 mb-0">
        <div class="">
            <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Projects</h5>
        </div>

        <div class="row">
            @forelse ($projects as $project)
                <div class="col-6">
                <div class="card border">
                    <div class="card-body">
                        <h4 class="m-0 fw-semibold text-dark fs-16">{{ $project->title }}</h4>
                        <div class="row mt-2 d-flex align-items-center">
                            <div class="col">
                                <h5 class="fs-20 mt-1 fw-bold">₹ {{ number_format($project->budget) }}</h5>
                                <p class="mb-0 text-muted">Total Budget</p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ authRoute('user.project-board.show', ['slug' => $project->slug]) }}" class="btn btn-sm btn-outline-dark px-3">More Details</a>
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div>
            </div>
            @empty
                <x-no-data />
            @endforelse
        </div>
    </div><!-- end project -->

    @if (!empty($experties) && count($experties) > 0)
        <div class="col-md-6 col-sm-6 col-md-6 mb-0">
        <div class="">
            <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Expertise</h5>
        </div>

        <div class="row align-items-center g-3">
        @foreach ($experties as $skill => $level)
            <div class="col-sm-3">
                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i>
                    {{ $skill }}
                </p>
            </div>

            <div class="col-sm-9">
                <div class="progress mt-1" style="height: 8px;">
                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: {{ $level }}%"
                        aria-valuenow="{{ $level }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
            @endforeach
        </div>


    </div>
    @endif
    <!-- end skill -->
</div>
