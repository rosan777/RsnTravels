@extends("staff.staff-base")

@section("area_title", $t["my_tasks"])

@section("staff_content")
  <div class="container">
    <h5>{{ $t["open_tasks"] }}</h5>
    @forelse ($my_tasks as $task)
      @include("_task_card")
    @empty
      <p class="text-muted">{{ $t["no_tasks"] }}</p>
    @endforelse
    @if ($done->isNotEmpty())
      <h5 class="mt-4">{{ $t["done_tasks"] }}</h5>
      @foreach ($done as $task)
        @include("_task_card")
      @endforeach
    @endif
  </div>
@endsection
