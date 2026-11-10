@extends("owner.owner-base")

@section("area_title", $t["dashboard"])

@section("owner_content")
  <div class="container">
    <div class="row">
      @foreach ([[$bookings_count, "Bookings"], ["৳" . number_format($revenue), "Revenue"], [$hotels_count, "Hotels"], [$packages_count, "Packages"], [$pending_count, "Pending"]] as $stat)
        <div class="col-6 col-md mb-3">
          <div class="card stat-card">
            <div class="card-body">
              <div class="stat-number">{{ $stat[0] }}</div>
              <div class="stat-label">{{ $stat[1] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
