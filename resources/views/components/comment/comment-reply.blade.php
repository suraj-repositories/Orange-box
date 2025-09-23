@foreach ($replies as $reply)
    <div class="d-flex ps-4" @if(!empty($is_newly_created)) data-ob-is-newly-created="true" data-ob-new-identification-key="{{ $reply->id }}" @endif>
        <img class="rounded-circle comment-img" src="{{ $reply->user->profilePicture() }}" width="128" height="128">
        <div class="flex-grow-1 ms-2">
            <div class="mb-1"><a href="#" class="fw-bold link-body-emphasis pe-1">{{ $reply->user->name() }}</a>
                <span class="text-body-secondary text-nowrap">{{ $reply->created_at->diffForHumans() }}</span>
            </div>
            <div class="mb-1"><i
                    class="text-primary">{{ $reply->parent_id != $comment->id ? '@' . $reply->parent->user->name() : '' }}</i>
                {{ $reply->message }} </div>
            <div class="hstack align-items-center" style="margin-left:-.25rem;">
                <button class="icon-btn me-1" href="#"><svg
                        class="svg-icon material-symbols-outlined material-symbols-thumb_up" width="48"
                        height="48">
                        <use xlink:href="#google-thumb_up{{ $reply->likedBy(Auth::id()) ? '-fill' : '' }}"></use>
                    </svg></button>
                <span class="me-3 small">
                    @php $likeCount = $reply->likesCount(); @endphp
                    {{ $likeCount > 0 ? $likeCount : 'Like' }}
                </span>
                <button class="icon-btn me-4" href="#"><svg
                        class="svg-icon material-symbols-outlined material-symbols-thumb_down" width="48"
                        height="48">
                        <use xlink:href="#google-thumb_down{{ $reply->dislikedBy(Auth::id()) ? '-fill' : '' }}"></use>
                    </svg></button>
                <button data-ob-replyto="{{ $reply->user->name() }}"
                    data-ob-commentable-type=@json($commentable::class)
                    data-ob-commentable-id="{{ $commentable->id }}" data-ob-parent-id="{{ $reply->id }}"
                    class="btn btn-sm btn-secondary rounded-pill small reply-btn">Reply</button>
            </div>
        </div>
    </div>
@endforeach
