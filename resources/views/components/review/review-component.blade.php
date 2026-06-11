<div class="border-bottom pb-3 mb-3">
    <div class="d-flex justify-content-between">
        <strong>{{ $review->user->username }}</strong>
        <small class="text-warning">
            @for ($i = 0; $i < 5; $i++)
                @if ($i < ($review->rating ?? 0))
                    ★
                @else
                    ☆
                @endif
            @endfor
        </small>
    </div>
    <p class="text-muted small mb-0">
        {{ $review->comment }}
    </p>

    @if ($review->user_id == Auth::id())
        <div class="mt-2">
            <form action="{{ route('ajax.doc-template=review.delete-reviews', ['review' => $review]) }}" method="post"
                data-submit-type='ajax'>
                @method('delete')
                @csrf
                <button class="btn btn-danger btn-sm">Delete</button>
            </form>
        </div>
    @endif
</div>
