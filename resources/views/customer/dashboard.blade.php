@extends("customer.customer-base")

@section("area_title", $t["welcome"] . ", " . auth()->user()->name)

@section("customer_content")
  <div class="container">
    <div class="row">
      @foreach ([[$bookings_count, "Bookings"], [$upcoming_count, "Upcoming trips"], ["৳" . number_format($spent), "Spent"]] as $stat)
        <div class="col-6 col-md-4 mb-3">
          <div class="card stat-card">
            <div class="card-body">
              <div class="stat-number">{{ $stat[0] }}</div>
              <div class="stat-label">{{ $stat[1] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <a href="{{ route('view_hotels') }}" class="btn btn-primary">{{ $t["hotels"] }}</a>
    <a href="{{ route('view_packages') }}" class="btn btn-outline-primary">{{ $t["packages"] }}</a>
  </div>
@endsection
