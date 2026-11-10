<div class="trip-card">
  <a class="card-link" href="{{ route('view_hotel', $hotel->id) }}{{ isset($query) ? '?' . $query : '' }}">
    <div class="photo-frame">
      @if ($hotel->picture_url)
        <img src="{{ $hotel->picture_url }}" alt="{{ $hotel->name }}" loading="lazy" />
      @endif
      <span class="photo-name"><i class="fas fa-map-marker-alt"></i> {{ $hotel->city }}</span>
      @if ($hotel->roomTypes->count())
        <span class="price-tag">{{ $t["from"] }} ৳{{ number_format($hotel->roomTypes->min("base_price")) }}</span>
      @endif
    </div>
    <div class="trip-body">
      <h5>{{ $hotel->name }}</h5>
      <div class="d-flex align-items-center mb-1">
        <span class="stars small mr-2">{{ str_repeat("★", $hotel->stars) }}</span>
        @if ($hotel->reviews_count ?? 0)
          <span class="rating-pill">{{ number_format($hotel->reviews_avg_rating, 1) }}</span>
          <span class="meta ml-1">({{ $hotel->reviews_count }})</span>
        @endif
      </div>
      <p class="text-muted mb-1">{{ $hotel->description }}</p>
      <div class="meta">{{ $hotel->roomTypes->count() }} {{ $t["room_types"] }}</div>
    </div>
  </a>
  @include("_heart", ["type" => "hotel", "id" => $hotel->id])
</div>
