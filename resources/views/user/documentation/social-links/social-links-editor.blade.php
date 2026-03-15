@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentations</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.show.latest', ['documentation' => $documentation]) }}">{{ $documentation->title }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">

                                    <h5 class="card-title mb-0"> {{ $title }} </h5>


                                </div>
                            </div>

                            <div class="card-body">
                                <form
                                    action="{{ authRoute('user.documentation.social-links.update', ['documentation' => $documentation]) }}"
                                    method="POST">
                                    @csrf

                                    @foreach ($socialMediaPlatforms as $platform)
                                        <div class="form-group mb-3 row">
                                            <label class="form-label">{{ $platform->name }}</label>

                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="{{ $platform->icon }} fs-5"></i>
                                                    </span>

                                                    <input type="hidden" name="platform_id[]" value="{{ $platform->id }}">

                                                    <input type="text" class="form-control"
                                                        value="{{ $platform->doc_link ?? '' }}" name="social_media_link[]"
                                                        placeholder="{{ $platform->url }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="mt-3">
                                        <button class="btn btn-primary" type="submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>
        <!-- End content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>



@endsection
