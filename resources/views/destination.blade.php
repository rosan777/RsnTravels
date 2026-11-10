@extends("base")

@section("content")
  <div class="container py-4">
    <div class="row mb-4">
      <div class="col-md-7 mb-3">
        <div class="photo-frame wide rounded" style="border-radius: 14px">
          @if ($destination->picture_url)
            <img src="{{ $destination->picture_url }}" alt="{{ $destination->name }}" />
          @endif
        </div>
      </div>
      <div class="col-md-5">
        <div class="section-title">{{ $destination->country }}</div>
        <h1>{{ $destination->name }}</h1>
        <p>{{ $destination->description }}</p>
        <p><strong>{{ $t["best_time"] }}:</strong> {{ $destination->best_time }}</p>
      </div>
    </div>

    <h3>{{ $t["packages"] }}</h3>
    <div class="row">
      @forelse ($packages as $package)
        <div class="col-lg-4 col-md-6 mb-4">
          @include("_package_card")
        </div>
      @empty
        <p class="col text-muted">{{ $t["nothing_found"] }}</p>
      @endforelse
    </div>

    <h3>{{ $t["hotels"] }}</h3>
    <div class="row">
      @forelse ($hotels as $hotel)
        <div class="col-lg-4 col-md-6 mb-4">
          @include("_hotel_card")
        </div>
      @empty
        <p class="col text-muted">{{ $t["nothing_found"] }}</p>
      @endforelse
    </div>
  </div>
@endsection
