<div class="border-top pt-2 mt-2">
    <div class="d-flex align-items-start gap-2">

        <img src="{{ $review->user->avatar_url }}" alt="" class="avatar avatar-sm rounded-circle flex-shrink-0">

        <div class="flex-grow-1">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                <strong>{{ $review->user->username }}</strong>

                <small class="text-warning">
                    @for ($i = 0; $i < 5; $i++)
                        @if ($i < ($review->rating ?? 0))
                            <i class="bi bi-star-fill star"></i>
                        @else
                            <i class="bi bi-star star"></i>
                        @endif
                    @endfor
                </small>
            </div>

            <p class="text-muted small mb-0">
                {{ $review->comment }}
            </p>

            @if ($review->user_id == Auth::id())
                <div class="mt-1">
                    <div class="action-container mt-0">
                        <form action="{{ route('ajax.doc-template=review.delete-reviews', ['review' => $review]) }}"
                            method="post" data-submit-type='ajax'>
                            @method('delete')
                            @csrf
                            <button type="submit" class="delete btn-no-style" data-loading-text="deleting...">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>
