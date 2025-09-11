@forelse ($comments as $comment)
    <div class="comment-box">
        <div class="d-flex comment">
            <img class="rounded-circle comment-img" src="{{ $comment->user->profilePicture() }}" width="128"
                height="128">
            <div class="flex-grow-1 ms-3">
                <div class="mb-1"><a href="#" class="fw-bold link-body-emphasis me-1">
                        {{ $comment->user->name() }} </a> <i class="zmdi zmdi-check me-1 fw-bold" title="verified"></i>
                    <span class="text-body-secondary text-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="mb-1">{{ $comment->message }}</div>
                <div class="hstack align-items-center mb-0" style="margin-left:-.25rem;">
                    <button class="icon-btn me-1" href="#"><svg
                            class="svg-icon material-symbols-filled material-symbols-thumb_up" width="48"
                            height="48">
                            <use xlink:href="#google-thumb_up-fill"></use>
                        </svg></button>
                    <span class="me-3 small">55</span>
                    <button class="icon-btn me-4"><svg
                            class="svg-icon material-symbols-outlined material-symbols-thumb_down" width="48"
                            height="48">
                            <use xlink:href="#google-thumb_down"></use>
                        </svg></button>
                    <button class="btn btn-sm btn-secondary rounded-pill small reply-btn">Reply</button>
                    <button class="btn btn-sm btn-danger rounded-pill small">Delete</button>
                </div>
                @php $totalReplies = $comment->totalReplies(); @endphp

                @if($totalReplies > 0)
                    <div style="margin-left:-.769rem;">
                        <button class="btn btn-primary rounded-pill d-inline-flex align-items-center collapsed"
                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->iteration }}" aria-expanded="true"
                            aria-controls="collapse-{{ $loop->iteration }}">
                            <i class="chevron-down zmdi zmdi-chevron-down fs-4 me-2"></i>
                            <i class="chevron-up zmdi zmdi-chevron-up fs-4 me-2"></i>
                            <span>{{ $totalReplies }} replies</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>


        <div class="collapse" id="collapse-{{ $loop->iteration }}" style="">
            <div class="comment-replies vstack gap-3 mt-1 bg-body-tertiary p-3 rounded-3">

                @foreach ($comment->replies()->take(3)->get() as $reply)
                    <div class="d-flex">
                        <img class="rounded-circle comment-img" src="https://placehold.co/100/cc99ff/ffffff?text=S"
                            width="128" height="128">
                        <div class="flex-grow-1 ms-3">
                            <div class="mb-1"><a href="#"
                                    class="fw-bold link-body-emphasis pe-1">{{ $reply->user->name() }}</a>
                                <span
                                    class="text-body-secondary text-nowrap">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mb-2"> {{ $reply->message }} </div>
                            <div class="hstack align-items-center" style="margin-left:-.25rem;">
                                <button class="icon-btn me-1" href="#"><svg
                                        class="svg-icon material-symbols-outlined material-symbols-thumb_up"
                                        width="48" height="48">
                                        <use xlink:href="#google-thumb_up"></use>
                                    </svg></button>
                                <span class="me-3 small">1</span>
                                <button class="icon-btn me-4" href="#"><svg
                                        class="svg-icon material-symbols-outlined material-symbols-thumb_down"
                                        width="48" height="48">
                                        <use xlink:href="#google-thumb_down"></use>
                                    </svg></button>
                                <button class="btn btn-sm btn-secondary rounded-pill small reply-btn">Reply</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@empty
    <x-no-data />
@endforelse
