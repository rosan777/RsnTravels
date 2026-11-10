@extends("base")

@section("content")
  <div class="container py-4">
    <div class="row">
      <div class="col-lg-7 mb-3">
        <div class="photo-frame wide" style="border-radius: 14px">
          @if ($package->picture_url)
            <img src="{{ $package->picture_url }}" alt="{{ $package->name }}" />
          @endif
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
        <div class="card card-body mb-3">
          <div class="stat-card"><span class="stat-number">৳{{ number_format($package->price) }}</span> <span class="stat-label">{{ $t["per_person"] }} · {{ $package->days }} {{ $t["days"] }}</span></div>
          @if ($userEx && $userEx->user_type == "customer" && Route::has("customer_book_package"))
            <a href="{{ route('customer_book_package', $package->id) }}" class="btn btn-primary btn-block mt-3">{{ $t["book_now"] }}</a>
          @elseif (!$userEx)
            <a href="{{ route('flogin') }}" class="btn btn-outline-primary btn-block mt-3">{{ $t["login_to_book"] }}</a>
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
