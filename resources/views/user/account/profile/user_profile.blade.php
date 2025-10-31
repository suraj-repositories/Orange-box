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

                        <x-alert-component />

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
                                                @include('user.account.profile.about')
                                            </div>

                                            <div class="tab-pane pt-4 {{ request('tab') == 'experience' ? 'active show' : '' }}" id="tab_experience" role="tabpanel">
                                               <x-account.work-experience-component :experiences="$experiences"/>
                                            </div>

                                            <div class="tab-pane pt-4 {{ request('tab') == 'education' ? 'active show' : '' }}" id="tab_education" role="tabpanel">
                                                <x-account.education-component :educations="$educations"/>
                                            </div>

                                            <div class="tab-pane {{ request('tab') == 'setting' ? 'active show' : '' }}" id="tab_setting" role="tabpanel">
                                                <div class="row">

                                                    <div class="row g-3">
                                                        <div class="col-lg-6 col-xl-6">
                                                           @include('user.account.profile.personal_information_form')
                                                        </div>
                                                        <div class="col-lg-6 col-xl-6">
                                                           @include('user.account.profile.social_media_links_form')
                                                        </div>

                                                        <div class="col-lg-6 col-xl-6">
                                                            @include('user.account.profile.address_details_form')
                                                        </div>
                                                        <div class="col-lg-6 col-xl-6">
                                                            @include('user.account.profile.user_skill_form')
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
  <script src="{{ asset('assets/js/pages/user-profile.js') }}"></script>
@endsection
