<a class="trip-card" href="{{ route('view_hotel', $hotel->id) }}{{ isset($query) ? '?' . $query : '' }}">
  <div class="photo-frame">
    @if ($hotel->picture_url)
      <img src="{{ $hotel->picture_url }}" alt="{{ $hotel->name }}" loading="lazy" />
    @endif
    <span class="photo-name">{{ $hotel->city }}</span>
    @if ($hotel->roomTypes->count())
      <span class="price-tag">{{ $t["from"] }} ৳{{ number_format($hotel->roomTypes->min("base_price")) }}</span>
    @endif
  </div>
  <div class="trip-body">
    <h5>{{ $hotel->name }}</h5>
    <div class="stars small mb-1">{{ str_repeat("★", $hotel->stars) }}</div>
    <p class="text-muted mb-1">{{ $hotel->description }}</p>
    <div class="meta">{{ $hotel->city }} · {{ $hotel->roomTypes->count() }} {{ $t["room_types"] }}</div>
  </div>
</a>
