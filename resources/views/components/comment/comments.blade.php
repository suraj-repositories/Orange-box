@forelse ($comments as $comment)
    <div class="comment-box">
        <div class="d-flex comment">
            <img class="rounded-circle comment-img" src="{{ $comment->user->profilePicture() }}" width="128"
                height="128">
            <div class="flex-grow-1 ms-3 text-break">
                <div class="mb-1"><a href="#" class="fw-bold link-body-emphasis me-1">
                        {{ $comment->user->name() }} </a> <i class="zmdi zmdi-check me-1 fw-bold" title="verified"></i>
                    <span class="text-body-secondary text-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="mb-1">{{ $comment->message }}</div>
                <div class="hstack align-items-center mb-0" style="margin-left:-.25rem;">
                    <button class="icon-btn me-1" href="#"><svg
                            class="svg-icon material-symbols-filled material-symbols-thumb_up" width="48"
                            height="48">
                            <use xlink:href="#google-thumb_up{{ $comment->likedBy(Auth::id()) ? '-fill' : '' }}"></use>
                        </svg></button>
                    <span class="me-3 small">
                        @php $likeCount = $comment->likesCount(); @endphp
                        {{ $likeCount > 0 ? $likeCount : 'Like' }}
                    </span>
                    <button class="icon-btn me-4"><svg
                            class="svg-icon material-symbols-outlined material-symbols-thumb_down" width="48"
                            height="48">
                            <use xlink:href="#google-thumb_down{{ $comment->dislikedBy(Auth::id()) ? '-fill' : '' }}">
                            </use>
                        </svg></button>
                    <button class="btn btn-sm btn-secondary rounded-pill small reply-btn"
                        data-ob-replyto="{{ $comment->user->name() }}"
                        data-ob-commentable-type=@json($commentable::class)
                        data-ob-commentable-id="{{ $commentable->id }}"
                        data-ob-parent-id="{{ $comment->id }}">Reply</button>
                    @if ($comment->user->id == auth()->id())
                        <button data-comment-id="{{ $comment->id }}"
                            onclick="deleteCommentBtnClick(this, this.dataset.commentId)"
                            class="btn btn-sm btn-danger rounded-pill small">Delete</button>
                    @endif
                </div>
                @php $totalReplies = $comment->totalTopLevelReplies(); @endphp

                @if ($totalReplies > 0)
                    <div style="margin-left:-.769rem;">
                        <button class="btn btn-primary rounded-pill d-inline-flex align-items-center collapsed"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->iteration }}"
                            aria-expanded="true" aria-controls="collapse-{{ $loop->iteration }}">
                            <i class="chevron-down zmdi zmdi-chevron-down fs-4 me-2"></i>
                            <i class="chevron-up zmdi zmdi-chevron-up fs-4 me-2"></i>
                            <span><span class="replies_count">{{ $totalReplies }}</span> replies</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        <div class="collapse" id="collapse-{{ $loop->iteration }}" style="">
            <div class="comment-replies vstack gap-3 mt-1 bg-body-tertiary p-3 ps-4 pt-2 rounded-3">

                <x-comment.comment-reply :commentable="$commentable" :comment="$comment" :replies="$comment->topLevelReplies()->take(5)->get()" />

                @if ($comment->totalTopLevelReplies() > 5)
                    <button data-ob-commentable-type="{{ $commentable::class }}"
                        data-ob-commentable-id="{{ $commentable->id }}" data-ob-comment-id="{{ $comment->id }}"
                        data-ob-page="2" onclick="loadReplyBtnClick(this)"
                        class="btn btn-primary load-more-replies-btn loading-btn px-2 btn-sm rounded-pill w-fit ms-4">
                        <span class="spinner-border spinner-border-sm " aria-hidden="true"></span>
                        <span class="btn-text" role="status">Load More</span>
                    </button>
                @endif
            </div>
        </div>

    </div>
@empty
    <x-no-data />
@endforelse
