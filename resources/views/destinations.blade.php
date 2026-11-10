@extends("base")

@section("content")
  <div class="container py-4">
    <div class="page-hero mb-4">
      <h2 class="mb-1">{{ $t["destinations"] }}</h2>
    </div>
    <div class="row">
      @foreach ($destinations as $destination)
        <div class="col-lg-4 col-md-6 col-6 mb-3 mb-md-4 reveal">
          @include("_destination_card", ["wide" => true])
        </div>
      @endforeach
    </div>
  </div>
@endsection
