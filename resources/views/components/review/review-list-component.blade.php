@foreach ($reviews as $review)
    <x-review.review-component :review="$review" />
@endforeach
