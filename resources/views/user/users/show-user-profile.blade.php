@extends('user.layout.layout')

@section('title', Route::is('users.profile.index') ? 'Profile' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-xxl">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Profile</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">

                                <div class="align-items-center">
                                    <div
                                        class="d-flex align-items-center flex-wrap gap-4 justify-content-center justify-content-sm-start text-center text-sm-start">
                                        <div class="position-relative">
                                            <img src="{{ $user->profilePicture() }}"
                                                class="user_profile_picture rounded-circle avatar-xxl img-thumbnail float-start"
                                                alt="image profile">

                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="m-0 text-dark fs-20">{{ $user->fullname() }} <small
                                                    class="text-primary text-italic">{{ '@' . $user->username }}</small>
                                            </h4>
                                            <p class="my-1 text-muted fs-16">{{ $user->details?->tag_line ?? '' }}</p>


                                            @if ($address)
                                                <span class="fs-15">
                                                    <i class="mdi mdi-map-marker-outline fs-5 me-1 align-middle"></i>
                                                    <span>{{ $address->city }} {{ $address->state }}
                                                        {{ $address->country }}</span>
                                                </span>
                                            @endif
                                            @if (!empty($experties) && count($experties) > 0)
                                                <div class="fs-15"><span
                                                        class="mdi mdi-book-open-page-variant-outline fs-5 me-1"></span>
                                                    <span>
                                                        <strong>Expertise In : </strong>
                                                        {{ $experties->keys()->implode(', ') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link p-2 {{ request('tab') == 'about' || !request()->has('tab') ? 'active' : '' }}"
                                            id="about_tab" data-bs-toggle="tab" href="#tab_about" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                            <span class="d-none d-sm-block">About</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2 {{ request('tab') == 'experience' ? 'active' : '' }}"
                                            id="experience_tab" data-bs-toggle="tab" href="#tab_experience" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-sitemap-outline"></i></span>
                                            <span class="d-none d-sm-block">Work experience</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2 {{ request('tab') == 'education' ? 'active' : '' }}"
                                            id="education_tab" data-bs-toggle="tab" href="#tab_education" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-school-outline"></i></span>
                                            <span class="d-none d-sm-block">Education</span>
                                        </a>
                                    </li>

                                </ul>

                                <div class="tab-content text-muted bg-white">
                                    <div class="tab-pane pt-4 {{ request('tab') == 'about' || !request()->has('tab') ? 'active show' : '' }}"
                                        id="tab_about" role="tabpanel">
                                        @include('user.account.profile.about')
                                    </div>

                                    <div class="tab-pane pt-4 {{ request('tab') == 'experience' ? 'active show' : '' }}"
                                        id="tab_experience" role="tabpanel">
                                        <x-account.work-experience-component :experiences="$experiences" />
                                    </div>

                                    <div class="tab-pane pt-4 {{ request('tab') == 'education' ? 'active show' : '' }}"
                                        id="tab_education" role="tabpanel">
                                        <x-account.education-component :educations="$educations" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->

        @include('layout.components.copyright')

    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/work-experience.js') }}"></script>
    <script src="{{ asset('assets/js/pages/education.js') }}"></script>
    <script src="{{ asset('assets/js/pages/user-profile.js') }}"></script>
@endsection
