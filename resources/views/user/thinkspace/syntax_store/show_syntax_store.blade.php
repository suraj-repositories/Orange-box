@extends('user.layout.layout')

@section('title', Route::is('user.syntax-store.show') ? 'Syntax Store' : '🟢🟢🟢')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/css/syntax-store-editor.css') }}">
@endsection

@section('content')
    <div class="content-page">
        <div class="content">


            <div class="container-xxl">

                <div class="pt-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Syntax Store</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.syntax-store') }}">Syntax Store List</a>
                            </li>
                            <li class="breadcrumb-item active">Show</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3 syntax-store-show-card show-card">

                        <div class="card-body">

                            <div class="align-items-center">
                                <div class="d-flex flex-column flex-md-row align-items-center">

                                    <svg class="rounded-circle avatar-xxl img-thumbnail float-start"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="m7 8l-4 4l4 4m10-8l4 4l-4 4M14 4l-4 16" />
                                    </svg>


                                    <div class="overflow-hidden mt-3 mt-md-0 ms-md-4">
                                        <h4 class="m-0 text-dark fs-20">{{ $syntaxStore->title }}</h4>
                                        <p class="my-1 text-muted fs-16">{{ $syntaxStore->preview_text }}</p>
                                        <span class="fs-15">
                                            <button class="like-btn" data-syntax-store-id='{{ $syntaxStore->uuid }}'>
                                                <i
                                                    class="mdi {{ $syntaxStore->likedBy(auth()->id()) ? 'mdi-thumb-up' : 'mdi-thumb-up-outline' }} me-1 align-middle"></i>
                                            </button>
                                            @php $likes = $syntaxStore->likesCount(); @endphp
                                            <span id="like-count"
                                                data-syntax-store-id='{{ $syntaxStore->uuid }}'>{{ $likes > 0 ? $likes : 'Like' }}
                                            </span>

                                            <button class="dislike-btn" data-syntax-store-id='{{ $syntaxStore->uuid }}'>
                                                <i
                                                    class="mdi {{ $syntaxStore->dislikedBy(auth()->id()) ? 'mdi-thumb-down' : 'mdi-thumb-down-outline' }} me-1 ms-1 align-middle"></i>
                                            </button>
                                            <span id="dislike-count">
                                                @php $dislikes = $syntaxStore->dislikesCount(); @endphp
                                                {{ $dislikes > 0 ? $dislikes : 'Dislike' }}
                                            </span>
                                            <i
                                                class="mdi ms-2 fs-5 {{ $syntaxStore->commentBy(Auth::id()) ? 'mdi-message' : 'mdi-message-outline' }} me-1 align-middle">
                                            </i>
                                            @php $totalCommnents = $syntaxStore->totalCommentsCount(); @endphp
                                            <span
                                                class="total_comment_count">{{ $totalCommnents == 0 ? 'No comments' : $totalCommnents }}</span>
                                            <i class="mdi mdi-calendar-blank-outline ms-2 me-1 align-middle"> </i>

                                            <span>Since - <span
                                                    class="badge bg-primary-subtle text-primary px-2 py-1 fs-13 fw-normal">
                                                    {{ date('M d, Y', strtotime($syntaxStore->created_at)) }}
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
                                <li class="nav-item">
                                    <a class="nav-link p-2" id="ob_actions_tab" data-bs-toggle="tab" href="#ob_actions"
                                        role="tab">
                                        <span class="d-block d-sm-none"><i class="mdi mdi-cog-outline"></i></span>
                                        <span class="d-none d-sm-block">Actions</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content text-muted bg-white">
                                <div class="tab-pane active show pt-4" id="ob_description" role="tabpanel">
                                    <div id="description-area" class="rich-editor-content ">
                                        @if ($syntaxStore->content)
                                            <div data-ob-preview-type="editorjs" data-ob-content="{{ $syntaxStore->content }}">

                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane pt-2" id="ob_comments" role="tabpanel">
                                    <x-comment.comment-component :commentable="$syntaxStore" />
                                </div>

                                <div class="tab-pane ob-actions-tab pt-4" id="ob_actions" role="tabpanel">
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ authRoute('user.syntax-store.edit', ['syntaxStore'=>$syntaxStore]) }}"
                                            class="action edit"><i class="bx bx-edit"></i></a>
                                        <form
                                            action="{{ authRoute('user.syntax-store.delete', ['syntaxStore' => $syntaxStore]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="action delete"><i class="bx bx-trash"></i></button>
                                        </form>

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


@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/syntax-store.js') }}"></script>
    <script src="{{ asset('assets/js/pages/comment.js') }}"></script>
@endsection
