{{-- one task: icon, title, booking, due date and a Done button --}}
@php($late = $task->status == "Open" && $task->due_date && $task->due_date->lt(\Carbon\Carbon::today()))
<div class="task-card {{ $late ? "late" : "" }} {{ $task->status == "Done" ? "done" : "" }}">
  <div class="ticon"><i class="fas {{ $task->icon() }}"></i></div>
  <div class="tbody">
    <strong>{{ $task->title }}</strong>
    @if ($task->booking)<span class="badge badge-light">#{{ $task->booking_id }}</span>@endif
    <div class="meta">{{ $task->detail }}</div>
    <div class="meta">
      @if ($task->due_date)<i class="far fa-calendar"></i> {{ $t["due"] }} {{ $task->due_date->format("d M") }}@if ($late) <span class="text-danger font-weight-bold">!</span>@endif @endif
      @if (!empty($show_owner) && $task->assignee) · <i class="fas fa-user"></i> {{ $task->assignee->name }}@endif
    </div>
  </div>
  @if ($task->status == "Open")
    <form method="post" action="{{ route('task_done', $task->id) }}">@csrf<button class="btn btn-sm btn-primary"><i class="fas fa-check"></i> {{ $t["mark_done"] }}</button></form>
  @else
    <span class="text-success"><i class="fas fa-check-circle"></i></span>
  @endif
</div>
