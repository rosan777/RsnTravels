@extends("agent.agent-base")

@section("area_title", $t["welcome"] . ", " . auth()->user()->name)

@section("agent_content")
  <div class="container">
    <div class="row">
      @foreach ([[$my_tasks->count(), "open_tasks", "fa-clipboard-list"], [$done_count, "done_tasks", "fa-check-double"], [$new_questions, "new_inquiries", "fa-comments"], [$waiting_owner, "waiting", "fa-hourglass-half"]] as $stat)
        <div class="col-6 col-md-3 mb-3">
          <div class="card stat-card"><div class="card-body">
            <i class="fas {{ $stat[2] }} text-muted float-right"></i>
            <div class="stat-number">{{ $stat[0] }}</div>
            <div class="stat-label">{{ $t[$stat[1]] }}</div>
          </div></div>
        </div>
      @endforeach
    </div>
    <div class="row">
      <div class="col-lg-7">
        <h5>{{ $t["my_tasks"] }}</h5>
        @forelse ($my_tasks as $task)
          @include("_task_card")
        @empty
          <p class="text-muted">{{ $t["no_tasks"] }}</p>
        @endforelse
      </div>
      <div class="col-lg-5">
        <div class="d-flex justify-content-between"><h5>{{ $t["new_inquiries"] }}</h5><a href="{{ route('agent_inquiries') }}">{{ $t["view_all"] }} &rarr;</a></div>
        @forelse ($latest_questions as $q)
          <div class="card mb-2"><div class="card-body py-2">
            <strong>{{ $q->subject }}</strong>
            <div class="meta">{{ $q->user->name }}</div>
            <div class="small">{{ \Illuminate\Support\Str::limit($q->message, 90) }}</div>
          </div></div>
        @empty
          <p class="text-muted">{{ $t["no_inquiries"] }}</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection
