@extends("base")

@section("content")
  <div class="container py-4">
    <div class="page-hero mb-4">
      <h2 class="mb-1">{{ $t["destinations"] }}</h2>
    </div>
    <div class="row">
      @foreach ($destinations as $destination)
        <div class="col-lg-4 col-md-6 mb-4">
          <a class="trip-card" href="{{ route('view_destination', $destination->id) }}">
            <div class="photo-frame wide">
              @if ($destination->picture_url)
                <img src="{{ $destination->picture_url }}" alt="{{ $destination->name }}" loading="lazy" />
              @endif
              <span class="photo-name">{{ $destination->name }}</span>
            </div>
            <div class="trip-body">
              <h5>{{ $destination->name }}, {{ $destination->country }}</h5>
              <p class="text-muted mb-1">{{ $destination->description }}</p>
              <div class="meta">{{ $t["best_time"] }}: {{ $destination->best_time }} · {{ $destination->packages_count }} {{ $t["packages"] }} · {{ $destination->hotels_count }} {{ $t["hotels"] }}</div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endsection
