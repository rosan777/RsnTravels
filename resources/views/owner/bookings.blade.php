@extends("owner.owner-base")

@section("area_title", $t["bookings"])

@section("owner_content")
  <div class="container">
    <div class="mb-3">
      <a href="{{ route('owner_bookings') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}">{{ $t["all"] }}</a>
      @foreach (App\Models\Booking::CHOICES as $c)
        <a href="{{ route('owner_bookings', ['status' => $c[0]]) }}" class="btn btn-sm {{ $status == $c[0] ? 'btn-primary' : 'btn-outline-primary' }}">{{ $c[1] }}</a>
      @endforeach
    </div>
    <table class="table">
      <thead><tr><th>ID</th><th>{{ $t["guest"] }}</th><th>{{ $t["item"] }}</th><th>{{ $t["dates"] }}</th><th>{{ $t["total"] }}</th><th>{{ $t["payment"] }}</th><th>{{ $t["status"] }}</th><th></th></tr></thead>
      <tbody>
        @foreach ($bookings as $booking)
          <tr>
            <th scope="row">{{ $booking->id }}</th>
            <td>{{ $booking->user->name }}</td>
            <td>{{ $booking->title() }}</td>
            <td>{{ $booking->check_in->format("d M") }} - {{ $booking->check_out->format("d M Y") }}</td>
            <td>৳{{ number_format($booking->total) }}</td>
            <td>{{ $booking->payment_status }}</td>
            <td>{{ $booking->status }}</td>
            <td class="text-nowrap">
              @if ($booking->status == "Pending")
                <form method="post" action="{{ route('owner_approve_booking', $booking->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success"><i class="fas fa-check"></i> {{ $t["approve"] }}</button></form>
                <form method="post" action="{{ route('owner_reject_booking', $booking->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> {{ $t["reject"] }}</button></form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $bookings->links() }}
  </div>
@endsection
