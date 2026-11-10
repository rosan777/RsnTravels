@extends("base")

@section("content")
  <div class="container pt-4">
    <div class="page-hero mb-4">
      <h2 class="mb-1">@yield("area_title")</h2>
      <p class="mb-0">{{ $t["staff_intro"] }}</p>
    </div>
  </div>

  @yield("staff_content")
@endsection
