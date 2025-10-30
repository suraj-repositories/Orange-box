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
                                                    <span class="fs-15"><i class="mdi mdi-message me-2 align-middle"></i>Speaks: <span>English <span class="badge badge-soft-dark text-light px-2 py-1 fs-13 fw-normal">native</span> , Bitish, Turkish </span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link p-2 {{ (request('tab') == 'about' || !request()->has('tab')) ? 'active' : '' }}" id="about_tab" data-bs-toggle="tab" href="#tab_about" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                                    <span class="d-none d-sm-block">About</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2 {{ request('tab') == 'experience' ? 'active' : '' }}" id="experience_tab" data-bs-toggle="tab" href="#tab_experience" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-sitemap-outline"></i></span>
                                                    <span class="d-none d-sm-block">Work experience</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2 {{ request('tab') == 'education' ? 'active' : '' }}" id="education_tab" data-bs-toggle="tab" href="#tab_education" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-school-outline"></i></span>
                                                    <span class="d-none d-sm-block">Education</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2 {{ request('tab') == 'setting' ? 'active' : '' }}" id="setting_tab" data-bs-toggle="tab" href="#tab_setting" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-cog-outline"></i></span>
                                                    <span class="d-none d-sm-block">Setting</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link p-2 {{ request('tab') == 'locker' ? 'active' : '' }}" id="locker_tab" data-bs-toggle="tab" href="#tab_locker" role="tab">
                                                    <span class="d-block d-sm-none"><i class="mdi mdi-lock-outline"></i></span>
                                                    <span class="d-none d-sm-block">Password Locker</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content text-muted bg-white">
                                            <div class="tab-pane pt-4 {{ (request('tab') == 'about' || !request()->has('tab')) ? 'active show' : '' }}" id="tab_about" role="tabpanel">
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

                                            </div>

                                            <div class="tab-pane pt-4 {{ request('tab') == 'experience' ? 'active show' : '' }}" id="tab_experience" role="tabpanel">
                                               <x-account.work-experience-component :experiences="$experiences"/>
                                            </div>

                                            <div class="tab-pane pt-4 {{ request('tab') == 'education' ? 'active show' : '' }}" id="tab_education" role="tabpanel">
                                                <x-account.education-component :educations="$educations"/>
                                            </div>

                                            <div class="tab-pane pt-4 {{ request('tab') == 'setting' ? 'active show' : '' }}" id="tab_setting" role="tabpanel">
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
                                            </div>

                                             <div class="tab-pane {{ request('tab') == 'locker' ? 'active show' : '' }}" id="tab_locker" role="tabpanel">
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
  <script src="{{asset('assets/js/pages/education.js')}}"></script>
@endsection
