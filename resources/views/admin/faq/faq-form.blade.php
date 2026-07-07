@extends('admin.layout.layout')

@section('title', empty($faq) ? 'Create Faq' : 'Edit Faq')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">FAQ's</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ authRoute('admin.faq.index') }}">Faqs</a>
                            </li>

                            <li class="breadcrumb-item active">{{ empty($faq) ? 'New' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>




                <div class="row">

                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($faq) ? 'Create' : 'Edit' }} Faq
                                </h5>
                            </div>

                            <div class="card-body">
                                <form
                                    action="{{ empty($faq) ? route('admin.faq.store') : route('admin.faq.update', ['faq' => $faq]) }}"
                                    method="POST">

                                    @csrf
                                    @if (!empty($faq))
                                        @method('PUT')
                                    @endif

                                    <div class="row">

                                        <!-- Question -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Question</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-question-octagon"></i>
                                                </span>
                                                <input type="text" name="question" class="form-control"
                                                    placeholder="Enter question"
                                                    value="{{ old('question', $faq->question ?? '') }}">
                                            </div>

                                            @error('question')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Answer -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Answer</label>

                                            <textarea name="answer" rows="3" class="form-control ckeditor" placeholder="Enter answer" data-markdown="{{ old('answer', $faq->answer ?? '') }}">{!! old('answer', $faq->answer ?? '') !!}</textarea>

                                            @error('answer')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Submit -->
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary" type="submit">
                                                {{ !empty($faq) ? 'Update' : 'Save' }}
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.components.copyright')

        @include('layout.extras.ckeditor5')

    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/admin/docs-templates.js') }}"></script>
@endsection
