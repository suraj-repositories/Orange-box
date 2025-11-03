@extends('user.layout.layout')

@section('title', Route::is('user.think-pad.show') ? 'Think Pad' : '🟢🟢🟢')

@section('content')
    <div class="content-page">
        <div class="content">


            <div class="container-xxl">

                <div class="pt-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Think Pad</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.think-pad') }}">Think Pad List</a>
                            </li>
                            <li class="breadcrumb-item active">Show</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3 think-pad-show-card show-card">

                        <div class="card-body position-relative">

                            <div class="visibility-badge">
                                <span class="icon"><img src="{{ $thinkPad->visibility_icon }}" class="icon-20" alt="" title="{{ ucfirst( $thinkPad->visibility ??"") }}"></span>
                                <span class="text">{{ ucfirst( $thinkPad->visibility ??"") }}</span>
                            </div>

                            <div class="align-items-center">
                                <div class="d-flex flex-column flex-md-row align-items-center">
                                    @if ($thinkPad->emoji())
                                        <div
                                            class="rounded-circle avatar-xxl img-thumbnail float-start d-flex align-items-center">
                                            <div class="emoji">{{ $thinkPad->emoji->emoji }}</div>
                                        </div>
                                    @elseif($thinkPad->picture())
                                        <img src="http://ideas.free.nf/storage/profile/qbZVED4EfOwqn5vMAu92GszM8VmSrXmhGV3EBS92.png"
                                            class="rounded-circle avatar-xxl img-thumbnail float-start" alt="image profile">
                                    @else
                                        <div
                                            class="rounded-circle avatar-xxl img-thumbnail float-start d-flex align-items-center">
                                            <div class="emoji">{{ config('constants')['DEFAULT_DIGEST_EMOJI'] }}</div>
                                        </div>
                                    @endif



                                    <div class="overflow-hidden mt-3 mt-md-0 ms-md-4">
                                        <h4 class="m-0 text-dark fs-20">{{ $thinkPad->title }}</h4>
                                        <p class="my-1 text-muted fs-16">{{ $thinkPad->sub_title }}</p>
                                        <span class="fs-15">
                                            <button class="like-btn" data-think-pad-id='{{ $thinkPad->uuid }}'>
                                                <i
                                                    class="mdi {{ $thinkPad->likedBy(auth()->id()) ? 'mdi-thumb-up' : 'mdi-thumb-up-outline' }} me-1 align-middle"></i>
                                            </button>
                                            @php $likes = $thinkPad->likesCount(); @endphp
                                            <span id="like-count"
                                                data-think-pad-id='{{ $thinkPad->uuid }}'>{{ $likes > 0 ? $likes : 'Like' }}
                                            </span>

                                            <button class="dislike-btn" data-think-pad-id='{{ $thinkPad->uuid }}'>
                                                <i
                                                    class="mdi {{ $thinkPad->dislikedBy(auth()->id()) ? 'mdi-thumb-down' : 'mdi-thumb-down-outline' }} me-1 ms-1 align-middle"></i>
                                            </button>
                                            <span id="dislike-count">
                                                @php $dislikes = $thinkPad->dislikesCount(); @endphp
                                                {{ $dislikes > 0 ? $dislikes : 'Dislike' }}
                                            </span>
                                            <i
                                                class="mdi ms-2 fs-5 {{ $thinkPad->commentBy(Auth::id()) ? 'mdi-message' : 'mdi-message-outline' }} me-1 align-middle">
                                            </i>
                                            @php $totalCommnents = $thinkPad->totalCommentsCount(); @endphp
                                            <span
                                                class="total_comment_count">{{ $totalCommnents == 0 ? 'No comments' : $totalCommnents }}</span>
                                            <i class="mdi mdi-calendar-blank-outline ms-2 me-1 align-middle"> </i>

                                            <span>Since - <span
                                                    class="badge badge-soft-dark text-light px-2 py-1 fs-13 fw-normal">
                                                    {{ date('M d, Y', strtotime($thinkPad->created_at)) }}
                                                </span>
                                            </span></span>
                                    </div>
                                </div>
                            </div>

                            <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active p-2" id="ob_description_tab" data-bs-toggle="tab"
                                        href="#ob_description" role="tab">
                                        <span class="d-block d-sm-none"><i class="mdi mdi-information-outline"></i></span>
                                        <span class="d-none d-sm-block">Description</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link p-2" id="ob_comments_tab" data-bs-toggle="tab" href="#ob_comments"
                                        role="tab">
                                        <span class="d-block d-sm-none"><i class="mdi mdi-comment-outline"></i></span>
                                        <span class="d-none d-sm-block">Comments</span>
                                    </a>
                                </li>

                                @canany(['update', 'delete'], $thinkPad)
                                    <li class="nav-item">
                                        <a class="nav-link p-2" id="ob_actions_tab" data-bs-toggle="tab" href="#ob_actions"
                                            role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-cog-outline"></i></span>
                                            <span class="d-none d-sm-block">Actions</span>
                                        </a>
                                    </li>
                                @endcanany

                            </ul>

                            <div class="tab-content text-muted bg-white">
                                <div class="tab-pane active show pt-4" id="ob_description" role="tabpanel">
                                    <div id="description-area" class="rich-editor-content ">
                                        @if ($thinkPad->description)
                                            {!! $thinkPad->description !!}
                                        @endif
                                    </div>
                                    <br>
                                    <hr>
                                    <div
                                        class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-6 g-3 media-upload-preview image-cards card-view-container">

                                        @foreach ($media as $file)
                                            @if ($file['is_image'])
                                                <div class="col" data-media-files-index="0">
                                                    <div class="card h-100 border">
                                                        <div class="img-container">
                                                            <img src="{{ $file['file_path'] }}" alt="image">
                                                            <div class="hover-actions">
                                                                <a class="show" href="{{ $file['file_path'] }}"
                                                                    target="_blank" data-bs-toggle="tooltip"
                                                                    data-bs-title="View">
                                                                    <i class="bx bx-show-alt"></i>
                                                                </a>
                                                                <a href="javascript::void(0)" class="rename"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#{{ $file['file_id'] }}"
                                                                    title="Rename">
                                                                    <i class="bx bx-rename"></i>
                                                                </a>
                                                                <form class="delete"
                                                                    action="{{ route('file.delete', $file['file_id']) }}"
                                                                    data-bs-toggle="tooltip" data-bs-title="Delete"
                                                                    data-ob-dismiss="delete-card" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button class="delete"> <i
                                                                            class="bx bx-trash-alt"></i></button>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $file['file_name'] }}</h5>
                                                            <ul class="list-unstyled mb-0">
                                                                <li><span class="text-muted">Type:</span>
                                                                    {{ $file['extension'] }}</li>
                                                                <li><span class="text-muted">Size:</span>
                                                                    {{ $file['size'] }}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col" data-media-files-index="3">
                                                    <div class="card h-100 border">
                                                        <div class="file-thumb-holder">
                                                            <div class="file-thumb-box">
                                                                <i class="{{ $file['file_icon_class'] }}"></i>
                                                            </div>
                                                            <div class="hover-actions">
                                                                <a class="show" href="{{ $file['file_path'] }}"
                                                                    target="_blank" data-bs-toggle="tooltip"
                                                                    data-bs-title="View">
                                                                    <i class="bx bx-show-alt"></i>
                                                                </a>
                                                                <a href="javascript::void(0)" class="rename"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#{{ $file['file_id'] }}"
                                                                    title="Rename">
                                                                    <i class="bx bx-rename"></i>

                                                                </a>

                                                                <form class="delete"
                                                                    action="{{ route('file.delete', $file['file_id']) }}"
                                                                    data-bs-toggle="tooltip" data-bs-title="Delete"
                                                                    data-ob-dismiss="delete-card" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button class="delete"> <i
                                                                            class="bx bx-trash-alt"></i></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $file['file_name'] }}</h5>
                                                            <ul class="list-unstyled mb-0">
                                                                <li><span class="text-muted">Type:</span>
                                                                    {{ $file['extension'] }}</li>
                                                                <li><span class="text-muted">Size:</span>
                                                                    {{ $file['size'] }}</li>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endif

                                            <x-modals.rename-modal :modalId="$file['file_id']" :prevResourceName="$file['file_name']" :formActionUrl="route('file.rename', $file['file_id'])" />
                                        @endforeach
                                    </div>

                                </div>

                                <div class="tab-pane pt-2" id="ob_comments" role="tabpanel">
                                    <x-comment.comment-component :commentable="$thinkPad" />
                                </div>

                                <div class="tab-pane ob-actions-tab pt-4" id="ob_actions" role="tabpanel">
                                    <div class="d-flex gap-2 mb-2">
                                        @can('update', $thinkPad)
                                            <a href="{{ authRoute('user.think-pad.edit', ['thinkPad' => $thinkPad]) }}"
                                                class="action edit"><i class="bx bx-edit"></i></a>
                                        @endcan

                                        @can('delete', $thinkPad)
                                            <form action="{{ authRoute('user.think-pad.delete', ['thinkPad' => $thinkPad]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="action delete"><i class="bx bx-trash"></i></button>
                                            </form>
                                        @endcan
                                        {{-- <form action="">
                                            <button class="action make-private"><i class='bx bx-hide'></i>
                                        </form> --}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>



                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- content -->

    <!-- Footer Start -->
    @include('layout.components.copyright')
    <!-- end Footer -->


    <script src="{{ asset('assets/js/services/file-service.js') }}"></script>
    <script src="{{ asset('assets/js/pages/think-pad.js') }}"></script>
    <script src="{{ asset('assets/js/pages/comment.js') }}"></script>
@endsection
