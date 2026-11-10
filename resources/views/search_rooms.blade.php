@extends("base")

@section("content")
  <div class="container py-4">
    <h2 class="mb-3">{{ $t["availability"] }}</h2>
    @include("_search_box")
    @if ($error)
      <div class="alert alert-error mt-3">{{ $error }}</div>
    @endif
    <div class="row mt-4">
      @if ($check_in && $check_out && !$error)
        @forelse ($results as $result)
          @php($room_type = $result["room_type"])
          <div class="col-lg-4 col-md-6 mb-4 reveal">
            <div class="trip-card"><a class="card-link" href="{{ route('view_hotel', $room_type->hotel->id) }}?check_in={{ $check_in }}&check_out={{ $check_out }}&guests={{ $guests }}">
              <div class="photo-frame">
                @if ($room_type->picture_url)
                  <img src="{{ $room_type->picture_url }}" alt="{{ $room_type->name }}" loading="lazy" />
                @endif
                <span class="photo-name">{{ $room_type->hotel->city }}</span>
                <span class="price-tag">৳{{ number_format($result["total"]) }}</span>
              </div>
              <div class="trip-body">
                <h5>{{ $room_type->hotel->name }}</h5>
                <p class="text-muted mb-1">{{ $room_type->name }} · {{ $t["capacity"] }} {{ $room_type->capacity }} {{ $t["guests"] }}</p>
                <div class="meta">৳{{ number_format($room_type->base_price) }} × {{ $result["nights"] }} {{ $t["nights"] }} · <span class="text-success">{{ $result["free"] }} {{ $t["rooms_free"] }}</span></div>
              </div>
            </a>
            </div>
          </div>
        @empty
          <p class="col text-muted">{{ $t["nothing_found"] }}</p>
        @endforelse
      @endif
    </div>
  </div>
@endsection
