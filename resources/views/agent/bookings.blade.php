@extends("agent.agent-base")

@section("area_title", $t["bookings"])

@section("agent_content")
  <div class="container">
    <table class="table">
      <thead><tr><th>ID</th><th>{{ $t["guest"] }}</th><th>Phone</th><th>Trip</th><th>{{ $t["dates"] }}</th><th>Status</th></tr></thead>
      <tbody>
        @forelse ($bookings as $b)
          <tr>
            <th>{{ $b->id }}</th>
            <td>{{ $b->user->name }}</td>
            <td><i class="fas fa-phone text-muted"></i> {{ optional($b->user->userEx)->phone }}</td>
            <td>{{ $b->title() }}</td>
            <td>{{ $b->check_in->format("d M") }} - {{ $b->check_out->format("d M") }}</td>
            <td><span class="badge badge-{{ $b->status == "Pending" ? "warning" : "success" }}">{{ $b->status }}</span></td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-muted">{{ $t["none"] }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
