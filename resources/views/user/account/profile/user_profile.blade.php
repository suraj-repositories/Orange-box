@extends('user.layout.layout')

@section('title',
    Route::is('user.profile.index') ? 'My Profile': '🟢🟢🟢')



@section('content')
   <div class="content-page">
                <div class="content">
                    <!-- Start Content-->
                    <div class="container-xxl">
                        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-18 fw-semibold m-0">My Profile</h4>
                            </div>

                            <div class="text-end">
                                <ol class="breadcrumb m-0 py-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body">

                                        <div class="align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->profilePicture() }}" class="rounded-circle avatar-xxl img-thumbnail float-start" alt="image profile">

                                                <div class="overflow-hidden ms-4">
                                                    <h4 class="m-0 text-dark fs-20">{{ $user->name() }}</h4>
                                                    <p class="my-1 text-muted fs-16">Passionate Software Engineer Crafting Innovative Solutions</p>
                                                    <span class="fs-15"><i class="mdi mdi-message me-2 align-middle"></i>Speaks: <span>English <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-13 fw-normal">native</span> , Bitish, Turkish </span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active p-2" id="profile_about_tab" data-bs-toggle="tab" href="#profile_about" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                                    <span class="d-none d-sm-block">About</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2" id="profile_experience_tab" data-bs-toggle="tab" href="#profile_experience" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-sitemap-outline"></i></span>
                                                    <span class="d-none d-sm-block">Work experience</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2" id="portfolio_education_tab" data-bs-toggle="tab" href="#profile_education" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-school-outline"></i></span>
                                                    <span class="d-none d-sm-block">Education</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2" id="setting_tab" data-bs-toggle="tab" href="#profile_setting" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-cog-outline"></i></span>
                                                    <span class="d-none d-sm-block">Setting</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2" id="locker_tab" data-bs-toggle="tab" href="#locker_tab_body" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-lock-outline"></i></span>
                                                    <span class="d-none d-sm-block">Password Locker</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content text-muted bg-white">
                                            <div class="tab-pane active show pt-4" id="profile_about" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-md-6 mb-4">
                                                        <div class="">
                                                            <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">About me</h5>
                                                            <p>Geetings, fellow software enthusiasts! I'm thrilled to see your intereset in exploring my profile. I'm Christian Mayo,
                                                                a 24-year-old software engineer from the United Kingdom. My educational path led me to earn a Bachelor's Degeer in Computer Science,
                                                                specializing in Software Engineering. With this qualification, I'm equipped to dive into the world of coding and develooment,ready
                                                                to tackle exciting projects and contribute to cutting-edge technological advancement...
                                                            </p>
                                                        </div>

                                                        <div class="skills-details mt-3">
                                                            <h6 class="text-uppercase fs-13">Skills</h6>

                                                            <div class="d-flex flex-wrap gap-2">
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">User Interface</span>
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">User Experience</span>
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">Interaction Design </span>
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">3D Design</span>
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">Information Architecture</span>
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">User Research</span>
                                                                <span class="badge bg-light px-3 text-dark py-2 fw-semibold">Wireframing</span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-6 col-md-6 mb-4">
                                                        <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Contact Details</h5>

                                                        <div class="row">
                                                            <div class="col-md-4 col-sm-4 col-lg-4">
                                                                <div class="profile-email">
                                                                    <h6 class="text-uppercase fs-13">Email Addess</h6>
                                                                    <a href="#" class="text-primary fs-14 text-decoration-underline">zoyothemes@gmail.com</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-lg-4">
                                                                <div class="profile-email">
                                                                    <h6 class="text-uppercase fs-13">Social Media</h6>
                                                                    <ul class="social-list list-inline mt-0 mb-0">
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript: void(0);" class="social-item border-primary text-primary justify-content-center align-content-center"><i class="mdi mdi-facebook fs-14"></i></a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript: void(0);" class="social-item border-danger text-danger justify-content-center align-content-center"><i class="mdi mdi-google fs-14"></i></a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript: void(0);" class="social-item border-info text-info justify-content-center align-content-center"><i class="mdi mdi-twitter fs-14"></i></a>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                            <a href="javascript: void(0);" class="social-item border-secondary text-secondary justify-content-center align-content-center"><i class="mdi mdi-github fs-14"></i></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-lg-4">
                                                                <div class="profile-email">
                                                                    <h6 class="text-uppercase fs-13">Location</h6>
                                                                    <a href="#" class="fs-14">Melbourne, Australia</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="skills-details mt-3">
                                                            <h6 class="text-uppercase fs-13">Fluent In</h6>

                                                            <div class="d-flex flex-wrap gap-2">
                                                                <span class="badge bg-light px-3 py-2 text-dark fw-semibold">English</span>
                                                                <span class="badge bg-light px-3 py-2 text-dark fw-semibold">Madrian</span>
                                                                <span class="badge bg-light px-3 py-2 text-dark fw-semibold">Spanish</span>
                                                                <span class="badge bg-light px-3 py-2 text-dark fw-semibold">French</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-md-6 mb-0">
                                                        <div class="">
                                                            <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Projects</h5>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="card border">
                                                                    <div class="card-body">
                                                                        <h4 class="m-0 fw-semibold text-dark fs-16">Website Developing</h4>
                                                                        <div class="row mt-2 d-flex align-items-center">
                                                                            <div class="col">
                                                                                <h5 class="fs-20 mt-1 fw-bold">$12,000</h5>
                                                                                <p class="mb-0 text-muted">Total Budget</p>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <a href="#" class="btn btn-sm btn-outline-dark px-3">More Details</a>
                                                                            </div>
                                                                        </div>
                                                                    </div><!--end card-body-->
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <div class="card border">
                                                                    <div class="card-body">
                                                                        <h4 class="m-0 fw-semibold text-dark fs-16">Algorithm Developing</h4>
                                                                        <div class="row mt-2 d-flex align-items-center">
                                                                            <div class="col">
                                                                                <h5 class="fs-20 mt-1 fw-bold">$35,800</h5>
                                                                                <p class="mb-0 text-muted">Total Budget</p>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <a href="#" class="btn btn-sm btn-outline-dark px-3">More Details</a>
                                                                            </div>
                                                                        </div>
                                                                    </div><!--end card-body-->
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <div class="card border mb-0">
                                                                    <div class="card-body ">
                                                                        <h4 class="m-0 fw-semibold text-dark fs-16">Digital Marketing</h4>
                                                                        <div class="row mt-2 d-flex align-items-center">
                                                                            <div class="col">
                                                                                <h5 class="fs-20 mt-1 fw-bold">$8,000</h5>
                                                                                <p class="mb-0 text-muted">Total Budget</p>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <a href="#" class="btn btn-sm btn-outline-dark px-3">More Details</a>
                                                                            </div>
                                                                        </div>
                                                                    </div><!--end card-body-->
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <div class="card border mb-0">
                                                                    <div class="card-body">
                                                                        <h4 class="m-0 fw-semibold text-dark fs-16">Mobile Developing</h4>
                                                                        <div class="row mt-2 d-flex align-items-center">
                                                                            <div class="col">
                                                                                <h5 class="fs-20 mt-1 fw-bold">$16,000</h5>
                                                                                <p class="mb-0 text-muted">Total Budget</p>
                                                                            </div>
                                                                            <div class="col-auto align-content-end">
                                                                                <a href="#" class="btn btn-sm btn-outline-dark px-3">More Details</a>
                                                                            </div>
                                                                        </div>
                                                                    </div><!--end card-body-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- end project -->

                                                    <div class="col-md-6 col-sm-6 col-md-6 mb-0">
                                                        <div class="">
                                                            <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Expertise</h5>
                                                        </div>

                                                        <div class="row align-items-center g-0">
                                                            <div class="col-sm-3">
                                                                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i> Photoshop </p>
                                                            </div>

                                                            <div class="col-sm-9">
                                                                <div class="progress mt-1" style="height: 8px;">
                                                                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: 72%" aria-valuenow="52" aria-valuemin="0" aria-valuemax="52">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center g-0 mt-3">
                                                            <div class="col-sm-3">
                                                                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i> illustrator </p>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <div class="progress mt-1" style="height: 8px;">
                                                                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="45">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center g-0 mt-3">
                                                            <div class="col-sm-3">
                                                                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i> HTML </p>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <div class="progress mt-1" style="height: 8px;">
                                                                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: 68%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="48">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center g-0 mt-3">
                                                            <div class="col-sm-3">
                                                                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i> CSS </p>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <div class="progress mt-1" style="height: 8px;">
                                                                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center g-0 mt-3">
                                                            <div class="col-sm-3">
                                                                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i> Javascript </p>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <div class="progress mt-1" style="height: 8px;">
                                                                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: 63%" aria-valuenow="63" aria-valuemin="0" aria-valuemax="63">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-center g-0 mt-3">
                                                            <div class="col-sm-3">
                                                                <p class="text-truncate mt-1 mb-0"><i class="mdi mdi-circle-medium text-primary me-2"></i> Php </p>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <div class="progress mt-1" style="height: 8px;">
                                                                    <div class="progress-bar progress-bar bg-primary rounded" role="progressbar" style="width: 48%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="48">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div><!-- end skill -->
                                                </div>

                                            </div><!-- end Experience -->

                                            <div class="tab-pane pt-4" id="profile_experience" role="tabpanel">
                                               <x-account.work-experience-component :experiences="$experiences"/>
                                            </div> <!-- end Experience -->

                                            <div class="tab-pane pt-4" id="profile_education" role="tabpanel">
                                                <div class="row">

                                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                                        <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">My Education</h5>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-4">
                                                            <ol class="profile-section list-unstyled mb-0 px-4">
                                                                <li class="profile-item">
                                                                    <div class="avatar-sm profile-icon p-1">
                                                                        <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512"><mask id="circleFlagsHausa0"><circle cx="256" cy="256" r="256" fill="#fff"/></mask><g mask="url(#circleFlagsHausa0)"><path fill="#eee" d="M0 0h512v512H0z"/><path fill="#6da544" d="m218 154l38-84l38 84l-140 140l-84-38l84-38l140 140l-38 84l-38-84l140-140l84 38l-84 38z"/><path fill="#333" d="M244.5 29.5c0 40.5-11.2 78.5-30.7 110.8l-49-49a45.1 45.1 0 0 0-63.7 0l-9.9 9.8a45.1 45.1 0 0 0 0 63.7l49.1 49a214.2 214.2 0 0 1-110.8 30.7v23c40.5 0 78.5 11.2 110.8 30.7l-49 49a45.1 45.1 0 0 0 0 63.7l9.8 9.9a45.1 45.1 0 0 0 63.7 0l49-49.1a214.2 214.2 0 0 1 30.7 110.8h23c0-40.5 11.2-78.5 30.7-110.8l49 49a45.1 45.1 0 0 0 63.7 0l9.9-9.8a45.1 45.1 0 0 0 0-63.7l-49.1-49a214.2 214.2 0 0 1 110.8-30.7v-23c-40.5 0-78.5-11.2-110.8-30.7l49-49a45.1 45.1 0 0 0 0-63.7l-9.8-9.9a45.1 45.1 0 0 0-63.7 0l-49 49.1a214.2 214.2 0 0 1-30.7-110.8zM256 92.2a233.8 233.8 0 0 0 27.7 62.6L256 182.5l-27.7-27.7A233.8 233.8 0 0 0 256 92.2M133 98a25 25 0 0 1 17.6 7.4l52 51.8a215.9 215.9 0 0 1-45.4 45.3l-51.8-51.9a24.7 24.7 0 0 1 0-35.3l9.9-10A25 25 0 0 1 133 98m246 0c6.4 0 12.8 2.4 17.7 7.4l10 9.9a24.7 24.7 0 0 1 0 35.3l-52 52a215.9 215.9 0 0 1-45.2-45.3l51.9-52A25 25 0 0 1 379 98m-162.3 73.5l25.2 25.1l-45.3 45.3l-25.2-25.2a236.7 236.7 0 0 0 45.3-45.3zm78.6 0a236.7 236.7 0 0 0 45.2 45.2l-25.1 25.2l-45.3-45.3zM256 210.6l45.3 45.3l-45.3 45.3l-45.3-45.3zm-101.2 17.6l27.7 27.7l-27.7 27.7A233.8 233.8 0 0 0 92.2 256a233.8 233.8 0 0 0 62.6-27.7zm202.4 0a233.8 233.8 0 0 0 62.6 27.7a233.8 233.8 0 0 0-62.6 27.7L329.5 256l27.7-27.7zM196.6 270l45.3 45.3l-25.2 25.1a236.7 236.7 0 0 0-45.3-45.2zm118.8 0l25.1 25.2a236.7 236.7 0 0 0-45.2 45.3l-25.2-25.2zm-158.1 39.4a215.9 215.9 0 0 1 45.2 45.3l-51.9 51.8a24.7 24.7 0 0 1-35.3 0l-10-9.9a24.7 24.7 0 0 1 0-35.3zm197.4 0l52 51.9a24.7 24.7 0 0 1 0 35.3l-10 10a24.7 24.7 0 0 1-35.3 0l-52-52a215.9 215.9 0 0 1 45.4-45.2zm-98.7 20l27.7 27.7a233.8 233.8 0 0 0-27.7 62.6a233.8 233.8 0 0 0-27.7-62.6z"/></g></svg>
                                                                        </div>
                                                                    </div>
                                                                    <div class="exper-item-list">
                                                                        <h5 class="fs-18 text-dark">Middles Earth Technic University</h5>
                                                                        <p class="mb-2 fw-semibold text-dark">Master Degree In Computer Science and Mathematies</p>
                                                                        <div class="list-inline list-inline-dots mb-2 fs-14">
                                                                            <div class="list-inline-item">January 2018</div>
                                                                            <div class="list-inline-item list-inline-item-second">Istanbul, Turkey</div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ol>
                                                        </div>

                                                        <div class="col-4">
                                                            <ol class="profile-section list-unstyled mb-0 px-4">
                                                                <li class="profile-item">
                                                                    <div class="avatar-sm profile-icon p-1">
                                                                        <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512"><mask id="circleFlagsCheckered0"><circle cx="256" cy="256" r="256" fill="#fff"/></mask><g mask="url(#circleFlagsCheckered0)"><path fill="#eee" d="M0 0h512v512H0z"/><path fill="#333" d="M384 0h128v128H0v128h512v128H0v128h128V0h128v512h128z"/></g></svg>
                                                                        </div>
                                                                    </div>
                                                                    <div class="exper-item-list">
                                                                        <h5 class="fs-16 text-dark">Bogazicied Technic University</h5>
                                                                        <p class="mb-2 fw-semibold text-dark">Bachelor Degree In Computer Science and Mathematies</p>
                                                                        <div class="list-inline list-inline-dots mb-2 fs-14">
                                                                            <div class="list-inline-item">June 2016</div>
                                                                            <div class="list-inline-item list-inline-item-second">Istanbul, Turkey</div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ol>
                                                        </div>

                                                        <div class="col-4">
                                                            <ol class="profile-section list-unstyled mb-0 px-4">
                                                                <li class="profile-item">
                                                                    <div class="avatar-sm profile-icon p-1">
                                                                        <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512"><mask id="circleFlagsUnitedNations0"><circle cx="256" cy="256" r="256" fill="#fff"/></mask><g mask="url(#circleFlagsUnitedNations0)"><path fill="#338af3" d="M0 0h512v512H0z"/><circle cx="256" cy="256" r="145" fill="#eee"/><circle cx="256" cy="256" r="111" fill="#338af3"/><path fill="#338af3" d="M76 76h360L256 256z"/><circle cx="256" cy="256" r="89" fill="#eee"/><circle cx="256" cy="256" r="69" fill="#338af3"/><path fill="#eee" d="M246 178h20v156h-20z"/><path fill="#eee" d="M334 246v20H178v-20z"/><path fill="#eee" d="m304 193.7l14.2 14.2l-110.3 110.3l-14.2-14.1z"/><path fill="#eee" d="m318.2 304l-14.1 14.2l-110.4-110.3l14.2-14.2z"/><circle cx="256" cy="256" r="44" fill="#eee"/><circle cx="256" cy="256" r="22" fill="#338af3"/><ellipse cx="256" cy="412" fill="#eee" rx="44" ry="40"/><path fill="#338af3" d="m256 407l-78 63h156z"/></g></svg>
                                                                        </div>
                                                                    </div>
                                                                    <div class="exper-item-list">
                                                                        <h5 class="fs-16 text-dark">Ascham School</h5>
                                                                        <p class="mb-2 fw-semibold text-dark">School Regular</p>
                                                                        <div class="list-inline list-inline-dots mb-2 fs-14">
                                                                            <div class="list-inline-item">February 2015</div>
                                                                            <div class="list-inline-item list-inline-item-second">Istanbul, Turkey</div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- end education -->

                                            <div class="tab-pane pt-4" id="profile_setting" role="tabpanel">
                                                <div class="row">

                                                    <div class="row">
                                                        <div class="col-lg-6 col-xl-6">
                                                            <div class="card border mb-0">

                                                                <div class="card-header">
                                                                    <div class="row align-items-center">
                                                                        <div class="col">
                                                                            <h4 class="card-title mb-0">Personal Information</h4>
                                                                        </div><!--end col-->
                                                                    </div>
                                                                </div>

                                                                <div class="card-body">
                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">First Name</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="text" value="Charles">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Last Name</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="text" value="Buncle">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Contact Phone</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
                                                                                <input class="form-control" type="text" placeholder="Phone" aria-describedby="basic-addon1" value="+61 399615">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Email Address</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                                                                <input type="text" class="form-control" value="CharlesBuncle@dayrep.com" placeholder="Email" aria-describedby="basic-addon1">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Company</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="text" value="zoyothemes">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">City</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="text" value="Adelaide">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Address</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="text" value="Australia">
                                                                        </div>
                                                                    </div>

                                                                </div><!--end card-body-->
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-xl-6">
                                                            <div class="card border mb-0">

                                                                <div class="card-header">
                                                                    <div class="row align-items-center">
                                                                        <div class="col">
                                                                            <h4 class="card-title mb-0">Change Password</h4>
                                                                        </div><!--end col-->
                                                                    </div>
                                                                </div>

                                                                <div class="card-body mb-0">
                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Old Password</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="password" placeholder="Old Password">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">New Password</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="password" placeholder="New Password">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mb-3 row">
                                                                        <label class="form-label">Confirm Password</label>
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <input class="form-control" type="password" placeholder="Confirm Password">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-lg-12 col-xl-12">
                                                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                                                            <button type="button" class="btn btn-danger">Cancel</button>
                                                                        </div>
                                                                    </div>

                                                                </div><!--end card-body-->
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div> <!-- end education -->

                                             <div class="tab-pane" id="locker_tab_body" role="tabpanel">
                                                <x-account.password-locker-component :passwords="$passwords" />
                                             </div>

                                        </div> <!-- Tab panes -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col fs-13 text-muted text-center">
                                &copy; <script>document.write(new Date().getFullYear())</script> - Made with <span class="mdi mdi-heart text-danger"></span> by <a href="#!" class="text-reset fw-semibold">Zoyothemes</a>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>
@endsection

@section('js')
  <script src="{{asset('assets/js/pages/password-locker.js')}}"></script>
  <script src="{{asset('assets/js/pages/work-experience.js')}}"></script>
@endsection
