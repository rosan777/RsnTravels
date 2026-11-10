@extends("customer.customer-base")

@section("area_title", $t["my_bookings"])

@section("customer_content")
  @php
    $steps = ["Pending" => 1, "Confirmed" => 2, "CheckedIn" => 3, "Completed" => 4];
  @endphp
  <div class="container">
    @forelse ($bookings as $booking)
      <div class="card mb-3">
        <div class="card-body">
          <div class="d-flex justify-content-between flex-wrap">
            <div>
              <span class="badge badge-secondary">#{{ $booking->id }}</span>
              <span class="badge badge-info">{{ $booking->booking_type == "Hotel" ? $t["hotel"] : $t["package"] }}</span>
              <h5 class="mb-0 mt-1">{{ $booking->title() }}</h5>
              <div class="meta">{{ $booking->check_in->format("d M Y") }} - {{ $booking->check_out->format("d M Y") }} · {{ $booking->guests }} {{ $t["guests"] }}</div>
            </div>
            <div class="text-right">
              <div class="h5 mb-0">৳{{ number_format($booking->total) }}</div>
              <span class="badge {{ $booking->payment_status == 'PAID' ? 'badge-success' : 'badge-warning' }}">{{ $booking->payment_status }}</span>
              <div class="meta">{{ $booking->payment_method }}</div>
            </div>
          </div>

          @if ($booking->status == "Cancelled")
            <div class="timeline"><div class="tl bad"><div class="dot"><i class="fas fa-times"></i></div>{{ $t["cancelled"] }}</div></div>
          @else
            @php($at = $steps[$booking->status] ?? 1)
            <div class="timeline">
              @foreach ([["Pending", "tl_pending"], ["Confirmed", "tl_confirmed"], ["CheckedIn", "tl_checkedin"], ["Completed", "tl_completed"]] as $i => $step)
                <div class="tl {{ $at >= $i + 1 ? 'done' : '' }}"><div class="dot"><i class="fas fa-check"></i></div>{{ $t[$step[1]] }}</div>
              @endforeach
            </div>
          @endif
          @if ($booking->status == "Pending")
            <div class="small text-muted"><i class="fas fa-hourglass-half"></i> {{ $t["waiting_owner"] }}</div>
          @endif

          <div class="mt-2">
            <a href="{{ route('customer_invoice', $booking->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-invoice"></i> {{ $t["invoice"] }}</a>
            @if (in_array($booking->status, ["Pending", "Confirmed"]))
              <form method="post" action="{{ route('customer_cancel_booking', $booking->id) }}" class="d-inline" onsubmit="return confirm('{{ $t['sure_cancel'] }}')">
                @csrf
                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-ban"></i> {{ $t["cancel"] }}</button>
              </form>
            @endif
          </div>

          @if ($booking->status == "Completed")
            @if ($booking->review)
              <div class="mt-3 small"><span class="stars">{{ str_repeat("★", $booking->review->rating) }}</span> {{ $booking->review->comment }}</div>
            @else
              <form method="post" action="{{ route('customer_review', $booking->id) }}" class="form-inline mt-3">
                @csrf
                <select name="rating" class="form-control form-control-sm mr-2 mb-1">
                  @foreach ([5, 4, 3, 2, 1] as $r)<option value="{{ $r }}">{{ str_repeat("★", $r) }}</option>@endforeach
                </select>
                <input name="comment" class="form-control form-control-sm mr-2 mb-1" style="min-width: 220px" placeholder="{{ $t['write_review'] }}" maxlength="300" />
                <button class="btn btn-sm btn-primary mb-1">{{ $t["send"] }}</button>
              </form>
            @endif
          @endif
        </div>
      </div>
    @empty
      <div class="card card-body text-center py-5"><h5>{{ $t["no_bookings"] }}</h5>
        <div><a href="{{ route('view_hotels') }}" class="btn btn-primary">{{ $t["hotels"] }}</a> <a href="{{ route('view_packages') }}" class="btn btn-outline-primary">{{ $t["packages"] }}</a></div>
      </div>
    @endforelse
  </div>
@endsection
