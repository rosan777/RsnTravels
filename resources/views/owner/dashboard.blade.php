@extends("owner.owner-base")

@section("area_title", $t["dashboard"])

@section("owner_content")
  <div class="container">
    <div class="row">
      @foreach ([[$bookings_count, "bookings", "fa-suitcase"], ["৳" . number_format($revenue), "revenue", "fa-coins"], [$hotels_count, "hotels", "fa-hotel"], [$packages_count, "packages", "fa-map"], [$pending_count, "waiting", "fa-hourglass-half"]] as $stat)
        <div class="col-6 col-md mb-3">
          <div class="card stat-card"><div class="card-body">
            <i class="fas {{ $stat[2] }} text-muted float-right"></i>
            <div class="stat-number">{{ $stat[0] }}</div>
            <div class="stat-label">{{ $t[$stat[1]] }}</div>
          </div></div>
        </div>
      @endforeach
    </div>

    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between"><h5>{{ $t["waiting_approval"] }}</h5><a href="{{ route('owner_bookings', ['status' => 'Pending']) }}">{{ $t["view_all"] }} &rarr;</a></div>
        @forelse ($pending as $booking)
          <div class="d-flex justify-content-between align-items-center flex-wrap border-top py-2">
            <div><strong>#{{ $booking->id }} {{ $booking->title() }}</strong><div class="meta">{{ $booking->user->name }} · {{ $booking->check_in->format("d M") }} - {{ $booking->check_out->format("d M Y") }} · ৳{{ number_format($booking->total) }} · {{ $booking->payment_status }}</div></div>
            <div>
              <form method="post" action="{{ route('owner_approve_booking', $booking->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success"><i class="fas fa-check"></i> {{ $t["approve"] }}</button></form>
              <form method="post" action="{{ route('owner_reject_booking', $booking->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button></form>
            </div>
          </div>
        @empty
          <p class="text-muted mb-0">{{ $t["nothing_waiting"] }}</p>
        @endforelse
      </div>
    </div>

    <div class="row">
      <div class="col-lg-7 mb-3"><div class="card"><div class="card-body"><h6>{{ $t["bookings_by_month"] }}</h6><canvas id="monthChart" height="140"></canvas></div></div></div>
      <div class="col-lg-5 mb-3"><div class="card"><div class="card-body"><h6>{{ $t["by_status"] }}</h6><div style="height: 250px"><canvas id="statusChart"></canvas></div></div></div></div>
    </div>
    <div class="card mb-4"><div class="card-body">
      <div class="d-flex justify-content-between"><h6>{{ $t["workload"] }}</h6><a href="{{ route('owner_team') }}">{{ $t["team"] }} &rarr;</a></div>
      <div class="row">
        @foreach ($team as $m)
          <div class="col-md-6"><div class="member">
            <div class="avatar {{ $m["type"] }}">{{ strtoupper(substr($m["user"]->name, 0, 1)) }}</div>
            <div class="flex-fill"><strong>{{ $m["user"]->name }}</strong> <span class="meta">· {{ $t["roles"][$m["type"]][0] }}</span>
              <div class="meta">{{ $m["open"] }} {{ $t["open_tasks"] }} · {{ $m["done"] }} {{ $t["done_tasks"] }}</div></div>
          </div></div>
        @endforeach
      </div>
    </div></div>
    <a href="{{ route('owner_bookings') }}" class="btn btn-primary">{{ $t["bookings"] }}</a>
    <a href="{{ route('owner_team') }}" class="btn btn-outline-primary">{{ $t["team"] }}</a>
    <a href="{{ route('owner_catalog') }}" class="btn btn-outline-primary">{{ $t["catalog"] }}</a>
  </div>
@endsection

@section("scripts")
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    new Chart(document.getElementById('monthChart'), { type: 'bar', data: { labels: @json($month_labels), datasets: [{ data: @json($month_values), backgroundColor: '#0b83b5', borderRadius: 6 }] }, options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } })
    new Chart(document.getElementById('statusChart'), { type: 'doughnut', data: { labels: @json($status_counts->keys()), datasets: [{ data: @json($status_counts->values()), backgroundColor: ['#f6c667', '#0b83b5', '#e5484d', '#138a72', '#8a93a6'] }] }, options: { maintainAspectRatio: false, cutout: '62%' } })
  </script>
@endsection
