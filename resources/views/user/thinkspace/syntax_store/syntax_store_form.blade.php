@extends('user.layout.layout')

@section('title',
    Route::is('user.syntax-store.create')
    ? 'Syntax'
    : (Route::is('user.syntax-store.edit')
    ? 'Edit
    Syntax'
    : '🟢🟢🟢'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/syntax-store-editor.css') }}">
@endsection

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($syntaxStore) ? 'Create' : 'Edit' }} Syntax</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.syntax-store') }}">Syntax Store</a></li>
                            <li class="breadcrumb-item active">{{ empty($syntaxStore) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="{{empty($syntaxStore) ? authRoute('user.syntax-store.store') : authRoute('user.syntax-store.update', ['syntaxStore' => $syntaxStore]) }}" id="saveSyntax" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header p-2 d-flex align-items-center gap-2">
                                    <input type="text" name="title" class="form-control border-0 fw-bold"
                                        value="{{ empty($syntaxStore) ? 'Untitled' : $syntaxStore->title }}" placeholder="Enter title" autocomplete="off">

                                    <!-- Main action buttons -->
                                    <input type="hidden" name="submit_status" value="publish">
                                    <div class="d-flex align-items-center gap-1">
                                        <!-- Preview button: more visible, with icon -->
                                        <input type="checkbox" class="btn-check" id="previewToggleCheckbox" autocomplete="off">
                                        <label class="btn btn-outline-dark" for="previewToggleCheckbox">Preview</label><br>

                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary">Publish</button>
                                            <button type="button"
                                                class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                                <i class="mdi mdi-chevron-down"></i>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item d-flex align-items-center" type="submit">
                                                        <i class='bx bx-save fs-5  me-1'></i> Save
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item d-flex align-items-center" type="submit">
                                                        <i class='bx bx-cloud-upload fs-5 me-1'></i>Save & Publish
                                                    </button>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="editorjs-editor" data-ob-submit-form="#saveSyntax"
                                        data-ob-image-upload-url="{{ authRoute('user.syntax-store.editor.images.store') }}"
                                        data-ob-preview-toggle-checkbox="#previewToggleCheckbox"
                                        data-ob-cacheable-id="syntax-store-editorjs"
                                        @if(!empty($syntaxStore))
                                        data-ob-content='{{ $syntaxStore->content }}'
                                        @endif
                                        data-ob-fetch-online-media-url="{{ authRoute('user.syntax-store.editor.fetch-url-media') }}"
                                        data-ob-fetch-data-url="{{ authRoute('user.syntax-store.editor.fetch-url-data') }}">
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
