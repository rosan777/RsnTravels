@extends("staff.staff-base")

@section("area_title", $t["dashboard"])

@section("staff_content")
  <div class="container">
    <div class="row">
      @foreach ([[$arrivals->count(), "Arrivals today"], [$departures->count(), "Departures today"], [$pending_count, "Pending"], [$occupied_count . " / " . $rooms_count, "Rooms occupied"]] as $stat)
        <div class="col-6 col-md-3 mb-3">
          <div class="card stat-card">
            <div class="card-body">
              <div class="stat-number">{{ $stat[0] }}</div>
              <div class="stat-label">{{ $stat[1] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <a href="{{ route('staff_bookings') }}" class="btn btn-primary mb-2">{{ $t["bookings"] }}</a>
    <a href="{{ route('staff_rooms') }}" class="btn btn-outline-primary mb-2">{{ $t["room_board"] }}</a>
    @foreach ([["Arrivals today", $arrivals], ["Departures today", $departures]] as $block)
      <h4 class="mt-3">{{ $block[0] }}</h4>
      @if ($block[1]->isEmpty())
        <p class="text-muted">None today.</p>
      @else
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Guest</th>
              <th scope="col">Hotel / Room</th>
              <th scope="col">Dates</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($block[1] as $booking)
              <tr>
                <th scope="row">{{ $booking->id }}</th>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->title() }} ({{ $booking->room->number }})</td>
                <td>{{ $booking->check_in->format("d M") }} - {{ $booking->check_out->format("d M") }}</td>
                <td>{{ $booking->status }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    @endforeach
  </div>
@endsection
