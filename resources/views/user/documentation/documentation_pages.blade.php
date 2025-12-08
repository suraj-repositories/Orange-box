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
                                        <small class="text-muted" id="sizeIndicator"></small>

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
                                <div class="d-flex" id="splitter">
                                    @include('user.documentation.partials.explorer_sidebar')
                                    <div id="separator"> </div>
                                    <div class="p-3 w-100 page-form" id="page-content">
                                        <form action="#" method="POST" enctype="multipart/form-data"
                                            id="add-digest-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col col-12 col-md-12 mb-3">

                                                    <label for="git_link-input" class="form-label">Git Link</label>

                                                    <div class="d-flex">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="bi bi-git"></i>
                                                            </span>

                                                            <input type="text" class="form-control"
                                                                placeholder="Enter autofetch link" id="git_link-input"
                                                                name="git_link"
                                                                value="{{ !empty($thinkPad) ? $thinkPad->git_link : '' }}">
                                                        </div>

                                                        <a href="#" id="redirect-link" class="d-none"></a>
                                                        <button id="load" type="button"
                                                            class="btn bg-outline-primary border ms-1 p-0 center-content px-2 text-primary "
                                                            aria-label="Open Folder" >
                                                            <i class='bx bx-download fs-4'></i></button>
                                                    </div>
                                                </div>





                                                <div class="col col-12 col-md-12 ">
                                                    <label for="title-input" class="form-label">Content</label>

                                                    <textarea class="form-control summernote" name="description" id="editor" cols="30" rows="3"
                                                        data-image-save-url="{{ authRoute('user.think-pad.editor.images.store') }}">
                                                {!! !empty($thinkPad) ? $thinkPad->description : '' !!}
                                            </textarea>

                                                    @error('description')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="mt-2">
                                                    <button class="btn btn-primary mt-2" type="submit">Submit</button>
                                                </div>
                                            </div>
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
