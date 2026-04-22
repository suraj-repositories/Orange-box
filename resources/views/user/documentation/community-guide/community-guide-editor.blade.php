@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/editorjs-custom.css') }}">
@endsection

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-xxl">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title }} </h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentations</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.document.pages.index', ['documentation' => $documentation]) }}">
                                    Page Editor
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <form action="{{ authRoute('user.documentation.community-guide.save', ['document' => $document]) }}"
                            id="saveCommunityGuideForm" method="POST">
                            @csrf
                            <div class="card">
                                <div
                                    class="card-header p-2 d-flex align-items-center gap-2 justify-content-between flex-wrap">

                                    <h6 class="mb-0">{{ $title }}</h6>

                                    <!-- Main action buttons -->
                                    <input type="hidden" name="submit_status" value="publish">
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="checkbox" class="btn-check" id="previewToggleCheckbox"
                                            autocomplete="off">
                                        <label class="btn btn-outline-dark" for="previewToggleCheckbox"><i
                                                class="bi bi-file-earmark-richtext me-1"></i> Preview</label><br>

                                        <button type="submit" class="btn btn-primary"><i class="bi bi-send-check me-1"></i>
                                            Publish</button>

                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="editorjs-editor" data-ob-submit-form="#saveCommunityGuideForm"
                                        data-ob-image-upload-url="{{ authRoute('user.documentation.document.editor.images.store') }}"
                                        data-ob-preview-toggle-checkbox="#previewToggleCheckbox"
                                        data-ob-cacheable-id="syntax-store-editorjs"
                                        @if (!empty($document)) data-ob-content='{{ $document->content }}' @endif
                                        data-ob-fetch-online-media-url="{{ authRoute('user.documentation.document.editor.fetch-url-media') }}"
                                        data-ob-fetch-data-url="{{ authRoute('user.documentation.document.editor.fetch-url-data') }}">
                                    </div>
                                    <pre id="output"></pre>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        @include('layout.components.copyright')
    </div>
@endsection

@section('js')

@endsection
