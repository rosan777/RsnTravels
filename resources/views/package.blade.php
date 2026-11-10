@extends("base")

@section("content")
  <div class="container py-4">
    <div class="row">
      <div class="col-lg-7 mb-3">
        <div class="photo-frame wide gallery-main">
          @if ($package->picture_url)
            <img src="{{ $package->picture_url }}" alt="{{ $package->name }}" />
          @endif
          @include("_heart", ["type" => "package", "id" => $package->id])
        </div>
        <h4 class="mt-4">{{ $t["itinerary"] }}</h4>
        <ul class="list-group">
          @foreach ($package->lines("itinerary") as $line)
            <li class="list-group-item">{{ $line }}</li>
          @endforeach
        </ul>
        @include("_reviews")
      </div>
      <div class="col-lg-5">
        <div class="section-title">{{ $package->destination->name }}, {{ $package->destination->country }}</div>
        <h1 class="h2">{{ $package->name }}</h1>
        <p>{{ $package->description }}</p>
        <div class="card card-body mb-3 booking-box">
          <div class="stat-card"><span class="stat-number">৳{{ number_format($package->price) }}</span> <span class="stat-label">{{ $t["per_person"] }} · {{ $package->days }} {{ $t["days"] }}</span></div>
          @if (!$userEx)
            <a href="{{ route('flogin') }}" class="btn btn-cta btn-block mt-3"><i class="fas fa-shopping-cart"></i> {{ $t["login_to_book"] }}</a>
          @elseif ($userEx->user_type == "customer")
            <form method="post" action="{{ route('customer_add_to_cart') }}" class="mt-3">
              @csrf
              <input type="hidden" name="type" value="package" />
              <input type="hidden" name="package_id" value="{{ $package->id }}" />
              <div class="form-row">
                <div class="col-7"><label class="small mb-0">{{ $t["travel_date"] }}</label><input type="date" name="travel_date" class="form-control" min="{{ now()->addDay()->toDateString() }}" value="{{ now()->addDays(14)->toDateString() }}" required /></div>
                <div class="col-5"><label class="small mb-0">{{ $t["travelers"] }}</label><input type="number" name="guests" class="form-control" min="1" max="10" value="2" /></div>
              </div>
              <button class="btn btn-cta btn-block mt-2" type="submit"><i class="fas fa-cart-plus"></i> {{ $t["add_to_cart"] }}</button>
            </form>
          @endif
        </div>
        <div class="row">
          <div class="col-6">
            <h6>{{ $t["includes"] }}</h6>
            <ul class="small pl-3">
              @foreach ($package->lines("includes") as $line)
                <li>{{ $line }}</li>
              @endforeach
            </ul>
          </div>
          <div class="col-6">
            <h6>{{ $t["excludes"] }}</h6>
            <ul class="small pl-3 text-muted">
              @foreach ($package->lines("excludes") as $line)
                <li>{{ $line }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
