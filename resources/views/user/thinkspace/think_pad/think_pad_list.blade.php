@extends('user.layout.layout')

@section('title', Route::is('user.think-pad') ? 'Think Pad' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Think Pad List</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Think Pad</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse ($thinkPads as $thinkPad)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card d-block">
                            <div class="card-header">
                                <a href="{{ authRoute('user.think-pad.show', ['thinkPad' => $thinkPad]) }}" class="card-title">{{ $thinkPad->title }}</a>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted mb-0">{{ $thinkPad->sub_title }}</p>


                                <div class="action-container">
                                    <div class="ago-string">
                                        {{ $thinkPad->created_at->diffForHumans() }}
                                    </div>

                                    <a href="{{ authRoute('user.think-pad.show', ['thinkPad' => $thinkPad]) }}"
                                        class="info">
                                        <i class='bx bx-info-circle'></i>
                                    </a>
                                    @can('update', $thinkPad)
                                        <a href="{{ authRoute('user.think-pad.edit', ['thinkPad' => $thinkPad]) }}"
                                            class="edit">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                    @endcan
                                    @can('delete', $thinkPad)
                                        <form action="{{ authRoute('user.think-pad.delete', ['thinkPad' => $thinkPad]) }}"
                                            method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="delete btn-no-style">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    @endcan
                                    {{-- <div class="more">
                                        <i class='bx bx-dots-vertical-rounded' ></i>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse


            </div>

            {{ $thinkPads->withQueryString()->links() }}

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>

    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>
@endsection
