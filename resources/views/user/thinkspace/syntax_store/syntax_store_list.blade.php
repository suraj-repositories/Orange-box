@extends('user.layout.layout')

@section('title', Route::is('user.syntax-store') ? 'Syntax Store' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Syntax Store</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Syntax Store</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @forelse ($items as $item)
                    <div class="col-sm-6 col-lg-6">
                        <div class="card d-flex flex-column h-100">
                            <div class="card-header border-0 pb-0">
                                <a href="{{ authRoute('user.syntax-store.show', ['syntaxStore'=>$item]) }}" class="card-title">{{ $item->title }}</a>
                            </div>

                            <div class="card-body flex-grow-1 pb-0 mb-0">
                                <p class="card-text text-muted mb-0 ob-text-wrap">{{ $item->preview_text }}</p>
                            </div>

                            <div class="card-footer mt-auto">
                                <div class="action-container  mt-0 d-flex justify-content-between align-items-center">
                                    <div class="ago-string">
                                        <strong>Created : </strong> {{ $item->created_at->diffForHumans() }}<br>
                                        <strong>Last Updated : </strong>{{ $item->created_at->diffForHumans() }}
                                    </div>


                                    <div class="action-buttons d-flex gap-1 h-100 align-items-bottom">
                                        <a href="{{ authRoute('user.syntax-store.show', ['syntaxStore'=>$item]) }}" class="info">
                                            <i class='bx bx-info-circle'></i>
                                        </a>
                                        <a href="{{ authRoute('user.syntax-store.edit', ['syntaxStore'=>$item]) }}" class="edit">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <form action="{{ authRoute('user.syntax-store.delete', ['syntaxStore'=>$item]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="delete btn-no-style">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <x-no-data />
                @endforelse
        </div>

    </div>

    <!-- Footer Start -->
    @include('layout.components.copyright')
    <!-- end Footer -->

    </div>


@endsection

