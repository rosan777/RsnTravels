@extends("base")

@section("content")
  <div class="container py-4">
    <div class="row">
      <div class="col-lg-7 mb-3">
        <div class="photo-frame wide gallery-main">
          @if ($hotel->picture_url)
            <img src="{{ $hotel->picture_url }}" alt="{{ $hotel->name }}" />
          @endif
          @include("_heart", ["type" => "hotel", "id" => $hotel->id])
        </div>
      </div>
      <div class="col-lg-5">
        <div class="section-title">{{ $hotel->city }}</div>
        <h1 class="h2">{{ $hotel->name }}</h1>
        <div class="stars mb-2">{{ str_repeat("★", $hotel->stars) }}</div>
        <p>{{ $hotel->description }}</p>
        <h6>{{ $t["amenities"] }}</h6>
        @foreach ($hotel->amenityList() as $amenity)
          <span class="chip">{{ $amenity }}</span>
        @endforeach
      </div>
    </div>

    <h3 class="mt-4">{{ $t["room_types"] }}</h3>
    <form method="get" class="card card-body mb-3">
      <div class="form-row align-items-end">
        <div class="col-6 col-md-3 mb-2">
          <label class="small mb-0">{{ $t["check_in"] }}</label>
          <input type="date" name="check_in" class="form-control" min="{{ now()->toDateString() }}" value="{{ $check_in }}" />
        </div>
        <div class="col-6 col-md-3 mb-2">
          <label class="small mb-0">{{ $t["check_out"] }}</label>
          <input type="date" name="check_out" class="form-control" min="{{ now()->addDay()->toDateString() }}" value="{{ $check_out }}" />
        </div>
        <div class="col-6 col-md-2 mb-2">
          <label class="small mb-0">{{ $t["guests"] }}</label>
          <input type="number" name="guests" class="form-control" min="1" max="10" value="{{ $guests }}" />
        </div>
        <div class="col-6 col-md-2 mb-2">
          <button class="btn btn-primary btn-block" type="submit">{{ $t["search"] }}</button>
        </div>
      </div>
      @if ($error)
        <div class="alert alert-error mb-0 mt-2">{{ $error }}</div>
      @endif
    </form>
    <div class="row">
      @foreach ($hotel->roomTypes as $room_type)
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="trip-card">
            <div class="photo-frame">
              @if ($room_type->picture_url)
                <img src="{{ $room_type->picture_url }}" alt="{{ $room_type->name }}" loading="lazy" />
              @endif
              <span class="price-tag">৳{{ number_format($room_type->base_price) }}</span>
            </div>
            <div class="trip-body">
              <h5>{{ $room_type->name }}</h5>
              <p class="text-muted mb-1">{{ $room_type->description }}</p>
              <div class="meta">{{ $t["capacity"] }} {{ $room_type->capacity }} {{ $t["guests"] }} · ৳{{ number_format($room_type->base_price) }} {{ $t["per_night"] }}</div>
              @if (isset($free[$room_type->id]))
                <div class="mt-2">
                  @if ($free[$room_type->id] > 0 && $room_type->capacity >= $guests)
                    <span class="badge badge-success">{{ $free[$room_type->id] }} {{ $t["rooms_free"] }}</span>
                    @php($nights = max(1, \Carbon\Carbon::parse($check_in)->diffInDays(\Carbon\Carbon::parse($check_out))))
                    <div class="meta mt-1">{{ $nights }} {{ $t["nights"] }}: <strong>৳{{ number_format($room_type->base_price * $nights) }}</strong></div>
                    @if (!$userEx)
                      <a href="{{ route('flogin') }}" class="btn btn-sm btn-cta btn-block mt-2"><i class="fas fa-shopping-cart"></i> {{ $t["login_to_book"] }}</a>
                    @elseif ($userEx->user_type == "customer")
                      <form method="post" action="{{ route('customer_add_to_cart') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="type" value="hotel" />
                        <input type="hidden" name="room_type_id" value="{{ $room_type->id }}" />
                        <input type="hidden" name="check_in" value="{{ $check_in }}" />
                        <input type="hidden" name="check_out" value="{{ $check_out }}" />
                        <input type="hidden" name="guests" value="{{ $guests }}" />
                        <button class="btn btn-sm btn-cta btn-block" type="submit"><i class="fas fa-cart-plus"></i> {{ $t["add_to_cart"] }}</button>
                      </form>
                    @endif
                  @else
                    <span class="badge badge-secondary">{{ $t["sold_out"] }}</span>
                  @endif
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
    @include("_reviews")
  </div>
@endsection
