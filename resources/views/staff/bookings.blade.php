@extends("staff.staff-base")

@section("area_title", $t["bookings"])

@section("staff_content")
  <div class="container">
    <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ $t["staff_note"] }} ({{ $waiting }} {{ $t["waiting"] }})</div>
    <h4>{{ $t["arrivals"] }}</h4>
    <table class="table">
      <thead><tr><th>ID</th><th>{{ $t["guest"] }}</th><th>{{ $t["hotel"] }} / Room</th><th>{{ $t["dates"] }}</th><th></th></tr></thead>
      <tbody>
        @forelse ($arrivals as $b)
          <tr>
            <th>{{ $b->id }}</th><td>{{ $b->user->name }}</td><td>{{ $b->title() }} ({{ $b->room->number }})</td>
            <td>{{ $b->check_in->format("d M") }} - {{ $b->check_out->format("d M") }}</td>
            <td><form method="post" action="{{ route('staff_check_in', $b->id) }}">@csrf<button class="btn btn-sm btn-primary"><i class="fas fa-sign-in-alt"></i> {{ $t["check_in_btn"] }}</button></form></td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-muted">{{ $t["none"] }}</td></tr>
        @endforelse
      </tbody>
    </table>
    <h4 class="mt-4">{{ $t["in_house"] }}</h4>
    <table class="table">
      <thead><tr><th>ID</th><th>{{ $t["guest"] }}</th><th>{{ $t["hotel"] }} / Room</th><th>{{ $t["dates"] }}</th><th></th></tr></thead>
      <tbody>
        @forelse ($in_house as $b)
          <tr>
            <th>{{ $b->id }}</th><td>{{ $b->user->name }}</td><td>{{ $b->title() }} ({{ $b->room->number }})</td>
            <td>{{ $b->check_in->format("d M") }} - {{ $b->check_out->format("d M") }}</td>
            <td><form method="post" action="{{ route('staff_check_out', $b->id) }}">@csrf<button class="btn btn-sm btn-outline-primary"><i class="fas fa-sign-out-alt"></i> {{ $t["check_out_btn"] }}</button></form></td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-muted">{{ $t["none"] }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
