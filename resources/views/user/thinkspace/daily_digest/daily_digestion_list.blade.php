@extends('user.layout.layout')

@section('title', Route::is('user.daily-digest') ? 'Daily Digest' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Daily Digestions List</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Daily Digest</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse ($digestions as $dailyDigest)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card d-block">
                            <div class="card-header">
                                <a href="{{ authRoute('user.daily-digest.show', ['dailyDigest' => $dailyDigest]) }}"
                                    class="card-title">{{ $dailyDigest->title }}</a>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted mb-0">{{ $dailyDigest->sub_title }}</p>


                                <div class="action-container">
                                    <div class="ago-string">
                                        {{ $dailyDigest->created_at->diffForHumans() }}
                                    </div>

                                    <a href="{{ authRoute('user.daily-digest.show', ['dailyDigest' => $dailyDigest]) }}"
                                        class="info">
                                        <i class='bx bx-info-circle'></i>
                                    </a>

                                    <a href="{{ authRoute('user.daily-digest.edit', ['dailyDigest' => $dailyDigest]) }}"
                                        class="edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form
                                        action="{{ authRoute('user.daily-digest.delete', ['dailyDigest' => $dailyDigest]) }}"
                                        method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="delete btn-no-style">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
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

            {{ $digestions->links() }}

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layout.components.copyright')
        <!-- end Footer -->

    </div>


    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/daily-digest.js') }}"></script>
@endsection
