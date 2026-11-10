@extends("customer.customer-base")

@section("area_title", $t["ask_agent"])

@section("customer_content")
  <div class="container">
    <div class="row">
      <div class="col-md-5 mb-4">
        <div class="card"><div class="card-body">
          <form method="post" action="{{ route('customer_inquiries') }}">
            @csrf
            <div class="form-group"><label>{{ $t["subject"] }}</label><input name="subject" class="form-control" value="{{ old('subject') }}" maxlength="160" /></div>
            <div class="form-group"><label>{{ $t["your_question"] }}</label><textarea name="message" rows="4" class="form-control" maxlength="2000">{{ old('message') }}</textarea></div>
            <button class="btn btn-primary"><i class="fas fa-paper-plane"></i> {{ $t["send"] }}</button>
          </form>
        </div></div>
      </div>
      <div class="col-md-7">
        @forelse ($inquiries as $q)
          <div class="card mb-3"><div class="card-body">
            <strong>{{ $q->subject }}</strong> <span class="meta">· {{ $q->created_at->format("d M") }}</span>
            <div class="bubble me mt-2">{{ $q->message }}</div>
            @if ($q->status == "Answered")
              <div class="bubble agent"><div class="meta">{{ $t["answered_by"] }} {{ optional($q->agent)->name }}</div>{{ $q->reply }}</div>
            @else
              <div class="meta"><i class="far fa-clock"></i> {{ $t["awaiting_reply"] }}</div>
            @endif
          </div></div>
        @empty
          <p class="text-muted">{{ $t["no_inquiries"] }}</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection
