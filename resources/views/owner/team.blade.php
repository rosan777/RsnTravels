@extends("owner.owner-base")

@section("area_title", $t["team"])

@section("owner_content")
  <div class="container">
    <div class="row">
      <div class="col-lg-5 mb-4">
        <div class="card mb-3"><div class="card-body">
          <h5>{{ $t["workload"] }}</h5>
          @php($max = max(1, collect($team)->max(fn ($m) => $m["open"] + $m["done"])))
          @foreach ($team as $m)
            <div class="member">
              <div class="avatar {{ $m["type"] }}">{{ strtoupper(substr($m["user"]->name, 0, 1)) }}</div>
              <div class="flex-fill">
                <strong>{{ $m["user"]->name }}</strong> <span class="meta">· {{ $t["roles"][$m["type"]][0] }}</span>
                <div class="bar mt-1"><span style="width: {{ ($m["open"] + $m["done"]) / $max * 100 }}%; background: linear-gradient(90deg, var(--c-{{ $m["type"] }}) {{ $m["open"] + $m["done"] ? $m["done"] / ($m["open"] + $m["done"]) * 100 : 0 }}%, #f6c667 0)"></span></div>
                <div class="meta">{{ $m["open"] }} {{ $t["open_tasks"] }} · {{ $m["done"] }} {{ $t["done_tasks"] }}</div>
              </div>
            </div>
          @endforeach
        </div></div>
        <div class="card"><div class="card-body">
          <h5>{{ $t["new_task"] }}</h5>
          <form method="post" action="{{ route('owner_create_task') }}">
            @csrf
            <div class="form-group"><input name="title" class="form-control" placeholder="{{ $t["task_title"] }}" maxlength="160" /></div>
            <div class="form-group"><textarea name="detail" rows="2" class="form-control" placeholder="{{ $t["task_detail"] }}"></textarea></div>
            <div class="form-row">
              <div class="form-group col-7"><select name="assigned_to" class="form-control">@foreach ($members as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select></div>
              <div class="form-group col-5"><input type="date" name="due_date" class="form-control" min="{{ date("Y-m-d") }}" /></div>
            </div>
            <button class="btn btn-primary"><i class="fas fa-plus"></i> {{ $t["assign"] }}</button>
          </form>
        </div></div>
      </div>
      <div class="col-lg-7">
        <div class="mb-2">
          <a href="{{ route('owner_team', ['status' => 'Open']) }}" class="btn btn-sm {{ $status == "Open" ? "btn-primary" : "btn-outline-primary" }}">{{ $t["open_tasks"] }}</a>
          <a href="{{ route('owner_team', ['status' => 'Done']) }}" class="btn btn-sm {{ $status == "Done" ? "btn-primary" : "btn-outline-primary" }}">{{ $t["done_tasks"] }}</a>
        </div>
        @forelse ($tasks as $task)
          @include("_task_card", ["show_owner" => true])
          @if ($task->status == "Open")
            <form method="post" action="{{ route('owner_reassign_task', $task->id) }}" class="form-inline mb-3" style="margin-top: -4px">
              @csrf
              <select name="assigned_to" class="form-control form-control-sm mr-2">@foreach ($members as $u)<option value="{{ $u->id }}" {{ $u->id == $task->assigned_to ? "selected" : "" }}>{{ $u->name }}</option>@endforeach</select>
              <button class="btn btn-sm btn-outline-primary">{{ $t["assign"] }}</button>
            </form>
          @endif
        @empty
          <p class="text-muted">{{ $t["no_tasks"] }}</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection
