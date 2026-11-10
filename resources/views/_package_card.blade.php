<a class="trip-card" href="{{ route('view_package', $package->id) }}">
  <div class="photo-frame">
    @if ($package->picture_url)
      <img src="{{ $package->picture_url }}" alt="{{ $package->name }}" loading="lazy" />
    @endif
    <span class="photo-name">{{ $package->destination->name }}</span>
    <span class="price-tag">৳{{ number_format($package->price) }}</span>
  </div>
  <div class="trip-body">
    <h5>{{ $package->name }}</h5>
    <p class="text-muted mb-1">{{ $package->description }}</p>
    <div class="meta">{{ $package->days }} {{ $t["days"] }} · {{ $package->destination->country }} · <span class="chip">{{ ucfirst($package->interest) }}</span></div>
  </div>
</a>
