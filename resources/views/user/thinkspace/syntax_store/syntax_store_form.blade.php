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
    <style>
        #editor {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* .ce-block img {
            max-width: 100%;
            height: auto;
            display: block;
        } */
    </style>
@endsection

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ empty($syntax) ? 'Create' : 'Edit' }} Syntax</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.syntax-store') }}">Syntax Store</a></li>
                            <li class="breadcrumb-item active">{{ empty($syntax) ? 'Create' : 'Edit' }}</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($syntax) ? 'Write' : 'Edit' }} Syntax</h5>
                            </div>

                            <div class="card-body">
                                <div id="editor" style="min-height: 300px; border:1px solid #ccc; padding:10px;"></div>
                                <button id="save-button" class="btn btn-primary mt-2">Save</button>
                                <pre id="output"></pre>
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
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/pages/syntax-store-editor.js') }}"></script>


@endsection
