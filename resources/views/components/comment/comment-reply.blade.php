@foreach ($replies as $reply)
    <div class="d-flex comment-reply ps-4"
        @if (!empty($is_newly_created)) data-ob-is-newly-created="true" data-ob-new-identification-key="{{ $reply->id }}" @endif>
        <img class="rounded-circle comment-img" src="{{ $reply->user->profilePicture() }}" width="128" height="128">
        <div class="flex-grow-1 ms-2 text-break">
            <div class="mb-1"><a href="#" class="fw-bold link-body-emphasis pe-1">{{ $reply->user->name() }}</a>
                <span class="text-body-secondary text-nowrap">{{ $reply->created_at->diffForHumans() }}</span>
            </div>

            <div class="mb-1"><i
                    class="text-primary">{{ !empty($reply->parent_id) ? '@' . $reply->parent->user->name() : '' }}</i>
                {{ $reply->message }} </div>
            <div class="hstack align-items-center" style="margin-left:-.25rem;">
                <button class="icon-btn comment-like-btn {{ $reply->likedBy(Auth::id()) ? 'filled' : '' }}"
                    onclick="toggleCommentReaction(this, {{ $reply->id }}, 'like')">
                    <svg class="svg-icon material-symbols-outlined material-symbols-thumb_up active" width="48"
                        height="48">
                        <use xlink:href="#google-thumb_up-fill"></use>
                    </svg>
                    <svg class="svg-icon material-symbols-outlined material-symbols-thumb_up inactive" width="48"
                        height="48">
                        <use xlink:href="#google-thumb_up"></use>
                    </svg>
                </button>
                <span class="me-3 small comment-like-count">
                    @php $likeCount = $reply->likesCount(); @endphp
                    {{ $likeCount > 0 ? $likeCount : 'Like' }}
                </span>
                <button class="icon-btn comment-dislike-btn me-2 {{ $reply->dislikedBy(Auth::id()) ? 'filled' : '' }}"
                    onclick="toggleCommentReaction(this, {{ $reply->id }}, 'dislike')">
                    <svg class="svg-icon material-symbols-outlined material-symbols-thumb_down active" width="48"
                        height="48">
                        <use xlink:href="#google-thumb_down-fill"></use>
                    </svg>
                    <svg class="svg-icon material-symbols-outlined material-symbols-thumb_down inactive" width="48"
                        height="48">
                        <use xlink:href="#google-thumb_down"></use>
                    </svg>
                </button>
                <button data-ob-replyto="{{ $reply->user->name() }}"
                    data-ob-commentable-type=@json($commentable::class)
                    data-ob-commentable-id="{{ $commentable->id }}" data-ob-parent-id="{{ $reply->id }}"
                    class="btn btn-sm btn-secondary rounded-pill small reply-btn">Reply</button>
                @if ($reply->user->id == auth()->id())
                    <button data-comment-id="{{ $reply->id }}"
                        onclick="deleteReplyBtnClick(this, this.dataset.commentId)"
                        class="btn btn-sm btn-danger rounded-pill small">Delete</button>
                @endif
            </div>
        </div>
    </div>
@endforeach
