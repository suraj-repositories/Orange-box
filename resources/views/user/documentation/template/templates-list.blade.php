@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content files">
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Templates</h4>
                    </div>
                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.dashboard') }}">Dashboard</a></li>

                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />


                <div class="d-flex gap-2 flex-wrap tag-scroll-wrapper mb-3">
                    <a href="{{ authRoute('user.templates.index', ['type' => 'popular']) }}" target="_self"
                        class="tag-chip {{ $type == 'popular' ? 'tag-chip-primary' : '' }} ">
                        <i class='bx bx-medal'></i>
                        <span class="text-dark">Popular</span>
                    </a>
                    <a href="{{ authRoute('user.templates.index', ['type' => 'free']) }}" target="_self"
                        class="tag-chip {{ $type == 'free' ? 'tag-chip-primary' : '' }}">
                        <i class='bx bx-leaf'></i>
                        <span class="text-dark">Free Templates</span>
                    </a>
                    <a href="{{ authRoute('user.templates.index', ['type' => 'premium']) }}" target="_self"
                        class="tag-chip {{ $type == 'premium' ? 'tag-chip-primary' : '' }}">
                        <i class='bx bxs-star-half'></i>
                        <span class="text-dark">Premium</span>
                    </a>
                    <a href="{{ authRoute('user.templates.index', ['type' => 'my']) }}" target="_self"
                        class="tag-chip {{ $type == 'my' ? 'tag-chip-primary' : '' }}">
                        <i class="bx bx-user"></i>
                        <span class="text-dark">My Templates</span>
                    </a>
                    <a href="{{ authRoute('user.templates.index', ['type' => 'purchased']) }}" target="_self"
                        class="tag-chip {{ $type == 'purchased' ? 'tag-chip-primary' : '' }}">
                        <i class='bx bx-money-withdraw'></i>
                        <span class="text-dark">Most Sell</span>
                    </a>
                </div>

                @if (!$templates->isEmpty())
                    <x-user.docs.template-list :templates="$templates" />

                    <div class="mt-3">
                        {{ $templates->links() }}
                    </div>
                @else
                    <x-no-data message="Empty" />
                @endif


            </div>
        </div>

        @include('layout.components.copyright')
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/documentation-template.js') }}"></script>
@endsection
