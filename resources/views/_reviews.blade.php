<h4 class="mt-4">{{ $t["reviews"] }} @if ($reviews->count())<small class="stars">{{ number_format($reviews->avg("rating"), 1) }} ★</small>@endif</h4>
@forelse ($reviews as $review)
  <div class="card card-body mb-2">
    <div><strong>{{ $review->user->name }}</strong> <span class="stars">{{ str_repeat("★", $review->rating) }}</span></div>
    <div class="text-muted">{{ $review->comment }}</div>
  </div>
@empty
  <p class="text-muted">{{ $t["no_reviews"] }}</p>
@endforelse
