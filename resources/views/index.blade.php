@extends("base")

@section("content")
  <div class="position-relative hero-carousel">
    <div id="heroSlides" class="carousel slide carousel-fade" data-ride="carousel" data-interval="4500">
      <div class="carousel-inner">
        @forelse ($slides as $slide)
          <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            <img src="{{ $slide->picture_url }}" alt="{{ $slide->name }}" />
            <span class="slide-name">{{ $slide->name }}, {{ $slide->country }}</span>
          </div>
        @empty
          <div class="carousel-item active"></div>
        @endforelse
      </div>
    </div>
    <div class="hero-text">
      <div class="container">
        <h1 class="display-4 mb-2">{{ $t["hero_title"] }}</h1>
        <p class="lead mb-4">{{ $t["hero_text"] }}</p>
        @include("_search_box")
      </div>
    </div>
  </div>

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-3">
      <div>
        <div class="section-title">{{ $t["destinations"] }}</div>
        <h2 class="mb-0">{{ $t["popular_destinations"] }}</h2>
      </div>
      <a href="{{ route('view_destinations') }}">{{ $t["view_all"] }} &rarr;</a>
    </div>
    <div class="row">
      @foreach ($destinations->take(4) as $destination)
        <div class="col-lg-3 col-md-6 mb-4">
          <a class="trip-card" href="{{ route('view_destination', $destination->id) }}">
            <div class="photo-frame">
              @if ($destination->picture_url)
                <img src="{{ $destination->picture_url }}" alt="{{ $destination->name }}" loading="lazy" />
              @endif
              <span class="photo-name">{{ $destination->name }}</span>
            </div>
            <div class="trip-body">
              <h5>{{ $destination->name }}</h5>
              <div class="meta">{{ $destination->country }} · {{ $t["best_time"] }}: {{ $destination->best_time }}</div>
            </div>
          </a>
        </div>
      @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-end mb-3 mt-3">
      <div>
        <div class="section-title">{{ $t["packages"] }}</div>
        <h2 class="mb-0">{{ $t["featured_packages"] }}</h2>
      </div>
      <a href="{{ route('view_packages') }}">{{ $t["view_all"] }} &rarr;</a>
    </div>
    <div class="row">
      @foreach ($packages as $package)
        <div class="col-lg-4 col-md-6 mb-4">
          @include("_package_card")
        </div>
      @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-end mb-3 mt-3">
      <div>
        <div class="section-title">{{ $t["hotels"] }}</div>
        <h2 class="mb-0">{{ $t["featured_hotels"] }}</h2>
      </div>
      <a href="{{ route('view_hotels') }}">{{ $t["view_all"] }} &rarr;</a>
    </div>
    <div class="row">
      @foreach ($hotels as $hotel)
        <div class="col-lg-4 col-md-6 mb-4">
          @include("_hotel_card")
        </div>
      @endforeach
    </div>
  </div>
@endsection
