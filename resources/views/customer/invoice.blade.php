@extends("base")

@section("no_tech", "1")

@section("content")
  <div class="container py-4" style="max-width: 760px">
    <div class="card card-body">
      <div class="d-flex justify-content-between">
        <div>
          <h3 class="mb-0"><i class="fas fa-plane-departure text-primary"></i> TravelDemo</h3>
          <div class="meta">{{ $t["invoice"] }} TD-{{ str_pad($booking->id, 6, "0", STR_PAD_LEFT) }}</div>
        </div>
        <div class="text-right">
          <div>{{ $booking->created_at->format("d M Y") }}</div>
          <span class="badge badge-status badge-{{ $booking->payment_status == 'PAID' ? 'success' : 'warning' }}">{{ $booking->payment_status }}</span>
        </div>
      </div>
      <hr />
      <div class="row">
        <div class="col-6"><div class="meta">{{ $t["guest"] }}</div><strong>{{ $booking->user->name }}</strong><br />{{ $booking->user->email }}</div>
        <div class="col-6 text-right"><div class="meta">{{ $t["status"] }}</div><strong>{{ $booking->status }}</strong><br />{{ $booking->payment_method }}</div>
      </div>
      <table class="table mt-3">
        <thead><tr><th>{{ $t["item"] }}</th><th>{{ $t["dates"] }}</th><th>{{ $t["guests"] }}</th><th class="text-right">{{ $t["total"] }}</th></tr></thead>
        <tbody>
          <tr>
            <td>{{ $booking->title() }}@if ($booking->room)<div class="meta">Room {{ $booking->room->number }}</div>@endif</td>
            <td>{{ $booking->check_in->format("d M Y") }} - {{ $booking->check_out->format("d M Y") }}</td>
            <td>{{ $booking->guests }}</td>
            <td class="text-right">৳{{ number_format($booking->total) }}</td>
          </tr>
        </tbody>
      </table>
      <div class="text-right h4">{{ $t["total"] }}: ৳{{ number_format($booking->total) }}</div>
      <div class="text-muted small">{{ $t["invoice_note"] }}</div>
      <div class="mt-3 d-print-none">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> {{ $t["print"] }}</button>
        <a href="{{ route('customer_bookings') }}" class="btn btn-outline-primary">{{ $t["my_bookings"] }}</a>
      </div>
    </div>
  </div>
@endsection
