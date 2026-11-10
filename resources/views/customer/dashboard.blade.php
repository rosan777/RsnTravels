@extends("customer.customer-base")

@section("area_title", $t["welcome"] . ", " . auth()->user()->name)

@section("customer_content")
  <div class="container">
    <div class="row">
      @foreach ([[$bookings_count, "my_bookings", "fa-suitcase"], [$upcoming_count, "upcoming", "fa-plane"], ["৳" . number_format($spent), "spent", "fa-wallet"]] as $stat)
        <div class="col-6 col-md-4 mb-3">
          <div class="card stat-card"><div class="card-body">
            <i class="fas {{ $stat[2] }} text-muted float-right"></i>
            <div class="stat-number">{{ $stat[0] }}</div>
            <div class="stat-label">{{ $t[$stat[1]] }}</div>
          </div></div>
        </div>
      @endforeach
    </div>
    <a href="{{ route('view_hotels') }}" class="btn btn-primary">{{ $t["hotels"] }}</a>
    <a href="{{ route('view_packages') }}" class="btn btn-outline-primary">{{ $t["packages"] }}</a>
    <a href="{{ route('customer_cart') }}" class="btn btn-outline-primary"><i class="fas fa-shopping-cart"></i> {{ $t["cart"] }}</a>
    <a href="{{ route('customer_bookings') }}" class="btn btn-outline-primary">{{ $t["my_bookings"] }}</a>
  </div>
@endsection
