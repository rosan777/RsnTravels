<div class="trip-card">
  <a class="card-link" href="{{ route('view_package', $package->id) }}">
    <div class="photo-frame">
      @if ($package->picture_url)
        <img src="{{ $package->picture_url }}" alt="{{ $package->name }}" loading="lazy" />
      @endif
      <span class="photo-name"><i class="fas fa-map-marker-alt"></i> {{ $package->destination->name }}</span>
      <span class="price-tag">৳{{ number_format($package->price) }}</span>
    </div>
    <div class="trip-body">
      <h5>{{ $package->name }}</h5>
      <div class="d-flex align-items-center mb-1">
        <span class="meta mr-2"><i class="far fa-clock"></i> {{ $package->days }} {{ $t["days"] }}</span>
        @if ($package->reviews_count ?? 0)
          <span class="rating-pill">{{ number_format($package->reviews_avg_rating, 1) }}</span>
          <span class="meta ml-1">({{ $package->reviews_count }})</span>
        @endif
      </div>
      <p class="text-muted mb-1">{{ $package->description }}</p>
      <div class="meta">{{ $package->destination->country }} · <span class="chip">{{ ucfirst($package->interest) }}</span></div>
    </div>
  </a>
  @include("_heart", ["type" => "package", "id" => $package->id])
</div>
