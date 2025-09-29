@extends('user.layout.layout')

@section('title', Route::is('user.syntax-store.create')  ? 'Syntax'
    : (Route::is('user.syntax-store.edit')
    ? 'Edit Syntax'
    : '🟢🟢🟢'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/syntax-store-editor.css') }}">
@endsection

@section('content')

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
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

                <!-- General Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ empty($syntax) ? 'Write' : 'Edit' }} Syntax</h5>
                            </div>

                            <div class="card-body">

                                <ul class="ob-sortable" data-ob-sortable="true">
                                    <li class="ui-state-default">
                                        <div class="editor">
                                            <i class='bx bx-grid-vertical'></i>
                                            <header>
                                                Know
                                            </header>
                                            <textarea class="form-control" id="" cols="30" rows="1"></textarea>
                                        </div>
                                    </li>


                                </ul>

                            </div>
                        </div>
                    </div>
                </div>


            </div> <!-- container-fluid -->

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/pages/syntax-store-editor.js') }}"></script>
@endsection
