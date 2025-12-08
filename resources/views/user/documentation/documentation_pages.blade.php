@extends('user.layout.layout')
@section('title', 'Pages')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="page-bredcrumb py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Documentation Pages</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ authRoute('user.documentation.index') }}">Documentation</a></li>

                            <li class="breadcrumb-item active">Pages</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card content-card">

                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($documentation->logo) }}"
                                        class="avatar avatar-sm img-fluid rounded-2 me-2" aria-label="tet">

                                    <h5 class="card-title mb-0">{{ $documentation->title }}</h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">
                                        <div class="btn-group" role="group" aria-label="Full Screen Toggle">
                                            <input type="checkbox" class="btn-check" id="toggleScreenType"
                                                autocomplete="off">
                                            <label class="btn btn-outline-dark btn-sm border center-content gap-1"
                                                for="toggleScreenType">
                                                <i class="bi bi-fullscreen me-1"></i>
                                                <div>Full Screen</div>
                                            </label>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="d-flex">
                                    @include('user.documentation.partials.explorer_sidebar')

                                    <div class="p-3 w-100 page-form">
                                        <form>
                                            <div class="mb-3">
                                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                                <input type="email" class="form-control" id="exampleInputEmail1"
                                                    aria-describedby="emailHelp">
                                                <div id="emailHelp" class="form-text">We'll never share your email with
                                                    anyone else.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="exampleInputPassword1">
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
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
    <script src="{{ asset('assets/js/pages/documentation-pages.js') }}"></script>
@endsection
