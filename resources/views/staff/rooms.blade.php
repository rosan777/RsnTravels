@extends("staff.staff-base")

@section("area_title", $t["room_board"])

@section("staff_content")
  <div class="container">
    @foreach ($hotels as $hotel)
      <h5 class="mt-3">{{ $hotel->name }} <small class="text-muted">{{ $hotel->city }}</small></h5>
      <div class="row">
        @foreach ($hotel->roomTypes as $room_type)
          @foreach ($room_type->rooms as $room)
            <div class="col-6 col-md-3 col-lg-2 mb-2">
              <div class="card card-body p-2 text-center">
                <strong>{{ $room->number }}</strong>
                <div class="meta">{{ $room_type->name }}</div>
                <form method="post" action="{{ route('staff_set_room', $room->id) }}" class="mt-1">@csrf
                  <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach (App\Models\Room::STATUSES as $s)<option value="{{ $s[0] }}" {{ $room->status == $s[0] ? "selected" : "" }}>{{ $s[1] }}</option>@endforeach
                  </select>
                </form>
              </div>
            </div>
          @endforeach
        @endforeach
      </div>
    @endforeach
  </div>
@endsection
