@extends("agent.agent-base")

@section("area_title", $t["inquiries"])

@section("agent_content")
  <div class="container">
    <h5>{{ $t["new_inquiries"] }}</h5>
    @forelse ($new as $q)
      <div class="card mb-3"><div class="card-body">
        <strong>{{ $q->subject }}</strong> <span class="meta">· {{ $q->user->name }} · {{ $q->created_at->format("d M, H:i") }}</span>
        <p class="mb-2 mt-1">{{ $q->message }}</p>
        <form method="post" action="{{ route('agent_reply_inquiry', $q->id) }}">
          @csrf
          <div class="form-group mb-2"><textarea name="reply" rows="2" class="form-control" placeholder="{{ $t["reply"] }}"></textarea></div>
          <button class="btn btn-sm btn-primary"><i class="fas fa-paper-plane"></i> {{ $t["send_reply"] }}</button>
        </form>
      </div></div>
    @empty
      <p class="text-muted">{{ $t["no_inquiries"] }}</p>
    @endforelse
    @if ($answered->isNotEmpty())
      <h5 class="mt-4">{{ $t["done_tasks"] }}</h5>
      @foreach ($answered as $q)
        <div class="card mb-2"><div class="card-body py-2">
          <strong>{{ $q->subject }}</strong> <span class="meta">· {{ $q->user->name }}</span>
          <div class="bubble me mt-1">{{ $q->message }}</div>
          <div class="bubble agent">{{ $q->reply }}</div>
        </div></div>
      @endforeach
    @endif
  </div>
@endsection
